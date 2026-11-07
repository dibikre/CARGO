<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour les tags / mots-clés du journal.
     */
    public function up(): void
    {
        Schema::dropIfExists('tags');

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 80);
            $table->string('slug', 100)->unique();
            $table->string('description', 191)->nullable();
            $table->unsignedInteger('compteur_articles')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
