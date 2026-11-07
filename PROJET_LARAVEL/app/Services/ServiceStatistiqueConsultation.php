<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StatistiqueArticle;
use App\Models\VueArticleIp;
use Illuminate\Database\UniqueConstraintViolationException;
use Throwable;

class ServiceStatistiqueConsultation
{
    /**
     * Enregistrer une vue d'article basée sur l'adresse IP unique du visiteur.
     * Si l'adresse IP a déjà consulté cet article, aucun nouveau visionnage n'est comptabilisé.
     *
     * @param Article $article L'article consulté
     * @param string|null $adresseIp L'adresse IP du lecteur
     * @param string|null $agentUtilisateur En-tête User-Agent optionnel
     * @return bool True si une nouvelle vue unique par IP a été enregistrée, False si déjà vue
     */
    public function enregistrerVueParIp(Article $article, ?string $adresseIp = null, ?string $agentUtilisateur = null): bool
    {
        // 1. Résolution et normalisation de l'adresse IP
        $ipNettoyee = trim($adresseIp ?? '');
        if (empty($ipNettoyee)) {
            $ipNettoyee = request()?->ip() ?? '127.0.0.1';
        }

        // Nettoyage de l'en-tête agent utilisateur
        $agentTronque = $agentUtilisateur ?? request()?->userAgent();
        if ($agentTronque !== null && strlen($agentTronque) > 255) {
            $agentTronque = substr($agentTronque, 0, 255);
        }

        try {
            // 2. Vérification d'antériorité : cette adresse IP a-t-elle déjà vu cet article ?
            $dejaVu = VueArticleIp::where('article_id', $article->id)
                ->where('adresse_ip', $ipNettoyee)
                ->exists();

            if ($dejaVu) {
                // L'adresse IP a déjà visionné cet article : aucune nouvelle vue n'est incrémentée
                return false;
            }

            // 3. Enregistrement atomique de l'adresse IP pour cet article
            try {
                VueArticleIp::create([
                    'article_id' => $article->id,
                    'adresse_ip' => $ipNettoyee,
                    'agent_utilisateur' => $agentTronque,
                ]);
            } catch (UniqueConstraintViolationException) {
                // Gestion de la concurrence simultanée (race condition) : l'IP vient d'être insérée en parallèle
                return false;
            }

            // 4. Incrémentation du compteur de vues de l'article (reflétant le nombre d'IP uniques)
            $article->incrementerVues();

            // 5. Consolidation dans la table des statistiques journalières
            $this->consoliderStatistiqueJournaliere($article->id);

            return true;
        } catch (Throwable $erreur) {
            // Sécurité absolue : un incident de métriques ne doit jamais bloquer la lecture de l'article (erreur 500)
            report($erreur);
            return false;
        }
    }

    /**
     * Méthode de compatibilité pour enregistrer une vue (délègue systématiquement à l'enregistrement par IP).
     *
     * @param Article $article
     * @param string|null $adresseIp
     * @param string|null $agentUtilisateur
     * @return bool
     */
    public function enregistrerVue(Article $article, ?string $adresseIp = null, ?string $agentUtilisateur = null): bool
    {
        return $this->enregistrerVueParIp($article, $adresseIp, $agentUtilisateur);
    }

    /**
     * Vérifier si une adresse IP a déjà consulté un article donné.
     */
    public function ipADejaConsulte(int $articleId, string $adresseIp): bool
    {
        $ipNettoyee = trim($adresseIp);
        if (empty($ipNettoyee)) {
            return false;
        }

        return VueArticleIp::where('article_id', $articleId)
            ->where('adresse_ip', $ipNettoyee)
            ->exists();
    }

    /**
     * Obtenir le décompte réel des adresses IP uniques ayant consulté un article.
     */
    public function obtenirTotalVuesIp(int $articleId): int
    {
        return VueArticleIp::where('article_id', $articleId)->count();
    }

    /**
     * Mettre à jour les statistiques journalières consolidées pour l'article.
     */
    protected function consoliderStatistiqueJournaliere(int $articleId): void
    {
        $dateAujourdhui = now()->format('Y-m-d');

        try {
            // Tenter d'incrémenter la statistique quotidienne existante
            $lignesAffectees = StatistiqueArticle::where('article_id', $articleId)
                ->where(function ($requete) use ($dateAujourdhui) {
                    $requete->where('date_statistique', $dateAujourdhui)
                        ->orWhere('date_statistique', $dateAujourdhui . ' 00:00:00');
                })
                ->increment('nombre_vues');

            // Création initiale si aucune statistique n'existe pour cette date
            if ($lignesAffectees === 0) {
                StatistiqueArticle::create([
                    'article_id' => $articleId,
                    'date_statistique' => $dateAujourdhui,
                    'nombre_vues' => 1,
                    'nombre_lectures_uniques' => 1,
                    'nombre_partages' => 0,
                ]);
            }
        } catch (UniqueConstraintViolationException) {
            try {
                StatistiqueArticle::where('article_id', $articleId)
                    ->where(function ($requete) use ($dateAujourdhui) {
                        $requete->where('date_statistique', $dateAujourdhui)
                            ->orWhere('date_statistique', 'like', $dateAujourdhui . '%');
                    })
                    ->increment('nombre_vues');
            } catch (Throwable $e) {
                report($e);
            }
        } catch (Throwable $e) {
            report($e);
        }
    }
}
