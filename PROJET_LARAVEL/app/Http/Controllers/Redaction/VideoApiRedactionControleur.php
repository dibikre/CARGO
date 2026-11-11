<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\VideoLogistique;
use App\Services\ServiceJetonSecuriteRedacteur;
use App\Services\ServiceVideoLogistique;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoApiRedactionControleur extends Controller
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
     * Retourne la liste paginée et filtrée des vidéos avec indicateurs et jeton rafraîchi.
     */
    public function donnees(Request $request, ?string $jeton = null): JsonResponse
    {
        $criteres = [
            'recherche' => $request->query('recherche'),
            'plateforme' => $request->query('plateforme'),
            'statut' => $request->query('statut'),
            'tri' => $request->query('tri', 'recent'),
        ];

        $parPage = max(6, min(48, (int) $request->query('par_page', 12)));
        $paginator = $this->serviceVideo->recupererVideosFiltrees($criteres, $parPage);
        $indicateurs = $this->serviceVideo->recupererIndicateurs();

        $items = collect($paginator->items())->map(function (VideoLogistique $video) {
            return [
                'id' => $video->id,
                'titre' => $video->titre,
                'slug' => $video->slug,
                'url_embed' => $video->url_embed,
                'url_iframe' => $video->url_iframe_formattee,
                'url_miniature' => $video->url_miniature,
                'code_iframe' => $video->code_iframe,
                'plateforme' => $video->plateforme,
                'source' => $video->source ?: 'Editorial Field Video',
                'duree' => $video->duree,
                'description' => $video->description,
                'est_actif' => (bool) $video->est_actif,
                'ordre_affichage' => (int) $video->ordre_affichage,
                'date_creation' => $video->created_at ? $video->created_at->format('M d, Y') : null,
                'date_relative' => $video->created_at ? $video->created_at->diffForHumans() : null,
                'auteur' => $video->auteur ? $video->auteur->name : 'Staff Desk',
            ];
        });

        $jetonActuel = $jeton ?? $request->query('jeton_securite') ?? $request->header('X-Jeton-Securite');
        $nouveauJeton = $request->user() ? $this->serviceJeton->rafraichirJeton((string) $jetonActuel, $request->user(), $request) : null;

        return response()->json([
            'succes' => true,
            'donnees' => $items,
            'pagination' => [
                'page_courante' => $paginator->currentPage(),
                'derniere_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'par_page' => $paginator->perPage(),
                'de' => $paginator->firstItem(),
                'a' => $paginator->lastItem(),
            ],
            'indicateurs' => $indicateurs,
            'jeton_securite' => $nouveauJeton ?? $jetonActuel,
        ]);
    }

    /**
     * Enregistre une nouvelle vidéo par requête AJAX sécurisée.
     */
    public function store(Request $request, ?string $jeton = null): JsonResponse
    {
        $donnees = $request->validate([
            'titre' => 'required|string|max:191',
            'url_embed' => 'required|string|max:500',
            'source' => 'nullable|string|max:120',
            'duree' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
            'est_actif' => 'nullable|boolean',
            'ordre_affichage' => 'nullable|integer|min:0|max:999',
        ]);

        $video = $this->serviceVideo->creerVideo($donnees, $request->user()->id);
        $indicateurs = $this->serviceVideo->recupererIndicateurs();
        $jetonActuel = $jeton ?? $request->query('jeton_securite') ?? $request->header('X-Jeton-Securite');
        $nouveauJeton = $request->user() ? $this->serviceJeton->rafraichirJeton((string) $jetonActuel, $request->user(), $request) : null;

        return response()->json([
            'succes' => true,
            'message' => 'Video embed successfully published to the newsroom desk.',
            'video' => [
                'id' => $video->id,
                'titre' => $video->titre,
                'slug' => $video->slug,
                'url_iframe' => $video->url_iframe_formattee,
                'url_miniature' => $video->url_miniature,
                'plateforme' => $video->plateforme,
                'source' => $video->source,
                'duree' => $video->duree,
                'est_actif' => (bool) $video->est_actif,
            ],
            'indicateurs' => $indicateurs,
            'jeton_securite' => $nouveauJeton ?? $jetonActuel,
        ], 201);
    }

    /**
     * Bascule l'état actif/inactif d'une vidéo sur la page d'accueil sans recharger la page.
     */
    public function toggleStatut(Request $request, $param1 = null, $param2 = null): JsonResponse
    {
        $routeId = $request->route('id');
        $id = 0;
        if (is_numeric($routeId)) {
            $id = (int) $routeId;
        } elseif (is_numeric($param2)) {
            $id = (int) $param2;
        } elseif (is_numeric($param1)) {
            $id = (int) $param1;
        } elseif ($request->has('id') && is_numeric($request->input('id'))) {
            $id = (int) $request->input('id');
        }

        if ($id <= 0) {
            return response()->json(['succes' => false, 'erreur' => 'Invalid video ID.'], 400);
        }

        $video = $this->serviceVideo->basculerStatut($id);
        $indicateurs = $this->serviceVideo->recupererIndicateurs();
        $jetonActuel = (string) ($request->route('jeton') ?? $request->header('X-Jeton-Securite') ?? $param1);
        $nouveauJeton = $request->user() ? $this->serviceJeton->rafraichirJeton($jetonActuel, $request->user(), $request) : null;

        return response()->json([
            'succes' => true,
            'message' => $video->est_actif ? 'Video enabled on homepage.' : 'Video hidden from homepage.',
            'id' => $video->id,
            'est_actif' => (bool) $video->est_actif,
            'indicateurs' => $indicateurs,
            'jeton_securite' => $nouveauJeton ?? $jetonActuel,
        ]);
    }

    /**
     * Supprime une vidéo de manière asynchrone sécurisée.
     */
    public function destroy(Request $request, $param1 = null, $param2 = null): JsonResponse
    {
        $routeId = $request->route('id');
        $id = 0;
        if (is_numeric($routeId)) {
            $id = (int) $routeId;
        } elseif (is_numeric($param2)) {
            $id = (int) $param2;
        } elseif (is_numeric($param1)) {
            $id = (int) $param1;
        } elseif ($request->has('id') && is_numeric($request->input('id'))) {
            $id = (int) $request->input('id');
        }

        if ($id <= 0) {
            return response()->json(['succes' => false, 'erreur' => 'Invalid video ID.'], 400);
        }

        $this->serviceVideo->supprimerVideo($id);
        $indicateurs = $this->serviceVideo->recupererIndicateurs();
        $jetonActuel = (string) ($request->route('jeton') ?? $request->header('X-Jeton-Securite') ?? $param1);
        $nouveauJeton = $request->user() ? $this->serviceJeton->rafraichirJeton($jetonActuel, $request->user(), $request) : null;

        return response()->json([
            'succes' => true,
            'message' => 'Video embed successfully removed from repository.',
            'id_supprime' => $id,
            'indicateurs' => $indicateurs,
            'jeton_securite' => $nouveauJeton ?? $jetonActuel,
        ]);
    }

    /**
     * Supprime un lot de vidéos sélectionnées sans aucun rechargement de page.
     */
    public function destroyLot(Request $request, ?string $jeton = null): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $nbSupprimes = $this->serviceVideo->supprimerLotVideos($request->input('ids'));
        $indicateurs = $this->serviceVideo->recupererIndicateurs();
        $jetonActuel = (string) ($jeton ?? $request->header('X-Jeton-Securite'));
        $nouveauJeton = $request->user() ? $this->serviceJeton->rafraichirJeton($jetonActuel, $request->user(), $request) : null;

        return response()->json([
            'succes' => true,
            'message' => "{$nbSupprimes} video(s) permanently removed.",
            'nb_supprimes' => $nbSupprimes,
            'indicateurs' => $indicateurs,
            'jeton_securite' => $nouveauJeton ?? $jetonActuel,
        ]);
    }
    }
}
