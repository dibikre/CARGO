<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifierAccesRedaction
{
    /**
     * Valider l'accès sécurisé à l'espace de rédaction et modération.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérification de l'authentification
        if (! Auth::check()) {
            return redirect()->route('redaction.connexion')->with('erreur', 'Please authenticate to access the CARGO Newsroom Desk.');
        }

        $utilisateur = $request->user();

        // 2. Vérification que le compte est actif
        if (! $utilisateur->est_actif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::warning('Tentative d\'accès rédaction avec un compte désactivé', [
                'user_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('redaction.connexion')->with('erreur', 'Your editorial account has been deactivated. Please contact the editor-in-chief.');
        }

        // 3. Vérification des privilèges de rédaction
        if (! $utilisateur->estMembreRedaction()) {
            Log::warning('Tentative d\'accès non autorisé au desk de rédaction', [
                'user_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('redaction.connexion')->with('erreur', 'Access restricted to accredited editorial journalists and editors. For subscribers, please use the PRO Reader portal.');
        }

        // 4. En-têtes de sécurité HTTP pour la session rédaction
        $reponse = $next($request);
        $reponse->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $reponse->headers->set('X-Content-Type-Options', 'nosniff');
        $reponse->headers->set('X-XSS-Protection', '1; mode=block');

        return $reponse;
    }
}
