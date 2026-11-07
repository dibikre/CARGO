<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des vidéos logistiques embarquées (embed).
     */
    public function up(): void
    {
        Schema::dropIfExists('videos_logistiques');

        Schema::create('videos_logistiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('url_embed'); // URL youtube, vimeo ou code d'intégration
            $table->string('source', 120)->nullable(); // e.g. Port of Rotterdam, CMA CGM, FreightWaves
            $table->string('duree', 30)->nullable(); // e.g. 05:14
            $table->text('description')->nullable();
            $table->boolean('est_actif')->default(true)->index();
            $table->unsignedSmallInteger('ordre_affichage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos_logistiques');
    }
};
