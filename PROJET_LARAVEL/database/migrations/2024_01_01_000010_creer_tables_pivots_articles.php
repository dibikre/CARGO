<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les tables pivots des articles.
     */
    public function up(): void
    {
        // Pivot Article <-> Tag
        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['article_id', 'tag_id']);
        });

        // Pivot Article <-> Zone Logistique
        Schema::create('article_zone_logistique', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('zone_logistique_id')->constrained('zones_logistiques')->cascadeOnDelete();
            $table->primary(['article_id', 'zone_logistique_id'], 'art_zone_primary');
        });

        // Pivot Article <-> Corridor de Transport
        Schema::create('article_corridor', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('corridor_transport_id')->constrained('corridors_transport')->cascadeOnDelete();
            $table->primary(['article_id', 'corridor_transport_id'], 'art_corridor_primary');
        });

        // Pivot Article <-> Dossier Thématique avec ordre éditorial
        Schema::create('article_dossier', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('dossier_thematique_id')->constrained('dossiers_thematiques')->cascadeOnDelete();
            $table->unsignedSmallInteger('ordre_dans_dossier')->default(0);
            $table->primary(['article_id', 'dossier_thematique_id'], 'art_dossier_primary');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_dossier');
        Schema::dropIfExists('article_corridor');
        Schema::dropIfExists('article_zone_logistique');
        Schema::dropIfExists('article_tag');
    }
};
