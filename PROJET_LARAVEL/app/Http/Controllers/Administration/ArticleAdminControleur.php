<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleAdminControleur extends Controller
{
    /**
     * Liste et supervision globale des articles du journal.
     */
    public function index(Request $requete): View
    {
        $recherche = $requete->input('recherche');
        $statutFiltre = $requete->input('statut');
        $categorieFiltre = $requete->input('categorie_id');

        $requeteArticles = Article::with(['auteur', 'categorie'])->latest('created_at');

        if (! empty($recherche)) {
            $requeteArticles->where('titre', 'like', "%{$recherche}%");
        }

        if (! empty($statutFiltre)) {
            $requeteArticles->where('statut', $statutFiltre);
        }

        if (! empty($categorieFiltre)) {
            $requeteArticles->where('categorie_id', $categorieFiltre);
        }

        $articles = $requeteArticles->paginate(15)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('administration.articles.index', compact('articles', 'categories', 'recherche', 'statutFiltre', 'categorieFiltre'));
    }

    /**
     * Basculer l'état de l'alerte urgente (bandeau flash sur le site public).
     */
    public function basculerAlerte(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $nouvelEtat = ! $article->est_urgent;

        $article->update(['est_urgent' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: "Toggled urgent alert to " . ($nouvelEtat ? 'ON' : 'OFF') . " for article #{$article->id}",
            nomLog: 'administration',
            evenement: 'bascule_alerte_urgente',
            auteur: Auth::user(),
            donnees: ['article_id' => $article->id, 'est_urgent' => $nouvelEtat]
        );

        return back()->with('succes', 'Urgent alert status updated.');
    }

    /**
     * Basculer l'exclusivité PRO (Paywall abonné).
     */
    public function basculerPro(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $nouvelEtat = ! $article->est_reserve_abonnes;

        $article->update(['est_reserve_abonnes' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: "Toggled PRO access to " . ($nouvelEtat ? 'PRO' : 'PUBLIC') . " for article #{$article->id}",
            nomLog: 'administration',
            evenement: 'bascule_paywall_pro',
            auteur: Auth::user(),
            donnees: ['article_id' => $article->id, 'est_reserve_abonnes' => $nouvelEtat]
        );

        return back()->with('succes', 'PRO subscription restriction updated.');
    }

    /**
     * Basculer la mise en avant à la une.
     */
    public function basculerAlaUne(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $nouvelEtat = ! $article->est_a_la_une;

        $article->update(['est_a_la_une' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: "Toggled featured headline to " . ($nouvelEtat ? 'FEATURED' : 'STANDARD') . " for article #{$article->id}",
            nomLog: 'administration',
            evenement: 'bascule_a_la_une',
            auteur: Auth::user(),
            donnees: ['article_id' => $article->id, 'est_a_la_une' => $nouvelEtat]
        );

        return back()->with('succes', 'Featured headline status updated.');
    }

    /**
     * Supprimer définitivement ou corbeille un article.
     */
    public function destroy(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $titre = $article->titre;
        $article->delete();

        JournalActivite::enregistrer(
            description: "Deleted article #{$id} ({$titre})",
            nomLog: 'administration',
            evenement: 'suppression_article',
            auteur: Auth::user(),
            donnees: ['article_id' => $id, 'titre' => $titre]
        );

        return back()->with('succes', 'Article removed successfully.');
    }
}
