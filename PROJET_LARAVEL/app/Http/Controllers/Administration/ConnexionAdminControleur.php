<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminConnexionRequest;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConnexionAdminControleur extends Controller
{
    /**
     * Afficher le formulaire de connexion sécurisé de l'administration.
     */
    public function afficherFormulaire(): View|RedirectResponse
    {
        if (Auth::check()) {
            $utilisateur = Auth::user();
            if (in_array($utilisateur->role, ['super_admin', 'administrateur'], true)) {
                return redirect()->route('admin.tableau-bord');
            }
        }

        return view('administration.connexion');
    }

    /**
     * Traiter la tentative de connexion administrative avec défenses renforcées.
     */
    public function connecter(AdminConnexionRequest $requete): RedirectResponse
    {
        // 1. Défense anti-bruteforce (limiteur de débit)
        $requete->assurerNonBloque();

        // 2. Défense anti-robot (champ piège honeypot)
        if (! empty($requete->input('jeton_securite'))) {
            $requete->incrementerTentatives();
            return redirect()->route('admin.connexion');
        }

        $identifiants = $requete->only('email', 'password');
        $seSouvenir = $requete->boolean('se_souvenir_de_moi');

        // 3. Vérification des identifiants
        if (! Auth::attempt($identifiants, $seSouvenir)) {
            $requete->incrementerTentatives();

            JournalActivite::enregistrer(
                description: 'Failed administrator authentication attempt',
                nomLog: 'securite',
                evenement: 'login_echec',
                donnees: [
                    'email' => $requete->input('email'),
                    'ip' => $requete->ip(),
                    'user_agent' => $requete->userAgent(),
                ]
            );

            throw ValidationException::withMessages([
                'email' => 'Invalid credentials or unauthorized access.',
            ]);
        }

        $utilisateur = Auth::user();

        // 4. Vérification de l'activation du compte
        if (! $utilisateur->est_actif) {
            Auth::logout();
            $requete->session()->invalidate();
            $requete->session()->regenerateToken();
            $requete->incrementerTentatives();

            JournalActivite::enregistrer(
                description: 'Attempt to authenticate on disabled administrator account',
                nomLog: 'securite',
                evenement: 'compte_desactive',
                auteur: $utilisateur,
                donnees: ['ip' => $requete->ip()]
            );

            throw ValidationException::withMessages([
                'email' => 'Your account is disabled. Please contact the security team.',
            ]);
        }

        // 5. Vérification du rôle administrateur strict
        $rolesAutorises = ['super_admin', 'administrateur'];
        if (! in_array($utilisateur->role, $rolesAutorises, true)) {
            Auth::logout();
            $requete->session()->invalidate();
            $requete->session()->regenerateToken();
            $requete->incrementerTentatives();

            JournalActivite::enregistrer(
                description: 'Attempt to access administrative console with insufficient role',
                nomLog: 'securite',
                evenement: 'privilege_insuffisant',
                auteur: $utilisateur,
                donnees: ['role' => $utilisateur->role, 'ip' => $requete->ip()]
            );

            throw ValidationException::withMessages([
                'email' => 'Invalid credentials or unauthorized access.',
            ]);
        }

        // 6. Succès : Régénération immédiate de l'ID de session (anti-fixation)
        $requete->session()->regenerate();
        $requete->effacerTentatives();

        $utilisateur->update([
            'derniere_connexion_a' => now(),
        ]);

        JournalActivite::enregistrer(
            description: 'Successful administrator login',
            nomLog: 'authentification',
            evenement: 'login_succes',
            auteur: $utilisateur,
            donnees: [
                'ip' => $requete->ip(),
                'user_agent' => $requete->userAgent(),
            ]
        );

        return redirect()->intended(route('admin.tableau-bord'));
    }

    /**
     * Déconnecter la session administrateur en toute sécurité.
     */
    public function deconnecter(Request $requete): RedirectResponse
    {
        $utilisateur = Auth::user();

        if ($utilisateur) {
            JournalActivite::enregistrer(
                description: 'Administrator logged out',
                nomLog: 'authentification',
                evenement: 'logout',
                auteur: $utilisateur,
                donnees: ['ip' => $requete->ip()]
            );
        }

        Auth::logout();

        $requete->session()->invalidate();
        $requete->session()->regenerateToken();

        return redirect()->route('admin.connexion')->with('statut', 'You have been safely disconnected.');
    }
}
