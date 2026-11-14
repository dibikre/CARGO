@extends('redaction.layouts.redaction')

@section('titre', 'Modifier l\'article #' . $article->id . ' — CARGO Rédaction')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Espace Rédaction &bull; Édition d'article</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Modifier l'article #{{ $article->id }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @if($article->statut === 'publie')
                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="bg-white hover:bg-gray-50 text-blue-600 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Voir en ligne</span>
                </a>
            @endif
            <a href="{{ route('redaction.articles.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Retour aux articles</span>
            </a>
        </div>
    </div>

    <div class="max-w-4xl">
    <form action="{{ route('redaction.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" style="background:#fff; padding:36px; display:flex; flex-direction:column; gap:22px; border:1px solid #E2E5EB;">
        @csrf
        @method('PUT')

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Titre de l'article *</label>
            <input type="text" name="titre" value="{{ old('titre', $article->titre) }}" required class="form-input">
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Rubrique / Catégorie *</label>
                <select name="categorie_id" required class="form-input">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Mode de transport *</label>
                <select name="mode_transport_principal" required class="form-input">
                    @foreach(['multimodal' => 'Multimodal', 'maritime' => 'Maritime', 'aerien' => 'Fret aérien', 'routier' => 'Fret routier', 'ferroviaire' => 'Fret ferroviaire', 'entreposage' => 'Entreposage', 'douanes' => 'Douanes & Réglementation'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('mode_transport_principal', $article->mode_transport_principal) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Format éditorial *</label>
                <select name="format_article" required class="form-input">
                    @foreach(['article' => 'Article standard', 'analyse' => 'Analyse approfondie', 'breve_flash' => 'Brève logistique', 'reportage' => 'Reportage terrain', 'interview' => 'Entretien dirigeant', 'etude_de_cas' => 'Étude de cas'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('format_article', $article->format_article) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Chapeau / Accroche</label>
            <textarea name="chapeau" rows="2" class="form-input" style="height:auto; padding:12px; font-family:inherit;">{{ old('chapeau', $article->chapeau) }}</textarea>
        </div>

        {{-- Configuration de l'image principale : Téléversement local structuré ou URL rapatriée --}}
        <div style="background:#F9FAFB; padding:18px; border:1px solid #E5E7EB; display:flex; flex-direction:column; gap:14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #E5E7EB; padding-bottom:8px;">
                <span class="kick" style="color:var(--dark); font-size:11px; font-weight:700;">Image éditoriale à la une</span>
                <span style="font-size:11px; color:#166534; background:#DCFCE7; padding:2px 8px; border-radius:4px; font-weight:600;">Conversion automatique en WebP compressé (.webp)</span>
            </div>

            @if($article->image_principale)
                <div style="display:flex; align-items:center; gap:14px; background:#fff; padding:10px; border:1px solid #E2E5EB;">
                    <img src="{{ $article->url_image }}" alt="Image actuelle" style="width:80px; height:50px; object-fit:cover; border:1px solid #D5D8E0;">
                    <div style="font-size:12px;">
                        <span style="font-weight:700; color:var(--dark); display:block;">Fichier actif actuel :</span>
                        <code style="color:var(--text-muted);">{{ $article->image_principale }}</code>
                    </div>
                </div>
            @endif

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                <div>
                    <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Téléverser un nouveau fichier image</label>
                    <input type="file" name="fichier_image" accept="image/*" class="form-input" style="height:42px; padding:6px 10px;">
                    <p style="font-size:10px; color:#6B7280; margin:4px 0 0;">Les images sont automatiquement converties au format WebP compressé.</p>
                </div>
                <div>
                    <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Ou chemin local / URL distante</label>
                    <input type="text" name="image_principale" value="{{ old('image_principale', $article->image_principale) }}" placeholder="images/articles/... ou https://..." class="form-input" style="height:42px;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                <div>
                    <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Légende de l'image</label>
                    <input type="text" name="legende_image" value="{{ old('legende_image', $article->legende_image) }}" class="form-input" style="height:42px;">
                </div>
                <div>
                    <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Crédit photo</label>
                    <input type="text" name="credit_photo" value="{{ old('credit_photo', $article->credit_photo) }}" class="form-input" style="height:42px;">
                </div>
            </div>
        </div>

        {{-- Éditeur HTML riche --}}
        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:8px; text-transform:uppercase;">
                Corps de l'article (HTML complet, styles CSS, tableaux, images) *
            </label>
            @include('redaction.composants.editeur-html', ['contenu' => old('contenu', $article->contenu)])
        </div>

        {{-- Sélection des étiquettes (Max 4) --}}
        <div style="background:#F9FAFB; padding:18px; border:1px solid #E5E7EB;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <div>
                    <label style="font-weight:700; font-size:13px; text-transform:uppercase; display:block;">
                        Thématiques &amp; Étiquettes marques <span style="color:var(--accent); font-weight:800;">(Max 4)</span>
                    </label>
                    <span style="font-size:11px; color:var(--text-muted);">Sélectionnez jusqu'à 4 étiquettes (ex. DHL, Lufthansa, FedEx, CARGO, Air France...)</span>
                </div>
                <span id="tags-counter" style="font-size:12px; font-weight:700; font-family:monospace; padding:3px 8px; border-radius:3px; background:#fff; border:1px solid #D5D8E0;">
                    0 / 4 sélectionnées
                </span>
            </div>
            @error('tags') <p style="color:#DC2626; font-size:12px; margin:4px 0;">{{ $message }}</p> @enderror
            @php $tagsSelectionnes = old('tags', $article->tags->pluck('id')->toArray()); @endphp
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:8px; max-height:180px; overflow-y:auto; padding:6px; background:#fff; border:1px solid #E5E7EB;">
                @foreach($tags as $tag)
                    @php $estCoche = in_array($tag->id, $tagsSelectionnes); @endphp
                    <label class="tag-checkbox-label" style="display:flex; align-items:center; gap:8px; font-size:12px; font-weight:600; padding:6px 10px; border:1px solid {{ $estCoche ? 'var(--accent)' : '#E5E7EB' }}; background:{{ $estCoche ? '#FEF2F2' : '#FAFAFA' }}; color:{{ $estCoche ? 'var(--accent)' : 'var(--dark)' }}; cursor:pointer; border-radius:3px;">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $estCoche ? 'checked' : '' }} class="tag-checkbox">
                        <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $tag->nom }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Options de mise en avant --}}
        <div style="display:flex; gap:20px; flex-wrap:wrap; background:var(--bg-light); padding:16px; border:1px solid #E2E5EB;">
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_a_la_une" value="1" {{ old('est_a_la_une', $article->est_a_la_une) ? 'checked' : '' }}> À la une (Page d'accueil)
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_urgent" value="1" {{ old('est_urgent', $article->est_urgent) ? 'checked' : '' }}> Bandeau Flash Urgent
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_reserve_abonnes" value="1" {{ old('est_reserve_abonnes', $article->est_reserve_abonnes) ? 'checked' : '' }}> Réservé aux abonnés PRO
            </label>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Statut de publication *</label>
            <select name="statut" required class="form-input">
                <option value="brouillon" {{ old('statut', $article->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="en_relecture" {{ old('statut', $article->statut) == 'en_relecture' ? 'selected' : '' }}>En relecture éditoriale</option>
                <option value="publie" {{ old('statut', $article->statut) == 'publie' ? 'selected' : '' }}>Publié en ligne</option>
                <option value="archive" {{ old('statut', $article->statut) == 'archive' ? 'selected' : '' }}>Archivé</option>
            </select>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; pt:16px; border-top:1px solid #E2E5EB;">
            <a href="{{ route('redaction.articles.index') }}" style="font-weight:600; color:var(--text-muted); font-size:14px;">
                Annuler
            </a>
            <button type="submit" style="background:var(--accent); color:#fff; font-weight:700; font-size:14px; padding:14px 32px; border:none; cursor:pointer; text-transform:uppercase; letter-spacing:0.05em; font-family:'Archivo',sans-serif;">
                Enregistrer les modifications
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
                counter.textContent = `${checked.length} / ${maxTags} sélectionnées`;
                counter.style.borderColor = checked.length >= maxTags ? '#DC2626' : '#D5D8E0';
                counter.style.color = checked.length >= maxTags ? '#DC2626' : 'var(--dark)';
            }
            checkboxes.forEach(cb => {
                const label = cb.closest('.tag-checkbox-label');
                if (cb.checked) {
                    if (label) {
                        label.style.borderColor = 'var(--accent)';
                        label.style.background = '#FEF2F2';
                        label.style.color = 'var(--accent)';
                    }
                    cb.disabled = false;
                } else {
                    if (label) {
                        label.style.borderColor = '#E5E7EB';
                        label.style.background = '#FAFAFA';
                        label.style.color = 'var(--dark)';
                    }
                    cb.disabled = checked.length >= maxTags;
                    if (label) {
                        label.style.opacity = cb.disabled ? '0.5' : '1';
                        label.style.cursor = cb.disabled ? 'not-allowed' : 'pointer';
                    }
                }
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateTags));
        updateTags();
    });
</script>
    </div>
</div>
@endsection
