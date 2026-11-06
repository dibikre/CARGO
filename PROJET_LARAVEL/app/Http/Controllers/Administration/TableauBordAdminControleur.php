<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\AbonnementUtilisateur;
use App\Models\Article;
use App\Models\Commentaire;
use App\Models\CorridorTransport;
use App\Models\JournalActivite;
use App\Models\StatistiqueArticle;
use App\Models\User;
use Illuminate\View\View;

class TableauBordAdminControleur extends Controller
{
    /**
     * Afficher le tableau de bord principal de l'administration CARGO.
     */
    public function index(): View
    {
        // 1. Indicateurs clés d'articles
        $totalArticles = Article::count();
        $articlesPublies = Article::where('statut', 'publie')->count();
        $articlesBrouillons = Article::where('statut', 'brouillon')->count();
        $articlesAlerteUrgente = Article::where('est_urgent', true)->where('statut', 'publie')->count();

        // 2. Modération et communauté
        $commentairesEnAttente = Commentaire::enAttente()->count();

        // 3. Abonnements et audience
        $totalAbonnesActifs = AbonnementUtilisateur::where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->count();
        $totalUtilisateurs = User::count();

        // 4. Corridors de transport surveillés
        $corridorsPerturbes = CorridorTransport::whereIn('statut_operationnel', ['ralenti', 'perturbe', 'bloque', 'congestionne'])
            ->get();
        $totalCorridors = CorridorTransport::count();

        // 5. Total consultations estimées (derniers 30 jours)
        $consultationsMois = (int) StatistiqueArticle::where('date_statistique', '>=', now()->subDays(30))
            ->sum('nombre_vues');

        // 6. Activités et alertes récentes
        $activitesRecentes = JournalActivite::with('auteur')
            ->latest()
            ->take(8)
            ->get();

        $derniersArticles = Article::with(['auteur', 'categorie'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('administration.tableau-bord', compact(
            'totalArticles',
            'articlesPublies',
            'articlesBrouillons',
            'articlesAlerteUrgente',
            'commentairesEnAttente',
            'totalAbonnesActifs',
            'totalUtilisateurs',
            'corridorsPerturbes',
            'totalCorridors',
            'consultationsMois',
            'activitesRecentes',
            'derniersArticles'
        ));
    }
}
