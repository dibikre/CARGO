<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Normaliser les dates statistiques existantes au format standard Y-m-d (sans composante horaire 00:00:00)
     * afin d'éviter les violations de contrainte UNIQUE sur SQLite.
     */
    public function up(): void
    {
        if (! Schema::hasTable('statistiques_articles_journalieres')) {
            return;
        }

        try {
            $lignes = DB::table('statistiques_articles_journalieres')
                ->whereRaw('LENGTH(date_statistique) > 10')
                ->get();

            foreach ($lignes as $ligne) {
                $dateTronquee = substr((string) $ligne->date_statistique, 0, 10);

                // Vérifier si une ligne existe déjà avec cette date tronquée
                $existant = DB::table('statistiques_articles_journalieres')
                    ->where('article_id', $ligne->article_id)
                    ->where('date_statistique', $dateTronquee)
                    ->where('id', '!=', $ligne->id)
                    ->first();

                if ($existant) {
                    // Fusionner les compteurs et supprimer le doublon
                    DB::table('statistiques_articles_journalieres')
                        ->where('id', $existant->id)
                        ->update([
                            'nombre_vues' => $existant->nombre_vues + $ligne->nombre_vues,
                            'nombre_lectures_uniques' => $existant->nombre_lectures_uniques + $ligne->nombre_lectures_uniques,
                            'nombre_partages' => $existant->nombre_partages + $ligne->nombre_partages,
                        ]);

                    DB::table('statistiques_articles_journalieres')->where('id', $ligne->id)->delete();
                } else {
                    DB::table('statistiques_articles_journalieres')
                        ->where('id', $ligne->id)
                        ->update(['date_statistique' => $dateTronquee]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        // Pas de réversion requise pour une normalisation de format de date
    }
};
