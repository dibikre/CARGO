<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter les champs d'image de fond et de protection de contraste aux rubriques.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_fond', 191)->nullable()->after('couleur');
            $table->unsignedTinyInteger('opacite_overlay')->default(65)->after('image_fond');
            $table->string('couleur_overlay', 20)->default('#14151C')->after('opacite_overlay');
        });
    }

    /**
     * Annuler l'ajout des colonnes.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['image_fond', 'opacite_overlay', 'couleur_overlay']);
        });
    }
};
