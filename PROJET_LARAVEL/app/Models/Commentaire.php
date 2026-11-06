<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commentaire extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'commentaires';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'auteur_nom',
        'auteur_email',
        'contenu',
        'statut',
        'ip_adresse',
        'user_agent',
        'votes_positifs',
        'signalements_compteur',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'votes_positifs' => 'integer',
            'signalements_compteur' => 'integer',
        ];
    }

    /**
     * Article commenté.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * Utilisateur ayant rédigé le commentaire.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Commentaire parent dans le fil de discussion.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Réponses directes au commentaire.
     */
    public function reponses(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('statut', 'approuve');
    }

    /**
     * Portée pour filtrer les commentaires validés par la modération.
     */
    public function scopeApprouve(Builder $query): Builder
    {
        return $query->where('statut', 'approuve');
    }

    /**
     * Portée pour la file de modération des commentaires en attente.
     */
    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }
}
