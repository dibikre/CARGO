<?php

namespace App\Http\Middleware;

use App\Services\ServiceJetonSecuriteRedacteur;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifierJetonApiRedaction
{
    protected ServiceJetonSecuriteRedacteur $serviceJeton;

    public function __construct(ServiceJetonSecuriteRedacteur $serviceJeton)
    {
        $this->serviceJeton = $serviceJeton;
    }

    /**
     * Valide que chaque appel API contient un secret JWT légitime, lié au login du rédacteur et à son appareil.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. L'utilisateur doit impérativement être authentifié
        if (! Auth::check()) {
            return new JsonResponse([
                'succes' => false,
                'erreur' => 'Authentification requise pour accéder à l\'API de la rédaction.',
                'code' => 'NON_AUTHENTIFIE',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $utilisateur = $request->user();

        // 2. Le compte doit être actif et posséder un rôle éditorial
        if (! $utilisateur->est_actif || ! $utilisateur->estMembreRedaction()) {
            return new JsonResponse([
                'succes' => false,
                'erreur' => 'Accès réservé aux journalistes et rédacteurs actifs.',
                'code' => 'ACCES_INTERDIT',
            ], Response::HTTP_FORBIDDEN);
        }

        // 3. Extraction du jeton de sécurité présent dans l'URL ou l'en-tête
        $jeton = $request->route('jeton')
            ?? $request->query('jeton_securite')
            ?? $request->query('jeton')
            ?? $request->header('X-Jeton-Securite')
            ?? $request->bearerToken();

        if (empty($jeton)) {
            return new JsonResponse([
                'succes' => false,
                'erreur' => 'Jeton de sécurité d\'appareil manquant dans la requête. Navigation dynamique bloquée.',
                'code' => 'JETON_MANQUANT',
            ], Response::HTTP_FORBIDDEN);
        }

        // 4. Validation cryptographique et vérification d'empreinte d'appareil
        $chargeUtile = $this->serviceJeton->validerJeton((string) $jeton, $utilisateur, $request);

        if ($chargeUtile === null) {
            return new JsonResponse([
                'succes' => false,
                'erreur' => 'Le jeton de sécurité est invalide, expiré ou associé à un autre appareil.',
                'code' => 'JETON_INVALIDE_OU_APPAREIL_NON_CONFORME',
            ], Response::HTTP_FORBIDDEN);
        }

        // Attacher la charge utile décodée à la requête pour utilisation ultérieure
        $request->attributes->set('charge_utile_jeton', $chargeUtile);

        return $next($request);
    }
}
