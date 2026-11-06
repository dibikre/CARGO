<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StatistiqueArticle;

class ServiceStatistiqueConsultation
{
    /**
     * Enregistrer une vue d'article et consolider la statistique quotidienne.
     */
    public function enregistrerVue(Article $article): void
    {
        // Incrémentation globale
        $article->incrementerVues();

        // Enregistrement consolidé journalier
        $dateAujourdhui = now()->toDateString();

        StatistiqueArticle::firstOrCreate(
            [
                'article_id' => $article->id,
                'date_statistique' => $dateAujourdhui,
            ],
            [
                'nombre_vues' => 0,
                'nombre_lectures_uniques' => 0,
                'nombre_partages' => 0,
            ]
        )->increment('nombre_vues');
    }
}
