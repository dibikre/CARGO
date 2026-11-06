<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategorieAdminControleur extends Controller
{
    /**
     * Liste et gestion des rubriques éditoriales du journal.
     */
    public function index(): View
    {
        $categories = Categorie::withCount('articles')
            ->orderBy('ordre_affichage')
            ->get();

        return view('administration.categories.index', compact('categories'));
    }

    /**
     * Création d'une nouvelle rubrique.
     */
    public function store(Request $requete): RedirectResponse
    {
        $donnees = $requete->validate([
            'nom' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'couleur' => ['nullable', 'string', 'max:20'],
            'ordre_affichage' => ['nullable', 'integer', 'min:0'],
            'afficher_dans_menu' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($donnees['slug'])
            ? Str::slug($donnees['slug'])
            : Str::slug($donnees['nom']);

        $categorie = Categorie::create([
            'nom' => $donnees['nom'],
            'slug' => $slug,
            'description' => $donnees['description'] ?? null,
            'couleur' => $donnees['couleur'] ?? '#D4201A',
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
            'afficher_dans_menu' => $requete->boolean('afficher_dans_menu', true),
            'est_actif' => true,
        ]);

        JournalActivite::enregistrer(
            description: "Created editorial category '{$categorie->nom}'",
            nomLog: 'administration',
            evenement: 'creation_categorie',
            auteur: Auth::user(),
            donnees: ['categorie_id' => $categorie->id, 'slug' => $categorie->slug]
        );

        return back()->with('succes', 'Editorial category successfully created.');
    }

    /**
     * Mise à jour d'une rubrique.
     */
    public function update(int $id, Request $requete): RedirectResponse
    {
        $categorie = Categorie::findOrFail($id);

        $donnees = $requete->validate([
            'nom' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'ordre_affichage' => ['required', 'integer', 'min:0'],
            'couleur' => ['nullable', 'string', 'max:20'],
        ]);

        $categorie->update([
            'nom' => $donnees['nom'],
            'description' => $donnees['description'] ?? null,
            'ordre_affichage' => (int) $donnees['ordre_affichage'],
            'couleur' => $donnees['couleur'] ?? $categorie->couleur,
            'afficher_dans_menu' => $requete->boolean('afficher_dans_menu'),
            'est_actif' => $requete->boolean('est_actif'),
        ]);

        JournalActivite::enregistrer(
            description: "Updated editorial category '{$categorie->nom}'",
            nomLog: 'administration',
            evenement: 'mise_a_jour_categorie',
            auteur: Auth::user(),
            donnees: ['categorie_id' => $categorie->id]
        );

        return back()->with('succes', 'Category configuration updated.');
    }

    /**
     * Basculer l'affichage dans le menu header.
     */
    public function basculerMenu(int $id): RedirectResponse
    {
        $categorie = Categorie::findOrFail($id);
        $nouvelEtat = ! $categorie->afficher_dans_menu;
        $categorie->update(['afficher_dans_menu' => $nouvelEtat]);

        return back()->with('succes', "Menu visibility toggled to " . ($nouvelEtat ? 'Visible' : 'Hidden'));
    }

    /**
     * Supprimer une rubrique.
     */
    public function destroy(int $id): RedirectResponse
    {
        $categorie = Categorie::findOrFail($id);

        if ($categorie->articles()->count() > 0) {
            return back()->with('erreur', 'Cannot delete a section containing published articles.');
        }

        $nom = $categorie->nom;
        $categorie->delete();

        JournalActivite::enregistrer(
            description: "Deleted editorial section '{$nom}'",
            nomLog: 'administration',
            evenement: 'suppression_categorie',
            auteur: Auth::user(),
            donnees: ['categorie_id' => $id]
        );

        return back()->with('succes', "Category '{$nom}' has been deleted.");
    }
}
