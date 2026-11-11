<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour ajouter le site web de l'auteur dans les commentaires.
     */
    public function up(): void
    {
        if (Schema::hasTable('commentaires') && ! Schema::hasColumn('commentaires', 'auteur_site_web')) {
            Schema::table('commentaires', function (Blueprint $table) {
                $table->string('auteur_site_web', 255)->nullable()->after('auteur_email');
            });
        }
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('commentaires') && Schema::hasColumn('commentaires', 'auteur_site_web')) {
            Schema::table('commentaires', function (Blueprint $table) {
                $table->dropColumn('auteur_site_web');
            });
        }
    }
};
