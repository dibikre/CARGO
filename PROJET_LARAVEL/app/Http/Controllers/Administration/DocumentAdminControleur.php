<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\DocumentRessource;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentAdminControleur extends Controller
{
    /**
     * Bibliothèque administrative des livres blancs et baromètres de fret.
     */
    public function index(): View
    {
        $documents = DocumentRessource::latest('created_at')
            ->paginate(15);

        return view('administration.documents.index', compact('documents'));
    }

    /**
     * Enregistrer une nouvelle ressource documentaire.
     */
    public function store(Request $requete): RedirectResponse
    {
        $donnees = $requete->validate([
            'titre' => ['required', 'string', 'max:191'],
            'type_document' => ['required', 'string', 'max:50'],
            'nom_fichier' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:1000'],
            'chemin_stockage' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($donnees['titre']);

        $document = DocumentRessource::create([
            'titre' => $donnees['titre'],
            'slug' => $slug,
            'type_document' => $donnees['type_document'],
            'nom_fichier' => $donnees['nom_fichier'],
            'chemin_stockage' => $donnees['chemin_stockage'] ?? 'documents/' . $donnees['nom_fichier'],
            'description' => $donnees['description'] ?? null,
            'taille_octets' => 1024 * 1024 * 2, // 2MB par défaut
            'est_reserve_abonnes' => $requete->boolean('est_reserve_abonnes'),
            'est_actif' => true,
            'telechargements_compteur' => 0,
        ]);

        JournalActivite::enregistrer(
            description: "Published technical document '{$document->titre}'",
            nomLog: 'administration',
            evenement: 'publication_document_ressource',
            auteur: Auth::user(),
            donnees: ['document_id' => $document->id, 'pro_only' => $document->est_reserve_abonnes]
        );

        return back()->with('succes', 'Technical resource added to publication library.');
    }

    /**
     * Basculer le statut réservé aux abonnés PRO.
     */
    public function basculerPro(int $id): RedirectResponse
    {
        $document = DocumentRessource::findOrFail($id);
        $nouvelEtat = ! $document->est_reserve_abonnes;
        $document->update(['est_reserve_abonnes' => $nouvelEtat]);

        return back()->with('succes', 'Subscriber paywall restriction updated.');
    }

    /**
     * Supprimer une ressource.
     */
    public function destroy(int $id): RedirectResponse
    {
        $document = DocumentRessource::findOrFail($id);
        $titre = $document->titre;
        $document->delete();

        JournalActivite::enregistrer(
            description: "Deleted technical document '{$titre}'",
            nomLog: 'administration',
            evenement: 'suppression_document_ressource',
            auteur: Auth::user(),
            donnees: ['document_id' => $id]
        );

        return back()->with('succes', "Document '{$titre}' has been removed.");
    }
}
