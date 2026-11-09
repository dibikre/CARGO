<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VideoLogistique;
use App\Services\ServiceJetonSecuriteRedacteur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SecuriteEtGestionVideosRedactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $redacteur;
    protected ServiceJetonSecuriteRedacteur $serviceJeton;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceJeton = app(ServiceJetonSecuriteRedacteur::class);

        // Créer un utilisateur avec rôle rédacteur
        $this->redacteur = User::factory()->create([
            'email' => 'redacteur.video@cargo-newsroom.test',
            'est_actif' => true,
            'role' => 'redacteur',
        ]);
    }

    public function test_acces_api_videos_sans_authentification_refuse(): void
    {
        $reponse = $this->getJson('/redaction/videos/api');
        $reponse->assertStatus(401);
    }

    public function test_acces_api_videos_sans_jeton_securite_bloque(): void
    {
        $this->actingAs($this->redacteur);

        $reponse = $this->getJson('/redaction/videos/api');
        $reponse->assertStatus(403)
            ->assertJson([
                'succes' => false,
                'code' => 'JETON_MANQUANT',
            ]);
    }

    public function test_acces_api_videos_avec_jeton_valide_reussit(): void
    {
        $this->actingAs($this->redacteur);

        VideoLogistique::factory()->create([
            'titre' => 'Tuas Port Autonomous AGV Fleet',
            'url_embed' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
            'est_actif' => true,
        ]);

        $requete = Request::create('/redaction/videos', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 Test CARGO Desk Browser',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $jeton = $this->serviceJeton->genererJeton($this->redacteur, $requete);

        $reponse = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Test CARGO Desk Browser',
            'X-Jeton-Securite' => $jeton,
        ])->getJson("/redaction/videos/api/{$jeton}");

        $reponse->assertStatus(200)
            ->assertJsonStructure([
                'succes',
                'donnees',
                'pagination',
                'indicateurs',
                'jeton_securite',
            ]);
    }

    public function test_recherche_instantanee_filtre_les_videos(): void
    {
        $this->actingAs($this->redacteur);

        VideoLogistique::factory()->create(['titre' => 'Maersk Triple-E Engine Tour']);
        VideoLogistique::factory()->create(['titre' => 'Panama Canal Transit']);

        $requete = Request::create('/redaction/videos', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 Test CARGO Desk Browser',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);
        $jeton = $this->serviceJeton->genererJeton($this->redacteur, $requete);

        $reponse = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Test CARGO Desk Browser',
        ])->getJson("/redaction/videos/api/{$jeton}?recherche=Panama");

        $reponse->assertStatus(200);
        $donnees = $reponse->json('donnees');
        $this->assertCount(1, $donnees);
        $this->assertEquals('Panama Canal Transit', $donnees[0]['titre']);
    }

    public function test_suppression_unitaire_asynchrone_securisee(): void
    {
        $this->actingAs($this->redacteur);

        $video = VideoLogistique::factory()->create(['titre' => 'Temporary Embed To Delete']);

        $requete = Request::create('/redaction/videos', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 Test CARGO Desk Browser',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);
        $jeton = $this->serviceJeton->genererJeton($this->redacteur, $requete);

        $reponse = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Test CARGO Desk Browser',
        ])->deleteJson("/redaction/videos/api/{$jeton}/{$video->id}");

        $reponse->assertStatus(200)->assertJson(['succes' => true]);
        $this->assertDatabaseMissing('videos_logistiques', ['id' => $video->id]);
    }

    public function test_rejet_si_empreinte_appareil_ne_correspond_pas(): void
    {
        $this->actingAs($this->redacteur);

        $requeteLegitime = Request::create('/redaction/videos', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 Legitimate Editor Laptop',
            'REMOTE_ADDR' => '192.168.1.50',
        ]);
        $jeton = $this->serviceJeton->genererJeton($this->redacteur, $requeteLegitime);

        // Tentative de rejeu depuis un appareil pirate avec un User-Agent différent
        $reponse = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Malicious Cloned Device Browser',
        ])->getJson("/redaction/videos/api/{$jeton}");

        $reponse->assertStatus(403)
            ->assertJson([
                'succes' => false,
                'code' => 'JETON_INVALIDE_OU_APPAREIL_NON_CONFORME',
            ]);
    }
}
