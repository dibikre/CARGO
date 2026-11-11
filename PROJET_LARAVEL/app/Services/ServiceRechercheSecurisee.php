<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ServiceRechercheSecurisee
{
    public const DUREE_VALIDITE_SECONDES = 10;
    public const LONGUEUR_CODE = 16;
    public const NB_RESULTATS_MAX = 10;
    public const PREFIXE_CACHE = 'recherche_securisee_';
    public const PREFIXE_VERROU = 'verrou_recherche_';

    /**
     * Génère un code cryptographiquement sécurisé de 16 caractères et stocke temporairement la recherche.
     *
     * @return array{code: string, url: string, expire_dans: int}
     */
    public function initialiserRecherche(string $motsCles, string $sessionId, string $ip): array
    {
        $code = $this->genererCodeCryptographique();
        $cleCache = self::PREFIXE_CACHE . $code;

        $donnees = [
            'q' => trim($motsCles),
            'session_id' => $sessionId,
            'ip' => $ip,
            'cree_a' => now()->timestamp,
        ];

        // Stockage sécurisé avec expiration stricte de 10 secondes
        Cache::put($cleCache, $donnees, self::DUREE_VALIDITE_SECONDES);

        return [
            'code' => $code,
            'url' => route('recherche.consulter', ['code' => $code]),
            'expire_dans' => self::DUREE_VALIDITE_SECONDES,
        ];
    }

    /**
     * Consomme de manière atomique et à usage unique le jeton de recherche pour la session appelante.
     *
     * @return array{valide: bool, motif?: string, donnees?: array<string, mixed>}
     */
    public function consommerJeton(string $code, string $sessionId): array
    {
        if (strlen($code) !== self::LONGUEUR_CODE || ! ctype_alnum($code)) {
            return [
                'valide' => false,
                'motif' => 'format_invalide',
            ];
        }

        $cleCache = self::PREFIXE_CACHE . $code;
        $cleVerrou = self::PREFIXE_VERROU . $code;

        // Verrouillage atomique pour interdire toute concurrence sur le même code
        $verrou = Cache::lock($cleVerrou, 3);
        $aObtenuVerrou = $verrou->get();

        if (! $aObtenuVerrou) {
            return [
                'valide' => false,
                'motif' => 'deja_consomme_ou_en_cours',
            ];
        }

        try {
            // Consommation atomique (pull récupère et supprime immédiatement la clé)
            $donnees = Cache::pull($cleCache);
        } finally {
            $verrou->release();
        }

        if (! is_array($donnees) || empty($donnees['q'])) {
            return [
                'valide' => false,
                'motif' => 'inexistant_ou_deja_utilise',
            ];
        }

        // Vérification stricte de la session propriétaire
        if (! isset($donnees['session_id']) || $donnees['session_id'] !== $sessionId) {
            return [
                'valide' => false,
                'motif' => 'session_invalide',
            ];
        }

        // Vérification du délai de 10 secondes (protection supplémentaire si le cache n'a pas purgé)
        $ageEnSecondes = now()->timestamp - (int) ($donnees['cree_a'] ?? 0);
        if ($ageEnSecondes > self::DUREE_VALIDITE_SECONDES) {
            return [
                'valide' => false,
                'motif' => 'expire',
            ];
        }

        return [
            'valide' => true,
            'donnees' => $donnees,
        ];
    }

    /**
     * Exécute la recherche d'articles sur les archives du journal avec priorisation :
     * 1. Terme présent dans le titre (priorité 1)
     * 2. Terme présent dans le sous-titre / chapeau (priorité 2)
     * 3. Terme présent dans le corps de texte (priorité 3)
     * Classement secondaire par date de publication décroissante.
     */
    public function rechercherArticles(string $motsCles, array $filtres = [], int $parPage = self::NB_RESULTATS_MAX): LengthAwarePaginator
    {
        $requete = trim($motsCles);
        $categorieId = $filtres['categorie_id'] ?? null;
        $modeTransport = $filtres['mode'] ?? null;
        $format = $filtres['format'] ?? null;

        return Article::with(['auteur', 'categorie'])
            ->publie()
            ->when($requete !== '', function (Builder $q) use ($requete) {
                $terme = "%{$requete}%";
                $q->where(function (Builder $subQuery) use ($terme) {
                    $subQuery->where('titre', 'like', $terme)
                        ->orWhere('chapeau', 'like', $terme)
                        ->orWhere('contenu', 'like', $terme);
                })
                ->orderByRaw(
                    "CASE 
                        WHEN titre LIKE ? THEN 1 
                        WHEN chapeau LIKE ? THEN 2 
                        ELSE 3 
                    END ASC",
                    [$terme, $terme]
                );
            })
            ->when($categorieId, fn (Builder $q) => $q->where('categorie_id', $categorieId))
            ->when($modeTransport, fn (Builder $q) => $q->where('mode_transport_principal', $modeTransport))
            ->when($format, fn (Builder $q) => $q->where('format_article', $format))
            ->latest('date_publication')
            ->paginate($parPage);
    }

    /**
     * Retourne les catégories actives pour enrichir l'interface de résultats.
     */
    public function recupererCategoriesActives(): Collection
    {
        return Categorie::actif()->orderBy('ordre_affichage')->get();
    }

    /**
     * Générateur cryptographique d'un code de 16 caractères hexadécimaux imprévisibles.
     */
    protected function genererCodeCryptographique(): string
    {
        return bin2hex(random_bytes(8));
    }
}
