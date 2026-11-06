<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RechercheControleur extends Controller
{
    /**
     * Moteur de recherche multicritères dans les archives du journal.
     */
    public function rechercher(Request $request): View
    {
        $requete = trim((string) $request->query('q', ''));
        $categorieId = $request->query('categorie_id');
        $modeTransport = $request->query('mode');
        $format = $request->query('format');

        $articles = Article::with(['auteur', 'categorie'])
            ->publie()
            ->when($requete !== '', function ($q) use ($requete) {
                $q->where(function ($subQuery) use ($requete) {
                    $subQuery->where('titre', 'like', "%{$requete}%")
                        ->orWhere('chapeau', 'like', "%{$requete}%")
                        ->orWhere('contenu', 'like', "%{$requete}%");
                });
            })
            ->when($categorieId, fn ($q) => $q->where('categorie_id', $categorieId))
            ->when($modeTransport, fn ($q) => $q->where('mode_transport_principal', $modeTransport))
            ->when($format, fn ($q) => $q->where('format_article', $format))
            ->paginate(10)
            ->withQueryString();

        $categories = Categorie::actif()->get();

        return view('journal.recherche.resultats', compact('articles', 'categories', 'requete', 'categorieId', 'modeTransport', 'format'));
    }
}
