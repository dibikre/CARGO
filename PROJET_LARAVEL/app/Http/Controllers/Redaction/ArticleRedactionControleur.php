<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleFormRequest;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\CorridorTransport;
use App\Models\Tag;
use App\Models\ZoneLogistique;
use App\Services\ServiceGestionImage;
use App\Services\ServicePublicationArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleRedactionControleur extends Controller
{
    public function __construct(
        protected ServicePublicationArticle $servicePublication,
        protected ServiceGestionImage $serviceImage
    ) {}

    /**
     * Tableau de bord des articles pour la rédaction.
     */
    public function index(Request $request): View
    {
        $statut = $request->query('statut');

        $articles = Article::with(['auteur', 'categorie'])
            ->when($statut, fn ($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('redaction.articles.index', compact('articles', 'statut'));
    }

    /**
     * Formulaire de rédaction d'un nouvel article.
     */
    public function create(): View
    {
        $categories = Categorie::actif()->get();
        $tags = Tag::all();
        $zones = ZoneLogistique::actif()->get();
        $corridors = CorridorTransport::actif()->get();

        return view('redaction.articles.create', compact('categories', 'tags', 'zones', 'corridors'));
    }

    /**
     * Enregistrer un nouvel article rédigé.
     */
    public function store(ArticleFormRequest $request): RedirectResponse
    {
        $donnees = $request->validated();

        $slug = $this->servicePublication->genererSlugUnique($donnees['titre']);
        $tempsLecture = $this->servicePublication->calculerTempsLecture($donnees['contenu']);

        // Structuration du stockage de l'image (téléversement direct ou rapatriement local d'URL)
        $cheminImage = $donnees['image_principale'] ?? null;
        if ($request->hasFile('fichier_image')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($request->file('fichier_image'), 'articles');
        } elseif (! empty($cheminImage) && Str::startsWith($cheminImage, ['http://', 'https://'])) {
            $imageLocale = $this->serviceImage->telechargerEtStockerImage($cheminImage, 'articles');
            if ($imageLocale) {
                $cheminImage = $imageLocale;
            }
        }

        $article = Article::create([
            'auteur_id' => $request->user()->id,
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
            'est_a_la_une' => $request->boolean('est_a_la_une'),
            'est_urgent' => $request->boolean('est_urgent'),
            'est_reserve_abonnes' => $request->boolean('est_reserve_abonnes'),
            'est_sponsorise' => $request->boolean('est_sponsorise'),
            'nom_partenaire' => $donnees['nom_partenaire'] ?? null,
            'temps_lecture_minutes' => $tempsLecture,
            'date_publication' => $donnees['date_publication'] ?? ($donnees['statut'] === 'publie' ? now() : null),
            'meta_titre' => $donnees['meta_titre'] ?? null,
            'meta_description' => $donnees['meta_description'] ?? null,
        ]);

        // Synchroniser les relations
        if (! empty($donnees['tags'])) {
            $article->tags()->sync($donnees['tags']);
        }
        if (! empty($donnees['zones'])) {
            $article->zonesLogistiques()->sync($donnees['zones']);
        }
        if (! empty($donnees['corridors'])) {
            $article->corridors()->sync($donnees['corridors']);
        }

        return redirect()->route('redaction.articles.index')->with('succes', 'Article enregistré avec succès.');
    }

    /**
     * Formulaire d'édition d'un article existant avec éditeur HTML.
     */
    public function edit(Article $article): View
    {
        $categories = Categorie::actif()->get();
        $tags = Tag::all();
        $zones = ZoneLogistique::actif()->get();
        $corridors = CorridorTransport::actif()->get();

        return view('redaction.articles.edit', compact('article', 'categories', 'tags', 'zones', 'corridors'));
    }

    /**
     * Mettre à jour un article rédigé.
     */
    public function update(ArticleFormRequest $request, Article $article): RedirectResponse
    {
        $donnees = $request->validated();
        $tempsLecture = $this->servicePublication->calculerTempsLecture($donnees['contenu']);

        // Structuration du stockage de l'image
        $cheminImage = $donnees['image_principale'] ?? $article->image_principale;
        if ($request->hasFile('fichier_image')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($request->file('fichier_image'), 'articles');
        } elseif (! empty($donnees['image_principale']) && Str::startsWith($donnees['image_principale'], ['http://', 'https://'])) {
            $imageLocale = $this->serviceImage->telechargerEtStockerImage($donnees['image_principale'], 'articles');
            if ($imageLocale) {
                $cheminImage = $imageLocale;
            }
        }

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
            'est_a_la_une' => $request->boolean('est_a_la_une'),
            'est_urgent' => $request->boolean('est_urgent'),
            'est_reserve_abonnes' => $request->boolean('est_reserve_abonnes'),
            'est_sponsorise' => $request->boolean('est_sponsorise'),
            'nom_partenaire' => $donnees['nom_partenaire'] ?? null,
            'temps_lecture_minutes' => $tempsLecture,
            'date_publication' => $donnees['date_publication'] ?? $article->date_publication,
            'meta_titre' => $donnees['meta_titre'] ?? null,
            'meta_description' => $donnees['meta_description'] ?? null,
        ]);

        $article->tags()->sync($donnees['tags'] ?? []);
        $article->zonesLogistiques()->sync($donnees['zones'] ?? []);
        $article->corridors()->sync($donnees['corridors'] ?? []);

        return redirect()->route('redaction.articles.index')->with('succes', 'Article mis à jour avec succès.');
    }

    /**
     * Publier directement un article.
     */
    public function publier(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $this->servicePublication->publierArticle($article);

        return back()->with('succes', 'L\'article a été publié en ligne.');
    }

    /**
     * Archiver un article.
     */
    public function archiver(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $this->servicePublication->archiverArticle($article);

        return back()->with('succes', 'L\'article a été déplacé vers les archives.');
    }

    /**
     * Supprimer un article (suppression douce).
     */
    public function destroy(int $id): RedirectResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return back()->with('succes', 'L\'article a été supprimé.');
    }
}
