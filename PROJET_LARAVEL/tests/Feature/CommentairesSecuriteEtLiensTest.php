<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Commentaire;
use App\Models\ConfigurationSysteme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentairesSecuriteEtLiensTest extends TestCase
{
    use RefreshDatabase;

    protected User $administrateur;
    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrateur = User::factory()->create([
            'role' => 'admin',
            'est_actif' => true,
        ]);

        $categorie = Categorie::create([
            'nom' => 'Maritime & Ports',
            'slug' => 'maritime-ports',
            'couleur' => '#0F4C81',
            'actif' => true,
        ]);

        $this->article = Article::create([
            'titre' => 'Gestion des flux conteneurisés au Port du Havre',
            'slug' => 'gestion-flux-conteneurises-le-havre',
            'chapeau' => 'Analyse des rotations de navires et temps d\'attente à quai.',
            'contenu' => 'Le trafic portuaire enregistre une hausse sensible des volumes manutentionnés...',
            'categorie_id' => $categorie->id,
            'statut' => 'publie',
            'auteur_id' => $this->administrateur->id,
            'date_publication' => now(),
        ]);
    }

    public function test_la_soumission_exige_email_site_web_et_commentaire(): void
    {
        $reponse = $this->post(route('commentaires.store'), [
            'article_id' => $this->article->id,
            'auteur_nom' => 'Jean Dupont',
            'auteur_email' => '',
            'auteur_site_web' => '',
            'contenu' => '',
        ]);

        $reponse->assertSessionHasErrors(['auteur_email', 'auteur_site_web', 'contenu']);
        $this->assertDatabaseCount('commentaires', 0);
    }

    public function test_la_soumission_avec_champs_valides_enregistre_le_site_web_et_le_commentaire(): void
    {
        $reponse = $this->post(route('commentaires.store'), [
            'article_id' => $this->article->id,
            'auteur_nom' => 'Claire Delamare',
            'auteur_email' => 'claire.delamare@logistique-europe.fr',
            'auteur_site_web' => 'cargo-transport.eu',
            'contenu' => 'Excellente analyse, les temps d\'attente ont été réduits grâce au portail numérique.',
        ]);

        $reponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('commentaires', [
            'article_id' => $this->article->id,
            'auteur_nom' => 'Claire Delamare',
            'auteur_email' => 'claire.delamare@logistique-europe.fr',
            'auteur_site_web' => 'https://cargo-transport.eu',
            'statut' => 'en_attente',
        ]);
    }

    public function test_l_admin_peut_basculer_le_reglage_des_liens_cliquables(): void
    {
        ConfigurationSysteme::definirValeur('commentaires_liens_cliquables', '0');
        $this->assertFalse(ConfigurationSysteme::liensCommentairesSontCliquables());

        $reponse = $this->actingAs($this->administrateur)
            ->post(route('admin.commentaires.basculer-liens'));

        $reponse->assertSessionHas('succes');
        $this->assertTrue(ConfigurationSysteme::liensCommentairesSontCliquables());

        // Deuxième basculement pour repasser en mode nettoyé
        $this->actingAs($this->administrateur)
            ->post(route('admin.commentaires.basculer-liens'));
        $this->assertFalse(ConfigurationSysteme::liensCommentairesSontCliquables());
    }

    public function test_formatage_des_liens_cliquables_versus_nettoyes(): void
    {
        $commentaire = Commentaire::create([
            'article_id' => $this->article->id,
            'auteur_nom' => 'Marc Logistique',
            'auteur_email' => 'marc@hub-portuaire.com',
            'auteur_site_web' => 'https://hub-portuaire.com/fr',
            'contenu' => 'Consultez les détails sur https://hub-portuaire.com/rapports pour plus d\'infos.',
            'statut' => 'approuve',
        ]);

        // Mode 1: Nettoyé (par défaut)
        ConfigurationSysteme::definirValeur('commentaires_liens_cliquables', '0');
        $this->assertFalse($commentaire->site_web_est_cliquable);
        $this->assertStringNotContainsString('<a href=', $commentaire->contenu_formate);
        $this->assertStringContainsString('https://hub-portuaire.com/rapports', $commentaire->contenu_formate);

        // Mode 2: Cliquable
        ConfigurationSysteme::definirValeur('commentaires_liens_cliquables', '1');
        $this->assertTrue($commentaire->site_web_est_cliquable);
        $this->assertStringContainsString('rel="nofollow noopener noreferrer"', $commentaire->contenu_formate);
        $this->assertStringContainsString('href="https://hub-portuaire.com/rapports"', $commentaire->contenu_formate);
        $this->assertEquals('hub-portuaire.com', $commentaire->nom_domaine_site_web);
    }
}
