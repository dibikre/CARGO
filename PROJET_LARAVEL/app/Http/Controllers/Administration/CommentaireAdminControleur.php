<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\ConfigurationSysteme;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommentaireAdminControleur extends Controller
{
    /**
     * Modération centralisée des commentaires du journal.
     */
    public function index(Request $requete): View
    {
        $statut = $requete->input('statut');

        $requeteCommentaires = Commentaire::with(['article', 'auteur'])
            ->latest('created_at');

        if (! empty($statut)) {
            $requeteCommentaires->where('statut', $statut);
        }

        $commentaires = $requeteCommentaires->paginate(20)->withQueryString();

        $metriques = [
            'total' => Commentaire::count(),
            'en_attente' => Commentaire::where('statut', 'en_attente')->count(),
            'approuve' => Commentaire::where('statut', 'approuve')->count(),
            'spam' => Commentaire::where('statut', 'spam')->count(),
        ];

        $liensCliquables = ConfigurationSysteme::liensCommentairesSontCliquables();

        return view('administration.commentaires.index', compact('commentaires', 'metriques', 'statut', 'liensCliquables'));
    }

    /**
     * Basculer le réglage global sur les liens cliquables ou nettoyés dans les commentaires.
     */
    public function basculerLiens(Request $requete): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerLiensCommentairesCliquables();
        $message = $nouvelEtat
            ? 'Les liens des commentaires et sites web sont maintenant CLIQUABLES (avec attributs nofollow et noopener sécurisés).'
            : 'Les liens des commentaires et sites web sont maintenant NETTOYÉS (affichés en texte brut sécurisé anti-spam).';

        JournalActivite::enregistrer(
            description: "Modification réglage liens commentaires : " . ($nouvelEtat ? 'cliquables' : 'nettoyés'),
            nomLog: 'configuration',
            evenement: 'modification_reglage_liens_commentaires',
            auteur: Auth::user(),
            donnees: ['liens_cliquables' => $nouvelEtat]
        );

        return back()->with('succes', $message);
    }

    /**
     * Approuver un commentaire.
     */
    public function approuver(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update(['statut' => 'approuve']);

        JournalActivite::enregistrer(
            description: "Approved reader comment #{$id}",
            nomLog: 'moderation',
            evenement: 'approbation_commentaire',
            auteur: Auth::user(),
            donnees: ['commentaire_id' => $id]
        );

        return back()->with('succes', 'Comment approved and published.');
    }

    /**
     * Marquer comme spam.
     */
    public function marquerSpam(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update(['statut' => 'spam']);

        return back()->with('succes', 'Comment flagged as spam.');
    }

    /**
     * Supprimer définitivement un commentaire.
     */
    public function destroy(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->delete();

        return back()->with('succes', 'Comment deleted.');
    }
}
