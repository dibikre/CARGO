<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagsArticleTest extends TestCase
{
    use RefreshDatabase;

    protected User $journaliste;
    protected User $administrateur;
    protected Categorie $categorie;
    protected array $tags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->journaliste = User::factory()->create([
            'role' => 'journaliste',
            'est_actif' => true,
        ]);

        $this->administrateur = User::factory()->create([
            'role' => 'super_admin',
            'est_actif' => true,
        ]);

        $this->categorie = Categorie::create([
            'nom' => 'Fret Aérien & Express',
            'slug' => 'fret-aerien',
            'est_actif' => true,
        ]);

        // Création des tags clés requis : DHL, Lufthansa, FedEx, CARGO, Air France...
        $motsCles = ['DHL', 'Lufthansa', 'FedEx', 'CARGO', 'Air France', 'CMA CGM', 'Maersk', 'MSC'];
        $this->tags = [];
        foreach ($motsCles as $nom) {
            $this->tags[$nom] = Tag::create([
                'nom' => $nom,
                'slug' => strtolower(str_replace(' ', '-', $nom)),
                'compteur_articles' => 0,
            ]);
        }
    }

    /**
     * Vérifier qu'un article peut être publié avec un maximum de 4 tags.
     */
    public function test_creation_article_avec_maximum_4_tags(): void
    {
        $tagIds = [
            $this->tags['DHL']->id,
            $this->tags['Lufthansa']->id,
            $this->tags['FedEx']->id,
            $this->tags['CARGO']->id,
        ];

        $reponse = $this->actingAs($this->journaliste)->post(route('redaction.articles.store'), [
            'titre' => 'Nouvelle ligne cargo Francfort - Singapour',
            'categorie_id' => $this->categorie->id,
            'mode_transport_principal' => 'aerien',
            'format_article' => 'article',
            'chapeau' => 'Chapeau éditorial de test...',
            'contenu' => '<p>Analyse de la capacité fret aérien...</p>',
            'statut' => 'publie',
            'tags' => $tagIds,
        ]);

        $reponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('articles', ['titre' => 'Nouvelle ligne cargo Francfort - Singapour']);

        $article = Article::where('titre', 'Nouvelle ligne cargo Francfort - Singapour')->first();
        $this->assertNotNull($article);
        $this->assertCount(4, $article->tags);
        $this->assertTrue($article->tags->contains($this->tags['DHL']));
        $this->assertTrue($article->tags->contains($this->tags['Lufthansa']));
    }

    /**
     * Vérifier qu'une tentative d'associer plus de 4 tags est rejetée par la validation.
     */
    public function test_creation_article_avec_plus_de_4_tags_echoue(): void
    {
        // Envoi de 5 tags (dépassement de la limite stricte de 4 tags)
        $tagIds = [
            $this->tags['DHL']->id,
            $this->tags['Lufthansa']->id,
            $this->tags['FedEx']->id,
            $this->tags['CARGO']->id,
            $this->tags['Air France']->id,
        ];

        $reponse = $this->actingAs($this->journaliste)->post(route('redaction.articles.store'), [
            'titre' => 'Article avec trop de tags',
            'categorie_id' => $this->categorie->id,
            'mode_transport_principal' => 'aerien',
            'format_article' => 'article',
            'contenu' => '<p>Test...</p>',
            'statut' => 'publie',
            'tags' => $tagIds,
        ]);

        $reponse->assertSessionHasErrors(['tags']);
        $this->assertDatabaseMissing('articles', ['titre' => 'Article avec trop de tags']);
    }

    /**
     * Vérifier que l'accueil affiche les 10 meilleurs tags dans l'aside sous les vidéos.
     */
    public function test_accueil_affiche_les_10_meilleurs_tags_dans_aside(): void
    {
        // Créer un article lié à DHL et Lufthansa
        $article = Article::create([
            'auteur_id' => $this->journaliste->id,
            'categorie_id' => $this->categorie->id,
            'titre' => 'Hub express de Leipzig',
            'slug' => 'hub-express-de-leipzig',
            'chapeau' => 'Chapeau...',
            'contenu' => '<p>Contenu...</p>',
            'statut' => 'publie',
            'format_article' => 'standard',
            'mode_transport_principal' => 'aerien',
            'vues_compteur' => 50,
            'date_publication' => now(),
        ]);
        $article->tags()->sync([$this->tags['DHL']->id, $this->tags['Lufthansa']->id]);

        $reponse = $this->get(route('accueil'));

        $reponse->assertStatus(200);
        $reponse->assertSee('Top 10 Freight Tags');
        $reponse->assertSee('DHL');
        $reponse->assertSee('Lufthansa');
        $reponse->assertSee('bloc-meilleurs-tags');
    }

    /**
     * Vérifier que la route de consultation par tag retourne les articles associés.
     */
    public function test_route_articles_par_tag_fonctionne(): void
    {
        $article = Article::create([
            'auteur_id' => $this->journaliste->id,
            'categorie_id' => $this->categorie->id,
            'titre' => 'FedEx étend sa flotte tout-cargo',
            'slug' => 'fedex-etend-sa-flotte-tout-cargo',
            'chapeau' => 'Chapeau...',
            'contenu' => '<p>Contenu...</p>',
            'statut' => 'publie',
            'format_article' => 'standard',
            'mode_transport_principal' => 'aerien',
            'date_publication' => now(),
        ]);
        $article->tags()->sync([$this->tags['FedEx']->id]);

        $reponse = $this->get(route('tags.articles', 'fedex'));

        $reponse->assertStatus(200);
        $reponse->assertSee('FedEx étend sa flotte tout-cargo');
    }
}
