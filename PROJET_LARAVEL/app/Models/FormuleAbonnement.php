<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormuleAbonnement extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'formules_abonnements';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'prix_ht',
        'taux_tva',
        'duree_en_jours',
        'acces_archives_illimite',
        'acces_dossiers_exclusifs',
        'nombre_utilisateurs_max',
        'est_actif',
        'ordre_affichage',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prix_ht' => 'decimal:2',
            'taux_tva' => 'decimal:2',
            'duree_en_jours' => 'integer',
            'acces_archives_illimite' => 'boolean',
            'acces_dossiers_exclusifs' => 'boolean',
            'nombre_utilisateurs_max' => 'integer',
            'est_actif' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    /**
     * Abonnements souscrits pour cette formule.
     */
    public function abonnements(): HasMany
    {
        return $this->hasMany(AbonnementUtilisateur::class, 'formule_id');
    }

    /**
     * Calculer le prix TTC de l'abonnement.
     */
    public function getPrixTtcAttribute(): float
    {
        return round((float) $this->prix_ht * (1 + ((float) $this->taux_tva / 100)), 2);
    }

    /**
     * Filtrer les formules disponibles aux lecteurs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true)->orderBy('ordre_affichage');
    }
}
