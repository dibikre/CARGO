<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationSysteme;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ParametreRedactionControleur extends Controller
{
    /**
     * Affiche l'écran des paramètres de compte et de sécurité du rédacteur.
     */
    public function index(): View
    {
        $utilisateur = Auth::user();
        $suppressionCompteActive = ConfigurationSysteme::suppressionCompteRedacteurEstActive();

        return view('redaction.parametres.index', compact('utilisateur', 'suppressionCompteActive'));
    }

    /**
     * Met à jour les informations du profil (pseudo/nom et adresse e-mail).
     */
    public function mettreAJourProfil(Request $requete): RedirectResponse
    {
        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        $valide = $requete->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore($utilisateur->id)],
            'telephone' => ['nullable', 'string', 'max:25'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $ancienEmail = $utilisateur->email;
        $ancienNom = $utilisateur->name;

        $utilisateur->update([
            'name' => trim($valide['name']),
            'email' => strtolower(trim($valide['email'])),
            'telephone' => ! empty($valide['telephone']) ? trim($valide['telephone']) : null,
            'bio' => ! empty($valide['bio']) ? trim($valide['bio']) : null,
        ]);

        JournalActivite::enregistrer(
            description: "Editorial staff member #{$utilisateur->id} updated profile details (name/email)",
            nomLog: 'redaction',
            evenement: 'maj_profil_redacteur',
            auteur: $utilisateur,
            donnees: [
                'ancien_nom' => $ancienNom,
                'nouveau_nom' => $utilisateur->name,
                'ancien_email' => $ancienEmail,
                'nouveau_email' => $utilisateur->email,
            ]
        );

        return back()->with('succes', 'Your profile information has been successfully updated.');
    }

    /**
     * Met à jour le mot de passe du rédacteur après vérification de son mot de passe actuel.
     */
    public function mettreAJourMotDePasse(Request $requete): RedirectResponse
    {
        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        $requete->validate([
            'mot_de_passe_actuel' => ['required', 'string'],
            'nouveau_mot_de_passe' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($requete->input('mot_de_passe_actuel'), $utilisateur->password)) {
            return back()->withErrors([
                'mot_de_passe_actuel' => 'The provided current password does not match our records.',
            ])->with('erreur', 'The current password you entered is incorrect.');
        }

        $utilisateur->update([
            'password' => Hash::make($requete->input('nouveau_mot_de_passe')),
        ]);

        JournalActivite::enregistrer(
            description: "Editorial staff member #{$utilisateur->id} changed their password",
            nomLog: 'securite',
            evenement: 'changement_mot_de_passe_redacteur',
            auteur: $utilisateur
        );

        return back()->with('succes', 'Your password has been successfully updated.');
    }

    /**
     * Supprime définitivement le compte du rédacteur si l'administrateur a autorisé cette option.
     */
    public function supprimerCompte(Request $requete): RedirectResponse
    {
        // 1. Vérification de l'autorisation globale configurée par l'administrateur
        if (! ConfigurationSysteme::suppressionCompteRedacteurEstActive()) {
            return back()->with('erreur', 'Self-service account deletion is currently disabled by system administrators.');
        }

        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        // 2. Empêcher la suppression du dernier Super Administrateur si le rédacteur a ce rôle
        if ($utilisateur->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('erreur', 'Cannot delete the only remaining Super Administrator account.');
        }

        // 3. Validation du mot de passe de confirmation
        $requete->validate([
            'mot_de_passe_confirmation' => ['required', 'string'],
        ]);

        if (! Hash::check($requete->input('mot_de_passe_confirmation'), $utilisateur->password)) {
            return back()->withErrors([
                'mot_de_passe_confirmation' => 'Password confirmation is incorrect. Account deletion cancelled.',
            ])->with('erreur', 'Password verification failed. Account was not deleted.');
        }

        $idUtilisateur = $utilisateur->id;
        $emailUtilisateur = $utilisateur->email;

        JournalActivite::enregistrer(
            description: "Editorial staff member #{$idUtilisateur} ({$emailUtilisateur}) self-deleted their account",
            nomLog: 'securite',
            evenement: 'auto_suppression_compte_redacteur',
            auteur: $utilisateur,
            donnees: [
                'utilisateur_id' => $idUtilisateur,
                'email' => $emailUtilisateur,
            ]
        );

        // Déconnexion et suppression du compte
        Auth::logout();
        $utilisateur->delete();

        $requete->session()->invalidate();
        $requete->session()->regenerateToken();

        return redirect()->route('redaction.connexion')->with('succes', 'Your editorial account has been permanently removed.');
    }
}
