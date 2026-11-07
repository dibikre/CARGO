<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\VideoLogistique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoRedactionControleur extends Controller
{
    /**
     * Liste des vidéos logistiques pour l'équipe de rédaction.
     */
    public function index(): View
    {
        $videos = VideoLogistique::with('auteur')->latest()->paginate(15);

        return view('redaction.videos.index', compact('videos'));
    }

    /**
     * Enregistrer une nouvelle vidéo embarquée par un publicateur.
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

        $slugBase = Str::slug($donnees['titre']);
        $slug = $slugBase;
        $compteur = 1;
        while (VideoLogistique::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $compteur++;
        }

        VideoLogistique::create([
            'user_id' => auth()->id(),
            'titre' => $donnees['titre'],
            'slug' => $slug,
            'url_embed' => $donnees['url_embed'],
            'source' => $donnees['source'] ?? 'Rédaction CARGO',
            'duree' => $donnees['duree'] ?? null,
            'description' => $donnees['description'] ?? null,
            'est_actif' => true,
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
        ]);

        return back()->with('succes', 'Vidéo logistique enregistrée avec succès et visible sur le journal.');
    }

    /**
     * Supprimer une vidéo logistique.
     */
    public function destroy(int $id): RedirectResponse
    {
        $video = VideoLogistique::findOrFail($id);
        $video->delete();

        return back()->with('succes', 'La vidéo a été retirée du journal.');
    }
}
