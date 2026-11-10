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

    /**
     * Bascule l'affichage public de la photo du journaliste sur les articles.
     */
    public function basculerPhotoAuteur(Request $requete): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerPhotoAuteurArticles();

        JournalActivite::enregistrer(
            description: ($nouvelEtat ? 'Enabled' : 'Disabled') . ' public author photo (40x40) display on articles',
            nomLog: 'administration',
            evenement: 'bascule_photo_auteur_articles',
            auteur: Auth::user(),
            donnees: [
                'nouvel_etat' => $nouvelEtat,
            ]
        );

        $message = $nouvelEtat
            ? 'Author photo display (40x40) on public articles has been ENABLED.'
            : 'Author photo display on public articles has been DISABLED.';

        return back()->with('succes', $message);
    }
}
