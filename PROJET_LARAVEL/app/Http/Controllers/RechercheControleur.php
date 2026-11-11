<?php

namespace App\Http\Controllers;

use App\Http\Requests\RechercheInitierRequest;
use App\Services\ServiceRechercheSecurisee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RechercheControleur extends Controller
{
    protected ServiceRechercheSecurisee $serviceRecherche;

    public function __construct(ServiceRechercheSecurisee $serviceRecherche)
    {
        $this->serviceRecherche = $serviceRecherche;
    }

    /**
     * Redirection de l'ancienne URL de recherche statique vers l'accueil.
     */
    public function anciennePage(): RedirectResponse
    {
        return redirect()->route('accueil')->with('info', 'La recherche s\'effectue désormais directement depuis la barre de navigation du journal.');
    }

    /**
     * Génère une URL temporaire cryptographique et éphémère (10 secondes, usage unique).
     */
    public function initier(RechercheInitierRequest $request): JsonResponse|RedirectResponse
    {
        $motsCles = (string) $request->validated('q');
        $sessionId = $request->session()->getId();
        $ip = (string) $request->ip();

        $jeton = $this->serviceRecherche->initialiserRecherche($motsCles, $sessionId, $ip);

        if ($request->expectsJson() || $request->isJson()) {
            return response()->json([
                'succes' => true,
                'url' => $jeton['url'],
                'code' => $jeton['code'],
                'expire_dans' => $jeton['expire_dans'],
            ]);
        }

        return redirect()->to($jeton['url']);
    }

    /**
     * Affiche les résultats de recherche après consommation atomique et unique du code temporaire.
     */
    public function afficherResultats(Request $request, string $code): View|Response
    {
        $sessionId = $request->session()->getId();
        $consommation = $this->serviceRecherche->consommerJeton($code, $sessionId);

        if (! $consommation['valide']) {
            $message = 'Votre session de recherche a expiré. Veuillez relancer une nouvelle recherche.';

            return response()->view('journal.recherche.invalide', [
                'code' => $code,
                'message' => $message,
            ], 410);
        }

        $donnees = $consommation['donnees'] ?? [];
        $motsCles = (string) ($donnees['q'] ?? '');

        $articles = $this->serviceRecherche->rechercherArticles($motsCles);
        $categories = $this->serviceRecherche->recupererCategoriesActives();

        return view('journal.recherche.resultats', [
            'articles' => $articles,
            'categories' => $categories,
            'motsCles' => $motsCles,
            'code' => $code,
        ]);
    }
}
