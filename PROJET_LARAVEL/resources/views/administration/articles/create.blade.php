@extends('administration.layouts.admin')

@section('titre', 'Rédiger un article — Administration')

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
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Rédiger un article éditorial</h1>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Direct administrator publication & emergency dispatching</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 border border-gray-300 rounded bg-white">
            Cancel
        </a>
    </div>

    {{-- Creation Form --}}
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-lg border border-gray-200 shadow-sm space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Article Title <span class="text-cargo-red">*</span>
            </label>
            <input type="text" name="titre" value="{{ old('titre') }}" required
                   placeholder="e.g. Asia-Europe Ocean Freight Rates Slide 14% on New Mega-Vessel Influx"
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
                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                @error('categorie_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Transport Mode <span class="text-cargo-red">*</span>
                </label>
                <select name="mode_transport_principal" required class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="multimodal">Multimodal</option>
                    <option value="maritime">Maritime</option>
                    <option value="aerien">Air Cargo</option>
                    <option value="routier">Road Freight</option>
                    <option value="ferroviaire">Rail Intermodal</option>
                    <option value="entreposage">Warehousing</option>
                    <option value="douanes">Customs & Regulatory</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Editorial Format <span class="text-cargo-red">*</span>
                </label>
                <select name="format_article" required class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="article">Standard Article</option>
                    <option value="analyse">In-Depth Analysis</option>
                    <option value="breve_flash">Flash Logistics Brief</option>
                    <option value="reportage">Field Investigation</option>
                    <option value="interview">Executive Interview</option>
                    <option value="etude_de_cas">Case Study</option>
                </select>
            </div>
        </div>

        {{-- Lead Chapeau --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Executive Standfirst / Lead Hook
            </label>
            <textarea name="chapeau" rows="2" placeholder="Brief executive summary or high-impact opening context..."
                      class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:ring-1 focus:ring-cargo-red focus:bg-white focus:outline-none">{{ old('chapeau') }}</textarea>
        </div>

        {{-- Featured Image Box --}}
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Lead Editorial Image</span>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                    Auto-converted to compressed WebP (.webp)
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Upload Local Image (JPEG, PNG, WebP)</label>
                    <input type="file" name="fichier_image" accept="image/*"
                           class="w-full bg-white border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none">
                    <p class="text-[10px] text-gray-500 mt-1">Image will be automatically converted to high-efficiency WebP format.</p>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Or Path / Remote URL</label>
                    <input type="text" name="image_principale" value="{{ old('image_principale') }}" placeholder="images/articles/... or https://..."
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Image Caption</label>
                    <input type="text" name="legende_image" value="{{ old('legende_image') }}" placeholder="Brief photo caption"
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Photo Credit</label>
                    <input type="text" name="credit_photo" value="{{ old('credit_photo') }}" placeholder="e.g. Port of Rotterdam Press"
                           class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Rich HTML & Visual Editor --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-800 mb-2">
                Article Body (Visual WYSIWYG & HTML Editor) <span class="text-cargo-red">*</span>
            </label>
            @include('redaction.composants.editeur-html', ['contenu' => old('contenu')])
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
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 max-h-48 overflow-y-auto">
                @foreach($tags as $tag)
                    @php $estCoche = in_array($tag->id, old('tags', [])); @endphp
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
                <input type="checkbox" name="est_a_la_une" value="1" {{ old('est_a_la_une') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <span>Featured Headline (Hero)</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-800">
                <input type="checkbox" name="est_urgent" value="1" {{ old('est_urgent') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <span>Breaking Urgent Ticker</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-800">
                <input type="checkbox" name="est_reserve_abonnes" value="1" {{ old('est_reserve_abonnes') ? 'checked' : '' }}
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
                <option value="publie" {{ old('statut', 'publie') === 'publie' ? 'selected' : '' }}>Publish Immediately (Live)</option>
                <option value="brouillon" {{ old('statut') === 'brouillon' ? 'selected' : '' }}>Save as Draft</option>
                <option value="en_relecture" {{ old('statut') === 'en_relecture' ? 'selected' : '' }}>Editorial Review</option>
                <option value="archive" {{ old('statut') === 'archive' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-6 py-3 rounded shadow transition">
                Save &amp; Publish as Administrator
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
