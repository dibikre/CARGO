@extends('administration.layouts.admin')

@section('titre', 'Logistics Corridors Supervision')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Freight Corridors & Disruption Dispatch</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Live supply chain disruption management across maritime, rail, road & air corridors</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- New Corridor Form --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm h-fit">
                <h2 class="text-sm font-heading font-bold text-gray-900 mb-4">Register New Freight Corridor</h2>
                <form action="{{ route('admin.corridors.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Corridor Name</label>
                        <input type="text" name="nom" required placeholder="e.g. Rotterdam - Genoa Rail Link"
                               class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Transport Mode</label>
                        <select name="type_flux" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="maritime">Maritime / Deep Sea</option>
                            <option value="ferroviaire">Rail Freight</option>
                            <option value="routier">Road Haulage</option>
                            <option value="aerien">Air Cargo</option>
                            <option value="multimodal">Multimodal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Initial Status</label>
                        <select name="statut_operationnel" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="normal">Fluide (Normal Flow)</option>
                            <option value="ralenti">Ralenti (Slowed Down)</option>
                            <option value="perturbe">Perturbe (Disrupted)</option>
                            <option value="congestionne">Congestionne (Congested)</option>
                            <option value="bloque">Bloque (Blocked / Closed)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Status Description / Warning</label>
                        <textarea name="description" rows="2" placeholder="Optional incident bulletin..."
                                  class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                        Add Corridor
                    </button>
                </form>
            </div>

            {{-- Corridors List & Status Switcher --}}
            <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <span class="text-xs font-heading font-bold text-gray-800 uppercase tracking-wider">Monitored Freight Lines</span>
                    <span class="text-xs text-gray-500">{{ count($corridors) }} Corridors Configured</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($corridors as $corridor)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-heading font-bold text-sm text-gray-900">{{ $corridor->nom }}</span>
                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">{{ $corridor->type_flux }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $corridor->description ?? 'No specific incident logged' }}</div>
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
                                    <option value="normal" {{ in_array($corridor->statut_operationnel, ['normal', 'fluide'], true) ? 'selected' : '' }}>Fluide</option>
                                    <option value="ralenti" {{ $corridor->statut_operationnel === 'ralenti' ? 'selected' : '' }}>Ralenti</option>
                                    <option value="perturbe" {{ $corridor->statut_operationnel === 'perturbe' ? 'selected' : '' }}>Perturbe</option>
                                    <option value="congestionne" {{ $corridor->statut_operationnel === 'congestionne' ? 'selected' : '' }}>Congestionne</option>
                                    <option value="bloque" {{ $corridor->statut_operationnel === 'bloque' ? 'selected' : '' }}>Bloque</option>
                                </select>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-500">No freight corridors registered yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
