<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\RedactionConnexionRequest;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConnexionRedactionControleur extends Controller
{
    /**
     * Afficher le formulaire de connexion dédié à la rédaction et aux journalistes.
     */
    public function afficherFormulaire(): View|RedirectResponse
    {
        if (Auth::check()) {
            $utilisateur = Auth::user();
            if ($utilisateur->estMembreRedaction()) {
                return redirect()->route('redaction.articles.index');
            }
        }

        return view('redaction.connexion');
    }

    /**
     * Authentifier un journaliste ou membre de la rédaction avec vérification stricte de rôle.
     */
    public function connecter(RedactionConnexionRequest $requete): RedirectResponse
    {
        // 1. Défense anti-bruteforce
        $requete->assurerNonBloque();

        // 2. Défense anti-robot (champ piège honeypot)
        if (! empty($requete->input('jeton_securite'))) {
            $requete->incrementerTentatives();
            return redirect()->route('redaction.connexion');
        }

        $identifiants = $requete->only('email', 'password');
        $seSouvenir = $requete->boolean('se_souvenir_de_moi');

        // 3. Vérification des identifiants
        if (! Auth::attempt($identifiants, $seSouvenir)) {
            $requete->incrementerTentatives();

            JournalActivite::enregistrer(
                description: 'Failed newsroom desk authentication attempt',
                nomLog: 'securite',
                evenement: 'login_redaction_echec',
                donnees: [
                    'email' => $requete->input('email'),
                    'ip' => $requete->ip(),
                    'user_agent' => $requete->userAgent(),
                ]
            );

            throw ValidationException::withMessages([
                'email' => 'Identifiants invalides ou compte éditorial non autorisé.',
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
                description: 'Attempt to authenticate on disabled editorial account',
                nomLog: 'securite',
                evenement: 'compte_redaction_desactive',
                auteur: $utilisateur,
                donnees: ['ip' => $requete->ip()]
            );

            throw ValidationException::withMessages([
                'email' => 'Votre compte éditorial a été désactivé. Veuillez contacter le rédacteur en chef.',
            ]);
        }

        // 5. Vérification du rôle éditorial (rejette les abonnés et lecteurs simples)
        if (! $utilisateur->estMembreRedaction()) {
            Auth::logout();
            $requete->session()->invalidate();
            $requete->session()->regenerateToken();
            $requete->incrementerTentatives();

            JournalActivite::enregistrer(
                description: 'Subscriber attempted to access editorial desk',
                nomLog: 'securite',
                evenement: 'acces_redaction_refuse_abonne',
                auteur: $utilisateur,
                donnees: ['role' => $utilisateur->role, 'ip' => $requete->ip()]
            );

            throw ValidationException::withMessages([
                'email' => 'Ce portail est strictement réservé aux membres de la rédaction et aux journalistes. Pour l\'accès abonné, veuillez utiliser l\'espace Lecteur PRO.',
            ]);
        }

        // 6. Succès : Régénération immédiate de l'ID de session
        $requete->session()->regenerate();
        $requete->effacerTentatives();

        $utilisateur->update([
            'derniere_connexion_a' => now(),
        ]);

        JournalActivite::enregistrer(
            description: "Journalist '{$utilisateur->name}' ({$utilisateur->role}) authenticated on Newsroom Desk",
            nomLog: 'authentification',
            evenement: 'login_redaction_succes',
            auteur: $utilisateur,
            donnees: [
                'ip' => $requete->ip(),
                'user_agent' => $requete->userAgent(),
            ]
        );

        return redirect()->intended(route('redaction.articles.index'));
    }

    /**
     * Déconnecter la session du journaliste ou membre de la rédaction.
     */
    public function deconnecter(Request $requete): RedirectResponse
    {
        $utilisateur = Auth::user();

        if ($utilisateur) {
            JournalActivite::enregistrer(
                description: "Journalist '{$utilisateur->name}' logged out from Newsroom Desk",
                nomLog: 'authentification',
                evenement: 'logout_redaction',
                auteur: $utilisateur,
                donnees: ['ip' => $requete->ip()]
            );
        }

        Auth::logout();

        $requete->session()->invalidate();
        $requete->session()->regenerateToken();

        return redirect()->route('redaction.connexion')->with('statut', 'Vous avez été déconnecté en toute sécurité de l\'espace rédaction.');
    }
}
