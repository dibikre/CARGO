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
    public function index(): View
    {
        $formules = FormuleAbonnement::actif()->get();

        return view('journal.abonnements.tarifs', compact('formules'));
    }

    /**
     * Traiter la souscription à une formule.
     */
    public function souscrire(AbonnementFormRequest $request, ServiceAbonnementLecteur $serviceAbonnement): RedirectResponse
    {
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
        $abonnementActif = $utilisateur->abonnementActif()->with('formule')->first();
        $historique = $utilisateur->abonnements()->with('formule')->latest()->get();

        return view('journal.abonnements.espace', compact('utilisateur', 'abonnementActif', 'historique'));
    }
}
