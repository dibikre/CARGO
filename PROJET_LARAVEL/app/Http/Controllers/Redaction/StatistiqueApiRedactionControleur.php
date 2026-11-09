<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\StatistiqueArticle;
use App\Services\ServiceJetonSecuriteRedacteur;
use App\Services\ServiceStatistiquesHebdomadaires;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueApiRedactionControleur extends Controller
{
    protected ServiceStatistiquesHebdomadaires $serviceStatistiquesHebdo;
    protected ServiceJetonSecuriteRedacteur $serviceJeton;

    public function __construct(
        ServiceStatistiquesHebdomadaires $serviceStatistiquesHebdo,
        ServiceJetonSecuriteRedacteur $serviceJeton
    ) {
        $this->serviceStatistiquesHebdo = $serviceStatistiquesHebdo;
        $this->serviceJeton = $serviceJeton;
    }

    /**
     * Fournit les données statistiques sous format JSON pour la navigation dynamique sans rechargement.
     */
    public function donnees(Request $request): JsonResponse
    {
        $periode = $request->query('periode', '7');
        $jours = in_array($periode, ['7', '30', '90'], true) ? (int) $periode : 7;
        $dateDebut = Carbon::now()->subDays($jours - 1)->startOfDay();

        // 1. Métriques globales
        $totalArticles = Article::count();
        $totalPublies = Article::where('statut', 'publie')->count();
        $totalBrouillons = Article::where('statut', 'brouillon')->count();
        $totalRelecture = Article::where('statut', 'en_relecture')->count();
        $totalVuesCumulees = (int) Article::sum('vues_compteur');

        $publicationsPeriode = Article::where('statut', 'publie')
            ->where('date_publication', '>=', $dateDebut)
            ->count();

        // 2. Évolution quotidienne
        $donneesJours = [];
        for ($i = $jours - 1; $i >= 0; $i--) {
            $jour = Carbon::now()->subDays($i)->format('Y-m-d');
            $donneesJours[$jour] = [
                'date' => $jour,
                'label' => Carbon::parse($jour)->format('d M'),
                'vues' => 0,
                'publications' => 0,
            ];
        }

        $statsVues = StatistiqueArticle::select('date_statistique', DB::raw('SUM(nombre_vues) as total_vues'))
            ->where('date_statistique', '>=', $dateDebut->format('Y-m-d'))
            ->groupBy('date_statistique')
            ->get();

        foreach ($statsVues as $stat) {
            $cle = substr((string) $stat->date_statistique, 0, 10);
            if (isset($donneesJours[$cle])) {
                $donneesJours[$cle]['vues'] = (int) $stat->total_vues;
            }
        }

        $statsPubs = Article::select(DB::raw('DATE(date_publication) as date_pub'), DB::raw('COUNT(*) as total_pubs'))
            ->where('statut', 'publie')
            ->where('date_publication', '>=', $dateDebut)
            ->groupBy(DB::raw('DATE(date_publication)'))
            ->get();

        foreach ($statsPubs as $pub) {
            $cle = substr((string) $pub->date_pub, 0, 10);
            if (isset($donneesJours[$cle])) {
                $donneesJours[$cle]['publications'] = (int) $pub->total_pubs;
            }
        }

        // Repli si vues quotidiennes vides
        $vuesTotalesSurPeriode = array_sum(array_column($donneesJours, 'vues'));
        if ($vuesTotalesSurPeriode === 0 && $totalVuesCumulees > 0) {
            $derniersArticles = Article::where('statut', 'publie')
                ->where('vues_compteur', '>', 0)
                ->orderByDesc('updated_at')
                ->limit($jours)
                ->get();

            $index = 0;
            $cles = array_keys($donneesJours);
            foreach ($derniersArticles as $art) {
                if (isset($cles[$index])) {
                    $donneesJours[$cles[$index]]['vues'] = (int) round($art->vues_compteur / max(1, count($derniersArticles) / 2));
                    $index++;
                }
            }
        }

        // 3. Répartition par catégories
        $categoriesStats = Categorie::withCount(['articles as total_articles'])
            ->withCount(['articles as articles_publies' => function ($q) {
                $q->where('statut', 'publie');
            }])
            ->having('total_articles', '>', 0)
            ->orderByDesc('articles_publies')
            ->limit(8)
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'nom' => $cat->nom,
                    'slug' => $cat->slug,
                    'articles_publies' => (int) $cat->articles_publies,
                    'total_articles' => (int) $cat->total_articles,
                ];
            });

        // 4. Top 10 articles
        $topArticles = Article::with(['categorie', 'auteur'])
            ->where('statut', 'publie')
            ->orderByDesc('vues_compteur')
            ->limit(10)
            ->get()
            ->map(function ($art) {
                return [
                    'id' => $art->id,
                    'titre' => $art->titre,
                    'slug' => $art->slug,
                    'categorie' => $art->categorie?->nom ?? 'General',
                    'auteur' => $art->auteur?->name ?? 'Staff Editorial',
                    'vues' => (int) $art->vues_compteur,
                ];
            });

        // 5. Statistiques hebdomadaires (diagrammes circulaires & champions quotidiens)
        $statistiquesSemaine = $this->serviceStatistiquesHebdo->calculerStatistiquesSemaineCourante();

        // 6. Jeton rafraîchi pour les requêtes suivantes
        $utilisateur = $request->user();
        $nouveauJeton = $utilisateur ? $this->serviceJeton->genererJeton($utilisateur, $request) : null;

        return response()->json([
            'succes' => true,
            'periode' => (string) $jours,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'kpis' => [
                'total_vues' => $totalVuesCumulees,
                'publications_periode' => $publicationsPeriode,
                'total_publies' => $totalPublies,
                'total_articles' => $totalArticles,
                'pipeline' => $totalRelecture + $totalBrouillons,
                'relecture' => $totalRelecture,
                'brouillons' => $totalBrouillons,
            ],
            'evolution' => [
                'labels' => array_column($donneesJours, 'label'),
                'vues' => array_column($donneesJours, 'vues'),
                'publications' => array_column($donneesJours, 'publications'),
            ],
            'statistiques_semaine' => $statistiquesSemaine,
            'categories' => $categoriesStats,
            'top_articles' => $topArticles,
            'jeton_securite' => $nouveauJeton,
        ]);
    }
}
