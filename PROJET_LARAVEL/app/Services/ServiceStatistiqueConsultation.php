<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StatistiqueArticle;
use Illuminate\Database\UniqueConstraintViolationException;
use Throwable;

class ServiceStatistiqueConsultation
{
    /**
     * Enregistrer une vue d'article et consolider la statistique quotidienne.
     * Résistant aux accès concurrents et aux disparités de format de date SQLite.
     */
    public function enregistrerVue(Article $article): void
    {
        try {
            // Incrémentation globale du compteur sur l'article
            $article->incrementerVues();

            // Date du jour normalisée au format strict Y-m-d
            $dateAujourdhui = now()->format('Y-m-d');

            // 1. Tenter d'incrémenter directement la statistique quotidienne si elle existe déjà
            // (supporte à la fois 'Y-m-d' et les anciens formats SQLite 'Y-m-d 00:00:00')
            $lignesAffectees = StatistiqueArticle::where('article_id', $article->id)
                ->where(function ($requete) use ($dateAujourdhui) {
                    $requete->where('date_statistique', $dateAujourdhui)
                        ->orWhere('date_statistique', $dateAujourdhui . ' 00:00:00');
                })
                ->increment('nombre_vues');

            // 2. Si aucune ligne n'existait pour ce jour, création initiale
            if ($lignesAffectees === 0) {
                StatistiqueArticle::create([
                    'article_id' => $article->id,
                    'date_statistique' => $dateAujourdhui,
                    'nombre_vues' => 1,
                    'nombre_lectures_uniques' => 1,
                    'nombre_partages' => 0,
                ]);
            }
        } catch (UniqueConstraintViolationException $e) {
            // En cas de concurrence simultanée (race condition), la ligne vient d'être créée par un autre thread :
            // on effectue alors simplement l'incrémentation
            try {
                StatistiqueArticle::where('article_id', $article->id)
                    ->where(function ($requete) use ($dateAujourdhui) {
                        $requete->where('date_statistique', $dateAujourdhui)
                            ->orWhere('date_statistique', 'like', $dateAujourdhui . '%');
                    })
                    ->increment('nombre_vues');
            } catch (Throwable $erreurSecondaire) {
                report($erreurSecondaire);
            }
        } catch (Throwable $e) {
            // Sécurité absolue : un incident de télémétrie ne doit jamais bloquer la lecture de l'article (erreur 500)
            report($e);
        }
    }
}
