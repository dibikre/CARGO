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
     * Préparer les données avant validation (normalisation d'URL et pré-remplissage).
     */
    protected function prepareForValidation(): void
    {
        $utilisateur = $this->user();
        $donnees = [];

        if ($utilisateur) {
            if (! $this->filled('auteur_nom')) {
                $donnees['auteur_nom'] = $utilisateur->name;
            }
            if (! $this->filled('auteur_email')) {
                $donnees['auteur_email'] = $utilisateur->email;
            }
        }

        if ($this->has('auteur_site_web')) {
            $site = trim((string) $this->input('auteur_site_web'));
            if ($site !== '' && ! preg_match('~^https?://~i', $site)) {
                $site = 'https://' . ltrim($site, '/');
            }
            $donnees['auteur_site_web'] = $site;
        }

        if (! empty($donnees)) {
            $this->merge($donnees);
        }
    }

    /**
     * Règles de validation du commentaire (nom, e-mail, site web et texte obligatoires).
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'article_id' => ['required', 'exists:articles,id'],
            'parent_id' => ['nullable', 'exists:commentaires,id'],
            'auteur_nom' => ['required', 'string', 'max:100'],
            'auteur_email' => ['required', 'string', 'email:rfc', 'max:150'],
            'auteur_site_web' => ['required', 'string', 'max:255', 'url:http,https'],
            'contenu' => ['required', 'string', 'min:3', 'max:2000'],
        ];
    }

    /**
     * Messages d'erreur explicites pour la soumission de commentaires.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'auteur_nom.required' => 'Le nom complet ou organisation est obligatoire.',
            'auteur_email.required' => 'L\'adresse e-mail professionnelle est obligatoire.',
            'auteur_email.email' => 'Veuillez renseigner une adresse e-mail valide.',
            'auteur_site_web.required' => 'L\'adresse de votre site web est obligatoire.',
            'auteur_site_web.url' => 'Veuillez saisir une URL de site web valide (ex: https://mon-entreprise.com).',
            'contenu.required' => 'Le texte du commentaire est obligatoire.',
            'contenu.min' => 'Le commentaire doit comporter au moins 3 caractères.',
        ];
    }
}

