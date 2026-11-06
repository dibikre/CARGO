@extends('layouts.journal')

@section('titre', 'Publish New Logistics Article — CARGO Editorial')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Logistics Journalism & HTML Publishing</span>
        <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Publish Article</h1>
    </div>
</div>

<div class="wrap" style="max-width:920px; padding-top:36px; padding-bottom:64px;">
    <form action="{{ route('redaction.articles.store') }}" method="POST" style="background:#fff; padding:36px; display:flex; flex-direction:column; gap:22px; border:1px solid #E2E5EB;">
        @csrf

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Article Title *</label>
            <input type="text" name="titre" value="{{ old('titre') }}" required placeholder="e.g. Maritime Decarbonization: Global Carriers Tackle New Fuel Directives" class="form-input">
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Section / Category *</label>
                <select name="categorie_id" required class="form-input">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Transport Mode *</label>
                <select name="mode_transport_principal" required class="form-input">
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
                <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Editorial Format *</label>
                <select name="format_article" required class="form-input">
                    <option value="article">Standard Article</option>
                    <option value="analyse">In-Depth Analysis</option>
                    <option value="breve_flash">Flash Logistics Brief</option>
                    <option value="reportage">Field Investigation</option>
                    <option value="interview">Executive Interview</option>
                    <option value="etude_de_cas">Case Study</option>
                </select>
            </div>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Standfirst / Lead Paragraph</label>
            <textarea name="chapeau" rows="2" placeholder="Executive summary or opening hook..." class="form-input" style="height:auto; padding:12px; font-family:inherit;">{{ old('chapeau') }}</textarea>
        </div>

        {{-- Lead Image Configuration --}}
        <div style="background:#F9FAFB; padding:16px; border:1px solid #E5E7EB; display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Lead Image URL</label>
                <input type="url" name="image_principale" value="{{ old('image_principale') }}" placeholder="https://..." class="form-input" style="height:42px;">
            </div>
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Image Caption</label>
                <input type="text" name="legende_image" value="{{ old('legende_image') }}" placeholder="Brief photo description" class="form-input" style="height:42px;">
            </div>
            <div>
                <label style="font-weight:700; font-size:12px; display:block; margin-bottom:4px; text-transform:uppercase;">Photo Credit</label>
                <input type="text" name="credit_photo" value="{{ old('credit_photo') }}" placeholder="e.g. CMA CGM Press" class="form-input" style="height:42px;">
            </div>
        </div>

        {{-- Rich HTML & Custom Scripting Editor --}}
        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:8px; text-transform:uppercase;">
                Article Body (Full HTML, CSS Styles, Tables, Images & Scripts) *
            </label>
            @include('redaction.composants.editeur-html', ['contenu' => old('contenu')])
        </div>

        {{-- Flags --}}
        <div style="display:flex; gap:20px; flex-wrap:wrap; background:var(--bg-light); padding:16px; border:1px solid #E2E5EB;">
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_a_la_une" value="1" {{ old('est_a_la_une') ? 'checked' : '' }}> Homepage Headline (Hero)
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_urgent" value="1" {{ old('est_urgent') ? 'checked' : '' }}> Flash Urgent Ticker
            </label>
            <label style="font-weight:600; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="est_reserve_abonnes" value="1" {{ old('est_reserve_abonnes') ? 'checked' : '' }}> Restricted to PRO Subscribers
            </label>
        </div>

        <div>
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:6px; text-transform:uppercase;">Publication Status *</label>
            <select name="statut" required class="form-input">
                <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>Draft</option>
                <option value="en_relecture" {{ old('statut') == 'en_relecture' ? 'selected' : '' }}>Editorial Review</option>
                <option value="publie" {{ old('statut') == 'publie' ? 'selected' : '' }}>Publish Immediately</option>
            </select>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; pt:16px; border-top:1px solid #E2E5EB;">
            <a href="{{ route('redaction.articles.index') }}" style="font-weight:600; color:var(--text-muted); font-size:14px;">
                Cancel
            </a>
            <button type="submit" style="background:var(--accent); color:#fff; font-weight:700; font-size:14px; padding:14px 32px; border:none; cursor:pointer; text-transform:uppercase; letter-spacing:0.05em; font-family:'Archivo',sans-serif;">
                Save & Publish
            </button>
        </div>
    </form>
</div>
@endsection
