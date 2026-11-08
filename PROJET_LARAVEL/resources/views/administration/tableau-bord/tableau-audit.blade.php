{{-- Journal d'Audit & Sécurité avec codes traduits (Point 6) --}}
<div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-heading font-bold text-gray-900">Journal d'Audit & Sécurité</h2>
            <p class="text-xs text-gray-500">Traçabilité des opérations administratives et authentifications</p>
        </div>
        <a href="{{ route('admin.securite.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">Piste d'audit complète &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                    <th class="py-2">Date & Heure</th>
                    <th class="py-2">Événement</th>
                    <th class="py-2">Description</th>
                    <th class="py-2">Auteur</th>
                    <th class="py-2">Adresse IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($activitesRecentes as $activite)
                    @php
                        $evenementLibelle = match($activite->event ?? $activite->log_name) {
                            'login_succes', 'LOGIN_SUCCES' => 'Connexion réussie',
                            'login_echec', 'LOGIN_ECHEC' => 'Échec de connexion',
                            'deconnexion', 'DECONNEXION' => 'Déconnexion',
                            'compte_desactive' => 'Compte désactivé',
                            'privilege_insuffisant' => 'Privilège insuffisant',
                            'creation_article_admin' => 'Création d\'article',
                            'modification_article_admin' => 'Mise à jour d\'article',
                            'bascule_alerte_urgente' => 'Alerte urgente modifiée',
                            'bascule_paywall_pro' => 'Paywall modifié',
                            'bascule_a_la_une' => 'Mise à la une',
                            'suppression_article' => 'Suppression d\'article',
                            'approuve_commentaire' => 'Commentaire approuvé',
                            'spam_commentaire' => 'Commentaire marqué spam',
                            'statut_corridor_modifie' => 'Statut corridor mis à jour',
                            'bascule_paywall_global' => 'Paywall global modifié',
                            default => ucfirst(str_replace('_', ' ', $activite->event ?? $activite->log_name))
                        };
                    @endphp
                    <tr>
                        <td class="py-2.5 font-mono text-gray-500">{{ $activite->created_at->translatedFormat('d/m/Y H:i') }}</td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                {{ $activite->log_name === 'securite' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $evenementLibelle }}
                            </span>
                        </td>
                        <td class="py-2.5 font-medium text-gray-900">{{ $activite->description }}</td>
                        <td class="py-2.5">{{ $activite->auteur->email ?? 'Système' }}</td>
                        <td class="py-2.5 font-mono text-gray-500">{{ $activite->properties['ip'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Aucun enregistrement d'audit récent.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
