<?php

namespace App\Http\Controllers;

use App\Models\DocumentRessource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentRessourceControleur extends Controller
{
    /**
     * Bibliothèque des livres blancs, fiches techniques et baromètres de taux de fret.
     */
    public function index(): View
    {
        $documents = DocumentRessource::actif()
            ->latest()
            ->paginate(12);

        return view('journal.ressources.index', compact('documents'));
    }

    /**
     * Téléchargement sécurisé avec incrémentation et contrôle d'accès abonné.
     */
    public function telecharger(string $slug, Request $request): StreamedResponse
    {
        $document = DocumentRessource::where('slug', $slug)
            ->actif()
            ->firstOrFail();

        // Contrôle paywall si le document est réservé aux abonnés
        if ($document->est_reserve_abonnes) {
            $utilisateur = $request->user();

            if (! $utilisateur || (! $utilisateur->estAbonne() && ! $utilisateur->estMembreRedaction())) {
                abort(403, 'Ce document exclusif est réservé à nos abonnés professionnels.');
            }
        }

        // Incrémenter le nombre de téléchargements
        $document->incrementerTelechargements();

        return Storage::download($document->chemin_stockage, $document->nom_fichier);
    }
}
