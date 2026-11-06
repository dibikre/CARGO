<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les abonnés aux newsletters et veilles logistiques.
     */
    public function up(): void
    {
        Schema::create('abonnes_newsletter', function (Blueprint $table) {
            $table->id();
            $table->string('email', 191)->unique();
            $table->string('nom_complet', 120)->nullable();
            $table->string('entreprise', 120)->nullable();
            $table->string('fonction', 100)->nullable();
            $table->string('frequence', 30)->default('quotidienne')->index(); // quotidienne, hebdomadaire, alertes_urgentes
            $table->json('preferences_rubriques')->nullable(); // Liste d'identifiants de catégories sélectionnées
            $table->string('token_confirmation', 100)->nullable()->index();
            $table->string('token_desinscription', 100)->unique();
            $table->boolean('est_verifie')->default(false)->index();
            $table->boolean('est_actif')->default(true)->index();
            $table->dateTime('date_confirmation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnes_newsletter');
    }
};
