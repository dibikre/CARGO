<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\ConfigurationSysteme;
use App\Models\CorridorTransport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests fonctionnels pour la gestion administrative et l'activation/désactivation
 * de la fonctionnalité "Corridors de Fret & Gestion des Perturbations" côté visiteur.
 */
class CorridorAdminTest extends TestCase
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
     * Vérifie que la page d'administration des corridors affiche le commutateur de visibilité.
     */
    public function test_affichage_page_admin_corridors_et_commutateur(): void
    {
        $admin = $this->creerAdministrateur();

        $corridor = CorridorTransport::create([
            'nom' => 'Liaison Maritime Le Havre - New York',
            'slug' => 'liaison-maritime-le-havre-new-york',
            'type_flux' => 'maritime',
            'statut_operationnel' => 'normal',
            'est_actif' => true,
        ]);

        $reponse = $this->actingAs($admin)->get(route('admin.corridors.index'));

        $reponse->assertStatus(200);
        $reponse->assertSee('Corridors de Fret & Gestion des Perturbations');
        $reponse->assertSee('corridors_actifs');
        $reponse->assertSee('Feature Active on Visitor Site');
        $reponse->assertSee('Liaison Maritime Le Havre - New York');
    }

    /**
     * Vérifie la bascule d'activation/désactivation de la fonctionnalité côté visiteur.
     */
    public function test_basculer_visibilite_publique_des_corridors(): void
    {
        $admin = $this->creerAdministrateur();

        // Par défaut, la fonctionnalité est active
        $this->assertTrue(ConfigurationSysteme::corridorsSontActifs());

        // 1. Désactivation
        $reponseDesactivation = $this->actingAs($admin)->post(route('admin.corridors.basculer'));
        $reponseDesactivation->assertRedirect();
        $this->assertFalse(ConfigurationSysteme::corridorsSontActifs());

        // 2. Réactivation
        $reponseReactivation = $this->actingAs($admin)->post(route('admin.corridors.basculer'));
        $reponseReactivation->assertRedirect();
        $this->assertTrue(ConfigurationSysteme::corridorsSontActifs());
    }

    /**
     * Vérifie que l'accès visiteur à /corridors/{slug} est bloqué avec 404 lorsque désactivé.
     */
    public function test_page_visiteur_corridor_retourne_404_lorsque_desactivee(): void
    {
        $corridor = CorridorTransport::create([
            'nom' => 'Corridor Rail Rotterdam - Gênes',
            'slug' => 'corridor-rail-rotterdam-genes',
            'type_flux' => 'ferroviaire',
            'statut_operationnel' => 'perturbe',
            'est_actif' => true,
        ]);

        // Fonctionnalité active : accès 200
        ConfigurationSysteme::definirValeur('corridors_actifs', '1');
        $reponseActive = $this->get(route('corridors.articles', $corridor->slug));
        $reponseActive->assertStatus(200);
        $reponseActive->assertSee('Corridor Rail Rotterdam - Gênes');

        // Désactivation : accès 404
        ConfigurationSysteme::definirValeur('corridors_actifs', '0');
        $reponseDesactivee = $this->get(route('corridors.articles', $corridor->slug));
        $reponseDesactivee->assertStatus(404);
    }

    /**
     * Vérifie que les badges de corridors sont masqués sur la fiche article lorsque la fonctionnalité est désactivée.
     */
    public function test_fiche_article_masque_les_badges_corridors_si_desactives(): void
    {
        $auteur = User::factory()->create();
        $categorie = Categorie::create([
            'nom' => 'Transport Maritime',
            'slug' => 'transport-maritime',
            'est_actif' => true,
        ]);

        $corridor = CorridorTransport::create([
            'nom' => 'Détroit de Malacca',
            'slug' => 'detroit-de-malacca',
            'type_flux' => 'maritime',
            'statut_operationnel' => 'normal',
            'est_actif' => true,
        ]);

        $article = Article::create([
            'titre' => 'Trafic maritime au détroit de Malacca',
            'slug' => 'trafic-maritime-au-detroit-de-malacca',
            'chapeau' => 'Analyse des flux de conteneurs.',
            'contenu' => '<p>Détails sur les flux maritimes et la fluidité des corridors.</p>',
            'auteur_id' => $auteur->id,
            'categorie_id' => $categorie->id,
            'statut' => 'publie',
            'date_publication' => now(),
            'vues_compteur' => 10,
        ]);

        $article->corridors()->attach($corridor->id);

        // 1. Actif : le lien vers le corridor apparaît
        ConfigurationSysteme::definirValeur('corridors_actifs', '1');
        $reponseActive = $this->get(route('articles.show', $article->slug));
        $reponseActive->assertStatus(200);
        $reponseActive->assertSee('Corridor : Détroit de Malacca');

        // 2. Désactivé : le lien vers le corridor est masqué
        ConfigurationSysteme::definirValeur('corridors_actifs', '0');
        $reponseDesactivee = $this->get(route('articles.show', $article->slug));
        $reponseDesactivee->assertStatus(200);
        $reponseDesactivee->assertDontSee('Corridor : Détroit de Malacca');
    }
}
