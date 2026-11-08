<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UtilisateurAdminControleur extends Controller
{
    /**
     * Liste des comptes utilisateurs et gestion des permissions.
     */
    public function index(Request $requete): View
    {
        $recherche = $requete->input('recherche');
        $roleFiltre = $requete->input('role');
        $statutFiltre = $requete->input('statut');

        $requeteUtilisateurs = User::query()->latest('created_at');

        if (! empty($recherche)) {
            $requeteUtilisateurs->where(function ($q) use ($recherche) {
                $q->where('name', 'like', "%{$recherche}%")
                  ->orWhere('email', 'like', "%{$recherche}%")
                  ->orWhere('telephone', 'like', "%{$recherche}%");
            });
        }

        if (! empty($roleFiltre)) {
            $requeteUtilisateurs->where('role', $roleFiltre);
        }

        if ($statutFiltre !== null && $statutFiltre !== '') {
            $requeteUtilisateurs->where('est_actif', (bool) $statutFiltre);
        }

        $utilisateurs = $requeteUtilisateurs->paginate(15)->withQueryString();
        $totalActifs = User::where('est_actif', true)->count();
        $totalDesactives = User::where('est_actif', false)->count();

        return view('administration.utilisateurs.index', compact(
            'utilisateurs',
            'recherche',
            'roleFiltre',
            'statutFiltre',
            'totalActifs',
            'totalDesactives'
        ));
    }

    /**
     * Ajouter un nouvel utilisateur depuis l'administration.
     */
    public function store(Request $requete): RedirectResponse
    {
        $valide = $requete->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'administrateur', 'redacteur_en_chef', 'journaliste', 'redacteur', 'abonne', 'lecteur'])],
            'telephone' => ['nullable', 'string', 'max:25'],
            'est_actif' => ['sometimes', 'boolean'],
        ]);

        $utilisateur = User::create([
            'name' => $valide['name'],
            'email' => strtolower(trim($valide['email'])),
            'password' => Hash::make($valide['password']),
            'role' => $valide['role'],
            'telephone' => $valide['telephone'] ?? null,
            'est_actif' => (bool) ($valide['est_actif'] ?? true),
        ]);

        JournalActivite::enregistrer(
            description: "Created user #{$utilisateur->id} ({$utilisateur->email}) with role {$utilisateur->role}",
            nomLog: 'administration',
            evenement: 'creation_utilisateur',
            auteur: Auth::user(),
            donnees: [
                'nouvel_utilisateur_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
                'est_actif' => $utilisateur->est_actif,
            ]
        );

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'User account created successfully.');
    }

    /**
     * Mettre à jour les informations, rôles ou statut d'un utilisateur.
     */
    public function update(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        $valide = $requete->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore($id)],
            'role' => ['required', 'string', Rule::in(['super_admin', 'administrateur', 'redacteur_en_chef', 'journaliste', 'redacteur', 'abonne', 'lecteur'])],
            'telephone' => ['nullable', 'string', 'max:25'],
            'est_actif' => ['required', 'boolean'],
            'nouveau_mot_de_passe' => ['nullable', 'string', 'min:8'],
        ]);

        $ancienRole = $utilisateur->role;
        $ancienStatut = $utilisateur->est_actif;

        // Protection 1 : un administrateur ne peut pas se rétrograder lui-même
        if ($utilisateur->id === Auth::id() && $valide['role'] !== $ancienRole) {
            return back()->with('erreur', 'You cannot change your own administrator role.');
        }

        // Protection 2 : un administrateur ne peut pas désactiver son propre compte
        if ($utilisateur->id === Auth::id() && ! $valide['est_actif']) {
            return back()->with('erreur', 'You cannot deactivate your own account.');
        }

        $donneesMaj = [
            'name' => $valide['name'],
            'email' => strtolower(trim($valide['email'])),
            'role' => $valide['role'],
            'telephone' => $valide['telephone'] ?? null,
            'est_actif' => (bool) $valide['est_actif'],
        ];

        if (! empty($valide['nouveau_mot_de_passe'])) {
            $donneesMaj['password'] = Hash::make($valide['nouveau_mot_de_passe']);
        }

        $utilisateur->update($donneesMaj);

        JournalActivite::enregistrer(
            description: "Updated user #{$utilisateur->id} ({$utilisateur->email})",
            nomLog: 'administration',
            evenement: 'maj_utilisateur',
            auteur: Auth::user(),
            donnees: [
                'ancien_role' => $ancienRole,
                'nouveau_role' => $valide['role'],
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $valide['est_actif'],
            ]
        );

        return back()->with('succes', 'User updated successfully.');
    }

    /**
     * Basculer l'état actif/désactivé d'un utilisateur en un clic.
     */
    public function basculerStatut(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        if ($utilisateur->id === Auth::id()) {
            return back()->with('erreur', 'You cannot deactivate your own account.');
        }

        $nouvelEtat = ! $utilisateur->est_actif;
        $utilisateur->update(['est_actif' => $nouvelEtat]);

        JournalActivite::enregistrer(
            description: ($nouvelEtat ? 'Activated' : 'Deactivated') . " user #{$utilisateur->id} ({$utilisateur->email})",
            nomLog: 'administration',
            evenement: 'bascule_statut_utilisateur',
            auteur: Auth::user(),
            donnees: [
                'cible_id' => $utilisateur->id,
                'est_actif' => $nouvelEtat,
            ]
        );

        $message = $nouvelEtat ? 'User account activated successfully.' : 'User account deactivated successfully.';

        return back()->with('succes', $message);
    }

    /**
     * Supprimer un utilisateur (suppression douce avec SoftDeletes).
     */
    public function destroy(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        if ($utilisateur->id === Auth::id()) {
            return back()->with('erreur', 'You cannot delete your own account.');
        }

        if ($utilisateur->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('erreur', 'Cannot delete the only remaining Super Administrator.');
        }

        $email = $utilisateur->email;
        $userId = $utilisateur->id;

        JournalActivite::enregistrer(
            description: "Deleted user #{$userId} ({$email})",
            nomLog: 'administration',
            evenement: 'suppression_utilisateur',
            auteur: Auth::user(),
            donnees: ['cible_id' => $userId, 'email' => $email]
        );

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'User account deleted successfully.');
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur par un administrateur.
     */
    public function reinitialiserMotDePasse(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        $valide = $requete->validate([
            'nouveau_mot_de_passe' => ['required', 'string', Password::min(8)],
        ]);

        $utilisateur->update([
            'password' => Hash::make($valide['nouveau_mot_de_passe']),
        ]);

        JournalActivite::enregistrer(
            description: "Password reset for user #{$utilisateur->id} ({$utilisateur->email})",
            nomLog: 'securite',
            evenement: 'reset_password_admin',
            auteur: Auth::user(),
            donnees: ['cible_id' => $utilisateur->id]
        );

        return back()->with('succes', 'Password updated successfully.');
    }
}
