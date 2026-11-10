<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationSysteme;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametreAdminControleur extends Controller
{
    /**
     * Bascule l'autorisation accordée aux rédacteurs de supprimer leur propre compte.
     */
    public function basculerSuppressionCompteRedacteur(Request $requete): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerSuppressionCompteRedacteur();

        JournalActivite::enregistrer(
            description: ($nouvelEtat ? 'Enabled' : 'Disabled') . ' writer self-service account deletion',
            nomLog: 'securite',
            evenement: 'bascule_auto_suppression_redacteur',
            auteur: Auth::user(),
            donnees: [
                'nouvel_etat' => $nouvelEtat,
            ]
        );

        $message = $nouvelEtat
            ? 'Writer self-service account deletion has been ENABLED. Writers can now delete their account from their settings.'
            : 'Writer self-service account deletion has been DISABLED. The option is now hidden from the writers settings.';

        return back()->with('succes', $message);
    }
}
