<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les zones géographiques et corridors de flux logistiques.
     */
    public function up(): void
    {
        Schema::dropIfExists('corridors_transport');
        Schema::dropIfExists('zones_logistiques');

        Schema::create('zones_logistiques', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('slug', 120)->unique();
            $table->string('code_region', 20)->nullable()->index(); // APAC, EMEA, LATAM, etc.
            $table->text('description')->nullable();
            $table->boolean('est_actif')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('corridors_transport', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 120);
            $table->string('slug', 140)->unique();
            $table->string('type_flux', 30)->default('multimodal')->index(); // maritime, aerien, routier, ferroviaire, multimodal
            $table->string('statut_operationnel', 30)->default('normal')->index(); // normal, ralenti, perturbe, bloque, congestionne
            $table->text('description')->nullable();
            $table->boolean('est_actif')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corridors_transport');
        Schema::dropIfExists('zones_logistiques');
    }
};
