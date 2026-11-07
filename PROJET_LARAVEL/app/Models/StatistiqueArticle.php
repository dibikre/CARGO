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
            'date_statistique' => 'date:Y-m-d',
            'nombre_vues' => 'integer',
            'nombre_lectures_uniques' => 'integer',
            'nombre_partages' => 'integer',
        ];
    }

    /**
     * Normaliser la date statistique au format strict Y-m-d pour SQLite et MySQL.
     */
    public function setDateStatistiqueAttribute($valeur): void
    {
        if ($valeur instanceof \DateTimeInterface) {
            $this->attributes['date_statistique'] = $valeur->format('Y-m-d');
        } elseif (is_string($valeur) && strlen($valeur) >= 10) {
            $this->attributes['date_statistique'] = substr($valeur, 0, 10);
        } else {
            $this->attributes['date_statistique'] = $valeur;
        }
    }

    /**
     * Obtenir la date statistique au format strict Y-m-d.
     */
    public function getDateStatistiqueAttribute($valeur): ?string
    {
        if (empty($valeur)) {
            return null;
        }

        return substr((string) $valeur, 0, 10);
    }

    /**
     * Article associé à ces métriques journalières.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
