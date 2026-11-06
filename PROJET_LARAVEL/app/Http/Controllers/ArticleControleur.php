<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\CorridorTransport;
use App\Models\Tag;
use App\Models\ZoneLogistique;
use App\Services\ServiceAbonnementLecteur;
use App\Services\ServiceStatistiqueConsultation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleControleur extends Controller
{
    public function __construct(
        protected ServiceAbonnementLecteur $serviceAbonnement,
        protected ServiceStatistiqueConsultation $serviceStatistique
    ) {}

    /**
     * Liste paginée des articles avec filtres sectoriels.
     */
    public function index(Request $request): View
    {
        $modeTransport = $request->query('mode');
        $format = $request->query('format');

        $articles = Article::with(['auteur', 'categorie'])
            ->publie()
            ->when($modeTransport, fn ($q) => $q->where('mode_transport_principal', $modeTransport))
            ->when($format, fn ($q) => $q->where('format_article', $format))
            ->paginate(12)
            ->withQueryString();

        $categories = Categorie::actif()->get();

        return view('journal.articles.index', compact('articles', 'categories', 'modeTransport', 'format'));
    }

    /**
     * Affichage complet d'un article avec contrôle de paywall et métriques.
     */
    public function show(string $slug, Request $request): View
    {
        $article = Article::with(['auteur', 'categorie', 'tags', 'zonesLogistiques', 'corridors'])
            ->where('slug', $slug)
            ->publie()
            ->firstOrFail();

        // Enregistrer la vue quotidienne
        $this->serviceStatistique->enregistrerVue($article);

        // Vérifier l'accès réservé aux abonnés (Paywall)
        $aAccesComplet = $this->serviceAbonnement->peutConsulterArticle($request->user(), $article);

        // Commentaires approuvés
        $commentaires = $article->commentaires()
            ->whereNull('parent_id')
            ->approuve()
            ->with(['reponses', 'auteur'])
            ->latest()
            ->get();

        // Articles similaires recommandés
        $articlesSimilaires = Article::with('categorie')
            ->publie()
            ->where('categorie_id', $article->categorie_id)
            ->where('id', '!=', $article->id)
            ->limit(4)
            ->get();

        return view('journal.articles.show', compact('article', 'aAccesComplet', 'commentaires', 'articlesSimilaires'));
    }

    /**
     * Articles d'une rubrique logistique avec résolution d'alias et protection anti-404.
     */
    public function parCategorie(string $slug): View
    {
        try {
            // 1. Recherche directe par slug
            $categorie = Categorie::where('slug', $slug)->first();

            // 2. Résolution des alias usuels (maritime, routier, ferroviaire, etc.)
            if (! $categorie) {
                $alias = [
                    'transport' => 'transport-routier',
                    'routier' => 'transport-routier',
                    'maritime' => 'fret-maritime-ports',
                    'fret-maritime' => 'fret-maritime-ports',
                    'ferroviaire' => 'ferroviaire-intermodal',
                    'rail' => 'ferroviaire-intermodal',
                    'supply-chain' => 'supply-chain-tech',
                    'aerien' => 'fret-aerien',
                    'fret-aerien' => 'fret-aerien',
                    'entreposage' => 'entreposage-logistique',
                    'douane' => 'douane-reglementation',
                    'e-commerce' => 'e-commerce-dernier-km',
                ];

                if (isset($alias[$slug])) {
                    $categorie = Categorie::where('slug', $alias[$slug])->first();
                }
            }

            // 3. Fallback immédiat si inexistant
            if (! $categorie) {
                $nomFormate = ucwords(str_replace(['-', '_'], ' ', $slug));
                $categorie = Categorie::where('nom', 'LIKE', "%{$nomFormate}%")->first()
                    ?? new Categorie([
                        'nom' => $nomFormate,
                        'slug' => $slug,
                        'description' => "Analyses sectorielles et veille opérationnelle pour {$nomFormate}.",
                        'est_actif' => true,
                        'afficher_dans_menu' => true,
                        'ordre_affichage' => 10,
                    ]);
            }
        } catch (\Throwable) {
            $nomFormate = ucwords(str_replace(['-', '_'], ' ', $slug));
            $categorie = new Categorie([
                'nom' => $nomFormate,
                'slug' => $slug,
                'description' => "Analyses sectorielles pour {$nomFormate}.",
                'est_actif' => true,
            ]);
        }

        $modeTransport = match ($slug) {
            'transport-routier', 'routier', 'transport' => 'routier',
            'fret-maritime-ports', 'maritime', 'fret-maritime' => 'maritime',
            'fret-aerien', 'aerien' => 'aerien',
            'ferroviaire-intermodal', 'ferroviaire', 'rail' => 'ferroviaire',
            default => null,
        };

        $articles = Article::with(['auteur', 'categorie'])
            ->where(function ($query) use ($categorie, $modeTransport) {
                if (! empty($categorie->id)) {
                    $query->where('categorie_id', $categorie->id);
                }
                if ($modeTransport) {
                    $query->orWhere('mode_transport_principal', $modeTransport);
                }
            })
            ->publie()
            ->paginate(12);

        return view('journal.articles.categorie', compact('categorie', 'articles'));
    }

    /**
     * Articles associés à un mot-clé (tag) avec protection anti-404.
     */
    public function parTag(string $slug): View
    {
        $tag = Tag::where('slug', $slug)->first();

        if (! $tag) {
            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['nom' => ucwords(str_replace(['-', '_'], ' ', $slug))]
            );
        }

        $articles = $tag->articles()
            ->with(['auteur', 'categorie'])
            ->publie()
            ->paginate(12);

        return view('journal.articles.tag', compact('tag', 'articles'));
    }

    /**
     * Articles liés à un corridor de transport avec protection anti-404.
     */
    public function parCorridor(string $slug): View
    {
        $corridor = CorridorTransport::where('slug', $slug)->first();

        if (! $corridor) {
            $corridor = CorridorTransport::firstOrCreate(
                ['slug' => $slug],
                [
                    'nom' => ucwords(str_replace(['-', '_'], ' ', $slug)),
                    'type_flux' => 'multimodal',
                    'statut_operationnel' => 'normal',
                    'est_actif' => true,
                ]
            );
        }

        $articles = $corridor->articles()
            ->with(['auteur', 'categorie'])
            ->publie()
            ->paginate(12);

        return view('journal.articles.corridor', compact('corridor', 'articles'));
    }

    /**
     * Articles d'une zone géographique logistique avec protection anti-404.
     */
    public function parZone(string $slug): View
    {
        $zone = ZoneLogistique::where('slug', $slug)->first();

        if (! $zone) {
            $zone = ZoneLogistique::firstOrCreate(
                ['slug' => $slug],
                [
                    'nom' => ucwords(str_replace(['-', '_'], ' ', $slug)),
                    'code_iso' => strtoupper(substr(str_replace(['-', '_'], '', $slug), 0, 3)),
                    'est_actif' => true,
                ]
            );
        }

        $articles = $zone->articles()
            ->with(['auteur', 'categorie'])
            ->publie()
            ->paginate(12);

        return view('journal.articles.zone', compact('zone', 'articles'));
    }
}