<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * Les attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'telephone',
        'est_actif',
        'derniere_connexion_a',
    ];

    /**
     * Les attributs cachés pour la sérialisation.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les conversions d'attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'derniere_connexion_a' => 'datetime',
            'est_actif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Articles rédigés par l'utilisateur.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'auteur_id');
    }

    /**
     * Commentaires rédigés par l'utilisateur.
     */
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'user_id');
    }

    /**
     * Historique des abonnements de l'utilisateur.
     */
    public function abonnements(): HasMany
    {
        return $this->hasMany(AbonnementUtilisateur::class, 'user_id');
    }

    /**
     * Abonnement en cours de validité.
     */
    public function abonnementActif(): HasOne
    {
        return $this->hasOne(AbonnementUtilisateur::class, 'user_id')
            ->where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->latestOfMany();
    }

    /**
     * Documents ressources publiés par l'utilisateur.
     */
    public function documentsRessources(): HasMany
    {
        return $this->hasMany(DocumentRessource::class, 'user_id');
    }

    /**
     * Vérifier si l'utilisateur possède un abonnement actif.
     */
    public function estAbonne(): bool
    {
        return $this->abonnementActif()->exists();
    }

    /**
     * Vérifier si l'utilisateur possède des privilèges d'administrateur.
     */
    public function estAdministrateur(): bool
    {
        return in_array($this->role, ['super_admin', 'administrateur', 'admin'], true);
    }

    /**
     * Vérifier si l'utilisateur fait partie de la rédaction ou de l'administration éditoriale.
     */
    public function estMembreRedaction(): bool
    {
        return in_array($this->role, ['super_admin', 'administrateur', 'admin', 'redacteur_en_chef', 'journaliste', 'redacteur'], true);
    }

    /**
     * URL publique de la photo de profil du journaliste.
     */
    public function getUrlAvatarAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        return str_starts_with($this->avatar, 'http') ? $this->avatar : asset($this->avatar);
    }

    /**
     * URL publique de la vignette 40x40 pixels pour affichage dans les articles publics.
     */
    public function getUrlAvatarVignetteAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        $cheminVignette = str_replace('images/avatars/', 'images/avatars/vignettes/', $this->avatar);
        if (file_exists(public_path($cheminVignette))) {
            return asset($cheminVignette);
        }

        return asset($this->avatar);
    }
}
