<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterFormRequest;
use App\Models\AbonneNewsletter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterControleur extends Controller
{
    /**
     * Enregistrer une nouvelle inscription à la veille logistique.
     */
    public function inscrire(NewsletterFormRequest $request): RedirectResponse
    {
        $donnees = $request->validated();

        $tokenConfirmation = Str::random(40);
        $tokenDesinscription = Str::random(40);

        AbonneNewsletter::create([
            'email' => strtolower($donnees['email']),
            'nom_complet' => $donnees['nom_complet'] ?? null,
            'entreprise' => $donnees['entreprise'] ?? null,
            'fonction' => $donnees['fonction'] ?? null,
            'frequence' => $donnees['frequence'],
            'preferences_rubriques' => $donnees['preferences_rubriques'] ?? [],
            'token_confirmation' => $tokenConfirmation,
            'token_desinscription' => $tokenDesinscription,
            'est_verifie' => true, // Actif directement ou par courriel selon configuration
            'est_actif' => true,
            'date_confirmation' => now(),
        ]);

        return back()->with('succes', 'Votre inscription à la newsletter logistique a été enregistrée avec succès.');
    }

    /**
     * Confirmer l'adresse e-mail via le lien reçu (double opt-in).
     */
    public function confirmer(string $token): View
    {
        $abonne = AbonneNewsletter::where('token_confirmation', $token)->firstOrFail();

        $abonne->update([
            'est_verifie' => true,
            'est_actif' => true,
            'date_confirmation' => now(),
            'token_confirmation' => null,
        ]);

        return view('journal.newsletter.confirmation', compact('abonne'));
    }

    /**
     * Se désinscrire de la newsletter via lien sécurisé.
     */
    public function desinscrire(string $token): View
    {
        $abonne = AbonneNewsletter::where('token_desinscription', $token)->firstOrFail();

        $abonne->update([
            'est_actif' => false,
        ]);

        return view('journal.newsletter.desinscription', compact('abonne'));
    }
}
