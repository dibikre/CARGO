<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\StatistiqueArticle;
use App\Services\ServiceJetonSecuriteRedacteur;
use App\Services\ServiceStatistiquesHebdomadaires;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatistiqueRedactionControleur extends Controller
{
    /**
     * Service dédié aux calculs statistiques hebdomadaires.
     */
    protected ServiceStatistiquesHebdomadaires $serviceStatistiquesHebdo;

    /**
     * Service de jetons JWT sécurisés basés sur le login et l'appareil.
     */
    protected ServiceJetonSecuriteRedacteur $serviceJeton;

    public function __construct(
        ServiceStatistiquesHebdomadaires $serviceStatistiquesHebdo,
        ServiceJetonSecuriteRedacteur $serviceJeton
    ) {
        $this->serviceStatistiquesHebdo = $serviceStatistiquesHebdo;
        $this->serviceJeton = $serviceJeton;
    }

    /**
     * Tableau de bord des statistiques d'audience et de publications pour la rédaction.
     */
    public function index(Request $request): View
    {
        $periode = $request->query('periode', '30'); // 7, 30, ou 90 jours
        $jours = in_array($periode, ['7', '30', '90'], true) ? (int) $periode : 30;
        $dateDebut = Carbon::now()->subDays($jours - 1)->startOfDay();

        // 1. Métriques globales
        $totalArticles = Article::count();
        $totalPublies = Article::where('statut', 'publie')->count();
        $totalBrouillons = Article::where('statut', 'brouillon')->count();
        $totalRelecture = Article::where('statut', 'en_relecture')->count();
        $totalVuesCumulees = (int) Article::sum('vues_compteur');

        // Publications sur la période
        $publicationsPeriode = Article::where('statut', 'publie')
            ->where('date_publication', '>=', $dateDebut)
            ->count();

        // 2. Évolution quotidienne : Vues et Publications par jour
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

        // Récupérer les vues consolidées par date
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

        // Récupérer les articles publiés par date
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

        // Vues de secours si les statistiques quotidiennes sont vides
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

        // 3. Répartition des publications par catégorie
        $categoriesStats = Categorie::withCount(['articles as total_articles'])
            ->withCount(['articles as articles_publies' => function ($q) {
                $q->where('statut', 'publie');
            }])
            ->having('total_articles', '>', 0)
            ->orderByDesc('articles_publies')
            ->limit(8)
            ->get();

        // 4. Top 10 des articles les plus consultés
        $topArticles = Article::with(['categorie', 'auteur'])
            ->where('statut', 'publie')
            ->orderByDesc('vues_compteur')
            ->limit(10)
            ->get();

        // 5. Nouvelles métriques de la semaine courante (lundi au dimanche)
        $statistiquesSemaine = $this->serviceStatistiquesHebdo->calculerStatistiquesSemaineCourante();

        // 6. Préparation des séries JSON pour les graphiques SVG / Canvas
        $labelsGraphique = array_column($donneesJours, 'label');
        $vuesGraphique = array_column($donneesJours, 'vues');
        $pubsGraphique = array_column($donneesJours, 'publications');

        // 7. Jeton JWT sécurisé lié au login et à l'appareil pour navigation sans rechargement
        $utilisateur = $request->user();
        $jetonSecurite = $utilisateur ? $this->serviceJeton->genererJeton($utilisateur, $request) : '';

        return view('redaction.statistiques.index', compact(
            'periode',
            'jours',
            'totalArticles',
            'totalPublies',
            'totalBrouillons',
            'totalRelecture',
            'totalVuesCumulees',
            'publicationsPeriode',
            'donneesJours',
            'labelsGraphique',
            'vuesGraphique',
            'pubsGraphique',
            'categoriesStats',
            'topArticles',
            'statistiquesSemaine',
            'jetonSecurite'
        ));
    }
}
