<?php

namespace App\Http\Controllers;

use App\Models\DossierThematique;
use Illuminate\View\View;

class DossierThematiqueControleur extends Controller
{
    /**
     * Liste des grands dossiers et enquêtes thématiques.
     */
    public function index(): View
    {
        $dossiers = DossierThematique::actif()
            ->withCount('articles')
            ->latest('date_publication')
            ->paginate(9);

        return view('journal.dossiers.index', compact('dossiers'));
    }

    /**
     * Consultation d'un dossier thématique avec ses articles ordonnés.
     */
    public function show(string $slug): View
    {
        $dossier = DossierThematique::where('slug', $slug)
            ->actif()
            ->firstOrFail();

        $articles = $dossier->articles()
            ->with(['auteur', 'categorie'])
            ->publie()
            ->get();

        return view('journal.dossiers.show', compact('dossier', 'articles'));
    }
}
