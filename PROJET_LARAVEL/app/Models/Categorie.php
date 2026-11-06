<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'nom',
        'slug',
        'description',
        'icone',
        'couleur',
        'ordre_affichage',
        'est_actif',
        'afficher_dans_menu',
        'meta_titre',
        'meta_description',
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
            'afficher_dans_menu' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    /**
     * Catégorie parente.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Sous-catégories enfants.
     */
    public function enfants(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('ordre_affichage');
    }

    /**
     * Articles rattachés à cette catégorie.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'categorie_id');
    }

    /**
     * Filtrer uniquement les catégories actives.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /**
     * Filtrer les rubriques à afficher dans la barre de navigation.
     */
    public function scopeMenu(Builder $query): Builder
    {
        return $query->where('est_actif', true)
            ->where('afficher_dans_menu', true)
            ->orderBy('ordre_affichage');
    }
}
