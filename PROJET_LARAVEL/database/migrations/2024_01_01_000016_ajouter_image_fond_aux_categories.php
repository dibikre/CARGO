<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter les champs d'image de fond et de protection de contraste aux rubriques (idempotent).
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (! Schema::hasColumn('categories', 'image_fond')) {
                    $table->string('image_fond', 191)->nullable();
                }
                if (! Schema::hasColumn('categories', 'opacite_overlay')) {
                    $table->unsignedTinyInteger('opacite_overlay')->default(65);
                }
                if (! Schema::hasColumn('categories', 'couleur_overlay')) {
                    $table->string('couleur_overlay', 20)->default('#14151C');
                }
            });
        }
    }

    /**
     * Annuler l'ajout des colonnes.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $colonnesASupprimer = [];
                if (Schema::hasColumn('categories', 'image_fond')) {
                    $colonnesASupprimer[] = 'image_fond';
                }
                if (Schema::hasColumn('categories', 'opacite_overlay')) {
                    $colonnesASupprimer[] = 'opacite_overlay';
                }
                if (Schema::hasColumn('categories', 'couleur_overlay')) {
                    $colonnesASupprimer[] = 'couleur_overlay';
                }
                if (! empty($colonnesASupprimer)) {
                    $table->dropColumn($colonnesASupprimer);
                }
            });
        }
    }
};
