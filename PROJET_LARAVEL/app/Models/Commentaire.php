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
        'auteur_site_web',
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

    /**
     * URL sécurisée du site web de l'auteur (uniquement http ou https, pas de protocoles malveillants).
     */
    public function getUrlSiteWebSecuriseeAttribute(): ?string
    {
        $url = trim((string) $this->auteur_site_web);
        if (empty($url)) {
            return null;
        }

        if (! preg_match('~^https?://~i', $url)) {
            $url = 'https://' . $url;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) && ! preg_match('~^(javascript|data|vbscript):~i', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Nom de domaine épuré pour affichage élégant.
     */
    public function getNomDomaineSiteWebAttribute(): string
    {
        $url = $this->url_site_web_securisee ?? $this->auteur_site_web;
        $hote = parse_url((string) $url, PHP_URL_HOST);

        return $hote ?: preg_replace('~^https?://~i', '', (string) $this->auteur_site_web);
    }

    /**
     * Indique si le site web et les liens du commentaire peuvent être cliqués publiquement.
     */
    public function getSiteWebEstCliquableAttribute(): bool
    {
        return ConfigurationSysteme::liensCommentairesSontCliquables() && ! empty($this->url_site_web_securisee);
    }

    /**
     * Contenu du commentaire formaté selon le réglage d'administration :
     * - Liens cliquables : balises <a> sécurisées (rel="nofollow noopener noreferrer")
     * - Liens nettoyés : texte brut sans hyperlien et aseptisé
     */
    public function getContenuFormateAttribute(): string
    {
        $texteBrut = strip_tags((string) $this->contenu);

        if (! ConfigurationSysteme::liensCommentairesSontCliquables()) {
            return nl2br(e($texteBrut));
        }

        // Remplacement sécurisé des URLs en liens cliquables
        $texteEchappe = e($texteBrut);
        $motif = '~(?<!["\'])(https?://[^\s<]+|www\.[^\s<]+)~i';

        $texteAvecLiens = preg_replace_callback($motif, function ($correspondances) {
            $urlComplete = $correspondances[0];
            $href = str_starts_with(strtolower($urlComplete), 'www.') ? 'https://' . $urlComplete : $urlComplete;
            return sprintf(
                '<a href="%s" target="_blank" rel="nofollow noopener noreferrer" style="color:var(--accent);text-decoration:underline;font-weight:600;">%s</a>',
                e($href),
                e($urlComplete)
            );
        }, $texteEchappe);

        return nl2br((string) $texteAvecLiens);
    }
}
