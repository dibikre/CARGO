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
     * Convertir intelligemment l'URL fournie en URL d'intégration iframe valide.
     */
    public function getUrlIframeFormatteeAttribute(): string
    {
        $chaine = trim($this->url_embed);

        // Si l'utilisateur a collé un code iframe complet
        if (preg_match('/src=["\']([^"\']+)["\']/', $chaine, $matches)) {
            return $matches[1];
        }

        // Détection et conversion YouTube classique
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $chaine, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        // Détection et conversion Vimeo
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/i', $chaine, $matches)) {
            $idVimeo = end($matches);
            return 'https://player.vimeo.com/video/' . $idVimeo;
        }

        return $chaine;
    }
}
