<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration pour créer la table de traçabilité des vues d'articles par adresse IP.
     */
    public function up(): void
    {
        Schema::dropIfExists('vues_articles_ip');

        Schema::create('vues_articles_ip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->string('adresse_ip', 45)->index();
            $table->string('agent_utilisateur', 255)->nullable();
            $table->timestamps();

            // Index unique garantissant qu'une adresse IP ne peut comptabiliser qu'une seule vue par article
            $table->unique(['article_id', 'adresse_ip'], 'vue_article_adresse_ip_unique');
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('vues_articles_ip');
    }
};
