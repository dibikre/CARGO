<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\User;
use App\Services\ServiceRechercheSecurisee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RechercheSecuriseeEphemereTest extends TestCase
{
    use RefreshDatabase;

    protected User $redacteur;
    protected Categorie $categorie;
    protected Article $articleMaritime;
    protected Article $articleFerroviaire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redacteur = User::factory()->create(['est_actif' => true]);
        $this->categorie = Categorie::create([
            'nom' => 'Maritime & Ports',
            'slug' => 'maritime-ports',
            'actif' => true,
        ]);

        $this->articleMaritime = Article::create([
            'titre' => 'Expansion du terminal à conteneurs du Havre',
            'slug' => 'expansion-terminal-conteneurs-le-havre',
            'chapeau' => 'Nouveaux portiques pour accueillir les porte-conteneurs géants.',
            'contenu' => 'Les investissements logistiques permettent de doubler la cadence des escales maritimes.',
            'categorie_id' => $this->categorie->id,
            'auteur_id' => $this->redacteur->id,
            'statut' => 'publie',
            'date_publication' => now(),
        ]);

        $this->articleFerroviaire = Article::create([
            'titre' => 'Corridor ferroviaire Rotterdam-Duisbourg',
            'slug' => 'corridor-ferroviaire-rotterdam-duisbourg',
            'chapeau' => 'Augmentation des fréquences de trains de fret intermodaux.',
            'contenu' => 'Le trafic ferroviaire capte une part grandissante des flux de l\'hinterland.',
            'categorie_id' => $this->categorie->id,
            'auteur_id' => $this->redacteur->id,
            'statut' => 'publie',
            'date_publication' => now(),
        ]);
    }

    public function test_initiation_recherche_retourne_url_temporaire_et_code_16_caracteres(): void
    {
        $reponse = $this->postJson(route('recherche.initier'), [
            'q' => 'terminal maritime',
        ]);

        $reponse->assertOk()
            ->assertJsonStructure(['succes', 'url', 'code', 'expire_dans']);

        $code = $reponse->json('code');
        $this->assertEquals(16, strlen($code));
        $this->assertTrue(ctype_alnum($code));
        $this->assertStringEndsWith("/recherche-{$code}", $reponse->json('url'));
        $this->assertEquals(10, $reponse->json('expire_dans'));
    }

    public function test_url_temporaire_affiche_les_resultats_une_premiere_fois(): void
    {
        $initiation = $this->postJson(route('recherche.initier'), [
            'q' => 'conteneurs',
        ]);

        $code = $initiation->json('code');

        $reponse = $this->get(route('recherche.consulter', ['code' => $code]));

        $reponse->assertOk()
            ->assertSee('Expansion du terminal à conteneurs du Havre')
            ->assertSee('Résultats pour')
            ->assertSee('target="_blank"', false)
            ->assertSee('rel="noopener noreferrer"', false)
            ->assertSee('noindex, nofollow, noarchive', false);
    }

    public function test_la_meme_url_ne_peut_pas_etre_utilisee_une_seconde_fois(): void
    {
        $initiation = $this->postJson(route('recherche.initier'), [
            'q' => 'ferroviaire',
        ]);
        $code = $initiation->json('code');

        // Première consultation : succès
        $premiereRequete = $this->get(route('recherche.consulter', ['code' => $code]));
        $premiereRequete->assertOk();

        // Seconde consultation avec le même code : refus 410 Gone
        $secondeRequete = $this->get(route('recherche.consulter', ['code' => $code]));
        $secondeRequete->assertStatus(410)
            ->assertSee('Votre session de recherche a expiré');
    }

    public function test_une_url_expiree_apres_10_secondes_est_refusee(): void
    {
        $initiation = $this->postJson(route('recherche.initier'), [
            'q' => 'Rotterdam',
        ]);
        $code = $initiation->json('code');

        // Avancer le temps de 11 secondes
        Carbon::setTestNow(now()->addSeconds(11));

        $reponse = $this->get(route('recherche.consulter', ['code' => $code]));
        $reponse->assertStatus(410);

        Carbon::setTestNow();
    }

    public function test_un_code_invente_ou_inexistant_est_refuse(): void
    {
        $reponse = $this->get(route('recherche.consulter', ['code' => 'abcdef0123456789']));
        $reponse->assertStatus(410);
    }

    public function test_un_utilisateur_ne_peut_pas_utiliser_le_code_d_une_autre_session(): void
    {
        // Session A génère un code
        $initiation = $this->withSession(['user_session' => 'session_A'])
            ->postJson(route('recherche.initier'), [
                'q' => 'logistique',
            ]);
        $code = $initiation->json('code');

        // Session B tente de consommer le code de session A
        $reponseSessionB = $this->withSession(['user_session' => 'session_B'])
            ->get(route('recherche.consulter', ['code' => $code]));

        $reponseSessionB->assertStatus(410);
    }

    public function test_consommation_atomique_empeche_deux_lectures_sur_le_meme_code(): void
    {
        $service = app(ServiceRechercheSecurisee::class);
        $sessionId = 'session_test_atomique';

        $jeton = $service->initialiserRecherche('ports', $sessionId, '127.0.0.1');
        $code = $jeton['code'];

        // Première consommation : doit réussir
        $premiere = $service->consommerJeton($code, $sessionId);
        $this->assertTrue($premiere['valide']);
        $this->assertEquals('ports', $premiere['donnees']['q']);

        // Seconde tentative immédiate : doit échouer
        $seconde = $service->consommerJeton($code, $sessionId);
        $this->assertFalse($seconde['valide']);
    }

    public function test_ancienne_page_recherche_redirige_vers_accueil(): void
    {
        $reponse = $this->get('/recherche');
        $reponse->assertRedirect(route('accueil'));
    }

    public function test_les_articles_avec_terme_dans_le_titre_sont_classes_avant_ceux_dans_le_sous_titre(): void
    {
        $service = app(ServiceRechercheSecurisee::class);

        // Article A : mot-clé dans le sous-titre (chapeau)
        $articleChapeau = Article::create([
            'titre' => 'Bilan annuel des transports intermodaux',
            'slug' => 'bilan-annuel-transports-intermodaux',
            'chapeau' => 'Focus sur l\'automatisation portuaire et fluviale.',
            'contenu' => 'Analyse détaillée des corridors logistiques.',
            'categorie_id' => $this->categorie->id,
            'auteur_id' => $this->redacteur->id,
            'statut' => 'publie',
            'date_publication' => now()->subDay(),
        ]);

        // Article B : mot-clé dans le titre (doit être classé EN PREMIER)
        $articleTitre = Article::create([
            'titre' => 'Automatisation portuaire : nouvelles technologies à quai',
            'slug' => 'automatisation-portuaire-nouvelles-technologies',
            'chapeau' => 'Déploiement de grues autonomes au terminal.',
            'contenu' => 'Présentation des innovations.',
            'categorie_id' => $this->categorie->id,
            'auteur_id' => $this->redacteur->id,
            'statut' => 'publie',
            'date_publication' => now()->subDays(2),
        ]);

        // Article C : mot-clé uniquement dans le contenu (doit être classé DERNIER)
        $articleContenu = Article::create([
            'titre' => 'Gestion de flotte de camions électriques',
            'slug' => 'gestion-flotte-camions-electriques',
            'chapeau' => 'Transition énergétique des transporteurs.',
            'contenu' => 'Ces véhicules participent à l\'automatisation portuaire des liaisons courtes.',
            'categorie_id' => $this->categorie->id,
            'auteur_id' => $this->redacteur->id,
            'statut' => 'publie',
            'date_publication' => now(),
        ]);

        $resultats = $service->rechercherArticles('Automatisation portuaire');

        $ids = collect($resultats->items())->pluck('id')->all();

        // Vérification de l'ordre strict : Titre en premier, Chapeau en second, Contenu en dernier
        $this->assertEquals($articleTitre->id, $ids[0]);
        $this->assertEquals($articleChapeau->id, $ids[1]);
        $this->assertEquals($articleContenu->id, $ids[2]);
    }

    public function test_la_recherche_est_strictement_limitee_a_10_resultats(): void
    {
        $service = app(ServiceRechercheSecurisee::class);

        // Créer 14 articles contenant le mot-clé
        for ($i = 1; $i <= 14; $i++) {
            Article::create([
                'titre' => "Analyse logistique volume {$i}",
                'slug' => "analyse-logistique-volume-{$i}",
                'chapeau' => 'Chapeau descriptif du marché.',
                'contenu' => 'Contenu stratégique pour les professionnels du transport.',
                'categorie_id' => $this->categorie->id,
                'auteur_id' => $this->redacteur->id,
                'statut' => 'publie',
                'date_publication' => now()->subMinutes($i),
            ]);
        }

        $resultats = $service->rechercherArticles('logistique');

        // La liste retournée ne doit jamais dépasser 10 résultats
        $this->assertCount(10, $resultats->items());
        $this->assertEquals(10, $resultats->perPage());
    }
}
