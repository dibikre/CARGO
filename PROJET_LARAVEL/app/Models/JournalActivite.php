<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalActivite extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'event',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Utilisateur ou acteur à l'origine de l'action.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Filtrer par type d'événement (ex: securite, administration, authentification).
     */
    public function scopeSecurite(Builder $query): Builder
    {
        return $query->whereIn('log_name', ['securite', 'authentification', 'admin_access']);
    }

    /**
     * Enregistrer rapidement une entrée d'audit de sécurité.
     */
    public static function enregistrer(
        string $description,
        string $nomLog = 'administration',
        ?string $evenement = null,
        ?User $auteur = null,
        array $donnees = []
    ): self {
        return self::create([
            'log_name' => $nomLog,
            'description' => $description,
            'causer_type' => $auteur ? get_class($auteur) : null,
            'causer_id' => $auteur?->id,
            'event' => $evenement,
            'properties' => $donnees,
        ]);
    }
}
