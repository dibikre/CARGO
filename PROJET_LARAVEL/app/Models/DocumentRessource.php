<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRessource extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'documents_ressources';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'titre',
        'slug',
        'description',
        'nom_fichier',
        'chemin_stockage',
        'type_mime',
        'taille_octets',
        'nombre_telechargements',
        'est_reserve_abonnes',
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
            'taille_octets' => 'integer',
            'nombre_telechargements' => 'integer',
            'est_reserve_abonnes' => 'boolean',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Auteur ou administrateur ayant téléversé la ressource.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Portée pour filtrer les documents actifs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /**
     * Portée pour filtrer les documents en accès libre (sans abonnement).
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('est_actif', true)
            ->where('est_reserve_abonnes', false);
    }

    /**
     * Incrémenter le compteur de téléchargements.
     */
    public function incrementerTelechargements(): void
    {
        $this->increment('nombre_telechargements');
    }
}
