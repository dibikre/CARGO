<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
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

        return view('administration.commentaires.index', compact('commentaires', 'metriques', 'statut'));
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
