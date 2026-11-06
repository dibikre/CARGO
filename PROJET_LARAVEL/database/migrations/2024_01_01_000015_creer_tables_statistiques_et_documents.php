<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les statistiques journalières et les documents ressources.
     */
    public function up(): void
    {
        Schema::create('statistiques_articles_journalieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->date('date_statistique')->index();
            $table->unsignedInteger('nombre_vues')->default(0);
            $table->unsignedInteger('nombre_lectures_uniques')->default(0);
            $table->unsignedSmallInteger('nombre_partages')->default(0);
            $table->timestamps();
            
            $table->unique(['article_id', 'date_statistique'], 'stat_article_date_unique');
        });

        Schema::create('documents_ressources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->string('nom_fichier', 191);
            $table->string('chemin_stockage', 191);
            $table->string('type_mime', 100);
            $table->unsignedBigInteger('taille_octets');
            $table->unsignedInteger('nombre_telechargements')->default(0);
            $table->boolean('est_reserve_abonnes')->default(false)->index();
            $table->boolean('est_actif')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_ressources');
        Schema::dropIfExists('statistiques_articles_journalieres');
    }
};
