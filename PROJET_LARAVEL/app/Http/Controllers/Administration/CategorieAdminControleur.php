<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\JournalActivite;
use App\Services\ServiceGestionImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategorieAdminControleur extends Controller
{
    /**
     * Service de manipulation et sécurisation des images locales.
     */
    protected ServiceGestionImage $serviceImage;

    public function __construct(ServiceGestionImage $serviceImage)
    {
        $this->serviceImage = $serviceImage;
    }

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
            'image_fond_url' => ['nullable', 'string', 'max:500'],
            'image_fond_fichier' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'opacite_overlay' => ['nullable', 'integer', 'min:20', 'max:95'],
            'couleur_overlay' => ['nullable', 'string', 'max:20'],
        ]);

        $slug = ! empty($donnees['slug'])
            ? Str::slug($donnees['slug'])
            : Str::slug($donnees['nom']);

        // Traitement de l'image de fond
        $cheminImage = null;
        if ($requete->hasFile('image_fond_fichier')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($requete->file('image_fond_fichier'), 'categories');
        } elseif (! empty($donnees['image_fond_url'])) {
            $url = $donnees['image_fond_url'];
            if (Str::startsWith($url, ['http://', 'https://'])) {
                $cheminImage = $this->serviceImage->telechargerEtStockerImage($url, 'categories') ?? $url;
            } else {
                $cheminImage = $url;
            }
        }

        $categorie = Categorie::create([
            'nom' => $donnees['nom'],
            'slug' => $slug,
            'description' => $donnees['description'] ?? null,
            'couleur' => $donnees['couleur'] ?? '#D4201A',
            'image_fond' => $cheminImage,
            'opacite_overlay' => (int) ($donnees['opacite_overlay'] ?? 65),
            'couleur_overlay' => $donnees['couleur_overlay'] ?? '#14151C',
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
            'afficher_dans_menu' => $requete->boolean('afficher_dans_menu', true),
            'est_actif' => true,
        ]);

        JournalActivite::enregistrer(
            description: "Created editorial category '{$categorie->nom}' with custom background",
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
            'image_fond_url' => ['nullable', 'string', 'max:500'],
            'image_fond_fichier' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'opacite_overlay' => ['nullable', 'integer', 'min:20', 'max:95'],
            'couleur_overlay' => ['nullable', 'string', 'max:20'],
            'supprimer_image_fond' => ['nullable', 'boolean'],
        ]);

        $cheminImage = $categorie->image_fond;
        if ($requete->boolean('supprimer_image_fond')) {
            $cheminImage = null;
        } elseif ($requete->hasFile('image_fond_fichier')) {
            $cheminImage = $this->serviceImage->traiterImageTeleversee($requete->file('image_fond_fichier'), 'categories');
        } elseif (! empty($donnees['image_fond_url']) && $donnees['image_fond_url'] !== $categorie->image_fond) {
            $url = $donnees['image_fond_url'];
            if (Str::startsWith($url, ['http://', 'https://'])) {
                $cheminImage = $this->serviceImage->telechargerEtStockerImage($url, 'categories') ?? $url;
            } else {
                $cheminImage = $url;
            }
        }

        $categorie->update([
            'nom' => $donnees['nom'],
            'description' => $donnees['description'] ?? null,
            'ordre_affichage' => (int) $donnees['ordre_affichage'],
            'couleur' => $donnees['couleur'] ?? $categorie->couleur,
            'image_fond' => $cheminImage,
            'opacite_overlay' => (int) ($donnees['opacite_overlay'] ?? $categorie->opacite_overlay ?? 65),
            'couleur_overlay' => $donnees['couleur_overlay'] ?? $categorie->couleur_overlay ?? '#14151C',
            'afficher_dans_menu' => $requete->boolean('afficher_dans_menu'),
            'est_actif' => $requete->boolean('est_actif'),
        ]);

        JournalActivite::enregistrer(
            description: "Updated editorial category '{$categorie->nom}' background & contrast settings",
            nomLog: 'administration',
            evenement: 'mise_a_jour_categorie',
            auteur: Auth::user(),
            donnees: ['categorie_id' => $categorie->id]
        );

        return back()->with('succes', 'Category configuration and background overlay updated.');
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
