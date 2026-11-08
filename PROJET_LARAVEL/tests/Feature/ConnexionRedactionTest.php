<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests fonctionnels pour l'authentification et l'accès au desk de rédaction :
 * Page de connexion dédiée aux rédacteurs/journalistes, étanchéité vis-à-vis des abonnés PRO,
 * et protection par le middleware VerifierAccesRedaction.
 */
class ConnexionRedactionTest extends TestCase
{
    use RefreshDatabase;

    protected function creerJournaliste(): User
    {
        return User::factory()->create([
            'role' => 'journaliste',
            'est_actif' => true,
            'password' => Hash::make('CargoRedac2026!'),
        ]);
    }

    protected function creerAbonne(): User
    {
        return User::factory()->create([
            'role' => 'abonne',
            'est_actif' => true,
            'password' => Hash::make('CargoPro2026!'),
        ]);
    }

    /**
     * Vérifie que la page de connexion dédiée à la rédaction s'affiche correctement.
     */
    public function test_affichage_page_connexion_redaction(): void
    {
        $reponse = $this->get(route('redaction.connexion'));

        $reponse->assertStatus(200);
        $reponse->assertSee('Newsroom Authentication');
        $reponse->assertSee('Editorial Desk');
        $reponse->assertSee('Editorial Email Address');
        $reponse->assertSee('PRO Reader Portal');
        $reponse->assertSee('Security Gateway');
    }

    /**
     * Vérifie qu'un journaliste peut se connecter via le portail de rédaction.
     */
    public function test_journaliste_peut_se_connecter_au_desk(): void
    {
        $journaliste = $this->creerJournaliste();

        $reponse = $this->post(route('redaction.connexion.post'), [
            'email' => $journaliste->email,
            'password' => 'CargoRedac2026!',
        ]);

        $reponse->assertRedirect(route('redaction.articles.index'));
        $this->assertAuthenticatedAs($journaliste);
    }

    /**
     * Vérifie qu'un abonné PRO ne peut pas se connecter via le portail rédaction.
     */
    public function test_abonne_pro_est_refuse_sur_le_portail_redaction(): void
    {
        $abonne = $this->creerAbonne();

        $reponse = $this->post(route('redaction.connexion.post'), [
            'email' => $abonne->email,
            'password' => 'CargoPro2026!',
        ]);

        $reponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Vérifie qu'un invité non authentifié tentant d'accéder à la rédaction est redirigé vers /redaction/connexion.
     */
    public function test_invite_est_redirige_vers_connexion_redaction(): void
    {
        $reponse = $this->get(route('redaction.articles.index'));

        $reponse->assertRedirect(route('redaction.connexion'));
    }

    /**
     * Vérifie qu'un abonné connecté tentant d'accéder au desk de rédaction est bloqué par VerifierAccesRedaction.
     */
    public function test_abonne_connecte_est_bloque_par_middleware_redaction(): void
    {
        $abonne = $this->creerAbonne();

        $reponse = $this->actingAs($abonne)->get(route('redaction.articles.index'));

        $reponse->assertRedirect(route('redaction.connexion'));
        $reponse->assertSessionHas('erreur');
    }

    /**
     * Vérifie qu'un journaliste peut se déconnecter du desk de rédaction.
     */
    public function test_deconnexion_journaliste(): void
    {
        $journaliste = $this->creerJournaliste();

        $reponse = $this->actingAs($journaliste)->post(route('redaction.deconnexion'));

        $reponse->assertRedirect(route('redaction.connexion'));
        $this->assertGuest();
    }
}
