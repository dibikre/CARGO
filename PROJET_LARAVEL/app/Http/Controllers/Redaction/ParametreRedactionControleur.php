<?php

namespace App\Http\Controllers\Redaction;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationSysteme;
use App\Models\JournalActivite;
use App\Models\User;
use App\Services\ServiceAvatarUtilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ParametreRedactionControleur extends Controller
{
    protected ServiceAvatarUtilisateur $serviceAvatar;

    public function __construct(ServiceAvatarUtilisateur $serviceAvatar)
    {
        $this->serviceAvatar = $serviceAvatar;
    }

    /**
     * Affiche l'écran des paramètres de compte et de sécurité du rédacteur.
     */
    public function index(): View
    {
        $utilisateur = Auth::user();
        $suppressionCompteActive = ConfigurationSysteme::suppressionCompteRedacteurEstActive();
        $photoAuteurActive = ConfigurationSysteme::photoAuteurArticlesEstActivee();

        return view('redaction.parametres.index', compact('utilisateur', 'suppressionCompteActive', 'photoAuteurActive'));
    }

    /**
     * Met à jour les informations du profil (pseudo/nom, photo d'avatar et adresse e-mail).
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
            'photo_profil' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        // Traitement et génération de la vignette 40x40 si une nouvelle photo est fournie
        if ($requete->hasFile('photo_profil')) {
            $this->serviceAvatar->enregistrerAvatar($requete->file('photo_profil'), $utilisateur);
        }

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

        return back()->with('succes', 'Vos informations de profil ont été mises à jour avec succès.');
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
                'mot_de_passe_actuel' => 'Le mot de passe actuel fourni ne correspond pas à nos enregistrements.',
            ])->with('erreur', 'Le mot de passe actuel saisi est incorrect.');
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

        return back()->with('succes', 'Votre mot de passe a été mis à jour avec succès.');
    }

    /**
     * Supprime définitivement le compte du rédacteur si l'administrateur a autorisé cette option.
     */
    public function supprimerCompte(Request $requete): RedirectResponse
    {
        // 1. Vérification de l'autorisation globale configurée par l'administrateur
        if (! ConfigurationSysteme::suppressionCompteRedacteurEstActive()) {
            return back()->with('erreur', 'La suppression autonome de compte est actuellement désactivée par les administrateurs système.');
        }

        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        // 2. Empêcher la suppression du dernier Super Administrateur si le rédacteur a ce rôle
        if ($utilisateur->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('erreur', 'Impossible de supprimer le seul compte Super Administrateur restant.');
        }

        // 3. Validation du mot de passe de confirmation
        $requete->validate([
            'mot_de_passe_confirmation' => ['required', 'string'],
        ]);

        if (! Hash::check($requete->input('mot_de_passe_confirmation'), $utilisateur->password)) {
            return back()->withErrors([
                'mot_de_passe_confirmation' => 'La confirmation du mot de passe est incorrecte. Suppression du compte annulée.',
            ])->with('erreur', 'Échec de la vérification du mot de passe. Le compte n\'a pas été supprimé.');
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

        return redirect()->route('redaction.connexion')->with('succes', 'Votre compte éditorial a été supprimé définitivement.');
    }
}
