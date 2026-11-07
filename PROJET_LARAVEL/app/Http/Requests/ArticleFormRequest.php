<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleFormRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return $this->user()?->estMembreRedaction() ?? false;
    }

    /**
     * Règles de validation applicables.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $idArticle = $this->route('article')?->id ?? $this->route('id');

        return [
            'categorie_id' => ['required', 'exists:categories,id'],
            'titre' => ['required', 'string', 'max:191'],
            'chapeau' => ['nullable', 'string', 'max:500'],
            'contenu' => ['required', 'string'],
            'fichier_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,avif', 'max:8192'],
            'image_principale' => ['nullable', 'string', 'max:191'],
            'legende_image' => ['nullable', 'string', 'max:191'],
            'credit_photo' => ['nullable', 'string', 'max:120'],
            'statut' => ['required', 'in:brouillon,en_relecture,approuve,publie,archive'],
            'format_article' => ['required', 'in:article,breve_flash,analyse,reportage,interview,tribune,etude_de_cas,infographie'],
            'mode_transport_principal' => ['required', 'in:multimodal,maritime,aerien,routier,ferroviaire,logistique_urbaine,entreposage,douanes'],
            'est_a_la_une' => ['boolean'],
            'est_urgent' => ['boolean'],
            'est_reserve_abonnes' => ['boolean'],
            'est_sponsorise' => ['boolean'],
            'nom_partenaire' => ['nullable', 'string', 'max:120'],
            'date_publication' => ['nullable', 'date'],
            'meta_titre' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'tags' => ['nullable', 'array', 'max:4'],
            'tags.*' => ['exists:tags,id'],
            'zones' => ['nullable', 'array'],
            'zones.*' => ['exists:zones_logistiques,id'],
            'corridors' => ['nullable', 'array'],
            'corridors.*' => ['exists:corridors_transport,id'],
        ];
    }

    /**
     * Messages de validation personnalisés.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tags.max' => 'An article can have at most 4 tags (maximum 4 tags allowed).',
        ];
    }
}
