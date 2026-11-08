<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatierePremiereFormRequest;
use App\Models\MatierePremiere;
use App\Services\ServiceMatierePremiere;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controleur d'administration pour la gestion du bandeau des matieres premieres.
 * Permet a l'administrateur de creer, modifier, supprimer des matieres premieres,
 * de mettre a jour les images transparentes sans arriere-plan, et de saisir
 * ou actualiser les noeuds de courbe (5 a 10 points JSON).
 */
class MatierePremiereAdminControleur extends Controller
{
    public function __construct(
        protected ServiceMatierePremiere $serviceMatiere
    ) {}

    /**
     * Affiche la liste des matieres premieres et l'etat du quota (max 20).
     */
    public function index(): View
    {
        $matieres = MatierePremiere::orderBy('ordre_affichage')
            ->orderBy('nom')
            ->get();

        $totalProduits = $matieres->count();
        $quotaAtteint = $totalProduits >= MatierePremiere::NOMBRE_MAX_PRODUITS;

        return view('administration.matieres-premieres.index', [
            'matieres' => $matieres,
            'totalProduits' => $totalProduits,
            'maxProduits' => MatierePremiere::NOMBRE_MAX_PRODUITS,
            'quotaAtteint' => $quotaAtteint,
        ]);
    }

    /**
     * Formulaire de creation d'une nouvelle matiere premiere.
     */
    public function create(): View|RedirectResponse
    {
        if (MatierePremiere::count() >= MatierePremiere::NOMBRE_MAX_PRODUITS) {
            return redirect()
                ->route('admin.matieres-premieres.index')
                ->with('erreur', 'La limite maximale de 20 matières premières est atteinte.');
        }

        $nouvelOrdre = (int) MatierePremiere::max('ordre_affichage') + 1;

        return view('administration.matieres-premieres.create', [
            'nouvelOrdre' => $nouvelOrdre,
            'exempleJson' => json_encode([
                [1792800000000, 5620.04471],
                [1793059200000, 5492.79088],
                [1793253600000, 5463.49258],
                [1793444400000, 5456.59578],
                [1793700000000, 5394.52022],
                [1793892600000, 5457.98716],
                [1794088800000, 5154.56431],
            ], JSON_PRETTY_PRINT),
        ]);
    }

    /**
     * Enregistre une nouvelle matiere premiere.
     */
    public function store(MatierePremiereFormRequest $requete): RedirectResponse
    {
        $donneesValidees = $requete->validated();

        try {
            $this->serviceMatiere->creerMatierePremiere($donneesValidees, $requete->file('image_png'));

            return redirect()
                ->route('admin.matieres-premieres.index')
                ->with('succes', 'Matière première ajoutée avec succès au bandeau de cours.');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['donnees_courbe_json' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erreur', "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage());
        }
    }

    /**
     * Formulaire d'edition d'une matiere premiere existante.
     */
    public function edit(int $id): View
    {
        $matiere = MatierePremiere::findOrFail($id);

        return view('administration.matieres-premieres.edit', [
            'matiere' => $matiere,
            'jsonFormate' => json_encode($matiere->donnees_courbe_json, JSON_PRETTY_PRINT),
        ]);
    }

    /**
     * Met a jour une matiere premiere existante.
     */
    public function update(MatierePremiereFormRequest $requete, int $id): RedirectResponse
    {
        $matiere = MatierePremiere::findOrFail($id);
        $donneesValidees = $requete->validated();

        try {
            $this->serviceMatiere->mettreAJourMatierePremiere($matiere, $donneesValidees, $requete->file('image_png'));

            return redirect()
                ->route('admin.matieres-premieres.index')
                ->with('succes', 'Matière première mise à jour avec succès.');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['donnees_courbe_json' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erreur', "Une erreur est survenue lors de la mise à jour : " . $e->getMessage());
        }
    }

    /**
     * Met a jour uniquement les donnees de courbe d'une matiere premiere.
     */
    public function actualiserDonnees(Request $requete, int $id): RedirectResponse
    {
        $matiere = MatierePremiere::findOrFail($id);

        $requete->validate([
            'donnees_courbe_json' => 'required|string',
        ], [
            'donnees_courbe_json.required' => 'Les coordonnées de courbe sont requises.',
        ]);

        try {
            $this->serviceMatiere->actualiserDonneesCourbe($matiere, $requete->input('donnees_courbe_json'));

            return redirect()
                ->route('admin.matieres-premieres.index')
                ->with('succes', 'Cours et variation actualisés pour ' . $matiere->nom);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['donnees_courbe_json' => $e->getMessage()]);
        }
    }

    /**
     * Supprime une matiere premiere.
     */
    public function destroy(int $id): RedirectResponse
    {
        $matiere = MatierePremiere::findOrFail($id);
        $nom = $matiere->nom;

        $this->serviceMatiere->supprimerMatierePremiere($matiere);

        return redirect()
            ->route('admin.matieres-premieres.index')
            ->with('succes', "Matière première '{$nom}' supprimée avec succès.");
    }
}

