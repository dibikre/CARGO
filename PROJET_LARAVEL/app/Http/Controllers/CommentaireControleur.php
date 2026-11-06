<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentaireFormRequest;
use App\Models\Commentaire;
use Illuminate\Http\RedirectResponse;

class CommentaireControleur extends Controller
{
    /**
     * Enregistrer un nouveau commentaire de lecteur soumis à modération.
     */
    public function store(CommentaireFormRequest $request): RedirectResponse
    {
        $donnees = $request->validated();
        $utilisateur = $request->user();

        Commentaire::create([
            'article_id' => $donnees['article_id'],
            'user_id' => $utilisateur?->id,
            'parent_id' => $donnees['parent_id'] ?? null,
            'auteur_nom' => $utilisateur ? $utilisateur->name : $donnees['auteur_nom'],
            'auteur_email' => $utilisateur ? $utilisateur->email : $donnees['auteur_email'],
            'contenu' => strip_tags($donnees['contenu']),
            'statut' => 'en_attente',
            'ip_adresse' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 191),
        ]);

        return back()->with('succes', 'Votre commentaire a été soumis avec succès et sera publié après validation par la modération.');
    }

    /**
     * Ajouter un vote positif à un commentaire.
     */
    public function voter(int $id): RedirectResponse
    {
        $commentaire = Commentaire::approuve()->findOrFail($id);
        $commentaire->increment('votes_positifs');

        return back()->with('succes', 'Merci pour votre vote.');
    }

    /**
     * Signaler un commentaire inapproprié.
     */
    public function signaler(int $id): RedirectResponse
    {
        $commentaire = Commentaire::approuve()->findOrFail($id);
        $commentaire->increment('signalements_compteur');

        return back()->with('succes', 'Le commentaire a été signalé à notre équipe éditoriale.');
    }
}
