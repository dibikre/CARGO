<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbonnementUtilisateur extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'abonnements_utilisateurs';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'formule_id',
        'date_debut',
        'date_fin',
        'statut',
        'renouvellement_automatique',
        'reference_commande',
        'montant_paye_ttc',
        'mode_paiement',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'renouvellement_automatique' => 'boolean',
            'montant_paye_ttc' => 'decimal:2',
        ];
    }

    /**
     * Utilisateur détenteur de l'abonnement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias français de l'utilisateur détenteur de l'abonnement.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Formule souscrite.
     */
    public function formule(): BelongsTo
    {
        return $this->belongsTo(FormuleAbonnement::class, 'formule_id');
    }

    /**
     * Filtrer les abonnements actuellement valides.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('statut', 'actif')
            ->where('date_fin', '>=', now());
    }

    /**
     * Vérifier si l'abonnement est actuellement valide.
     */
    public function estValide(): bool
    {
        return $this->statut === 'actif' && $this->date_fin !== null && $this->date_fin->isFuture();
    }
}
