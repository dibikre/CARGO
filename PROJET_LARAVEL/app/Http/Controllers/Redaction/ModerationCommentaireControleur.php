<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationCommentaireControleur extends Controller
{
    /**
     * File de modération des commentaires des lecteurs.
     */
    public function index(Request $request): View
    {
        $statut = $request->query('statut', 'en_attente');

        $commentaires = Commentaire::with(['article', 'auteur'])
            ->where('statut', $statut)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('redaction.commentaires.index', compact('commentaires', 'statut'));
    }

    /**
     * Approuver et rendre public un commentaire.
     */
    public function approuver(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update(['statut' => 'approuve']);

        return back()->with('succes', 'Le commentaire a été approuvé et publié.');
    }

    /**
     * Rejeter un commentaire.
     */
    public function rejeter(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update(['statut' => 'rejete']);

        return back()->with('succes', 'Le commentaire a été rejeté.');
    }

    /**
     * Marquer un commentaire comme spam.
     */
    public function marquerSpam(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update(['statut' => 'spam']);

        return back()->with('succes', 'Le commentaire a été classé comme spam.');
    }

    /**
     * Supprimer définitivement un commentaire.
     */
    public function destroy(int $id): RedirectResponse
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->forceDelete();

        return back()->with('succes', 'Le commentaire a été supprimé définitivement.');
    }
}
