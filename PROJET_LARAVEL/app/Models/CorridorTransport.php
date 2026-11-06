<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CorridorTransport extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'corridors_transport';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'slug',
        'type_flux',
        'statut_operationnel',
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
     * Articles traitant de ce corridor de transport.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_corridor');
    }

    /**
     * Filtrer les corridors actifs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /**
     * Filtrer les corridors subissant des perturbations ou des congestions.
     */
    public function scopePerturbe(Builder $query): Builder
    {
        return $query->whereIn('statut_operationnel', ['ralenti', 'perturbe', 'bloque', 'congestionne']);
    }
}
