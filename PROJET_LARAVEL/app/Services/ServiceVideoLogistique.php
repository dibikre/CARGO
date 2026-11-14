<?php

namespace App\Services;

use App\Models\VideoLogistique;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ServiceVideoLogistique
{
    /**
     * Récupère la liste paginée des vidéos selon les critères de recherche, filtre et tri.
     */
    public function recupererVideosFiltrees(array $criteres = [], int $parPage = 12): LengthAwarePaginator
    {
        $requete = VideoLogistique::query()->with('auteur');

        // Recherche par mot-clé (titre, source, description)
        if (! empty($criteres['recherche'])) {
            $requete->recherche(trim((string) $criteres['recherche']));
        }

        // Filtre de plateforme
        if (! empty($criteres['plateforme'])) {
            $requete->plateforme((string) $criteres['plateforme']);
        }

        // Filtre de statut (actif / inactif)
        if (! empty($criteres['statut'])) {
            $requete->statut((string) $criteres['statut']);
        }

        // Tri
        $tri = $criteres['tri'] ?? 'recent';
        switch ($tri) {
            case 'ancien':
                $requete->oldest();
                break;
            case 'titre_az':
                $requete->orderBy('titre', 'asc');
                break;
            case 'titre_za':
                $requete->orderBy('titre', 'desc');
                break;
            case 'ordre':
                $requete->orderBy('ordre_affichage', 'asc')->latest();
                break;
            case 'recent':
            default:
                $requete->latest();
                break;
        }

        return $requete->paginate($parPage);
    }

    /**
     * Calcule les indicateurs clés de synthèse sur l'ensemble de la vidéothèque.
     */
    public function recupererIndicateurs(): array
    {
        $total = VideoLogistique::count();
        $actifs = VideoLogistique::where('est_actif', true)->count();
        $inactifs = $total - $actifs;

        $youtube = VideoLogistique::where(function ($q) {
            $q->where('url_embed', 'like', '%youtube%')
              ->orWhere('url_embed', 'like', '%youtu.be%');
        })->count();

        $vimeo = VideoLogistique::where('url_embed', 'like', '%vimeo%')->count();
        $autres = max(0, $total - $youtube - $vimeo);

        return [
            'total' => $total,
            'actifs' => $actifs,
            'inactifs' => $inactifs,
            'youtube' => $youtube,
            'vimeo' => $vimeo,
            'autres' => $autres,
        ];
    }

    /**
     * Crée une nouvelle vidéo logistique avec un slug unique garanti.
     */
    public function creerVideo(array $donnees, ?int $userId): VideoLogistique
    {
        $titre = trim($donnees['titre']);
        $slugBase = Str::slug($titre);
        $slug = $slugBase ?: 'video-logistique';
        $compteur = 1;

        while (VideoLogistique::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $compteur++;
        }

        return VideoLogistique::create([
            'user_id' => $userId,
            'titre' => $titre,
            'slug' => $slug,
            'url_embed' => trim($donnees['url_embed']),
            'source' => ! empty($donnees['source']) ? trim($donnees['source']) : 'Rédaction CARGO',
            'duree' => ! empty($donnees['duree']) ? trim($donnees['duree']) : null,
            'description' => ! empty($donnees['description']) ? trim($donnees['description']) : null,
            'est_actif' => isset($donnees['est_actif']) ? (bool) $donnees['est_actif'] : true,
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
        ]);
    }

    /**
     * Supprime une vidéo par son identifiant unique.
     */
    public function supprimerVideo(int $id): bool
    {
        $video = VideoLogistique::findOrFail($id);
        return (bool) $video->delete();
    }

    /**
     * Supprime un lot de vidéos sélectionnées par leurs identifiants.
     */
    public function supprimerLotVideos(array $ids): int
    {
        $idsValides = array_filter(array_map('intval', $ids), fn ($id) => $id > 0);
        if (empty($idsValides)) {
            return 0;
        }

        return VideoLogistique::whereIn('id', $idsValides)->delete();
    }

    /**
     * Bascule l'état actif / inactif d'une vidéo sur la page d'accueil.
     */
    public function basculerStatut(int $id): VideoLogistique
    {
        $video = VideoLogistique::findOrFail($id);
        $video->est_actif = ! $video->est_actif;
        $video->save();

        return $video;
    }
}
