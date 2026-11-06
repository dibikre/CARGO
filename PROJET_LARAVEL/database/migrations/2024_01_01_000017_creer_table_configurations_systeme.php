<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des configurations globales du système CARGO.
     */
    public function up(): void
    {
        if (! Schema::hasTable('configurations_systeme')) {
            Schema::create('configurations_systeme', function (Blueprint $table) {
                $table->id();
                $table->string('cle', 100)->unique();
                $table->text('valeur')->nullable();
                $table->string('description', 255)->nullable();
                $table->timestamps();
            });

            // Insérer la configuration par défaut pour le statut des abonnements
            DB::table('configurations_systeme')->insert([
                'cle' => 'abonnements_actifs',
                'valeur' => '1',
                'description' => 'Activer ou désactiver globalement les abonnements et le paywall (mode 100% gratuit si désactivé)',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations_systeme');
    }
};
