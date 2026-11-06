<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class ServicePublicationArticle
{
    /**
     * Calculer le temps de lecture estimé en minutes.
     */
    public function calculerTempsLecture(string $texte): int
    {
        $nombreMots = str_word_count(strip_tags($texte));
        $minutes = (int) ceil($nombreMots / 200);

        return max(1, $minutes);
    }

    /**
     * Générer un slug unique pour un article.
     */
    public function genererSlugUnique(string $titre, ?int $idExclu = null): string
    {
        $slugBase = Str::slug($titre);
        $slug = $slugBase;
        $compteur = 1;

        while (Article::where('slug', $slug)->when($idExclu, fn ($q) => $q->where('id', '!=', $idExclu))->exists()) {
            $slug = "{$slugBase}-{$compteur}";
            $compteur++;
        }

        return $slug;
    }

    /**
     * Publier immédiatement un article.
     */
    public function publierArticle(Article $article): bool
    {
        return $article->update([
            'statut' => 'publie',
            'date_publication' => $article->date_publication ?? now(),
        ]);
    }

    /**
     * Archiver un article.
     */
    public function archiverArticle(Article $article): bool
    {
        return $article->update([
            'statut' => 'archive',
        ]);
    }
}
