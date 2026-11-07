<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\JournalActivite;
use App\Services\ServiceGestionImage;
use App\Services\ServicePublicationArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleAdminControleur extends Controller
{
    public function __construct(
        protected ServicePublicationArticle $servicePublication,
        protected ServiceGestionImage $serviceImage
    ) {}

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
     * Formulaire administrateur de création d'un article (maintient le layout admin).
     */
    public function create(): View
    {
        $categories = Categorie::actif()->orderBy('nom')->get();

        return view('administration.articles.create', compact('categories'));
    }

    /**
     * Enregistrer un nouvel article avec les privilèges administrateur.
     */
    public function store(Request $requete): RedirectResponse
    {
        $donnees = $requete->validate([
            'titre' => 'required|string|max:191',
            'categorie_id' => 'required|exists:categories,id',
            'mode_transport_principal' => 'required|string|max:30',
            'format_article' => 'required|string|max:30',
            'chapeau' => 'nullable|string',
            'contenu' => 'required|string',
            'image_principale' => 'nullable|string|max:191',
            'legende_image' => 'nullable|string|max:191',
            'credit_photo' => 'nullable|string|max:120',
            'statut' => 'required|string|in:brouillon,en_relecture,publie,archive',
            'fichier_image' => 'nullable|image|max:10240',
        ]);

        $cheminImage = $donnees['image_principale'] ?? null;
        if ($requete->hasFile('fichier_image')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($requete->file('fichier_image'), 'articles');
        } elseif (! empty($cheminImage) && Str::startsWith($cheminImage, ['http://', 'https://'])) {
            $imageLocale = $this->serviceImage->telechargerEtStockerImage($cheminImage, 'articles');
            if ($imageLocale) {
                $cheminImage = $imageLocale;
            }
        }

        $slug = $this->servicePublication->genererSlugUnique($donnees['titre']);
        $tempsLecture = $this->servicePublication->calculerTempsLecture($donnees['contenu']);

        $article = Article::create([
            'auteur_id' => Auth::id(),
            'categorie_id' => $donnees['categorie_id'],
            'titre' => $donnees['titre'],
            'slug' => $slug,
            'chapeau' => $donnees['chapeau'] ?? null,
            'contenu' => $donnees['contenu'],
            'image_principale' => $cheminImage,
            'legende_image' => $donnees['legende_image'] ?? null,
            'credit_photo' => $donnees['credit_photo'] ?? null,
            'statut' => $donnees['statut'],
            'format_article' => $donnees['format_article'],
            'mode_transport_principal' => $donnees['mode_transport_principal'],
            'est_a_la_une' => $requete->boolean('est_a_la_une'),
            'est_urgent' => $requete->boolean('est_urgent'),
            'est_reserve_abonnes' => $requete->boolean('est_reserve_abonnes'),
            'temps_lecture_minutes' => $tempsLecture,
            'date_publication' => $donnees['statut'] === 'publie' ? now() : null,
        ]);

        JournalActivite::enregistrer("Admin created article #{$article->id} ({$article->titre})", 'administration', 'creation_article_admin', Auth::user(), ['article_id' => $article->id]);

        return redirect()->route('admin.articles.index')->with('succes', 'Article created successfully with administrator privileges.');
    }

    /**
     * Formulaire administrateur de modification d'un article (maintient le layout admin).
     */
    public function edit(int $id): View
    {
        $article = Article::findOrFail($id);
        $categories = Categorie::actif()->orderBy('nom')->get();

        return view('administration.articles.edit', compact('article', 'categories'));
    }

    /**
     * Mettre à jour un article avec les privilèges administrateur.
     */
    public function update(Request $requete, int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);

        $donnees = $requete->validate([
            'titre' => 'required|string|max:191',
            'categorie_id' => 'required|exists:categories,id',
            'mode_transport_principal' => 'required|string|max:30',
            'format_article' => 'required|string|max:30',
            'chapeau' => 'nullable|string',
            'contenu' => 'required|string',
            'image_principale' => 'nullable|string|max:191',
            'legende_image' => 'nullable|string|max:191',
            'credit_photo' => 'nullable|string|max:120',
            'statut' => 'required|string|in:brouillon,en_relecture,publie,archive',
            'fichier_image' => 'nullable|image|max:10240',
        ]);

        $cheminImage = $donnees['image_principale'] ?? $article->image_principale;
        if ($requete->hasFile('fichier_image')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($requete->file('fichier_image'), 'articles');
        } elseif (! empty($donnees['image_principale']) && Str::startsWith($donnees['image_principale'], ['http://', 'https://'])) {
            $imageLocale = $this->serviceImage->telechargerEtStockerImage($donnees['image_principale'], 'articles');
            if ($imageLocale) {
                $cheminImage = $imageLocale;
            }
        }

        $tempsLecture = $this->servicePublication->calculerTempsLecture($donnees['contenu']);

        $article->update([
            'categorie_id' => $donnees['categorie_id'],
            'titre' => $donnees['titre'],
            'chapeau' => $donnees['chapeau'] ?? null,
            'contenu' => $donnees['contenu'],
            'image_principale' => $cheminImage,
            'legende_image' => $donnees['legende_image'] ?? null,
            'credit_photo' => $donnees['credit_photo'] ?? null,
            'statut' => $donnees['statut'],
            'format_article' => $donnees['format_article'],
            'mode_transport_principal' => $donnees['mode_transport_principal'],
            'est_a_la_une' => $requete->boolean('est_a_la_une'),
            'est_urgent' => $requete->boolean('est_urgent'),
            'est_reserve_abonnes' => $requete->boolean('est_reserve_abonnes'),
            'temps_lecture_minutes' => $tempsLecture,
            'date_publication' => ($article->date_publication === null && $donnees['statut'] === 'publie') ? now() : $article->date_publication,
        ]);

        JournalActivite::enregistrer("Admin updated article #{$article->id} ({$article->titre})", 'administration', 'modification_article_admin', Auth::user(), ['article_id' => $article->id]);

        return redirect()->route('admin.articles.index')->with('succes', 'Article updated successfully with administrator privileges.');
    }

    /**
     * Basculer l'état de l'alerte urgente.
     */
    public function basculerAlerte(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $nouvelEtat = ! $article->est_urgent;
        $article->update(['est_urgent' => $nouvelEtat]);

        JournalActivite::enregistrer("Toggled urgent alert to " . ($nouvelEtat ? 'ON' : 'OFF') . " for article #{$article->id}", 'administration', 'bascule_alerte_urgente', Auth::user(), ['article_id' => $article->id]);

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

        JournalActivite::enregistrer("Toggled PRO access to " . ($nouvelEtat ? 'PRO' : 'PUBLIC') . " for article #{$article->id}", 'administration', 'bascule_paywall_pro', Auth::user(), ['article_id' => $article->id, 'est_reserve_abonnes' => $nouvelEtat]);

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

        JournalActivite::enregistrer("Toggled featured headline to " . ($nouvelEtat ? 'FEATURED' : 'STANDARD') . " for article #{$article->id}", 'administration', 'bascule_a_la_une', Auth::user(), ['article_id' => $article->id, 'est_a_la_une' => $nouvelEtat]);

        return back()->with('succes', 'Featured headline status updated.');
    }

    /**
     * Supprimer définitivement un article.
     */
    public function destroy(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $titre = $article->titre;
        $article->delete();

        JournalActivite::enregistrer("Deleted article #{$id} ({$titre})", 'administration', 'suppression_article', Auth::user(), ['article_id' => $id, 'titre' => $titre]);

        return back()->with('succes', 'Article removed successfully.');
    }
}
