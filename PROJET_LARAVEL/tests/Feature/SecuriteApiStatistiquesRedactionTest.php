<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ServiceJetonSecuriteRedacteur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests de sécurité approfondis pour l'API des statistiques rédactionnelles :
 * - Chiffrement et intégrité du jeton JWT basé sur le login et l'empreinte appareil
 * - Blocage des tentatives de réutilisation depuis un autre appareil ou navigateur
 * - Blocage des accès non authentifiés ou falsifiés
 * - Navigation dynamique asynchrone sans rechargement de page
 */
class SecuriteApiStatistiquesRedactionTest extends TestCase
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

    /**
     * Vérifie que le ServiceJetonSecuriteRedacteur génère et valide un jeton intègre lié à l'appareil.
     */
    public function test_generation_et_validation_jeton_securite(): void
    {
        $journaliste = $this->creerJournaliste();
        $service = app(ServiceJetonSecuriteRedacteur::class);

        $requete = Request::create('/redaction/statistiques', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Cargo-Newsroom-Desktop/2.0)',
            'REMOTE_ADDR' => '192.168.1.50',
            'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9',
        ]);

        $jeton = $service->genererJeton($journaliste, $requete);
        $this->assertNotEmpty($jeton);
        $this->assertCount(3, explode('.', $jeton));

        // Validation nominale : même utilisateur et même appareil
        $chargeUtile = $service->validerJeton($jeton, $journaliste, $requete);
        $this->assertNotNull($chargeUtile);
        $this->assertEquals($journaliste->email, $chargeUtile['login']);
        $this->assertEquals($journaliste->id, $chargeUtile['sub']);

        // Tentative d'utilisation depuis un autre appareil (User-Agent altéré)
        $requeteAutreAppareil = Request::create('/redaction/statistiques', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
            'REMOTE_ADDR' => '192.168.1.50',
            'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9',
        ]);

        $chargeUtileRejetee = $service->validerJeton($jeton, $journaliste, $requeteAutreAppareil);
        $this->assertNull($chargeUtileRejetee);
    }

    /**
     * Vérifie que l'API refuse catégoriquement l'accès si l'utilisateur n'est pas authentifié.
     */
    public function test_api_rejette_acces_non_authentifie(): void
    {
        $reponse = $this->getJson(route('redaction.statistiques.api', ['periode' => '7']));
        $reponse->assertStatus(401);
        $reponse->assertJsonFragment(['code' => 'NON_AUTHENTIFIE']);
    }

    /**
     * Vérifie que l'API refuse l'accès si aucun jeton de sécurité d'appareil n'est fourni.
     */
    public function test_api_rejette_requete_sans_jeton_securite(): void
    {
        $journaliste = $this->creerJournaliste();

        $reponse = $this->actingAs($journaliste)->getJson(route('redaction.statistiques.api', ['periode' => '7']));
        $reponse->assertStatus(403);
        $reponse->assertJsonFragment(['code' => 'JETON_MANQUANT']);
    }

    /**
     * Vérifie que l'API autorise l'accès et renvoie les données JSON complètes avec un jeton valide.
     */
    public function test_api_autorise_acces_avec_jeton_valide_et_appareil_conforme(): void
    {
        $journaliste = $this->creerJournaliste();
        $service = app(ServiceJetonSecuriteRedacteur::class);

        // Simulation de la requête avec en-têtes d'appareil cohérents
        $enTetes = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64)',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_ACCEPT_LANGUAGE' => 'fr-FR',
        ];

        $requete = Request::create('/redaction/statistiques', 'GET', [], [], [], $enTetes);
        $jeton = $service->genererJeton($journaliste, $requete);

        $reponse = $this->actingAs($journaliste)
            ->withServerVariables($enTetes)
            ->getJson(route('redaction.statistiques.api', [
                'periode' => '7',
                'jeton_securite' => $jeton,
            ]));

        $reponse->assertStatus(200);
        $reponse->assertJsonStructure([
            'succes',
            'periode',
            'kpis' => ['total_vues', 'publications_periode', 'total_publies'],
            'evolution' => ['labels', 'vues', 'publications'],
            'statistiques_semaine',
            'categories',
            'top_articles',
            'jeton_securite',
        ]);
    }

    /**
     * Vérifie qu'un jeton légitime ne peut pas être volé et rejoué depuis un autre appareil.
     */
    public function test_api_rejette_jeton_utilise_depuis_un_appareil_different(): void
    {
        $journaliste = $this->creerJournaliste();
        $service = app(ServiceJetonSecuriteRedacteur::class);

        // Création du jeton sur l'ordinateur A
        $enTetesAppareilA = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'REMOTE_ADDR' => '10.0.0.1',
        ];
        $requeteA = Request::create('/redaction/statistiques', 'GET', [], [], [], $enTetesAppareilA);
        $jeton = $service->genererJeton($journaliste, $requeteA);

        // Rejeu de la requête depuis l'appareil B (autre User-Agent et autre IP)
        $enTetesAppareilB = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'REMOTE_ADDR' => '192.168.99.99',
        ];

        $reponse = $this->actingAs($journaliste)
            ->withServerVariables($enTetesAppareilB)
            ->getJson(route('redaction.statistiques.api', [
                'periode' => '7',
                'jeton_securite' => $jeton,
            ]));

        $reponse->assertStatus(403);
        $reponse->assertJsonFragment(['code' => 'JETON_INVALIDE_OU_APPAREIL_NON_CONFORME']);
    }
}
