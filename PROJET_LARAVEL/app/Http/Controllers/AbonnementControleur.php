<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbonnementFormRequest;
use App\Models\FormuleAbonnement;
use App\Services\ServiceAbonnementLecteur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbonnementControleur extends Controller
{
    /**
     * Présenter la grille des formules d'abonnements lecteurs et entreprises.
     */
    public function index(Request $request): View
    {
        $abonnementsActifs = \App\Models\ConfigurationSysteme::abonnementsSontActifs();
        $souscriptionsActives = \App\Models\ConfigurationSysteme::souscriptionsSontActives();
        $formules = FormuleAbonnement::actif()->get();
        $utilisateur = $request->user();
        $abonnementActif = $utilisateur
            ? $utilisateur->abonnementActif()->with('formule')->first()
            : null;

        return view('journal.abonnements.tarifs', compact(
            'formules',
            'abonnementsActifs',
            'souscriptionsActives',
            'abonnementActif'
        ));
    }

    /**
     * Traiter la souscription à une formule.
     */
    public function souscrire(AbonnementFormRequest $request, ServiceAbonnementLecteur $serviceAbonnement): RedirectResponse
    {
        if (! \App\Models\ConfigurationSysteme::souscriptionsSontActives()) {
            return back()->with('erreur', 'New subscription sign-ups and purchases are currently disabled by the administration.');
        }

        if (! \App\Models\ConfigurationSysteme::abonnementsSontActifs()) {
            return back()->with('erreur', 'The site is currently in 100% Free Open Access mode; subscriptions are not required.');
        }

        $donnees = $request->validated();
        $utilisateur = $request->user();

        $formule = FormuleAbonnement::actif()->findOrFail($donnees['formule_id']);

        $abonnement = $serviceAbonnement->souscrireAbonnement(
            $utilisateur,
            $formule,
            $donnees['mode_paiement']
        );

        return redirect()->route('abonnements.confirmation', ['reference' => $abonnement->reference_commande])
            ->with('succes', 'Félicitations ! Votre abonnement professionnel est désormais actif.');
    }

    /**
     * Page de confirmation de souscription.
     */
    public function confirmation(string $reference, Request $request): View
    {
        $abonnement = $request->user()
            ->abonnements()
            ->with('formule')
            ->where('reference_commande', $reference)
            ->firstOrFail();

        return view('journal.abonnements.confirmation', compact('abonnement'));
    }

    /**
     * Espace de gestion de l'abonnement du lecteur connecté.
     */
    public function monAbonnement(Request $request): View
    {
        $utilisateur = $request->user();
        $abonnementsActifs = \App\Models\ConfigurationSysteme::abonnementsSontActifs();
        $souscriptionsActives = \App\Models\ConfigurationSysteme::souscriptionsSontActives();
        $abonnementActif = $utilisateur->abonnementActif()->with('formule')->first();
        $historique = $utilisateur->abonnements()->with('formule')->latest()->get();
        $formulesDisponibles = FormuleAbonnement::actif()->get();

        return view('journal.abonnements.espace', compact(
            'utilisateur',
            'abonnementActif',
            'historique',
            'abonnementsActifs',
            'souscriptionsActives',
            'formulesDisponibles'
        ));
    }
}
