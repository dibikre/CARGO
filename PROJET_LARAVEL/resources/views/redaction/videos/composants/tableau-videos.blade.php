{{-- Vue tabulaire dense pour gérer rapidement des centaines de vidéos --}}
<div id="conteneur-vue-tableau" class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-700">
            <thead class="bg-cargo-dark text-white uppercase text-xs font-heading font-black tracking-wider">
                <tr>
                    <th scope="col" class="py-3.5 px-4 w-10 text-center">
                        <input type="checkbox" id="case-tout-selectionner" class="rounded border-gray-400 text-cargo-red focus:ring-cargo-red cursor-pointer">
                    </th>
                    <th scope="col" class="py-3.5 px-4">Video Asset & Synopsis</th>
                    <th scope="col" class="py-3.5 px-4 w-32">Platform</th>
                    <th scope="col" class="py-3.5 px-4 w-44">Source Channel</th>
                    <th scope="col" class="py-3.5 px-4 w-28 text-center">Homepage</th>
                    <th scope="col" class="py-3.5 px-4 w-40 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="corps-tableau-videos" class="divide-y divide-gray-100">
                @forelse($videos as $video)
                    <tr class="hover:bg-gray-50/80 transition ligne-video" data-id="{{ $video->id }}" id="ligne-video-{{ $video->id }}">
                        <td class="py-3 px-4 text-center">
                            <input type="checkbox" value="{{ $video->id }}" class="case-selection-video rounded border-gray-300 text-cargo-red focus:ring-cargo-red cursor-pointer">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-start gap-3">
                                {{-- Miniature avec bouton play --}}
                                <div 
                                    class="relative w-20 h-12 bg-gray-900 rounded overflow-hidden flex-shrink-0 group cursor-pointer bouton-declencher-apercu"
                                    data-id="{{ $video->id }}"
                                    data-titre="{{ $video->titre }}"
                                    data-url-iframe="{{ $video->url_iframe_formattee }}"
                                    data-source="{{ $video->source }}"
                                    data-duree="{{ $video->duree }}"
                                    data-code-iframe="{{ $video->code_iframe }}"
                                    title="Click to preview video"
                                >
                                    @if($video->url_miniature)
                                        <img src="{{ $video->url_miniature }}" alt="{{ $video->titre }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    @else
                                        <div class="w-full h-full bg-cargo-dark flex items-center justify-center text-gray-500">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-70 group-hover:opacity-100 transition">
                                        <div style="background-color: #D4201A;" class="w-6 h-6 rounded-full text-white flex items-center justify-center shadow">
                                            <svg class="w-3 h-3 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Titre et résumé --}}
                                <div class="min-w-0 flex-1">
                                    <button 
                                        type="button" 
                                        class="text-left font-bold text-gray-900 hover:text-cargo-red transition text-sm leading-snug line-clamp-1 bouton-declencher-apercu"
                                        data-id="{{ $video->id }}"
                                        data-titre="{{ $video->titre }}"
                                        data-url-iframe="{{ $video->url_iframe_formattee }}"
                                        data-source="{{ $video->source }}"
                                        data-duree="{{ $video->duree }}"
                                        data-code-iframe="{{ $video->code_iframe }}"
                                    >
                                        {{ $video->titre }}
                                    </button>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                        <span>{{ $video->created_at ? $video->created_at->format('M d, Y') : 'Unknown' }}</span>
                                        @if($video->description)
                                            <span>&bull;</span>
                                            <span class="truncate max-w-xs text-gray-400">{{ $video->description }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col gap-1 items-start">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">
                                    {{ ucfirst($video->plateforme) }}
                                </span>
                                @if($video->duree)
                                    <span class="text-xs font-mono text-gray-500">{{ $video->duree }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs font-medium text-gray-700">
                            {{ $video->source ?: 'Editorial Field Video' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button 
                                type="button" 
                                style="{{ $video->est_actif ? 'background-color: #D4201A;' : 'background-color: #D1D5DB;' }}"
                                class="bouton-basculer-statut relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                data-id="{{ $video->id }}"
                                data-actif="{{ $video->est_actif ? '1' : '0' }}"
                                title="Toggle display on homepage sidebar"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $video->est_actif ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button 
                                    type="button" 
                                    class="bouton-copier-code p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition"
                                    data-code="{{ $video->code_iframe }}"
                                    title="Copy embed HTML snippet"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <button 
                                    type="button" 
                                    class="bouton-declencher-apercu p-1.5 text-gray-500 hover:text-cargo-dark hover:bg-gray-100 rounded transition"
                                    data-id="{{ $video->id }}"
                                    data-titre="{{ $video->titre }}"
                                    data-url-iframe="{{ $video->url_iframe_formattee }}"
                                    data-source="{{ $video->source }}"
                                    data-duree="{{ $video->duree }}"
                                    data-code-iframe="{{ $video->code_iframe }}"
                                    title="Preview player"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button 
                                    type="button" 
                                    class="bouton-demander-suppression p-1.5 text-gray-400 hover:text-cargo-red hover:bg-red-50 rounded transition"
                                    data-id="{{ $video->id }}"
                                    data-titre="{{ $video->titre }}"
                                    title="Delete video"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="ligne-tableau-vide">
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <p class="font-bold text-gray-700">No video embeds found matching criteria.</p>
                            <p class="text-xs text-gray-400 mt-1">Try clearing your filters or add a new video report.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
