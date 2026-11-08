<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonneNewsletter extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'abonnes_newsletter';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'nom_complet',
        'entreprise',
        'fonction',
        'frequence',
        'preferences_rubriques',
        'token_confirmation',
        'token_desinscription',
        'est_verifie',
        'est_actif',
        'date_confirmation',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferences_rubriques' => 'array',
            'est_verifie' => 'boolean',
            'est_actif' => 'boolean',
            'date_confirmation' => 'datetime',
        ];
    }

    /**
     * Portée pour les abonnés valides et actifs.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true)->where('est_verifie', true);
    }

    /**
     * Portée pour les abonnés ayant validé leur confirmation (Double Opt-In).
     */
    public function scopeConfirme(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNotNull('date_confirmation')
              ->orWhere('est_verifie', true);
        });
    }

    /**
     * Portée pour les abonnés en attente de confirmation.
     */
    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('date_confirmation')
              ->where('est_verifie', false);
        });
    }

    /**
     * Accesseur de compatibilité pour confirme_a (date_confirmation).
     */
    public function getConfirmeAAttribute()
    {
        return $this->date_confirmation;
    }

    /**
     * Accesseur de compatibilité pour nom (nom_complet).
     */
    public function getNomAttribute(): ?string
    {
        return $this->nom_complet;
    }

    /**
     * Indique si l'abonné a validé son adresse e-mail.
     */
    public function getEstConfirmeAttribute(): bool
    {
        return $this->est_verifie || ! is_null($this->date_confirmation);
    }
}
