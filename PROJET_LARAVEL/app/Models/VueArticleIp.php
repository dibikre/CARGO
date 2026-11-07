<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VueArticleIp extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'vues_articles_ip';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'article_id',
        'adresse_ip',
        'agent_utilisateur',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'article_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Article associé à cette consultation par IP.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * Portée locale pour filtrer les vues d'un article spécifique.
     */
    public function scopePourArticle(Builder $requete, int $articleId): Builder
    {
        return $requete->where('article_id', $articleId);
    }

    /**
     * Portée locale pour filtrer les vues provenant d'une adresse IP donnée.
     */
    public function scopePourAdresseIp(Builder $requete, string $adresseIp): Builder
    {
        return $requete->where('adresse_ip', trim($adresseIp));
    }
}
