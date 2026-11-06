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
        'image_fond',
        'opacite_overlay',
        'couleur_overlay',
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
            'opacite_overlay' => 'integer',
        ];
    }

    /**
     * Obtenir l'URL de l'image de fond ou repli par défaut.
     */
    public function urlImageFond(): ?string
    {
        if (empty($this->image_fond)) {
            return null;
        }

        if (str_starts_with($this->image_fond, 'http://') || str_starts_with($this->image_fond, 'https://')) {
            return $this->image_fond;
        }

        if (file_exists(public_path($this->image_fond))) {
            return asset($this->image_fond);
        }

        return asset('storage/' . $this->image_fond);
    }

    /**
     * Générer le style CSS de la bannière avec scrim anti-blanc sur blanc.
     */
    public function styleBanniere(): string
    {
        $opacite = ($this->opacite_overlay ?? 65) / 100;
        $couleur = $this->couleur_overlay ?: '#14151C';
        $url = $this->urlImageFond();

        if ($url) {
            return "background: linear-gradient(rgba(20,21,28,{$opacite}), rgba(20,21,28," . min(0.95, $opacite + 0.15) . ")), url('{$url}') center/cover no-repeat, {$couleur};";
        }

        return "background: {$couleur};";
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
