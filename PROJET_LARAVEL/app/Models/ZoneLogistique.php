<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ZoneLogistique extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'zones_logistiques';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'slug',
        'code_region',
        'description',
        'est_actif',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Articles liés à cette zone logistique.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_zone_logistique');
    }

    /**
     * Filtrer les zones actives.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }
}
