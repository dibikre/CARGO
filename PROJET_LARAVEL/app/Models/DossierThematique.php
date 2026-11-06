<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DossierThematique extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'dossiers_thematiques';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'titre',
        'slug',
        'chapeau',
        'description_complete',
        'image_couverture',
        'date_publication',
        'date_cloture',
        'est_en_vedette',
        'est_actif',
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
            'date_publication' => 'datetime',
            'date_cloture' => 'datetime',
            'est_en_vedette' => 'boolean',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Articles composant ce dossier thématique.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_dossier')
            ->withPivot('ordre_dans_dossier')
            ->orderByPivot('ordre_dans_dossier');
    }

    /**
     * Filtrer les dossiers actifs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /**
     * Filtrer les dossiers mis en avant.
     */
    public function scopeEnVedette(Builder $query): Builder
    {
        return $query->where('est_actif', true)
            ->where('est_en_vedette', true);
    }
}
