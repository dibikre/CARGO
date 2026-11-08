<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormuleAbonnementFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à exécuter cette requête.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->estAdministrateur();
    }

    /**
     * Règles de validation pour les formules d'abonnement.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'prix_ht' => ['required', 'numeric', 'min:0'],
            'taux_tva' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'duree_en_jours' => ['required', 'integer', 'min:1', 'max:3650'],
            'acces_archives_illimite' => ['nullable', 'boolean'],
            'acces_dossiers_exclusifs' => ['nullable', 'boolean'],
            'nombre_utilisateurs_max' => ['nullable', 'integer', 'min:1', 'max:500'],
            'est_actif' => ['nullable', 'boolean'],
            'ordre_affichage' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
