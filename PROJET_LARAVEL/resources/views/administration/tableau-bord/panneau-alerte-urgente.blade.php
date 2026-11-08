{{-- Panneau Dédié Alerte Urgente Actionnable (Point 5) --}}
@if ($alerteUrgenteActive)
    <div class="bg-red-50 border-2 border-cargo-red rounded-lg p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-cargo-red text-white text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                        En direct sur le portail public
                    </span>
                    <span class="text-xs text-red-700 font-semibold">
                        Diffusée depuis le {{ $alerteUrgenteActive->date_publication?->translatedFormat('d F Y à H:i') ?? $alerteUrgenteActive->created_at->translatedFormat('d F Y à H:i') }}
                    </span>
                </div>
                <h2 class="text-lg font-heading font-black text-gray-900">
                    {{ $alerteUrgenteActive->titre }}
                </h2>
                <p class="text-xs text-gray-600">
                    Rubrique : <strong>{{ $alerteUrgenteActive->categorie->nom ?? 'Général' }}</strong> &bull; Par {{ $alerteUrgenteActive->auteur->name ?? 'La Rédaction' }} &bull; Bandeau rouge visible en tête de toutes les pages publiques.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.articles.edit', $alerteUrgenteActive->id) }}" class="bg-white hover:bg-gray-100 text-gray-900 border border-gray-300 text-xs font-bold px-3 py-2 rounded transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier
                </a>

                <form action="{{ route('admin.articles.alerte', $alerteUrgenteActive->id) }}" method="POST" onsubmit="return confirm('Désactiver immédiatement cette alerte du bandeau public ?');">
                    @csrf
                    <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white text-xs font-bold px-3 py-2 rounded transition flex items-center gap-1 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Désactiver l'alerte
                    </button>
                </form>

                <a href="{{ route('articles.show', $alerteUrgenteActive->slug) }}" target="_blank" class="text-xs text-gray-600 hover:text-gray-900 underline px-2 py-1">
                    Aperçu public &rarr;
                </a>
            </div>
        </div>
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span class="text-gray-700 font-medium">Bandeau d'alerte public inactif : aucun article urgent n'est diffusé en temps réel.</span>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="text-cargo-red hover:underline font-semibold flex items-center gap-1">
            <span>Créer une dépêche urgente &rarr;</span>
        </a>
    </div>
@endif
