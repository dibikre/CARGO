<?php

namespace Tests\Feature;

use App\Models\AbonnementUtilisateur;
use App\Models\ConfigurationSysteme;
use App\Models\FormuleAbonnement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormuleAbonnementAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $lecteur;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'administrateur',
            'est_actif' => true,
        ]);

        $this->lecteur = User::factory()->create([
            'role' => 'lecteur',
            'est_actif' => true,
        ]);
    }

    public function test_administrateur_peut_creer_une_formule_d_abonnement(): void
    {
        $reponse = $this->actingAs($this->admin)->post(route('admin.abonnements.formules.store'), [
            'nom' => 'Formule Test Mensuel',
            'prix_ht' => 45.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 30,
            'description' => 'Test description plan',
            'nombre_utilisateurs_max' => 1,
            'acces_archives_illimite' => 1,
            'acces_dossiers_exclusifs' => 1,
            'est_actif' => 1,
        ]);

        $reponse->assertRedirect();
        $this->assertDatabaseHas('formules_abonnements', [
            'nom' => 'Formule Test Mensuel',
            'slug' => 'formule-test-mensuel',
            'prix_ht' => 45.00,
            'duree_en_jours' => 30,
            'est_actif' => true,
        ]);
    }

    public function test_administrateur_peut_modifier_une_formule_d_abonnement(): void
    {
        $formule = FormuleAbonnement::create([
            'nom' => 'Plan Original',
            'slug' => 'plan-original',
            'prix_ht' => 50.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 30,
            'est_actif' => true,
        ]);

        $reponse = $this->actingAs($this->admin)->put(route('admin.abonnements.formules.update', $formule->id), [
            'nom' => 'Plan Modifie',
            'prix_ht' => 65.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 60,
            'description' => 'Updated desc',
            'nombre_utilisateurs_max' => 2,
            'acces_archives_illimite' => 1,
            'acces_dossiers_exclusifs' => 1,
            'est_actif' => 1,
        ]);

        $reponse->assertRedirect();
        $formule->refresh();
        $this->assertEquals('Plan Modifie', $formule->nom);
        $this->assertEquals('plan-modifie', $formule->slug);
        $this->assertEquals(65.00, (float) $formule->prix_ht);
        $this->assertEquals(60, $formule->duree_en_jours);
    }

    public function test_administrateur_peut_basculer_etat_d_une_formule(): void
    {
        $formule = FormuleAbonnement::create([
            'nom' => 'Plan Actif',
            'slug' => 'plan-actif',
            'prix_ht' => 20.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 30,
            'est_actif' => true,
        ]);

        $reponse = $this->actingAs($this->admin)->post(route('admin.abonnements.formules.basculer', $formule->id));
        $reponse->assertRedirect();
        $this->assertFalse($formule->fresh()->est_actif);
    }

    public function test_administrateur_peut_desactiver_le_fait_de_s_abonner_globalement(): void
    {
        ConfigurationSysteme::definirValeur('souscriptions_actives', '1');
        $this->assertTrue(ConfigurationSysteme::souscriptionsSontActives());

        $reponse = $this->actingAs($this->admin)->post(route('admin.abonnements.basculer-souscriptions'));
        $reponse->assertRedirect();
        $this->assertFalse(ConfigurationSysteme::souscriptionsSontActives());

        // Vérifier le blocage lors d'une tentative de souscription par un lecteur
        $formule = FormuleAbonnement::create([
            'nom' => 'Formule Souscription',
            'slug' => 'formule-souscription',
            'prix_ht' => 30.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 30,
            'est_actif' => true,
        ]);

        $tentative = $this->actingAs($this->lecteur)->post(route('abonnements.souscrire'), [
            'formule_id' => $formule->id,
            'mode_paiement' => 'carte_bancaire',
        ]);

        $tentative->assertSessionHas('erreur');
        $this->assertDatabaseMissing('abonnements_utilisateurs', [
            'user_id' => $this->lecteur->id,
            'formule_id' => $formule->id,
        ]);
    }

    public function test_administrateur_peut_supprimer_une_formule_sans_abonnes(): void
    {
        $formule = FormuleAbonnement::create([
            'nom' => 'Formule Orpheline',
            'slug' => 'formule-orpheline',
            'prix_ht' => 15.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 15,
            'est_actif' => true,
        ]);

        $reponse = $this->actingAs($this->admin)->delete(route('admin.abonnements.formules.destroy', $formule->id));
        $reponse->assertRedirect();
        $this->assertDatabaseMissing('formules_abonnements', ['id' => $formule->id]);
    }

    public function test_suppression_refusee_si_la_formule_possede_des_abonnes(): void
    {
        $formule = FormuleAbonnement::create([
            'nom' => 'Formule Avec Abonnes',
            'slug' => 'formule-avec-abonnes',
            'prix_ht' => 99.00,
            'taux_tva' => 20.0,
            'duree_en_jours' => 365,
            'est_actif' => true,
        ]);

        AbonnementUtilisateur::create([
            'user_id' => $this->lecteur->id,
            'formule_id' => $formule->id,
            'date_debut' => now(),
            'date_fin' => now()->addDays(365),
            'statut' => 'actif',
            'reference_commande' => 'CMD-TEST-12345',
            'montant_paye_ttc' => 118.80,
            'mode_paiement' => 'carte_bancaire',
        ]);

        $reponse = $this->actingAs($this->admin)->delete(route('admin.abonnements.formules.destroy', $formule->id));
        $reponse->assertSessionHas('erreur');
        $this->assertDatabaseHas('formules_abonnements', ['id' => $formule->id]);
    }
}
