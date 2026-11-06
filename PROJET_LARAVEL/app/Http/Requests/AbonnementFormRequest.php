<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbonnementFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Règles de validation pour la souscription.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'formule_id' => ['required', 'exists:formules_abonnements,id'],
            'mode_paiement' => ['required', 'string', 'in:carte_bancaire,virement,prelevement,facture_pro'],
        ];
    }
}
