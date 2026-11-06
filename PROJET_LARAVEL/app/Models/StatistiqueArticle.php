<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatistiqueArticle extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'statistiques_articles_journalieres';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'article_id',
        'date_statistique',
        'nombre_vues',
        'nombre_lectures_uniques',
        'nombre_partages',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_statistique' => 'date',
            'nombre_vues' => 'integer',
            'nombre_lectures_uniques' => 'integer',
            'nombre_partages' => 'integer',
        ];
    }

    /**
     * Article associé à ces métriques journalières.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
