<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementLogistique extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'evenements_logistiques';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'titre',
        'slug',
        'description',
        'lieu',
        'ville',
        'pays',
        'organisateur',
        'url_site_officiel',
        'date_debut',
        'date_fin',
        'type_evenement',
        'est_partenaire',
        'est_actif',
        'image_banniere',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'est_partenaire' => 'boolean',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Filtrer les événements actifs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /**
     * Filtrer les événements à venir.
     */
    public function scopeAVenir(Builder $query): Builder
    {
        return $query->where('est_actif', true)
            ->where('date_fin', '>=', now())
            ->orderBy('date_debut');
    }
}
