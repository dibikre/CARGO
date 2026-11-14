<?php

namespace Tests\Feature;

use App\Models\AbonnementUtilisateur;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\ConfigurationSysteme;
use App\Models\FormuleAbonnement;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbonnementProEtPaywallTest extends TestCase
{
    use RefreshDatabase;

    protected User $journaliste;
    protected User $utilisateurPro;
    protected User $utilisateurNonPro;
    protected FormuleAbonnement $formulePro;
    protected Categorie $categorie;
    protected Article $articlePro;

    protected function setUp(): void
    {
        parent::setUp();

        ConfigurationSysteme::definirValeur('abonnements_actifs', '1');
        ConfigurationSysteme::definirValeur('souscriptions_actives', '1');

        $this->journaliste = User::factory()->create([
            'role' => 'journaliste',
            'est_actif' => true,
        ]);

        $this->utilisateurPro = User::factory()->create([
            'name' => 'Claire Abonnee Pro',
            'role' => 'abonne',
            'est_actif' => true,
        ]);

        $this->utilisateurNonPro = User::factory()->create([
            'name' => 'Marc Lecteur Standard',
            'role' => 'abonne',
            'est_actif' => true,
        ]);

        $this->formulePro = FormuleAbonnement::create([
            'nom' => 'CARGO PRO Mensuel',
            'slug' => 'cargo-pro-mensuel-test',
            'description' => 'Accès illimité aux articles PRO.',
            'prix_ht' => 49.00,
            'taux_tva' => 20.00,
            'duree_en_jours' => 30,
            'acces_archives_illimite' => true,
            'acces_dossiers_exclusifs' => true,
            'nombre_utilisateurs_max' => 1,
            'est_actif' => true,
            'ordre_affichage' => 1,
        ]);

        AbonnementUtilisateur::create([
            'user_id' => $this->utilisateurPro->id,
            'formule_id' => $this->formulePro->id,
            'date_debut' => now()->subDay(),
            'date_fin' => now()->addDays(29),
            'statut' => 'actif',
            'reference_commande' => 'CMD-PRO-ACTIF-001',
            'montant_paye_ttc' => 58.80,
            'mode_paiement' => 'carte_bancaire',
        ]);

        $this->categorie = Categorie::create([
            'nom' => 'Fret Maritime & Ports',
            'slug' => 'fret-maritime-ports',
            'description' => 'Actualités maritimes',
            'est_actif' => true,
        ]);

        $texteIntro = str_repeat('Introduction analytique sur les taux de fret maritime et la congestion portuaire. ', 6);
        $texteSuiteExclusive = 'DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET';

        $this->articlePro = Article::create([
            'auteur_id' => $this->journaliste->id,
            'categorie_id' => $this->categorie->id,
            'titre' => 'Analyse tarifaire Asie-Europe PRO',
            'slug' => 'analyse-tarifaire-asie-europe-pro',
            'chapeau' => 'Décryptage exclusif des grilles spot.',
            'contenu' => '<p>' . $texteIntro . '</p><p>' . $texteSuiteExclusive . '</p>',
            'statut' => 'publie',
            'format_article' => 'analyse',
            'mode_transport_principal' => 'maritime',
            'est_a_la_une' => true,
            'est_urgent' => false,
            'est_reserve_abonnes' => true,
            'temps_lecture_minutes' => 6,
            'date_publication' => now(),
        ]);
    }

    public function test_utilisateur_connecte_voit_son_statut_pro_actif_ou_non_pro(): void
    {
        // Utilisateur PRO connecté
        $reponsePro = $this->actingAs($this->utilisateurPro)->get(route('abonnements.espace'));
        $reponsePro->assertStatus(200);
        $reponsePro->assertSee('ABONNEMENT PRO ACTIF');
        $reponsePro->assertSee($this->formulePro->nom);

        $reponseHeaderPro = $this->actingAs($this->utilisateurPro)->get(route('accueil'));
        $reponseHeaderPro->assertStatus(200);
        $reponseHeaderPro->assertSee('Abonné PRO Actif');

        // Utilisateur non-PRO connecté
        $reponseNonPro = $this->actingAs($this->utilisateurNonPro)->get(route('abonnements.espace'));
        $reponseNonPro->assertStatus(200);
        $reponseNonPro->assertSee('NON ABONNÉ PRO');
        $reponseNonPro->assertSee('Souscrire à un abonnement CARGO PRO');

        $reponseTarifsNonPro = $this->actingAs($this->utilisateurNonPro)->get(route('abonnements.index'));
        $reponseTarifsNonPro->assertStatus(200);
        $reponseTarifsNonPro->assertSee('Aucun abonnement PRO actif');
        $reponseTarifsNonPro->assertSee('Souscrire à cette formule');

        $reponseHeaderNonPro = $this->actingAs($this->utilisateurNonPro)->get(route('accueil'));
        $reponseHeaderNonPro->assertStatus(200);
        $reponseHeaderNonPro->assertSee('Mon compte · Non abonné');
    }

    public function test_article_pro_entier_visible_uniquement_par_abonne_pro_quand_paywall_actif(): void
    {
        // Visiteur anonyme : tronqué + invitation à s'abonner
        $reponseInvite = $this->get(route('articles.show', $this->articlePro->slug));
        $reponseInvite->assertStatus(200);
        $reponseInvite->assertDontSee('DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET');
        $reponseInvite->assertSee('Abonnez-vous à CARGO PRO pour voir le reste de cet article');

        // Utilisateur connecté non-PRO : tronqué + invitation à s'abonner
        $reponseNonPro = $this->actingAs($this->utilisateurNonPro)->get(route('articles.show', $this->articlePro->slug));
        $reponseNonPro->assertStatus(200);
        $reponseNonPro->assertDontSee('DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET');
        $reponseNonPro->assertSee('Aucun abonnement PRO actif');
        $reponseNonPro->assertSee("S'abonner pour lire la suite");

        // Utilisateur abonné PRO actif : article complet visible sans paywall
        $reponsePro = $this->actingAs($this->utilisateurPro)->get(route('articles.show', $this->articlePro->slug));
        $reponsePro->assertStatus(200);
        $reponsePro->assertSee('DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET');
        $reponsePro->assertDontSee('Abonnez-vous à CARGO PRO pour voir le reste de cet article');
    }

    public function test_utilisateur_non_pro_peut_souscrire_et_debloquer_les_articles_pro(): void
    {
        $this->assertFalse($this->utilisateurNonPro->estAbonne());

        $reponseSouscription = $this->actingAs($this->utilisateurNonPro)->post(route('abonnements.souscrire'), [
            'formule_id' => $this->formulePro->id,
            'mode_paiement' => 'carte_bancaire',
        ]);

        $reponseSouscription->assertRedirect();
        $this->assertTrue($this->utilisateurNonPro->fresh()->estAbonne());

        // Désormais, il peut consulter l'article PRO en entier
        $reponseArticle = $this->actingAs($this->utilisateurNonPro->fresh())
            ->get(route('articles.show', $this->articlePro->slug));
        $reponseArticle->assertStatus(200);
        $reponseArticle->assertSee('DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET');
    }

    public function test_article_pro_est_accessible_en_entier_si_admin_desactive_abonnements(): void
    {
        ConfigurationSysteme::definirValeur('abonnements_actifs', '0');

        $reponseNonPro = $this->actingAs($this->utilisateurNonPro)->get(route('articles.show', $this->articlePro->slug));
        $reponseNonPro->assertStatus(200);
        $reponseNonPro->assertSee('DONNEES_EXCLUSIVES_RESERVEES_AUX_ABONNES_PRO_2026_COMPLET');
    }

    public function test_seeder_cree_un_compte_pro_actif_et_un_compte_non_pro(): void
    {
        $this->seed(DatabaseSeeder::class);

        $comptePro = User::where('email', 'abonne@chargeur-global.com')->first();
        $compteNonPro = User::where('email', 'lecteur@cargo-logistics.com')->first();

        $this->assertNotNull($comptePro);
        $this->assertNotNull($compteNonPro);
        $this->assertTrue($comptePro->estAbonne());
        $this->assertFalse($compteNonPro->estAbonne());
    }
}
