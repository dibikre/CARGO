<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\EvenementLogistique;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EvenementAdminControleur extends Controller
{
    /**
     * Liste et supervision de l'agenda des salons logistiques et congrès.
     */
    public function index(): View
    {
        $evenements = EvenementLogistique::orderBy('date_debut')
            ->paginate(15);

        return view('administration.evenements.index', compact('evenements'));
    }

    /**
     * Enregistrer un nouvel événement logistique.
     */
    public function store(Request $requete): RedirectResponse
    {
        $donnees = $requete->validate([
            'titre' => ['required', 'string', 'max:191'],
            'type_evenement' => ['required', 'string', 'max:50'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'lieu' => ['required', 'string', 'max:191'],
            'ville' => ['required', 'string', 'max:100'],
            'pays' => ['required', 'string', 'max:100'],
            'organisateur' => ['nullable', 'string', 'max:191'],
            'url_site_web' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $slug = Str::slug($donnees['titre']) . '-' . date('Y', strtotime($donnees['date_debut']));

        $evenement = EvenementLogistique::create([
            'titre' => $donnees['titre'],
            'slug' => $slug,
            'type_evenement' => $donnees['type_evenement'],
            'date_debut' => $donnees['date_debut'],
            'date_fin' => $donnees['date_fin'],
            'lieu' => $donnees['lieu'],
            'ville' => $donnees['ville'],
            'pays' => $donnees['pays'],
            'organisateur' => $donnees['organisateur'] ?? null,
            'url_site_web' => $donnees['url_site_web'] ?? null,
            'description' => $donnees['description'] ?? null,
            'est_actif' => true,
            'est_en_vedette' => $requete->boolean('est_en_vedette'),
        ]);

        JournalActivite::enregistrer(
            description: "Scheduled logistics industry event '{$evenement->titre}'",
            nomLog: 'administration',
            evenement: 'creation_evenement_logistique',
            auteur: Auth::user(),
            donnees: ['evenement_id' => $evenement->id]
        );

        return back()->with('succes', 'Logistics event added to industry agenda.');
    }

    /**
     * Basculer l'état en vedette d'un événement.
     */
    public function basculerVedette(int $id): RedirectResponse
    {
        $evenement = EvenementLogistique::findOrFail($id);
        $nouvelEtat = ! $evenement->est_en_vedette;
        $evenement->update(['est_en_vedette' => $nouvelEtat]);

        return back()->with('succes', 'Featured event status updated.');
    }

    /**
     * Supprimer un événement.
     */
    public function destroy(int $id): RedirectResponse
    {
        $evenement = EvenementLogistique::findOrFail($id);
        $titre = $evenement->titre;
        $evenement->delete();

        JournalActivite::enregistrer(
            description: "Removed logistics event '{$titre}'",
            nomLog: 'administration',
            evenement: 'suppression_evenement_logistique',
            auteur: Auth::user(),
            donnees: ['evenement_id' => $id]
        );

        return back()->with('succes', "Event '{$titre}' removed from calendar.");
    }
}
