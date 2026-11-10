<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VideoLogistique extends Model
{
    use HasFactory;

    protected $table = 'videos_logistiques';

    protected $fillable = [
        'user_id',
        'titre',
        'slug',
        'url_embed',
        'source',
        'duree',
        'description',
        'est_actif',
        'ordre_affichage',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'ordre_affichage' => 'integer',
    ];

    /**
     * Utilisateur ou publicateur ayant ajouté la vidéo.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope pour les vidéos actives et visibles.
     */
    public function scopeActif($query)
    {
        return $query->where('est_actif', true)->orderBy('ordre_affichage')->latest();
    }

    /**
     * Identifie la plateforme d'hébergement ou confirme l'intégration universelle.
     */
    public function getPlateformeAttribute(): string
    {
        $chaine = (string) $this->url_embed;
        if (preg_match('/(?:youtube\.com|youtu\.be)/i', $chaine)) {
            return 'youtube';
        }
        if (preg_match('/vimeo\.com/i', $chaine)) {
            return 'vimeo';
        }
        if (preg_match('/dailymotion\.com|dai\.ly/i', $chaine)) {
            return 'dailymotion';
        }
        if (preg_match('/loom\.com/i', $chaine)) {
            return 'loom';
        }
        if (str_contains($chaine, '<iframe')) {
            return 'iframe';
        }

        return 'web';
    }

    /**
     * Récupère l'URL de la miniature d'aperçu pour éviter d'instancier un lecteur iframe lourd.
     */
    public function getUrlMiniatureAttribute(): ?string
    {
        $chaine = (string) $this->url_embed;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $chaine, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
        }

        return null;
    }

    /**
     * Convertit intelligemment tout lien ou code d'intégration de n'importe quel site en URL iframe valide.
     */
    public function getUrlIframeFormatteeAttribute(): string
    {
        $chaine = trim($this->url_embed);

        // 1. Si le rédacteur a collé une balise HTML <iframe> complète provenant de n'importe quel site
        if (preg_match('/src=["\']([^"\']+)["\']/', $chaine, $matches)) {
            return $matches[1];
        }

        // 2. Conversion automatique YouTube classique ou partagée
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $chaine, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        // 3. Conversion automatique Vimeo
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/i', $chaine, $matches)) {
            $idVimeo = end($matches);
            return 'https://player.vimeo.com/video/' . $idVimeo;
        }

        // 4. Conversion automatique Dailymotion
        if (preg_match('/(?:dailymotion\.com\/(?:video|hub)\/|dai\.ly\/)([a-zA-Z0-9]+)/i', $chaine, $matches)) {
            return 'https://www.dailymotion.com/embed/video/' . $matches[1];
        }

        // 5. Conversion automatique Loom
        if (preg_match('/loom\.com\/share\/([a-zA-Z0-9]+)/i', $chaine, $matches)) {
            return 'https://www.loom.com/embed/' . $matches[1];
        }

        // 6. N'importe quelle URL web directe (CDN privé, lecteur interne, autre hébergeur web)
        return $chaine;
    }

    /**
     * Génère le fragment HTML d'intégration prêt à être copié dans un article.
     */
    public function getCodeIframeAttribute(): string
    {
        return sprintf(
            '<div class="cargo-video-embed" style="position:relative;padding-top:56.25%%;overflow:hidden;"><iframe src="%s" title="%s" style="position:absolute;top:0;left:0;width:100%%;height:100%%;border:0;" allowfullscreen loading="lazy"></iframe></div>',
            e($this->url_iframe_formattee),
            e($this->titre)
        );
    }

    /**
     * Scope de recherche plein texte sur le titre, la source ou la description.
     */
    public function scopeRecherche($query, ?string $terme)
    {
        if (empty($terme)) {
            return $query;
        }

        return $query->where(function ($q) use ($terme) {
            $q->where('titre', 'like', "%{$terme}%")
              ->orWhere('source', 'like', "%{$terme}%")
              ->orWhere('description', 'like', "%{$terme}%");
        });
    }

    /**
     * Scope de filtrage selon la plateforme vidéo.
     */
    public function scopePlateforme($query, ?string $plateforme)
    {
        if (empty($plateforme) || $plateforme === 'toutes') {
            return $query;
        }

        if ($plateforme === 'youtube') {
            return $query->where(function ($q) {
                $q->where('url_embed', 'like', '%youtube%')
                  ->orWhere('url_embed', 'like', '%youtu.be%');
            });
        }

        if ($plateforme === 'vimeo') {
            return $query->where('url_embed', 'like', '%vimeo%');
        }

        if ($plateforme === 'autre') {
            return $query->where('url_embed', 'not like', '%youtube%')
                         ->where('url_embed', 'not like', '%youtu.be%')
                         ->where('url_embed', 'not like', '%vimeo%');
        }

        return $query;
    }

    /**
     * Scope de filtrage par état de publication.
     */
    public function scopeStatut($query, ?string $statut)
    {
        if ($statut === 'actif') {
            return $query->where('est_actif', true);
        }
        if ($statut === 'inactif') {
            return $query->where('est_actif', false);
        }

        return $query;
    }
}

