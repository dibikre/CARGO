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

        $requeteUtilisateurs = User::query()->latest('created_at');

        if (! empty($recherche)) {
            $requeteUtilisateurs->where(function ($q) use ($recherche) {
                $q->where('name', 'like', "%{$recherche}%")
                  ->orWhere('email', 'like', "%{$recherche}%");
            });
        }

        if (! empty($roleFiltre)) {
            $requeteUtilisateurs->where('role', $roleFiltre);
        }

        $utilisateurs = $requeteUtilisateurs->paginate(15)->withQueryString();

        return view('administration.utilisateurs.index', compact('utilisateurs', 'recherche', 'roleFiltre'));
    }

    /**
     * Mettre à jour les privilèges ou les informations d'un utilisateur.
     */
    public function update(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        $valide = $requete->validate([
            'role' => ['required', 'string', Rule::in(['super_admin', 'administrateur', 'redacteur_en_chef', 'journaliste', 'redacteur', 'abonne', 'lecteur'])],
            'est_actif' => ['required', 'boolean'],
        ]);

        $ancienRole = $utilisateur->role;
        $ancienStatut = $utilisateur->est_actif;

        // Protection : un administrateur ne peut pas se rétrograder lui-même
        if ($utilisateur->id === Auth::id() && $valide['role'] !== $ancienRole) {
            return back()->with('erreur', 'You cannot change your own administrator role.');
        }

        $utilisateur->update([
            'role' => $valide['role'],
            'est_actif' => (bool) $valide['est_actif'],
        ]);

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

        return back()->with('succes', 'User permissions and status updated successfully.');
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur par un administrateur.
     */
    public function reinitialiserMotDePasse(Request $requete, int $id): RedirectResponse
    {
        $utilisateur = User::findOrFail($id);

        $valide = $requete->validate([
            'nouveau_mot_de_passe' => ['required', 'string', Password::min(10)->letters()->numbers()->symbols()],
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
