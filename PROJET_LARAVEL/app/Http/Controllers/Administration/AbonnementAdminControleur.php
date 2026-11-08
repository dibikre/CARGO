<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormuleAbonnementFormRequest;
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
        $formules = FormuleAbonnement::withCount('abonnements')->orderBy('ordre_affichage')->get();
        $abonnements = AbonnementUtilisateur::with(['utilisateur', 'formule'])->latest('date_debut')->paginate(15);
        $metriques = [
            'total_abonnes_actifs' => AbonnementUtilisateur::actif()->count(),
            'total_commandes' => AbonnementUtilisateur::count(),
            'revenu_total' => AbonnementUtilisateur::sum('montant_paye_ttc') ?: 0,
        ];

        $abonnementsActifs = ConfigurationSysteme::abonnementsSontActifs();
        $souscriptionsActives = ConfigurationSysteme::souscriptionsSontActives();

        return view('administration.abonnements.index', compact(
            'formules', 'abonnements', 'metriques', 'abonnementsActifs', 'souscriptionsActives'
        ));
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
     * Activer ou désactiver la possibilité pour les lecteurs de souscrire à un abonnement.
     */
    public function basculerSouscriptionsGlobales(): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerSouscriptions();
        JournalActivite::enregistrer(
            description: $nouvelEtat
                ? "Enabled subscription purchases platform-wide"
                : "Disabled subscription purchases: readers cannot subscribe to new plans",
            nomLog: 'administration',
            evenement: 'bascule_souscriptions_globales',
            auteur: Auth::user(),
            donnees: ['souscriptions_actives' => $nouvelEtat]
        );

        $message = $nouvelEtat
            ? 'Subscription sign-ups and purchases are now ENABLED.'
            : 'Subscription sign-ups and purchases are now DISABLED platform-wide.';

        return back()->with('succes', $message);
    }

    /**
     * Création d'une nouvelle formule d'abonnement.
     */
    public function storeFormule(FormuleAbonnementFormRequest $requete): RedirectResponse
    {
        $donnees = $requete->validated();
        $slug = $this->genererSlugUnique($donnees['nom']);

        $formule = FormuleAbonnement::create([
            'nom' => $donnees['nom'],
            'slug' => $slug,
            'description' => $donnees['description'] ?? null,
            'prix_ht' => $donnees['prix_ht'],
            'taux_tva' => $donnees['taux_tva'] ?? 20.0,
            'duree_en_jours' => (int) $donnees['duree_en_jours'],
            'acces_archives_illimite' => $requete->boolean('acces_archives_illimite'),
            'acces_dossiers_exclusifs' => $requete->boolean('acces_dossiers_exclusifs'),
            'nombre_utilisateurs_max' => (int) ($donnees['nombre_utilisateurs_max'] ?? 1),
            'est_actif' => $requete->has('est_actif') ? $requete->boolean('est_actif') : true,
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? (FormuleAbonnement::count() + 1)),
        ]);

        JournalActivite::enregistrer(
            description: "Created subscription plan '{$formule->nom}' ({$formule->prix_ht} EUR)",
            nomLog: 'administration',
            evenement: 'creation_formule_abonnement',
            auteur: Auth::user(),
            donnees: ['formule_id' => $formule->id]
        );

        return back()->with('succes', "Subscription plan '{$formule->nom}' created successfully.");
    }

    /**
     * Mettre à jour une formule d'abonnement existante.
     */
    public function updateFormule(int $id, FormuleAbonnementFormRequest $requete): RedirectResponse
    {
        $formule = FormuleAbonnement::findOrFail($id);
        $donnees = $requete->validated();

        if ($donnees['nom'] !== $formule->nom) {
            $formule->slug = $this->genererSlugUnique($donnees['nom'], $formule->id);
        }

        $formule->nom = $donnees['nom'];
        $formule->description = $donnees['description'] ?? null;
        $formule->prix_ht = $donnees['prix_ht'];
        $formule->taux_tva = $donnees['taux_tva'] ?? 20.00;
        $formule->duree_en_jours = (int) $donnees['duree_en_jours'];
        $formule->acces_archives_illimite = $requete->boolean('acces_archives_illimite');
        $formule->acces_dossiers_exclusifs = $requete->boolean('acces_dossiers_exclusifs');
        $formule->nombre_utilisateurs_max = (int) ($donnees['nombre_utilisateurs_max'] ?? 1);
        $formule->est_actif = $requete->boolean('est_actif');
        if (isset($donnees['ordre_affichage'])) {
            $formule->ordre_affichage = (int) $donnees['ordre_affichage'];
        }
        $formule->save();

        JournalActivite::enregistrer(
            description: "Updated subscription plan '{$formule->nom}' (ID: {$formule->id})",
            nomLog: 'administration',
            evenement: 'modification_formule_abonnement',
            auteur: Auth::user(),
            donnees: ['formule_id' => $formule->id, 'prix_ht' => $formule->prix_ht]
        );

        return back()->with('succes', "Subscription plan '{$formule->nom}' updated successfully.");
    }

    /**
     * Supprimer une formule d'abonnement.
     */
    public function destroyFormule(int $id): RedirectResponse
    {
        $formule = FormuleAbonnement::withCount('abonnements')->findOrFail($id);

        if ($formule->abonnements_count > 0) {
            return back()->with('erreur', "Cannot delete plan '{$formule->nom}' because {$formule->abonnements_count} subscriber record(s) are linked to it. Please deactivate the plan instead to stop new sign-ups while maintaining subscriber records.");
        }

        $nom = $formule->nom;
        $formule->delete();

        JournalActivite::enregistrer(
            description: "Deleted subscription plan '{$nom}' (ID: {$id})",
            nomLog: 'administration',
            evenement: 'suppression_formule_abonnement',
            auteur: Auth::user(),
            donnees: ['formule_id' => $id, 'nom' => $nom]
        );

        return back()->with('succes', "Subscription plan '{$nom}' deleted successfully.");
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

        return back()->with('succes', "Subscription plan status updated to " . ($nouvelEtat ? 'Active' : 'Inactive') . ".");
    }

    /**
     * Modifier manuellement le statut d'un abonnement lecteur.
     */
    public function updateStatutAbonnement(int $id, Request $requete): RedirectResponse
    {
        $abonnement = AbonnementUtilisateur::findOrFail($id);
        $statut = $requete->validate(['statut' => ['required', 'in:actif,expire,annule,en_attente']])['statut'];
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

    /**
     * Générer un slug unique pour une formule d'abonnement.
     */
    protected function genererSlugUnique(string $nom, ?int $exclureId = null): string
    {
        $slugBase = Str::slug($nom);
        $slug = $slugBase;
        $compteur = 1;

        while (FormuleAbonnement::where('slug', $slug)
            ->when($exclureId, fn ($q) => $q->where('id', '!=', $exclureId))
            ->exists()) {
            $slug = "{$slugBase}-{$compteur}";
            $compteur++;
        }

        return $slug;
    }
}
