<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'auteur_id',
        'categorie_id',
        'titre',
        'slug',
        'chapeau',
        'contenu',
        'image_principale',
        'legende_image',
        'credit_photo',
        'statut',
        'format_article',
        'mode_transport_principal',
        'est_a_la_une',
        'est_urgent',
        'est_reserve_abonnes',
        'est_sponsorise',
        'nom_partenaire',
        'temps_lecture_minutes',
        'vues_compteur',
        'partages_compteur',
        'date_publication',
        'meta_titre',
        'meta_description',
        'canonical_url',
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
            'est_a_la_une' => 'boolean',
            'est_urgent' => 'boolean',
            'est_reserve_abonnes' => 'boolean',
            'est_sponsorise' => 'boolean',
            'temps_lecture_minutes' => 'integer',
            'vues_compteur' => 'integer',
            'partages_compteur' => 'integer',
        ];
    }

    /**
     * Auteur / Journaliste ayant rédigé l'article.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    /**
     * Rubrique / Catégorie principale.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Tags associés à l'article.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * Zones logistiques couvertes.
     */
    public function zonesLogistiques(): BelongsToMany
    {
        return $this->belongsToMany(ZoneLogistique::class, 'article_zone_logistique');
    }

    /**
     * Corridors de transport concernés.
     */
    public function corridors(): BelongsToMany
    {
        return $this->belongsToMany(CorridorTransport::class, 'article_corridor');
    }

    /**
     * Dossiers thématiques incluant cet article.
     */
    public function dossiers(): BelongsToMany
    {
        return $this->belongsToMany(DossierThematique::class, 'article_dossier')
            ->withPivot('ordre_dans_dossier');
    }

    /**
     * Commentaires déposés sur l'article.
     */
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'article_id');
    }

    /**
     * Statistiques journalières de consultation.
     */
    public function statistiques(): HasMany
    {
        return $this->hasMany(StatistiqueArticle::class, 'article_id');
    }

    /**
     * Vues uniques enregistrées par adresse IP.
     */
    public function vuesIp(): HasMany
    {
        return $this->hasMany(VueArticleIp::class, 'article_id');
    }

    /**
     * Portée pour filtrer les articles publiés et visibles du public.
     */
    public function scopePublie(Builder $query): Builder
    {
        return $query->where('statut', 'publie')
            ->whereNotNull('date_publication')
            ->where('date_publication', '<=', now())
            ->orderByDesc('date_publication');
    }

    /**
     * Portée pour filtrer les articles à la une.
     */
    public function scopeALaUne(Builder $query): Builder
    {
        return $query->publie()
            ->where('est_a_la_une', true);
    }

    /**
     * Portée pour les alertes urgentes et actualités chaudes.
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->publie()
            ->where('est_urgent', true);
    }

    /**
     * Incrémenter le nombre de vues.
     */
    public function incrementerVues(): void
    {
        $this->increment('vues_compteur');
    }

    /**
     * Accesseur pour l'URL de l'image principale avec repli sur les visuels sectoriels.
     */
    public function getUrlImageAttribute(): string
    {
        return app(\App\Services\ServiceGestionImage::class)
            ->obtenirUrlImage($this->image_principale, $this->mode_transport_principal);
    }
}
