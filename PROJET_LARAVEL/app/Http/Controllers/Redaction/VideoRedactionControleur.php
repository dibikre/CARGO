<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Services\ServiceJetonSecuriteRedacteur;
use App\Services\ServiceVideoLogistique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoRedactionControleur extends Controller
{
    protected ServiceVideoLogistique $serviceVideo;
    protected ServiceJetonSecuriteRedacteur $serviceJeton;

    public function __construct(
        ServiceVideoLogistique $serviceVideo,
        ServiceJetonSecuriteRedacteur $serviceJeton
    ) {
        $this->serviceVideo = $serviceVideo;
        $this->serviceJeton = $serviceJeton;
    }

    /**
     * Interface principale de gestion de la vidéothèque embarquée avec navigation dynamique.
     */
    public function index(Request $request): View
    {
        $utilisateur = $request->user();
        $jetonSecurite = $this->serviceJeton->genererJeton($utilisateur, $request, 3600);

        $criteres = [
            'recherche' => $request->query('recherche'),
            'plateforme' => $request->query('plateforme'),
            'statut' => $request->query('statut'),
            'tri' => $request->query('tri', 'recent'),
        ];

        $videos = $this->serviceVideo->recupererVideosFiltrees($criteres, 12);
        $indicateurs = $this->serviceVideo->recupererIndicateurs();

        return view('redaction.videos.index', compact('videos', 'indicateurs', 'jetonSecurite', 'criteres'));
    }

    /**
     * Enregistrement classique en fallback si le client n'utilise pas l'API dynamique.
     */
    public function store(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'titre' => 'required|string|max:191',
            'url_embed' => 'required|string|max:500',
            'source' => 'nullable|string|max:120',
            'duree' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
            'ordre_affichage' => 'nullable|integer|min:0|max:999',
        ]);

        $this->serviceVideo->creerVideo($donnees, $request->user()->id);

        return back()->with('succes', 'Vidéo embarquée enregistrée avec succès.');
    }

    /**
     * Suppression classique en fallback si le client n'utilise pas l'API dynamique.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->serviceVideo->supprimerVideo($id);

        return back()->with('succes', 'Vidéo embarquée supprimée.');
    }

    /**
     * Bascule classique de statut en fallback si besoin.
     */
    public function toggleStatut(int $id): RedirectResponse
    {
        $video = $this->serviceVideo->basculerStatut($id);
        $message = $video->est_actif ? 'Vidéo activée sur la page d\'accueil.' : 'Vidéo masquée de la page d\'accueil.';

        return back()->with('succes', $message);
    }
}
