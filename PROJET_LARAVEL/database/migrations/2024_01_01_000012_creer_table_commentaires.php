<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les commentaires d'articles et la modération.
     */
    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('commentaires')->cascadeOnDelete();
            
            // Pour utilisateurs non connectés si activé par la modération
            $table->string('auteur_nom', 100)->nullable();
            $table->string('auteur_email', 150)->nullable();
            
            $table->text('contenu');
            $table->string('statut', 30)->default('en_attente')->index(); // en_attente, approuve, rejete, spam
            $table->string('ip_adresse', 45)->nullable();
            $table->string('user_agent', 191)->nullable();
            $table->unsignedInteger('votes_positifs')->default(0);
            $table->unsignedSmallInteger('signalements_compteur')->default(0)->index();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['article_id', 'statut'], 'commentaires_article_statut_idx');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
