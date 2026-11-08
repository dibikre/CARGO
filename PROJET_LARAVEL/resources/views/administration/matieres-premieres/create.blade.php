@extends('administration.layouts.admin')

@section('titre', 'Ajouter une Matière Première - Administration CARGO')

@section('contenu')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-heading font-black text-cargo-dark">Ajouter une Matière Première Brute</h1>
            <p class="text-sm text-gray-500 mt-1">Configurez l'icône PNG détourée (sans fond), les métadonnées et 5 à 10 coordonnées de cours au format JSON.</p>
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

    <form action="{{ route('admin.matieres-premieres.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nom" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nom de la matière première *</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required placeholder="ex. Fèves de Cacao, Pétrole Brent, Gaz Naturel" class="w-full text-sm border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="code_symbole" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Code Symbole *</label>
                <input type="text" id="code_symbole" name="code_symbole" value="{{ old('code_symbole') }}" required placeholder="ex. CACAO, BRENT, NATGAS" class="w-full text-sm border-gray-300 rounded uppercase font-mono focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="devise" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Devise de cotation *</label>
                <input type="text" id="devise" name="devise" value="{{ old('devise', 'USD') }}" required placeholder="USD, EUR" class="w-full text-sm border-gray-300 rounded font-mono uppercase focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="unite_mesure" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Unité commerciale *</label>
                <input type="text" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure', 'Tonne') }}" required placeholder="ex. Tonne, Baril, MMBtu, Livre" class="w-full text-sm border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div>
                <label for="ordre_affichage" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Ordre d'affichage</label>
                <input type="number" id="ordre_affichage" name="ordre_affichage" value="{{ old('ordre_affichage', $nouvelOrdre) }}" min="0" class="w-full text-sm border-gray-300 rounded font-mono focus:border-cargo-red focus:ring-cargo-red" />
            </div>

            <div class="flex items-center gap-3 pt-6">
                <input type="hidden" name="est_actif" value="0">
                <input type="checkbox" id="est_actif" name="est_actif" value="1" {{ old('est_actif', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red" />
                <label for="est_actif" class="text-sm font-semibold text-gray-700">Afficher immédiatement dans le bandeau défilant</label>
            </div>
        </div>

        {{-- Transparent PNG Upload --}}
        <div class="border-t border-gray-200 pt-6">
            <label for="image_png" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Image PNG transparente sans fond *</label>
            <p class="text-xs text-gray-500 mb-2">Sélectionnez votre fichier PNG (jusqu'à 8 Mo). Il est automatiquement redimensionné à la taille optimale (96x96px) tout en conservant la transparence alpha.</p>
            <input type="file" id="image_png" name="image_png" accept="image/png,.png,image/x-png" required class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" />
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
                Collez entre 5 et 10 points. Le cours actuel correspond au dernier point et le pourcentage d'écart entrée/sortie est calculé automatiquement.
            </p>
            <textarea id="donnees_courbe_json" name="donnees_courbe_json" rows="8" required class="w-full text-xs font-mono border-gray-300 rounded focus:border-cargo-red focus:ring-cargo-red bg-gray-50 p-3 leading-relaxed">{{ old('donnees_courbe_json', $exempleJson) }}</textarea>
        </div>

        <div class="border-t border-gray-200 pt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.matieres-premieres.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800">
                Annuler
            </a>
            <button type="submit" class="bg-cargo-red hover:bg-red-700 text-white font-semibold px-6 py-2 rounded text-sm transition shadow">
                Enregistrer le produit
            </button>
        </div>
    </form>
</div>
@endsection
