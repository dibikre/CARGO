<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les dossiers thématiques et grandes enquêtes.
     */
    public function up(): void
    {
        Schema::dropIfExists('dossiers_thematiques');

        Schema::create('dossiers_thematiques', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('chapeau')->nullable();
            $table->longText('description_complete')->nullable();
            $table->string('image_couverture', 191)->nullable();
            $table->dateTime('date_publication')->nullable()->index();
            $table->dateTime('date_cloture')->nullable();
            $table->boolean('est_en_vedette')->default(false)->index();
            $table->boolean('est_actif')->default(true)->index();
            $table->string('meta_titre', 191)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers_thematiques');
    }
};
