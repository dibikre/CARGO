@extends('layouts.journal')

@section('titre', 'Edit Article #' . $article->id . ' — CARGO Editorial')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Logistics Journalism & HTML Publishing</span>
        <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Edit Article: {{ Str::limit($article->titre, 40) }}</h1>
    </div>
</div>

<div class="wrap" style="max-width:920px; padding-top:36px; padding-bottom:64px;">
    <form action="{{ route('redaction.articles.update', $article->id) }}" method="POST" style="background:#fff; padding:36px; display:flex; flex-direction:column; gap:22px; border:1px solid #E2E5EB;">
        @csrf
        @method('PUT')

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Article Title *</label>
            <input type="text" name="titre" value="{{ old('titre', $article->titre) }}" required class="form-input">
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Section / Category *</label>
                <select name="categorie_id" required class="form-input">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Transport Mode *</label>
                <select name="mode_transport_principal" required class="form-input">
                    @foreach(['multimodal' => 'Multimodal', 'maritime' => 'Maritime', 'aerien' => 'Air Cargo', 'routier' => 'Road Freight', 'ferroviaire' => 'Rail Intermodal', 'entreposage' => 'Warehousing', 'douanes' => 'Customs & Regulatory'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('mode_transport_principal', $article->mode_transport_principal) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Editorial Format *</label>
                <select name="format_article" required class="form-input">
                    @foreach(['article' => 'Standard Article', 'analyse' => 'In-Depth Analysis', 'breve_flash' => 'Flash Logistics Brief', 'reportage' => 'Field Investigation', 'interview' => 'Executive Interview', 'etude_de_cas' => 'Case Study'] as $val => $libelle)
                        <option value="{{ $val }}" {{ old('format_article', $article->format_article) === $val ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Standfirst / Lead Paragraph</label>
            <textarea name="chapeau" rows="2" class="form-input" style="height:auto; padding:12px; font-family:inherit;">{{ old('chapeau', $article->chapeau) }}</textarea>
        </div>

        {{-- Lead Image Configuration --}}
        <div style="background:#F9FAFB; padding:16px; border:1px solid #E5E7EB; display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Lead Image URL</label>
                <input type="url" name="image_principale" value="{{ old('image_principale', $article->image_principale) }}" class="form-input" style="height:42px;">
            </div>
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Image Caption</label>
                <input type="text" name="legende_image" value="{{ old('legende_image', $article->legende_image) }}" class="form-input" style="height:42px;">
            </div>
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Photo Credit</label>
                <input type="text" name="credit_photo" value="{{ old('credit_photo', $article->credit_photo) }}" class="form-input" style="height:42px;">
            </div>
        </div>

        {{-- Rich HTML & Custom Scripting Editor --}}
        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:8px; text-transform:uppercase;">
                Article Body (Full HTML, CSS Styles, Tables, Images & Scripts) *
            </label>
            @include('redaction.composants.editeur-html', ['contenu' => old('contenu', $article->contenu)])
        </div>

        {{-- Flags --}}
        <div style="display:flex; gap:20px; flex-wrap:wrap; background:var(--bg-light); padding:16px; border:1px solid #E2E5EB;">
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_a_la_une" value="1" {{ old('est_a_la_une', $article->est_a_la_une) ? 'checked' : '' }}> Homepage Headline (Hero)
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_urgent" value="1" {{ old('est_urgent', $article->est_urgent) ? 'checked' : '' }}> Flash Urgent Ticker
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_reserve_abonnes" value="1" {{ old('est_reserve_abonnes', $article->est_reserve_abonnes) ? 'checked' : '' }}> Restricted to PRO Subscribers
            </label>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Publication Status *</label>
            <select name="statut" required class="form-input">
                <option value="brouillon" {{ old('statut', $article->statut) == 'brouillon' ? 'selected' : '' }}>Draft</option>
                <option value="en_relecture" {{ old('statut', $article->statut) == 'en_relecture' ? 'selected' : '' }}>Editorial Review</option>
                <option value="publie" {{ old('statut', $article->statut) == 'publie' ? 'selected' : '' }}>Published Live</option>
                <option value="archive" {{ old('statut', $article->statut) == 'archive' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; pt:16px; border-top:1px solid #E2E5EB;">
            <a href="{{ route('redaction.articles.index') }}" style="font-weight:600; color:var(--text-muted); font-size:14px;">
                Cancel
            </a>
            <button type="submit" style="background:var(--accent); color:#fff; font-weight:700; font-size:14px; padding:14px 32px; border:none; cursor:pointer; text-transform:uppercase; letter-spacing:0.05em; font-family:'Archivo',sans-serif;">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
