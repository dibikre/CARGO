@extends('administration.layouts.admin')

@section('titre', 'Supervision des Corridors Logistiques')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Corridors de Fret & Gestion des Perturbations</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Surveillance en temps réel des flux maritimes, ferroviaires, routiers et aériens</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Formulaire de nouveau corridor --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm h-fit">
                <h2 class="text-sm font-heading font-bold text-gray-900 mb-4">Enregistrer un Nouveau Corridor</h2>
                <form action="{{ route('admin.corridors.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Nom du corridor</label>
                        <input type="text" name="nom" required placeholder="ex: Liaison Ferroviaire Rotterdam - Gênes"
                               class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Mode de transport</label>
                        <select name="type_flux" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="maritime">Maritime & Hauturier</option>
                            <option value="ferroviaire">Fret Ferroviaire</option>
                            <option value="routier">Transport Routier</option>
                            <option value="aerien">Fret Aérien</option>
                            <option value="multimodal">Multimodal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Statut opérationnel initial</label>
                        <select name="statut_operationnel" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="normal">Normal & Fluide</option>
                            <option value="ralenti">Ralentissement modéré</option>
                            <option value="perturbe">Perturbation majeure</option>
                            <option value="congestionne">Congestion portuaire / hub</option>
                            <option value="bloque">Trafic interrompu / bloqué</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Description / Bulletin d'alerte</label>
                        <textarea name="description" rows="2" placeholder="Détails sur l'incident ou les prévisions de décongestion..."
                                  class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                        Ajouter le corridor
                    </button>
                </form>
            </div>

            {{-- Liste des corridors & sélecteur de statut rapide --}}
            <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <span class="text-xs font-heading font-bold text-gray-800 uppercase tracking-wider">Lignes de Fret Sous Surveillance</span>
                    <span class="text-xs text-gray-500">{{ count($corridors) }} Corridors configurés</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($corridors as $corridor)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-heading font-bold text-sm text-gray-900">{{ $corridor->nom }}</span>
                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">{{ ucfirst($corridor->type_flux) }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $corridor->description ?? 'Aucun incident spécifique signalé' }}</div>
                            </div>

                            <form action="{{ route('admin.corridors.statut', $corridor->id) }}" method="POST" class="flex items-center gap-2 shrink-0">
                                @csrf
                                @method('PUT')
                                <select name="statut_operationnel" onchange="this.form.submit()" class="text-xs font-bold rounded p-1.5 border border-gray-300
                                    @if($corridor->statut_operationnel === 'bloque') bg-red-100 text-red-800
                                    @elseif($corridor->statut_operationnel === 'perturbe') bg-amber-100 text-amber-800
                                    @elseif($corridor->statut_operationnel === 'congestionne') bg-yellow-100 text-yellow-800
                                    @elseif($corridor->statut_operationnel === 'ralenti') bg-blue-100 text-blue-800
                                    @else bg-emerald-100 text-emerald-800 @endif">
                                    <option value="normal" {{ in_array($corridor->statut_operationnel, ['normal', 'fluide'], true) ? 'selected' : '' }}>Normal & Fluide</option>
                                    <option value="ralenti" {{ $corridor->statut_operationnel === 'ralenti' ? 'selected' : '' }}>Trafic ralenti</option>
                                    <option value="perturbe" {{ $corridor->statut_operationnel === 'perturbe' ? 'selected' : '' }}>Perturbation majeure</option>
                                    <option value="congestionne" {{ $corridor->statut_operationnel === 'congestionne' ? 'selected' : '' }}>Congestion portuaire</option>
                                    <option value="bloque" {{ $corridor->statut_operationnel === 'bloque' ? 'selected' : '' }}>Trafic bloqué</option>
                                </select>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-500">Aucun corridor de fret enregistré pour le moment.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
