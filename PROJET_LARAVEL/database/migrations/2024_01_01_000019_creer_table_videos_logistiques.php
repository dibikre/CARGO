<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des vidéos logistiques embarquées (embed).
     */
    public function up(): void
    {
        Schema::dropIfExists('videos_logistiques');

        Schema::create('videos_logistiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titre', 191);
            $table->string('slug', 191)->unique();
            $table->text('url_embed'); // URL youtube, vimeo ou code d'intégration
            $table->string('source', 120)->nullable(); // e.g. Port of Rotterdam, CMA CGM, FreightWaves
            $table->string('duree', 30)->nullable(); // e.g. 04:20
            $table->text('description')->nullable();
            $table->boolean('est_actif')->default(true)->index();
            $table->unsignedSmallInteger('ordre_affichage')->default(0);
            $table->timestamps();
        });

        // Insertion de vidéos logistiques de démonstration
        DB::table('videos_logistiques')->insert([
            [
                'user_id' => 1,
                'titre' => 'Automated Container Terminals: Maasvlakte II Operations',
                'slug' => 'automated-container-terminals-maasvlakte-ii',
                'url_embed' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                'source' => 'Port of Rotterdam Logistics',
                'duree' => '05:14',
                'description' => 'Inside Europe\'s most automated deep-sea container terminal handling 24,000 TEU ultra-large vessels.',
                'est_actif' => true,
                'ordre_affichage' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'titre' => 'Intermodal Freight Trains: Trans-European Corridor Operations',
                'slug' => 'intermodal-freight-trains-corridor-operations',
                'url_embed' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                'source' => 'European Rail Logistics Desk',
                'duree' => '04:02',
                'description' => 'High-frequency rail shuttle linking sea ports to dry ports and continental distribution hubs.',
                'est_actif' => true,
                'ordre_affichage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos_logistiques');
    }
};
