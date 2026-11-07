<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les articles du journal logistique.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('statistiques_articles_journalieres');
        Schema::dropIfExists('commentaires');
        Schema::dropIfExists('article_dossier');
        Schema::dropIfExists('article_corridor');
        Schema::dropIfExists('article_zone_logistique');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('articles');
        Schema::enableForeignKeyConstraints();

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categorie_id')->constrained('categories')->restrictOnDelete();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('chapeau')->nullable();
            $table->longText('contenu');
            $table->string('image_principale', 191)->nullable();
            $table->string('legende_image', 191)->nullable();
            $table->string('credit_photo', 120)->nullable();
            
            // Typologies et états éditoriaux
            $table->string('statut', 30)->default('brouillon')->index(); // brouillon, en_relecture, approuve, publie, archive
            $table->string('format_article', 30)->default('article')->index(); // article, breve_flash, analyse, reportage, interview, tribune, etude_de_cas, infographie
            $table->string('mode_transport_principal', 30)->default('multimodal')->index(); // multimodal, maritime, aerien, routier, ferroviaire, logistique_urbaine, entreposage, douanes
            
            // Visibilité et accès
            $table->boolean('est_a_la_une')->default(false)->index();
            $table->boolean('est_urgent')->default(false)->index(); // Alerte info / Flash transport
            $table->boolean('est_reserve_abonnes')->default(false)->index(); // Paywall abonnés pro
            $table->boolean('est_sponsorise')->default(false);
            $table->string('nom_partenaire', 120)->nullable();
            
            // Métriques de lecture
            $table->unsignedSmallInteger('temps_lecture_minutes')->default(3);
            $table->unsignedBigInteger('vues_compteur')->default(0);
            $table->unsignedInteger('partages_compteur')->default(0);
            $table->dateTime('date_publication')->nullable()->index();
            
            // SEO & Référencement
            $table->string('meta_titre', 191)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 191)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index composites pour performances SQL optimales sur hébergement partagé
            $table->index(['statut', 'date_publication'], 'articles_statut_date_pub_idx');
            $table->index(['categorie_id', 'statut', 'date_publication'], 'articles_cat_statut_date_pub_idx');
            $table->index(['est_a_la_une', 'statut', 'date_publication'], 'articles_une_statut_date_pub_idx');
            $table->index(['est_urgent', 'statut', 'date_publication'], 'articles_urgent_statut_date_pub_idx');
            $table->index(['mode_transport_principal', 'statut', 'date_publication'], 'articles_mode_statut_date_pub_idx');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
