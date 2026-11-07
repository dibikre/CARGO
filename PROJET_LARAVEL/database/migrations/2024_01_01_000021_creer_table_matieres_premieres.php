<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration pour créer la table des matières premières et commodités brutes.
     */
    public function up(): void
    {
        Schema::dropIfExists('matieres_premieres');

        Schema::create('matieres_premieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 80);
            $table->string('code_symbole', 20)->unique();
            $table->string('devise', 10)->default('$');
            $table->string('unite_mesure', 30)->default('/ t');
            $table->decimal('prix_actuel', 12, 4)->default(0);
            $table->decimal('pourcentage_variation', 6, 2)->default(0); // Écart entrée vs sortie (-7.00%)
            $table->string('image_png', 255)->nullable(); // PNG transparent sans arrière-plan
            $table->text('donnees_courbe_json')->nullable(); // Tableau JSON de 5 à 10 nœuds [[timestamp, valeur], ...]
            $table->unsignedSmallInteger('ordre_affichage')->default(0);
            $table->boolean('est_actif')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('matieres_premieres');
    }
};
