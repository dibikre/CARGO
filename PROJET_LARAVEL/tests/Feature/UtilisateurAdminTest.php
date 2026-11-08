<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests fonctionnels pour la gestion administrative des utilisateurs :
 * Ajout, modification, bascule de statut (activation/désactivation) et suppression.
 */
class UtilisateurAdminTest extends TestCase
{
    protected function creerAdministrateur(): User
    {
        return User::factory()->create([
            'role' => 'administrateur',
            'est_actif' => true,
            'password' => Hash::make('PasswordAdmin123!'),
        ]);
    }

    /**
     * Vérifie qu'un administrateur peut créer un nouvel utilisateur.
     */
    public function test_administrateur_peut_creer_un_utilisateur(): void
    {
        $admin = $this->creerAdministrateur();

        $reponse = $this->actingAs($admin)->post(route('admin.utilisateurs.store'), [
            'name' => 'Alice Martin',
            'email' => 'alice.martin@test-cargo.com',
            'password' => 'SecretPass123!',
            'role' => 'journaliste',
            'telephone' => '+33 6 12 34 56 78',
            'est_actif' => '1',
        ]);

        $reponse->assertRedirect(route('admin.utilisateurs.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'alice.martin@test-cargo.com',
            'role' => 'journaliste',
            'est_actif' => true,
        ]);
    }

    /**
     * Vérifie qu'un administrateur peut mettre à jour un utilisateur.
     */
    public function test_administrateur_peut_modifier_un_utilisateur(): void
    {
        $admin = $this->creerAdministrateur();
        $cible = User::factory()->create(['role' => 'lecteur', 'est_actif' => true]);

        $reponse = $this->actingAs($admin)->put(route('admin.utilisateurs.update', $cible->id), [
            'name' => 'Bob Updated',
            'email' => 'bob.updated@test-cargo.com',
            'role' => 'redacteur',
            'telephone' => '+33 1 23 45 67 89',
            'est_actif' => '1',
        ]);

        $reponse->assertSessionHas('succes');
        $this->assertDatabaseHas('users', [
            'id' => $cible->id,
            'name' => 'Bob Updated',
            'email' => 'bob.updated@test-cargo.com',
            'role' => 'redacteur',
        ]);
    }

    /**
     * Vérifie qu'un administrateur peut désactiver puis réactiver un utilisateur.
     */
    public function test_administrateur_peut_basculer_statut_utilisateur(): void
    {
        $admin = $this->creerAdministrateur();
        $cible = User::factory()->create(['est_actif' => true]);

        // Désactivation
        $reponse = $this->actingAs($admin)->post(route('admin.utilisateurs.statut', $cible->id));
        $reponse->assertSessionHas('succes');
        $this->assertDatabaseHas('users', ['id' => $cible->id, 'est_actif' => false]);

        // Réactivation
        $reponseActive = $this->actingAs($admin)->post(route('admin.utilisateurs.statut', $cible->id));
        $reponseActive->assertSessionHas('succes');
        $this->assertDatabaseHas('users', ['id' => $cible->id, 'est_actif' => true]);
    }

    /**
     * Vérifie qu'un administrateur ne peut pas désactiver son propre compte.
     */
    public function test_administrateur_ne_peut_pas_desactiver_son_propre_compte(): void
    {
        $admin = $this->creerAdministrateur();

        $reponse = $this->actingAs($admin)->post(route('admin.utilisateurs.statut', $admin->id));
        $reponse->assertSessionHas('erreur');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'est_actif' => true]);
    }

    /**
     * Vérifie qu'un administrateur peut supprimer un compte (soft delete).
     */
    public function test_administrateur_peut_supprimer_un_utilisateur(): void
    {
        $admin = $this->creerAdministrateur();
        $cible = User::factory()->create(['role' => 'lecteur']);

        $reponse = $this->actingAs($admin)->delete(route('admin.utilisateurs.destroy', $cible->id));
        $reponse->assertRedirect(route('admin.utilisateurs.index'));

        $this->assertSoftDeleted('users', ['id' => $cible->id]);
    }

    /**
     * Vérifie qu'un administrateur ne peut pas supprimer son propre compte.
     */
    public function test_administrateur_ne_peut_pas_supprimer_son_propre_compte(): void
    {
        $admin = $this->creerAdministrateur();

        $reponse = $this->actingAs($admin)->delete(route('admin.utilisateurs.destroy', $admin->id));
        $reponse->assertSessionHas('erreur');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'deleted_at' => null]);
    }
}
