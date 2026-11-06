<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\AbonnementUtilisateur;
use App\Models\ConfigurationSysteme;
use App\Models\FormuleAbonnement;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AbonnementAdminControleur extends Controller
{
    /**
     * Supervision des formules et abonnés professionnels.
     */
    public function index(): View
    {
        $formules = FormuleAbonnement::withCount('abonnements')
            ->orderBy('ordre_affichage')
            ->get();

        $abonnements = AbonnementUtilisateur::with(['utilisateur', 'formule'])
            ->latest('date_debut')
            ->paginate(15);

        $metriques = [
            'total_abonnes_actifs' => AbonnementUtilisateur::actif()->count(),
            'total_commandes' => AbonnementUtilisateur::count(),
            'revenu_total' => AbonnementUtilisateur::sum('montant_paye_ttc') ?: 0,
        ];

        $abonnementsActifs = ConfigurationSysteme::abonnementsSontActifs();

        return view('administration.abonnements.index', compact('formules', 'abonnements', 'metriques', 'abonnementsActifs'));
    }

    /**
     * Basculer globalement le système d'abonnements (Monétisé <-> 100% Gratuit).
     */
    public function basculerPaywallGlobal(): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerAbonnements();

        JournalActivite::enregistrer(
            description: $nouvelEtat
                ? "Reactivated paid subscriptions & PRO paywall monetization"
                : "Disabled subscriptions: CARGO is now 100% Free Open Access for all readers",
            nomLog: 'administration',
            evenement: 'bascule_paywall_global',
            auteur: Auth::user(),
            donnees: ['abonnements_actifs' => $nouvelEtat]
        );

        $message = $nouvelEtat
            ? 'Subscriptions and PRO Paywall are now ACTIVE. Premium content is monetized.'
            : 'Subscriptions DEACTIVATED: The site is now 100% FREE for all readers (Open Access)!';

        return back()->with('succes', $message);
    }

    /**
     * Création d'une nouvelle formule d'abonnement.
     */
    public function storeFormule(Request $requete): RedirectResponse
    {
        $donnees = $requete->validate([
            'nom' => ['required', 'string', 'max:120'],
            'prix_ht' => ['required', 'numeric', 'min:0'],
            'taux_tva' => ['nullable', 'numeric', 'min:0'],
            'duree_en_jours' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $formule = FormuleAbonnement::create([
            'nom' => $donnees['nom'],
            'slug' => Str::slug($donnees['nom']),
            'prix_ht' => $donnees['prix_ht'],
            'taux_tva' => $donnees['taux_tva'] ?? 20.0,
            'duree_en_jours' => (int) $donnees['duree_en_jours'],
            'description' => $donnees['description'] ?? null,
            'est_actif' => true,
            'ordre_affichage' => FormuleAbonnement::count() + 1,
        ]);

        JournalActivite::enregistrer(
            description: "Created subscription plan '{$formule->nom}' ({$formule->prix_ht} EUR)",
            nomLog: 'administration',
            evenement: 'creation_formule_abonnement',
            auteur: Auth::user(),
            donnees: ['formule_id' => $formule->id]
        );

        return back()->with('succes', 'Subscription plan created.');
    }

    /**
     * Basculer l'état actif d'une formule.
     */
    public function basculerFormule(int $id): RedirectResponse
    {
        $formule = FormuleAbonnement::findOrFail($id);
        $nouvelEtat = ! $formule->est_actif;
        $formule->update(['est_actif' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: "Toggled subscription plan '{$formule->nom}' to " . ($nouvelEtat ? 'Active' : 'Inactive'),
            nomLog: 'administration',
            evenement: 'bascule_formule_abonnement',
            auteur: Auth::user(),
            donnees: ['formule_id' => $id, 'est_actif' => $nouvelEtat]
        );

        return back()->with('succes', "Subscription plan updated.");
    }

    /**
     * Modifier manuellement le statut d'un abonnement lecteur.
     */
    public function updateStatutAbonnement(int $id, Request $requete): RedirectResponse
    {
        $abonnement = AbonnementUtilisateur::findOrFail($id);
        $statut = $requete->validate([
            'statut' => ['required', 'in:actif,expire,annule,en_attente'],
        ])['statut'];

        $abonnement->update([
            'statut' => $statut,
            'date_fin' => $statut === 'actif' && $abonnement->date_fin < now() ? now()->addDays(30) : $abonnement->date_fin,
        ]);

        JournalActivite::enregistrer(
            description: "Updated subscriber #{$id} status to '{$statut}'",
            nomLog: 'administration',
            evenement: 'statut_abonnement_modifie',
            auteur: Auth::user(),
            donnees: ['abonnement_id' => $id, 'nouveau_statut' => $statut]
        );

        return back()->with('succes', 'Subscriber record updated.');
    }
}
