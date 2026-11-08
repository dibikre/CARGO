@extends('administration.layouts.admin')

@section('titre', 'Modifier l\'article #' . $article->id . ' — Administration')

@section('contenu')
<div class="space-y-6 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-gray-500 hover:text-cargo-red uppercase tracking-wider">
                    &larr; Gestion des articles
                </a>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">
                Edit Article: {{ Str::limit($article->titre, 48) }}
            </h1>
            <p class="text-xs text-gray-500 uppercase tracking-wider">
                Article #{{ $article->id }} &bull; Author: {{ $article->auteur->name ?? 'Staff' }} &bull; Created {{ $article->created_at->format('M d, Y') }} &bull; <span class="text-gray-900 font-bold font-mono">{{ number_format($article->vues_compteur) }} Unique IP Views</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="text-xs font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 border border-gray-300 rounded bg-white inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Live
            </a>
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 border border-gray-300 rounded bg-white">
                Cancel
            </a>
        </div>
    </div>

    {{-- Edit Form --}}
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-lg border border-gray-200 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Article Title <span class="text-cargo-red">*</span>
            </label>
            <input type="text" name="titre" value="{{ old('titre', $article->titre) }}" required
                   class="w-full bg-gray-50 border border-gray-300 rounded px-3.5 py-2.5 text-sm text-gray-900 focus:ring-1 focus:ring-cargo-red focus:bg-white focus:outline-none">
            @error('titre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Selectors Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Section / Category <span class="text-cargo-red">*</span>
                </label>
                <select name="categorie_id" required class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                @error('categorie_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Transport Mode <span class="text-cargo-red">*</span>
                </label>
                <select name="mode_transport_principal" required class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    @foreach(['multimodal' => 'Multimodal', 'maritime' => 'Maritime', 'aerien' => 'Air Cargo', 'routier' => 'Road Freight', 'ferroviaire' => 'Rail Intermodal', 'entreposage' => 'Warehousing', 'douanes' => 'Customs & Regulatory'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('mode_transport_principal', $article->mode_transport_principal) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Editorial Format <span class="text-cargo-red">*</span>
                </label>
                <select name="format_article" required class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    @foreach(['article' => 'Standard Article', 'analyse' => 'In-Depth Analysis', 'breve_flash' => 'Flash Logistics Brief', 'reportage' => 'Field Investigation', 'interview' => 'Executive Interview', 'etude_de_cas' => 'Case Study'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('format_article', $article->format_article) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Lead Chapeau --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Executive Standfirst / Lead Hook
            </label>
            <textarea name="chapeau" rows="2"
                      class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:bg-white focus:outline-none">{{ old('chapeau', $article->chapeau) }}</textarea>
        </div>

        {{-- Featured Image Box --}}
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Lead Editorial Image</span>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                    Auto-converted to compressed WebP (.webp)
                </span>
            </div>

            @if($article->image_principale)
                <div class="flex items-center gap-3 bg-white p-2.5 rounded border border-gray-200">
                    <img src="{{ $article->url_image }}" alt="Current image" class="w-20 h-12 object-cover rounded border border-gray-200">
                    <div class="text-xs">
                        <span class="font-bold text-gray-800 block">Current Active Path:</span>
                        <code class="text-gray-500 text-[11px]">{{ $article->image_principale }}</code>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Replace with New Local File (Auto-WebP)</label>
                    <input type="file" name="fichier_image" accept="image/*"
                           class="w-full bg-white border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none">
                    <p class="text-[10px] text-gray-500 mt-1">Uploaded images are automatically converted to lightweight WebP.</p>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Or Path / Remote URL</label>
                    <input type="text" name="image_principale" value="{{ old('image_principale', $article->image_principale) }}"
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Image Caption</label>
                    <input type="text" name="legende_image" value="{{ old('legende_image', $article->legende_image) }}"
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Photo Credit</label>
                    <input type="text" name="credit_photo" value="{{ old('credit_photo', $article->credit_photo) }}"
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Rich HTML & Visual Editor --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-800 mb-2">
                Article Body (Visual WYSIWYG & HTML Editor) <span class="text-cargo-red">*</span>
            </label>
            @include('redaction.composants.editeur-html', ['contenu' => old('contenu', $article->contenu)])
            @error('contenu') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tags Selection (Max 4) --}}
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2">
            <div class="flex items-center justify-between">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-800">
                        Article Topics &amp; Brand Tags <span class="text-xs text-cargo-red font-bold">(Max 4)</span>
                    </label>
                    <p class="text-[11px] text-gray-500">Select up to 4 tags (e.g. DHL, Lufthansa, FedEx, CARGO, Air France...)</p>
                </div>
                <span id="tags-counter" class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-700">
                    0 / 4 Selected
                </span>
            </div>
            @error('tags') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            @php $tagsSelectionnes = old('tags', $article->tags->pluck('id')->toArray()); @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 max-h-48 overflow-y-auto">
                @foreach($tags as $tag)
                    @php $estCoche = in_array($tag->id, $tagsSelectionnes); @endphp
                    <label class="tag-checkbox-label flex items-center gap-2 p-2 rounded border text-xs cursor-pointer transition {{ $estCoche ? 'bg-red-50 border-cargo-red text-cargo-red font-semibold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $estCoche ? 'checked' : '' }}
                               class="tag-checkbox rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                        <span class="truncate">{{ $tag->nom }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Control Badges / Flags --}}
        <div class="bg-gray-50 p-4 rounded border border-gray-200 flex flex-wrap gap-6 items-center">
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-800">
                <input type="checkbox" name="est_a_la_une" value="1" {{ old('est_a_la_une', $article->est_a_la_une) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <span>Featured Headline (Hero)</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-800">
                <input type="checkbox" name="est_urgent" value="1" {{ old('est_urgent', $article->est_urgent) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <span>Breaking Urgent Ticker</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-800">
                <input type="checkbox" name="est_reserve_abonnes" value="1" {{ old('est_reserve_abonnes', $article->est_reserve_abonnes) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <span>Restricted to PRO Subscribers</span>
            </label>
        </div>

        {{-- Publication Status --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Publication Status <span class="text-cargo-red">*</span>
            </label>
            <select name="statut" required class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <option value="publie" {{ old('statut', $article->statut) === 'publie' ? 'selected' : '' }}>Published Live</option>
                <option value="brouillon" {{ old('statut', $article->statut) === 'brouillon' ? 'selected' : '' }}>Draft</option>
                <option value="en_relecture" {{ old('statut', $article->statut) === 'en_relecture' ? 'selected' : '' }}>Editorial Review</option>
                <option value="archive" {{ old('statut', $article->statut) === 'archive' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-6 py-3 rounded shadow transition">
                Update Article as Administrator
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.tag-checkbox');
        const counter = document.getElementById('tags-counter');
        const maxTags = 4;

        function updateTags() {
            const checked = document.querySelectorAll('.tag-checkbox:checked');
            if (counter) {
                counter.textContent = `${checked.length} / ${maxTags} Selected`;
                counter.className = checked.length >= maxTags
                    ? 'text-xs font-mono font-bold px-2 py-0.5 rounded bg-red-100 border border-red-200 text-cargo-red'
                    : 'text-xs font-mono font-bold px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-700';
            }
            checkboxes.forEach(cb => {
                const label = cb.closest('.tag-checkbox-label');
                if (cb.checked) {
                    label?.classList.add('bg-red-50', 'border-cargo-red', 'text-cargo-red', 'font-semibold');
                    label?.classList.remove('bg-white', 'border-gray-200', 'text-gray-700');
                    cb.disabled = false;
                } else {
                    label?.classList.remove('bg-red-50', 'border-cargo-red', 'text-cargo-red', 'font-semibold');
                    label?.classList.add('bg-white', 'border-gray-200', 'text-gray-700');
                    cb.disabled = checked.length >= maxTags;
                    if (cb.disabled) {
                        label?.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        label?.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateTags));
        updateTags();
    });
</script>
@endsection
