{{-- Vue grille responsive de cartes pour consultation visuelle des vidéos --}}
<div id="conteneur-vue-grille" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($videos as $video)
        <div class="carte-video bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:border-gray-300 transition flex flex-col justify-between" data-id="{{ $video->id }}" id="carte-video-{{ $video->id }}">
            <div>
                {{-- Miniature avec bouton de lecture --}}
                <div 
                    class="relative aspect-video bg-gray-900 overflow-hidden cursor-pointer group bouton-declencher-apercu"
                    data-id="{{ $video->id }}"
                    data-titre="{{ $video->titre }}"
                    data-url-iframe="{{ $video->url_iframe_formattee }}"
                    data-source="{{ $video->source }}"
                    data-duree="{{ $video->duree }}"
                    data-code-iframe="{{ $video->code_iframe }}"
                >
                    @if($video->url_miniature)
                        <img src="{{ $video->url_miniature }}" alt="{{ $video->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-cargo-dark flex items-center justify-center text-gray-500">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 flex items-center justify-center transition">
                        <div class="w-10 h-10 rounded-full bg-cargo-red text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </div>
                    </div>
                    @if($video->duree)
                        <span class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-mono font-bold px-1.5 py-0.5 rounded">
                            {{ $video->duree }}
                        </span>
                    @endif
                    <span class="absolute top-2 left-2 bg-black/70 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                        {{ ucfirst($video->plateforme) }}
                    </span>
                </div>

                {{-- Contenu descriptif --}}
                <div class="p-4 space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-cargo-red block">{{ $video->source ?: 'Editorial Field Video' }}</span>
                    <h3 
                        class="font-heading font-black text-gray-900 text-sm leading-snug line-clamp-2 hover:text-cargo-red cursor-pointer transition bouton-declencher-apercu"
                        data-id="{{ $video->id }}"
                        data-titre="{{ $video->titre }}"
                        data-url-iframe="{{ $video->url_iframe_formattee }}"
                        data-source="{{ $video->source }}"
                        data-duree="{{ $video->duree }}"
                        data-code-iframe="{{ $video->code_iframe }}"
                    >
                        {{ $video->titre }}
                    </h3>
                    @if($video->description)
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $video->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Pied de carte avec actions --}}
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        class="bouton-basculer-statut relative inline-flex h-4 w-7 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $video->est_actif ? 'bg-cargo-red' : 'bg-gray-300' }}"
                        data-id="{{ $video->id }}"
                        data-actif="{{ $video->est_actif ? '1' : '0' }}"
                        title="Toggle homepage display"
                    >
                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $video->est_actif ? 'translate-x-3' : 'translate-x-0' }}"></span>
                    </button>
                    <span class="text-[11px] font-bold uppercase text-gray-500">{{ $video->est_actif ? 'Active' : 'Hidden' }}</span>
                </div>

                <div class="flex items-center gap-1">
                    <button 
                        type="button" 
                        class="bouton-copier-code p-1 text-gray-500 hover:text-gray-900 rounded transition"
                        data-code="{{ $video->code_iframe }}"
                        title="Copy embed snippet"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                    <button 
                        type="button" 
                        class="bouton-demander-suppression p-1 text-gray-400 hover:text-cargo-red rounded transition"
                        data-id="{{ $video->id }}"
                        data-titre="{{ $video->titre }}"
                        title="Delete video"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div id="grille-vide" class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-gray-100">
            <p class="font-bold text-gray-700">No video embeds available in repository.</p>
        </div>
    @endforelse
</div>
