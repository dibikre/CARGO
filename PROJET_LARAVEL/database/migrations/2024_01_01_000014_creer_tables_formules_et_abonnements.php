<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les formules d'abonnement et abonnements lecteurs.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('abonnements_utilisateurs');
        Schema::dropIfExists('formules_abonnements');
        Schema::enableForeignKeyConstraints();

        Schema::create('formules_abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->decimal('prix_ht', 8, 2);
            $table->decimal('taux_tva', 4, 2)->default(20.00);
            $table->unsignedSmallInteger('duree_en_jours')->default(30); // 30, 90, 365
            $table->boolean('acces_archives_illimite')->default(true);
            $table->boolean('acces_dossiers_exclusifs')->default(true);
            $table->unsignedSmallInteger('nombre_utilisateurs_max')->default(1); // Licence mono ou multi-postes
            $table->boolean('est_actif')->default(true)->index();
            $table->unsignedSmallInteger('ordre_affichage')->default(0);
            $table->timestamps();
        });

        Schema::create('abonnements_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('formule_id')->constrained('formules_abonnements')->restrictOnDelete();
            $table->dateTime('date_debut')->index();
            $table->dateTime('date_fin')->index();
            $table->string('statut', 30)->default('actif')->index(); // en_attente, actif, expire, annule, suspendu
            $table->boolean('renouvellement_automatique')->default(false);
            $table->string('reference_commande', 100)->unique();
            $table->decimal('montant_paye_ttc', 8, 2);
            $table->string('mode_paiement', 50)->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'statut', 'date_fin'], 'abonnement_user_statut_fin_idx');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements_utilisateurs');
        Schema::dropIfExists('formules_abonnements');
    }
};
