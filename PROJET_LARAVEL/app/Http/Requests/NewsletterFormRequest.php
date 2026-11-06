<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation de l'inscription newsletter.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:191', 'unique:abonnes_newsletter,email'],
            'nom_complet' => ['nullable', 'string', 'max:120'],
            'entreprise' => ['nullable', 'string', 'max:120'],
            'fonction' => ['nullable', 'string', 'max:100'],
            'frequence' => ['required', 'in:quotidienne,hebdomadaire,alertes_urgentes'],
            'preferences_rubriques' => ['nullable', 'array'],
            'preferences_rubriques.*' => ['exists:categories,id'],
        ];
    }
}
