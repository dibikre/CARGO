<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRessourceFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé.
     */
    public function authorize(): bool
    {
        return $this->user()?->estMembreRedaction() ?? false;
    }

    /**
     * Règles de validation pour le téléversement de documents.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:1000'],
            'fichier' => ['required', 'file', 'mimes:pdf,xlsx,csv,zip', 'max:20480'], // max 20Mo
            'est_reserve_abonnes' => ['boolean'],
            'est_actif' => ['boolean'],
        ];
    }
}
