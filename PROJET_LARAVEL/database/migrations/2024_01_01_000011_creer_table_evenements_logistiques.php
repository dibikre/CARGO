<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les événements, salons et conférences logistiques.
     */
    public function up(): void
    {
        Schema::dropIfExists('evenements_logistiques');

        Schema::create('evenements_logistiques', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->string('lieu', 150)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('pays', 80)->nullable();
            $table->string('organisateur', 120)->nullable();
            $table->string('url_site_officiel', 191)->nullable();
            $table->dateTime('date_debut')->index();
            $table->dateTime('date_fin')->index();
            $table->string('type_evenement', 30)->default('salon')->index(); // salon, conference, webinaire, forum, remise_prix
            $table->boolean('est_partenaire', 1)->default(false)->index();
            $table->boolean('est_actif', 1)->default(true)->index();
            $table->string('image_banniere', 191)->nullable();
            $table->timestamps();
            
            $table->index(['est_actif', 'date_debut'], 'evenements_actif_debut_idx');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements_logistiques');
    }
};
