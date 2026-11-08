<?php

namespace Tests\Feature;

use App\Models\AbonneNewsletter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests fonctionnels pour l'administration des abonnés newsletter :
 * Affichage de la liste, calcul des métriques de confirmation sans erreur SQL,
 * recherche par nom complet ou courriel, et filtrage par état de confirmation.
 */
class NewsletterAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function creerAdministrateur(): User
    {
        return User::factory()->create([
            'role' => 'administrateur',
            'est_actif' => true,
            'password' => Hash::make('PasswordAdmin123!'),
        ]);
    }

    /**
     * Vérifie que la page d'administration newsletter s'affiche sans erreur SQL (code 42S22).
     */
    public function test_affichage_page_admin_newsletter_et_metriques(): void
    {
        $admin = $this->creerAdministrateur();

        // Création d'un abonné confirmé
        AbonneNewsletter::create([
            'email' => 'confirme@cargo-test.com',
            'nom_complet' => 'Jean Dupont',
            'entreprise' => 'Transports Maritimes SA',
            'frequence' => 'quotidienne',
            'token_desinscription' => Str::random(40),
            'est_verifie' => true,
            'est_actif' => true,
            'date_confirmation' => now(),
        ]);

        // Création d'un abonné en attente de validation
        AbonneNewsletter::create([
            'email' => 'attente@cargo-test.com',
            'nom_complet' => 'Claire Delamare',
            'entreprise' => 'Logistique Express',
            'frequence' => 'hebdomadaire',
            'token_confirmation' => Str::random(40),
            'token_desinscription' => Str::random(40),
            'est_verifie' => false,
            'est_actif' => true,
            'date_confirmation' => null,
        ]);

        $reponse = $this->actingAs($admin)->get(route('admin.newsletter.index'));

        $reponse->assertStatus(200);
        $reponse->assertViewIs('administration.newsletter.index');
        $reponse->assertViewHas('metriques', function (array $metriques) {
            return $metriques['total'] === 2
                && $metriques['confirmes'] === 1
                && $metriques['actifs'] === 2;
        });
        $reponse->assertSee('confirme@cargo-test.com');
        $reponse->assertSee('attente@cargo-test.com');
        $reponse->assertSee('Jean Dupont');
    }

    /**
     * Vérifie le filtrage des abonnés selon le statut de confirmation.
     */
    public function test_filtrage_par_statut_confirme_et_en_attente(): void
    {
        $admin = $this->creerAdministrateur();

        AbonneNewsletter::create([
            'email' => 'valide@cargo-test.com',
            'nom_complet' => 'Valide Test',
            'frequence' => 'quotidienne',
            'token_desinscription' => Str::random(40),
            'est_verifie' => true,
            'est_actif' => true,
            'date_confirmation' => now(),
        ]);

        AbonneNewsletter::create([
            'email' => 'attente@cargo-test.com',
            'nom_complet' => 'Attente Test',
            'frequence' => 'quotidienne',
            'token_confirmation' => Str::random(40),
            'token_desinscription' => Str::random(40),
            'est_verifie' => false,
            'est_actif' => true,
            'date_confirmation' => null,
        ]);

        // Filtre Confirmé
        $reponseConfirme = $this->actingAs($admin)->get(route('admin.newsletter.index', ['statut' => 'confirme']));
        $reponseConfirme->assertStatus(200);
        $reponseConfirme->assertSee('valide@cargo-test.com');
        $reponseConfirme->assertDontSee('attente@cargo-test.com');

        // Filtre En attente
        $reponseAttente = $this->actingAs($admin)->get(route('admin.newsletter.index', ['statut' => 'en_attente']));
        $reponseAttente->assertStatus(200);
        $reponseAttente->assertSee('attente@cargo-test.com');
        $reponseAttente->assertDontSee('valide@cargo-test.com');
    }

    /**
     * Vérifie que la recherche par nom complet fonctionne sans erreur SQL sur 'nom'.
     */
    public function test_recherche_abonne_par_nom_complet(): void
    {
        $admin = $this->creerAdministrateur();

        AbonneNewsletter::create([
            'email' => 'marc@cargo-test.com',
            'nom_complet' => 'Marc Martin',
            'frequence' => 'quotidienne',
            'token_desinscription' => Str::random(40),
            'est_verifie' => true,
            'est_actif' => true,
            'date_confirmation' => now(),
        ]);

        $reponse = $this->actingAs($admin)->get(route('admin.newsletter.index', ['recherche' => 'Martin']));
        $reponse->assertStatus(200);
        $reponse->assertSee('marc@cargo-test.com');
        $reponse->assertSee('Marc Martin');
    }

    /**
     * Vérifie la bascule d'état actif/inactif d'un abonné.
     */
    public function test_basculer_etat_abonne(): void
    {
        $admin = $this->creerAdministrateur();

        $abonne = AbonneNewsletter::create([
            'email' => 'bascule@cargo-test.com',
            'nom_complet' => 'Bascule Test',
            'frequence' => 'quotidienne',
            'token_desinscription' => Str::random(40),
            'est_verifie' => true,
            'est_actif' => true,
            'date_confirmation' => now(),
        ]);

        $reponse = $this->actingAs($admin)->post(route('admin.newsletter.basculer', $abonne->id));
        $reponse->assertRedirect();

        $this->assertDatabaseHas('abonnes_newsletter', [
            'id' => $abonne->id,
            'est_actif' => false,
        ]);
    }
}
