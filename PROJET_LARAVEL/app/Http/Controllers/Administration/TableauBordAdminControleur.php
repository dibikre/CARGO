<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\AbonneNewsletter;
use App\Models\AbonnementUtilisateur;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Commentaire;
use App\Models\CorridorTransport;
use App\Models\DocumentRessource;
use App\Models\EvenementLogistique;
use App\Models\JournalActivite;
use App\Models\StatistiqueArticle;
use App\Models\User;
use App\Models\ConfigurationSysteme;
use App\Models\FormuleAbonnement;
use App\Models\MatierePremiere;
use Illuminate\View\View;

class TableauBordAdminControleur extends Controller
{
    /**
     * Afficher le tableau de bord principal de l'administration CARGO.
     */
    public function index(): View
    {
        // 1. Indicateurs clés d'articles et comparaisons de période
        $totalArticles = Article::count();
        $articlesPublies = Article::where('statut', 'publie')->count();
        $articlesBrouillons = Article::where('statut', 'brouillon')->count();
        $articlesCeMois = Article::where('statut', 'publie')
            ->where('date_publication', '>=', now()->startOfMonth())
            ->count();
        $articlesMoisPrecedent = Article::where('statut', 'publie')
            ->whereBetween('date_publication', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        // 2. Alerte urgente active en direct sur le portail
        $alerteUrgenteActive = Article::where('est_urgent', true)
            ->where('statut', 'publie')
            ->with(['auteur', 'categorie'])
            ->latest('date_publication')
            ->first();
        $articlesAlerteUrgente = Article::where('est_urgent', true)->where('statut', 'publie')->count();

        // 3. Modération et retours terrain
        $commentairesEnAttente = Commentaire::enAttente()->count();

        // 4. Abonnements, audience et paywall
        $totalAbonnesActifs = AbonnementUtilisateur::where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->count();
        $totalUtilisateurs = User::count();
        $totalNewsletter = AbonneNewsletter::count();
        $totalFormules = FormuleAbonnement::count();
        $abonnementsActifs = ConfigurationSysteme::abonnementsSontActifs();

        // 5. Corridors logistiques et commodités sous surveillance
        $corridorsPerturbes = CorridorTransport::whereIn('statut_operationnel', ['ralenti', 'perturbe', 'bloque', 'congestionne'])
            ->get();
        $totalCorridors = CorridorTransport::count();
        $totalMatieres = MatierePremiere::count();

        // 6. Événements et livres blancs
        $totalEvenements = EvenementLogistique::count();
        $totalDocuments = DocumentRessource::count();
        $totalCategories = Categorie::count();

        // 7. Audience et consultations vérifiées
        $totalLecturesCumulees = (int) Article::sum('vues_compteur');
        $consultationsMois = (int) StatistiqueArticle::where('date_statistique', '>=', now()->subDays(30))
            ->sum('nombre_vues');
        if ($consultationsMois === 0 && $totalLecturesCumulees > 0) {
            $consultationsMois = $totalLecturesCumulees;
        }

        // 8. Journal d'audit et dernières publications
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
            'articlesCeMois',
            'articlesMoisPrecedent',
            'alerteUrgenteActive',
            'articlesAlerteUrgente',
            'totalCategories',
            'commentairesEnAttente',
            'totalAbonnesActifs',
            'totalUtilisateurs',
            'totalNewsletter',
            'totalFormules',
            'abonnementsActifs',
            'corridorsPerturbes',
            'totalCorridors',
            'totalMatieres',
            'totalEvenements',
            'totalDocuments',
            'consultationsMois',
            'totalLecturesCumulees',
            'activitesRecentes',
            'derniersArticles'
        ));
    }
}
