<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechercheInitierRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à initier une recherche.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Préparer les données avant validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('q')) {
            $this->merge([
                'q' => trim(strip_tags((string) $this->input('q'))),
            ]);
        }
    }

    /**
     * Règles de validation pour l'initialisation de recherche sécurisée.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:1', 'max:150'],
        ];
    }

    /**
     * Messages d'erreur explicites.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.required' => 'Veuillez saisir au moins un mot-clé pour lancer la recherche.',
            'q.min' => 'La recherche doit comporter au moins un caractère.',
            'q.max' => 'La requête de recherche ne peut pas dépasser 150 caractères.',
        ];
    }
}
