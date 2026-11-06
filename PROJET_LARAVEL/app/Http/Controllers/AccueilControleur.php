<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\CorridorTransport;
use App\Models\DocumentRessource;
use App\Models\DossierThematique;
use App\Models\EvenementLogistique;
use Illuminate\View\View;

class AccueilControleur extends Controller
{
    /**
     * Afficher la page d'accueil du journal logistique.
     */
    public function index(): View
    {
        // Articles à la une (hero principal et secondaires)
        $articlesALaUne = Article::with(['auteur', 'categorie'])
            ->aLaUne()
            ->limit(4)
            ->get();

        // Flash infos et alertes transport urgentes
        $alertesUrgentes = Article::urgent()
            ->limit(5)
            ->get();

        // Dernières actualités récentes
        $articlesRecents = Article::with(['auteur', 'categorie'])
            ->publie()
            ->whereNotIn('id', $articlesALaUne->pluck('id'))
            ->limit(8)
            ->get();

        // Rubriques principales avec leurs articles récents
        $categoriesPrincipales = Categorie::menu()
            ->with(['articles' => fn ($q) => $q->publie()->limit(3)])
            ->get();

        // Corridors de transport sous surveillance opérationnelle
        $corridorsSousSurveillance = CorridorTransport::actif()
            ->limit(6)
            ->get();

        // Dossiers thématiques et grandes enquêtes à l'affiche
        $dossiersVedettes = DossierThematique::enVedette()
            ->limit(2)
            ->get();

        // Prochains salons et congrès logistiques
        $evenementsProchains = EvenementLogistique::aVenir()
            ->limit(3)
            ->get();

        // Dernier baromètre ou livre blanc en libre accès ou abonnés
        $dernierDocument = DocumentRessource::actif()
            ->latest()
            ->first();

        return view('journal.accueil', compact(
            'articlesALaUne',
            'alertesUrgentes',
            'articlesRecents',
            'categoriesPrincipales',
            'corridorsSousSurveillance',
            'dossiersVedettes',
            'evenementsProchains',
            'dernierDocument'
        ));
    }
}