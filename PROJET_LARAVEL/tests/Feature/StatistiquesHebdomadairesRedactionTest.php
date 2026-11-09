<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\StatistiqueArticle;
use App\Models\User;
use App\Services\ServiceStatistiquesHebdomadaires;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests fonctionnels et unitaires pour les métriques hebdomadaires de la rédaction :
 * - Calcul du volume de publication depuis le lundi (diagramme circulaire 1)
 * - Calcul du volume de vues depuis le lundi (diagramme circulaire 2)
 * - Détection du champion de lectorat pour chaque jour de la semaine
 * - Affichage et intégration dans le backoffice rédacteur
 */
class StatistiquesHebdomadairesRedactionTest extends TestCase
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
     * Vérifie que le ServiceStatistiquesHebdomadaires calcule correctement les métriques de la semaine.
     */
    public function test_calcul_statistiques_hebdomadaires_depuis_lundi(): void
    {
        $journaliste = $this->creerJournaliste();
        $categorie = Categorie::create([
            'nom' => 'Fret Maritime & Ports',
            'slug' => 'fret-maritime-ports',
            'description' => 'Test rubrique',
            'ordre_affichage' => 1,
            'afficher_dans_menu' => true,
        ]);

        $debutSemaine = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $lundi = $debutSemaine->copy()->format('Y-m-d');
        $mardi = $debutSemaine->copy()->addDays(1)->format('Y-m-d');

        // Créer un article publié le lundi
        $articleLundi = Article::create([
            'auteur_id' => $journaliste->id,
            'categorie_id' => $categorie->id,
            'titre' => 'Article Pilote du Lundi',
            'slug' => 'article-pilote-lundi',
            'chapeau' => 'Chapeau introductif',
            'contenu' => 'Contenu détaillé',
            'statut' => 'publie',
            'date_publication' => $debutSemaine->copy()->setHour(10),
            'vues_compteur' => 350,
        ]);

        // Créer les statistiques journalières pour cet article le lundi
        StatistiqueArticle::create([
            'article_id' => $articleLundi->id,
            'date_statistique' => $lundi,
            'nombre_vues' => 350,
            'nombre_lectures_uniques' => 300,
            'nombre_partages' => 12,
        ]);

        // Créer un article publié le mardi
        $articleMardi = Article::create([
            'auteur_id' => $journaliste->id,
            'categorie_id' => $categorie->id,
            'titre' => 'Article Maritime du Mardi',
            'slug' => 'article-maritime-mardi',
            'chapeau' => 'Chapeau mardi',
            'contenu' => 'Contenu mardi',
            'statut' => 'publie',
            'date_publication' => $debutSemaine->copy()->addDays(1)->setHour(14),
            'vues_compteur' => 520,
        ]);

        StatistiqueArticle::create([
            'article_id' => $articleMardi->id,
            'date_statistique' => $mardi,
            'nombre_vues' => 520,
            'nombre_lectures_uniques' => 450,
            'nombre_partages' => 20,
        ]);

        $service = app(ServiceStatistiquesHebdomadaires::class);
        $resultat = $service->calculerStatistiquesSemaineCourante();

        $this->assertIsArray($resultat);
        $this->assertEquals(2, $resultat['total_publications']);
        $this->assertEquals(870, $resultat['total_vues']);
        $this->assertCount(7, $resultat['labels_jours']);
        $this->assertEquals('Monday', $resultat['labels_jours'][0]);
        $this->assertEquals(1, $resultat['donnees_publications'][0]); // Lundi : 1 pub
        $this->assertEquals(1, $resultat['donnees_publications'][1]); // Mardi : 1 pub
        $this->assertEquals(350, $resultat['donnees_vues'][0]); // Lundi : 350 vues
        $this->assertEquals(520, $resultat['donnees_vues'][1]); // Mardi : 520 vues

        // Vérifier l'article champion du lundi
        $this->assertNotNull($resultat['articles_plus_vus'][0]['article']);
        $this->assertEquals('Article Pilote du Lundi', $resultat['articles_plus_vus'][0]['article']['titre']);

        // Vérifier l'article champion du mardi
        $this->assertNotNull($resultat['articles_plus_vus'][1]['article']);
        $this->assertEquals('Article Maritime du Mardi', $resultat['articles_plus_vus'][1]['article']['titre']);
    }

    /**
     * Vérifie que la vue des statistiques de la rédaction affiche les nouveaux diagrammes et sections.
     */
    public function test_affichage_tableau_bord_statistiques_redaction(): void
    {
        $journaliste = $this->creerJournaliste();

        $reponse = $this->actingAs($journaliste)->get(route('redaction.statistiques.index'));

        $reponse->assertStatus(200);
        $reponse->assertSee('Audience &amp; Publications Analytics', false);
        $reponse->assertSee('Publication Volume by Day');
        $reponse->assertSee('Views Distribution by Day');
        $reponse->assertSee('Most-Viewed Articles by Day (This Week)');
        $reponse->assertSee('graphiqueCirculairePublications');
        $reponse->assertSee('graphiqueCirculaireVues');
    }
}
