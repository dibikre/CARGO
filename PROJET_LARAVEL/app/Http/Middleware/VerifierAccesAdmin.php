<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifierAccesAdmin
{
    /**
     * Valider l'accès sécurisé à l'espace d'administration.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérification de l'authentification
        if (! Auth::check()) {
            return redirect()->route('admin.connexion')->with('erreur', 'Authentification requise pour accéder à la console d\'administration.');
        }

        $utilisateur = $request->user();

        // 2. Vérification que le compte est actif (non banni/suspendu)
        if (! $utilisateur->est_actif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::warning('Tentative d\'accès administrateur avec un compte désactivé', [
                'user_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('admin.connexion')->with('erreur', 'Votre compte administrateur a été désactivé par la direction.');
        }

        // 3. Vérification des privilèges d'administration
        $rolesAutorises = ['super_admin', 'administrateur', 'admin'];
        $aRoleAutorise = in_array($utilisateur->role, $rolesAutorises, true);

        if (! $aRoleAutorise && method_exists($utilisateur, 'hasAnyRole')) {
            $aRoleAutorise = $utilisateur->hasAnyRole(['super_admin', 'administrateur', 'admin']);
        }

        if (! $aRoleAutorise) {
            Log::warning('Tentative d\'accès non autorisé au backoffice administrateur', [
                'user_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            abort(403, 'Accès strictement restreint aux administrateurs habilités.');
        }

        // 4. En-têtes de sécurité HTTP pour la session admin (Clickjacking, MIME-sniffing)
        $reponse = $next($request);
        $reponse->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $reponse->headers->set('X-Content-Type-Options', 'nosniff');
        $reponse->headers->set('X-XSS-Protection', '1; mode=block');

        return $reponse;
    }
}
