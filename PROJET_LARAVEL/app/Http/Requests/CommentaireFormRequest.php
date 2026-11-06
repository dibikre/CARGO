<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentaireFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à commenter.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation du commentaire.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $estConnecte = $this->user() !== null;

        return [
            'article_id' => ['required', 'exists:articles,id'],
            'parent_id' => ['nullable', 'exists:commentaires,id'],
            'auteur_nom' => [$estConnecte ? 'nullable' : 'required', 'string', 'max:100'],
            'auteur_email' => [$estConnecte ? 'nullable' : 'required', 'email', 'max:150'],
            'contenu' => ['required', 'string', 'min:3', 'max:2000'],
        ];
    }
}
