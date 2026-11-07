<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les rubriques et catégories du journal.
     */
    public function up(): void
    {
        Schema::dropIfExists('categories');

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('nom', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('icone', 50)->nullable();
            $table->string('couleur', 20)->nullable(); // ex: #DC2626 pour maritime, #2563EB pour aérien
            $table->string('image_fond', 191)->nullable();
            $table->unsignedTinyInteger('opacite_overlay')->default(65);
            $table->string('couleur_overlay', 20)->default('#14151C');
            $table->unsignedSmallInteger('ordre_affichage')->default(0)->index();
            $table->boolean('est_actif')->default(true)->index();
            $table->boolean('afficher_dans_menu')->default(true)->index();
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
        Schema::dropIfExists('categories');
    }
};
