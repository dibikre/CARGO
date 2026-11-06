<?php

namespace App\Http\Controllers;

use App\Models\EvenementLogistique;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvenementLogistiqueControleur extends Controller
{
    /**
     * Agenda des salons, congrès et conférences logistiques.
     */
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $evenements = EvenementLogistique::actif()
            ->when($type, fn ($q) => $q->where('type_evenement', $type))
            ->orderBy('date_debut')
            ->paginate(12)
            ->withQueryString();

        return view('journal.evenements.index', compact('evenements', 'type'));
    }

    /**
     * Détail d'un événement logistique.
     */
    public function show(string $slug): View
    {
        $evenement = EvenementLogistique::where('slug', $slug)
            ->actif()
            ->firstOrFail();

        return view('journal.evenements.show', compact('evenement'));
    }
}
