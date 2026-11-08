<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RedactionConnexionRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la connexion au desk de rédaction.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:191'],
            'password' => ['required', 'string', 'min:6'],
            'se_souvenir_de_moi' => ['nullable', 'boolean'],
            'jeton_securite' => ['nullable', 'string', 'max:0'], // Champ piège honeypot anti-robot
        ];
    }

    /**
     * Messages de validation en cas d'erreur.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The editorial email address is required.',
            'email.email' => 'Please provide a valid editorial email address.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 6 characters.',
        ];
    }

    /**
     * S'assurer que la tentative de connexion n'est pas bloquée par le limiteur de débit.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function assurerNonBloque(): void
    {
        $cleTentatives = $this->obtenirCleTentatives();

        // Seuil : 5 tentatives infructueuses maximum autorisées
        if (! RateLimiter::tooManyAttempts($cleTentatives, 5)) {
            return;
        }

        event(new Lockout($this));

        $secondesRestantes = RateLimiter::availableIn($cleTentatives);
        $minutesRestantes = (int) ceil($secondesRestantes / 60);

        throw ValidationException::withMessages([
            'email' => "Too many failed editorial login attempts. Please try again in {$minutesRestantes} minutes.",
        ]);
    }

    /**
     * Incrémenter le compteur de tentatives avec une durée de verrouillage de 15 minutes (900s).
     */
    public function incrementerTentatives(): void
    {
        RateLimiter::hit($this->obtenirCleTentatives(), 900);
    }

    /**
     * Réinitialiser le compteur de tentatives en cas de succès.
     */
    public function effacerTentatives(): void
    {
        RateLimiter::clear($this->obtenirCleTentatives());
    }

    /**
     * Clé unique de limitation basée sur l'email et l'adresse IP.
     */
    public function obtenirCleTentatives(): string
    {
        $emailNormalise = Str::transliterate(Str::lower($this->input('email', '')));
        return 'redaction_connexion:' . $emailNormalise . '|' . $this->ip();
    }
}
