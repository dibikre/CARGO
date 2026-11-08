<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\AbonneNewsletter;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewsletterAdminControleur extends Controller
{
    /**
     * Gestion et supervision des abonnés à la veille logistique.
     */
    public function index(Request $requete): View
    {
        $recherche = $requete->input('recherche');
        $statut = $requete->input('statut');

        $requeteAbonnes = AbonneNewsletter::latest('created_at');

        if (! empty($recherche)) {
            $requeteAbonnes->where(function ($q) use ($recherche) {
                $q->where('email', 'like', "%{$recherche}%")
                  ->orWhere('nom_complet', 'like', "%{$recherche}%")
                  ->orWhere('entreprise', 'like', "%{$recherche}%");
            });
        }

        if ($statut === 'confirme') {
            $requeteAbonnes->confirme();
        } elseif ($statut === 'en_attente') {
            $requeteAbonnes->enAttente();
        }

        $abonnes = $requeteAbonnes->paginate(20)->withQueryString();

        $metriques = [
            'total' => AbonneNewsletter::count(),
            'actifs' => AbonneNewsletter::where('est_actif', true)->count(),
            'confirmes' => AbonneNewsletter::confirme()->count(),
        ];

        return view('administration.newsletter.index', compact('abonnes', 'metriques', 'recherche', 'statut'));
    }

    /**
     * Basculer l'état actif/inactif d'un abonné.
     */
    public function basculerEtat(int $id): RedirectResponse
    {
        $abonne = AbonneNewsletter::findOrFail($id);
        $nouvelEtat = ! $abonne->est_actif;
        $abonne->update(['est_actif' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: "Toggled newsletter subscriber '{$abonne->email}' to " . ($nouvelEtat ? 'Active' : 'Unsubscribed'),
            nomLog: 'administration',
            evenement: 'bascule_abonne_newsletter',
            auteur: Auth::user(),
            donnees: ['abonne_id' => $id, 'est_actif' => $nouvelEtat]
        );

        return back()->with('succes', 'Subscriber status updated.');
    }

    /**
     * Supprimer définitivement un abonné RGPD.
     */
    public function destroy(int $id): RedirectResponse
    {
        $abonne = AbonneNewsletter::findOrFail($id);
        $email = $abonne->email;
        $abonne->delete();

        JournalActivite::enregistrer(
            description: "Permanently purged newsletter contact '{$email}' (GDPR request)",
            nomLog: 'administration',
            evenement: 'suppression_abonne_newsletter',
            auteur: Auth::user(),
            donnees: ['email' => $email]
        );

        return back()->with('succes', "Contact '{$email}' has been removed.");
    }
}
