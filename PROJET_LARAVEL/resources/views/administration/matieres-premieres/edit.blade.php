@extends('administration.layouts.admin')

@section('titre', 'Modifier ' . $matiere->nom . ' - Administration CARGO')

@section('contenu')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-heading font-black text-cargo-dark">Modifier la Matière Première : {{ $matiere->nom }}</h1>
            <p class="text-sm text-gray-500 mt-1">Actualisez les points de cours, l'écart entrée/sortie ou remplacez l'icône PNG sans fond.</p>
        </div>
        <a href="{{ route('admin.matieres-premieres.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-200 px-3 py-1.5 rounded">
            Retour à la liste
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-600 p-4 rounded text-rose-800 text-sm">
            <div class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Aperçu du sparkline actuel --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between text-gray-900">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-gray-200 rounded flex items-center justify-center p-1 shadow-sm">
                <img src="{{ asset($matiere->image_png) }}" alt="{{ $matiere->nom }}" class="w-10 h-10 object-contain" />
            </div>
            <div>
                <div class="text-xs text-gray-500 font-mono">{{ $matiere->code_symbole }} &bull; {{ $matiere->unite_mesure }}</div>
                <div class="text-lg font-bold text-gray-900">{{ $matiere->nom }}</div>
                <div class="text-sm font-mono text-gray-600">
                    Cours actuel : <strong class="text-gray-900">{{ $matiere->prix_formate }} {{ $matiere->devise }}</strong>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <svg class="w-24 h-8 overflow-visible" viewBox="0 0 80 24">
                <path d="{{ $matiere->genererTraceSvg(80, 24) }}" fill="none" stroke="{{ $matiere->couleur_tendance }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm font-mono font-bold px-2 py-1 rounded border {{ $matiere->pourcentage_variation >= 0 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }}">
                {{ $matiere->pourcentage_variation >= 0 ? '+' : '' }}{{ $matiere->pourcentage_variation }}%
            </span>
        </div>
    </div>

    <form action="{{ route('admin.matieres-premieres.update', $matiere->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nom" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nom de la matière première *</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $matiere->nom) }}" required class="w-full text-sm border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="code_symbole" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Code Symbole *</label>
                <input type="text" id="code_symbole" name="code_symbole" value="{{ old('code_symbole', $matiere->code_symbole) }}" required class="w-full text-sm border-gray-300 rounded uppercase font-mono focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="devise" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Devise de cotation *</label>
                <input type="text" id="devise" name="devise" value="{{ old('devise', $matiere->devise) }}" required class="w-full text-sm border-gray-300 rounded font-mono uppercase focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="unite_mesure" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Unité commerciale *</label>
                <input type="text" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure', $matiere->unite_mesure) }}" required class="w-full text-sm border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="ordre_affichage" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Ordre d'affichage</label>
                <input type="number" id="ordre_affichage" name="ordre_affichage" value="{{ old('ordre_affichage', $matiere->ordre_affichage) }}" min="0" class="w-full text-sm border-gray-300 rounded font-mono focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div class="flex items-center gap-3 pt-6">
                <input type="hidden" name="est_actif" value="0">
                <input type="checkbox" id="est_actif" name="est_actif" value="1" {{ old('est_actif', $matiere->est_actif ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red" />
                <label for="est_actif" class="text-sm font-semibold text-gray-700">Afficher dans le bandeau défilant</label>
            </div>
        </div>

        {{-- Transparent PNG Update --}}
        <div class="border-t border-gray-200 pt-6">
            <label for="image_png" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Remplacer l'image PNG transparente (facultatif)</label>
            <div class="flex items-center gap-4 mt-2">
                <div class="w-12 h-12 rounded bg-gray-100 border border-gray-300 flex items-center justify-center p-1 flex-shrink-0">
                    <img src="{{ asset($matiere->image_png) }}" alt="{{ $matiere->nom }}" class="w-10 h-10 object-contain" />
                </div>
                <div class="flex-1">
                    <input type="file" id="image_png" name="image_png" accept="image/png,.png,image/x-png" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" />
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver l'image actuelle. Tout fichier PNG sélectionné est automatiquement redimensionné à 96x96px.</p>
                </div>
            </div>
        </div>

        {{-- JSON Points Textarea --}}
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-1">
                <label for="donnees_courbe_json" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Points de la courbe (JSON : 5 à 10 coordonnées) *
                </label>
                <span class="text-xs text-gray-500 font-mono">Format : [[timestamp_ms, cours], ...]</span>
            </div>
            <p class="text-xs text-gray-500 mb-2">
                Collez les 5 à 10 coordonnées. Le cours de sortie et le pourcentage d'écart sont recalculés automatiquement.
            </p>
            <textarea id="donnees_courbe_json" name="donnees_courbe_json" rows="9" required class="w-full text-xs font-mono border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red bg-gray-50 p-3 leading-relaxed">{{ old('donnees_courbe_json', $jsonFormate) }}</textarea>
        </div>

        <div class="border-t border-gray-200 pt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.matieres-premieres.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800">
                Annuler
            </a>
            <button type="submit" class="bg-cargo-red hover:bg-red-700 text-white font-semibold px-6 py-2 rounded text-sm transition shadow">
                Mettre à jour le produit
            </button>
        </div>
    </form>
</div>
@endsection
