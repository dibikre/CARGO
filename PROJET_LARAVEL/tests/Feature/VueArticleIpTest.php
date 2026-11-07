<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\User;
use App\Models\VueArticleIp;
use App\Services\ServiceStatistiqueConsultation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VueArticleIpTest extends TestCase
{
    use RefreshDatabase;

    protected User $administrateur;
    protected User $journaliste;
    protected Categorie $categorie;
    protected Article $article;
    protected ServiceStatistiqueConsultation $serviceStatistique;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceStatistique = app(ServiceStatistiqueConsultation::class);

        $this->administrateur = User::factory()->create([
            'role' => 'super_admin',
            'est_actif' => true,
        ]);

        $this->journaliste = User::factory()->create([
            'role' => 'journaliste',
            'est_actif' => true,
        ]);

        $this->categorie = Categorie::create([
            'nom' => 'Fret Maritime & Ports',
            'slug' => 'fret-maritime-ports',
            'description' => 'Actualités portuaires et armateurs',
            'est_actif' => true,
        ]);

        $this->article = Article::create([
            'auteur_id' => $this->journaliste->id,
            'categorie_id' => $this->categorie->id,
            'titre' => 'Alliance des armateurs en mer du Nord',
            'slug' => 'alliance-armateurs-mer-du-nord',
            'chapeau' => 'Restructuration majeure des lignes maritimes.',
            'contenu' => 'Analyse détaillée des corridors portuaires européens...',
            'statut' => 'publie',
            'format_article' => 'standard',
            'mode_transport_principal' => 'maritime',
            'est_a_la_une' => true,
            'est_urgent' => false,
            'est_reserve_abonnes' => false,
            'temps_lecture_minutes' => 5,
            'vues_compteur' => 0,
            'date_publication' => now(),
        ]);
    }

    /**
     * Vérifier qu'une première visite depuis une adresse IP donnée enregistre la vue.
     */
    public function test_premiere_consultation_ip_incremente_vues(): void
    {
        $adresseIp = '198.51.100.1';

        $retour = $this->serviceStatistique->enregistrerVueParIp($this->article, $adresseIp, 'TestBrowser/1.0');

        $this->assertTrue($retour);
        $this->article->refresh();
        $this->assertEquals(1, $this->article->vues_compteur);

        $this->assertDatabaseHas('vues_articles_ip', [
            'article_id' => $this->article->id,
            'adresse_ip' => $adresseIp,
        ]);
    }

    /**
     * Vérifier qu'une seconde visite provenant de la même adresse IP n'incrémente pas le compteur.
     */
    public function test_seconde_consultation_meme_ip_ne_reincremente_pas(): void
    {
        $adresseIp = '198.51.100.2';

        // 1ère visite
        $premierAppel = $this->serviceStatistique->enregistrerVueParIp($this->article, $adresseIp);
        $this->assertTrue($premierAppel);

        // 2ème visite (rafraîchissement multiple)
        $secondAppel = $this->serviceStatistique->enregistrerVueParIp($this->article, $adresseIp);
        $this->assertFalse($secondAppel);

        $this->article->refresh();
        $this->assertEquals(1, $this->article->vues_compteur);
        $this->assertEquals(1, VueArticleIp::where('article_id', $this->article->id)->count());
    }

    /**
     * Vérifier qu'une consultation depuis une adresse IP différente incrémente bien le compteur.
     */
    public function test_consultation_autre_ip_incremente_vues(): void
    {
        $ipAlpha = '198.51.100.10';
        $ipBeta = '198.51.100.20';

        $this->serviceStatistique->enregistrerVueParIp($this->article, $ipAlpha);
        $this->serviceStatistique->enregistrerVueParIp($this->article, $ipBeta);

        $this->article->refresh();
        $this->assertEquals(2, $this->article->vues_compteur);
        $this->assertEquals(2, VueArticleIp::where('article_id', $this->article->id)->count());
    }

    /**
     * Vérifier que l'accès via le contrôleur public HTTP enregistre l'adresse IP du client.
     */
    public function test_route_show_article_enregistre_ip_du_client(): void
    {
        $reponse = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.42'])
            ->get(route('articles.show', $this->article->slug));

        $reponse->assertStatus(200);

        $this->assertDatabaseHas('vues_articles_ip', [
            'article_id' => $this->article->id,
            'adresse_ip' => '203.0.113.42',
        ]);

        $this->article->refresh();
        $this->assertEquals(1, $this->article->vues_compteur);
    }

    /**
     * Vérifier que l'administrateur peut visualiser et trier les articles par vues en backoffice.
     */
    public function test_administrateur_peut_voir_et_trier_par_vues_en_backoffice(): void
    {
        $articleTresVu = Article::create([
            'auteur_id' => $this->journaliste->id,
            'categorie_id' => $this->categorie->id,
            'titre' => 'Analyse des taux de fret Asie',
            'slug' => 'analyse-taux-de-fret-asie',
            'chapeau' => 'Chute des cours spot...',
            'contenu' => 'Contenu stratégique...',
            'statut' => 'publie',
            'format_article' => 'analyse',
            'mode_transport_principal' => 'maritime',
            'est_a_la_une' => false,
            'est_urgent' => false,
            'est_reserve_abonnes' => true,
            'temps_lecture_minutes' => 4,
            'vues_compteur' => 840,
            'date_publication' => now(),
        ]);

        $reponse = $this->actingAs($this->administrateur)
            ->get(route('admin.articles.index', ['tri' => 'plus_vus']));

        $reponse->assertStatus(200);
        $reponse->assertSee('Views (Unique IPs)');
        $reponse->assertSee('840');
        $reponse->assertSee($articleTresVu->titre);
    }
}
