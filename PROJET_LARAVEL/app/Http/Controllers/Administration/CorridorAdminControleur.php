<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationSysteme;
use App\Models\CorridorTransport;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CorridorAdminControleur extends Controller
{
    /**
     * Liste et supervision opérationnelle des corridors de fret.
     */
    public function index(): View
    {
        $corridors = CorridorTransport::orderBy('statut_operationnel', 'desc')
            ->orderBy('nom', 'asc')
            ->get();

        $corridorsActifs = ConfigurationSysteme::corridorsSontActifs();

        return view('administration.corridors.index', compact('corridors', 'corridorsActifs'));
    }

    /**
     * Mettre à jour le statut opérationnel d'un corridor de transport.
     */
    public function updateStatut(Request $requete, int $id): RedirectResponse
    {
        $corridor = CorridorTransport::findOrFail($id);

        $valide = $requete->validate([
            'statut_operationnel' => ['required', 'string', Rule::in(['normal', 'fluide', 'ralenti', 'perturbe', 'bloque', 'congestionne'])],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $ancienStatut = $corridor->statut_operationnel;
        $nouveauStatut = $valide['statut_operationnel'] === 'fluide'
            ? 'normal'
            : $valide['statut_operationnel'];

        $corridor->update([
            'statut_operationnel' => $nouveauStatut,
            'description' => $valide['description'] ?? $corridor->description,
        ]);

        JournalActivite::enregistrer(
            description: "Corridor {$corridor->nom} operational status changed from {$ancienStatut} to {$nouveauStatut}",
            nomLog: 'administration',
            evenement: 'maj_corridor_statut',
            auteur: Auth::user(),
            donnees: [
                'corridor_id' => $corridor->id,
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $nouveauStatut,
            ]
        );

        return back()->with('succes', 'Operational status updated successfully.');
    }

    /**
     * Créer un nouveau corridor logistique à surveiller.
     */
    public function store(Request $requete): RedirectResponse
    {
        $valide = $requete->validate([
            'nom' => ['required', 'string', 'max:120', 'unique:corridors_transport,nom'],
            'type_flux' => ['required', 'string', Rule::in(['maritime', 'ferroviaire', 'routier', 'aerien', 'multimodal'])],
            'statut_operationnel' => ['required', 'string', Rule::in(['normal', 'fluide', 'ralenti', 'perturbe', 'bloque', 'congestionne'])],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $statutInitial = $valide['statut_operationnel'] === 'fluide'
            ? 'normal'
            : $valide['statut_operationnel'];

        $corridor = CorridorTransport::create([
            'nom' => $valide['nom'],
            'slug' => Str::slug($valide['nom']),
            'type_flux' => $valide['type_flux'],
            'statut_operationnel' => $statutInitial,
            'description' => $valide['description'] ?? null,
            'est_actif' => true,
        ]);

        JournalActivite::enregistrer(
            description: "Created logistics corridor {$corridor->nom}",
            nomLog: 'administration',
            evenement: 'creation_corridor',
            auteur: Auth::user(),
            donnees: ['corridor_id' => $corridor->id]
        );

        return back()->with('succes', 'New freight corridor registered.');
    }

    /**
     * Basculer l'activation publique de la fonctionnalité des corridors côté visiteur.
     */
    public function basculerVisibilitePublique(): RedirectResponse
    {
        $nouvelEtat = ConfigurationSysteme::basculerCorridors();

        JournalActivite::enregistrer(
            description: $nouvelEtat
                ? "Reactivated public 'Freight Corridors & Disruption Management' feature for visitors"
                : "Deactivated 'Freight Corridors & Disruption Management' feature on the visitor-facing website",
            nomLog: 'administration',
            evenement: 'bascule_visibilite_corridors',
            auteur: Auth::user(),
            donnees: ['corridors_actifs' => $nouvelEtat]
        );

        $message = $nouvelEtat
            ? 'Freight corridors and disruption alerts are now ACTIVE on the visitor site.'
            : 'Freight corridors and disruption management are now DEACTIVATED on the visitor site.';

        return back()->with('succes', $message);
    }
}
