<?php

namespace App\Services;

use App\Models\AbonnementUtilisateur;
use App\Models\Article;
use App\Models\FormuleAbonnement;
use App\Models\User;
use Illuminate\Support\Str;

class ServiceAbonnementLecteur
{
    /**
     * Vérifier si un utilisateur peut consulter un article (gestion du paywall).
     */
    public function peutConsulterArticle(?User $utilisateur, Article $article): bool
    {
        // Si l'article n'est pas réservé aux abonnés, tout le monde peut le lire
        if (! $article->est_reserve_abonnes) {
            return true;
        }

        // Si l'utilisateur n'est pas connecté
        if (! $utilisateur) {
            return false;
        }

        // Les membres de la rédaction ont accès complet
        if ($utilisateur->estMembreRedaction()) {
            return true;
        }

        // Vérifier l'abonnement actif
        return $utilisateur->estAbonne();
    }

    /**
     * Créer et activer un abonnement lecteur.
     */
    public function souscrireAbonnement(User $utilisateur, FormuleAbonnement $formule, string $modePaiement): AbonnementUtilisateur
    {
        $dateDebut = now();
        $dateFin = now()->addDays($formule->duree_en_jours);

        return AbonnementUtilisateur::create([
            'user_id' => $utilisateur->id,
            'formule_id' => $formule->id,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'statut' => 'actif',
            'renouvellement_automatique' => false,
            'reference_commande' => 'CMD-' . strtoupper(Str::random(10)),
            'montant_paye_ttc' => $formule->prix_ttc,
            'mode_paiement' => $modePaiement,
        ]);
    }
}
