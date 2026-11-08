@props(['articlesALaUne'])

@php
    $articlesListe = $articlesALaUne->take(5);
    $totalArticles = $articlesListe->count();
@endphp

<section id="diaporama-hero-section" class="hero-diaporama" aria-label="Featured logistics intelligence briefings" style="position:relative; background:var(--dark); color:#fff; overflow:hidden; min-height:440px;">
    {{-- Diaporama Background Images with 7-Second Cross-Fade --}}
    <div class="hero-fonds-conteneur" style="position:absolute; inset:0; pointer-events:none; z-index:1;">
        @foreach($articlesListe as $index => $article)
            <div class="hero-fond-slide {{ $index === 0 ? 'actif' : '' }}" 
                 data-hero-fond="{{ $index }}"
                 style="position:absolute; inset:0; background-image:url('{{ $article->url_image }}'); background-size:cover; background-position:center; opacity:{{ $index === 0 ? '1' : '0' }}; transition:opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 8s ease-out; transform:scale({{ $index === 0 ? '1' : '1.04' }});">
            </div>
        @endforeach
    </div>

    {{-- Dark Contrast Scrim Overlays to Guarantee 100% Text Readability --}}
    <div style="position:absolute; inset:0; z-index:2; pointer-events:none; background:linear-gradient(90deg, rgba(20,21,28,0.96) 0%, rgba(20,21,28,0.88) 55%, rgba(20,21,28,0.60) 100%), linear-gradient(180deg, rgba(20,21,28,0.35) 0%, rgba(20,21,28,0.95) 100%);"></div>

    <div class="wrap" style="position:relative; z-index:3; display:flex; flex-wrap:wrap; gap:32px; padding-top:28px; padding-bottom:28px; min-height:380px; align-items:center;">
        {{-- Left Column: Active Article Content Presentation (Fixed Coordinates to Prevent Text Jumping) --}}
        <div style="flex:3 1 520px; display:flex; flex-direction:column; justify-content:center;">
            <div class="hero-textes-conteneur" style="position:relative; width:100%; min-height:290px;">
                @foreach($articlesListe as $index => $article)
                    <div class="hero-texte-slide {{ $index === 0 ? 'actif' : '' }}"
                         data-hero-texte="{{ $index }}"
                         style="position:absolute; top:0; left:0; width:100%; display:flex; flex-direction:column; gap:14px; opacity:{{ $index === 0 ? '1' : '0' }}; pointer-events:{{ $index === 0 ? 'auto' : 'none' }}; z-index:{{ $index === 0 ? '2' : '1' }}; transition:opacity 0.65s cubic-bezier(0.4, 0, 0.2, 1);">
                        
                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px;">
                            <span class="kick" style="color:var(--yellow); background:rgba(20,21,28,0.7); padding:4px 8px; border-left:3px solid var(--accent); font-size:calc(11.5px * var(--echelle-texte));">
                                Featured Intelligence · {{ $article->categorie->nom ?? 'Logistics' }} · {{ strtoupper($article->mode_transport_principal) }}
                            </span>
                            @if($article->est_reserve_abonnes)
                                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                                    <span style="background:#0F766E; color:#fff; font-size:calc(11px * var(--echelle-texte)); font-weight:700; padding:3px 8px; text-transform:uppercase;">Open Access</span>
                                @else
                                    <span style="background:var(--yellow); color:#14151C; font-size:calc(11px * var(--echelle-texte)); font-weight:800; padding:3px 8px; text-transform:uppercase;">PRO</span>
                                @endif
                            @endif
                        </div>

                        <h1 class="dsp dsp-hero" style="margin:0; font-weight:900; font-size:clamp(calc(26px * var(--echelle-texte)), 3.5vw, calc(46px * var(--echelle-texte))); line-height:1.06; letter-spacing:-.015em; min-height:2.12em; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            <a href="{{ route('articles.show', $article->slug) }}" style="color:#fff; text-shadow:0 2px 14px rgba(0,0,0,0.85);">
                                {{ $article->titre }}
                            </a>
                        </h1>

                        <p style="margin:0; max-width:620px; font-size:calc(16.5px * var(--echelle-texte)); line-height:1.5; color:#D2D6E0; text-shadow:0 1px 6px rgba(0,0,0,0.75); min-height:3em; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            {{ Str::limit($article->chapeau ?? strip_tags($article->contenu), 170) }}
                        </p>

                        <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:2px; align-items:center;">
                            <a href="{{ route('articles.show', $article->slug) }}" class="btn-yellow" style="display:inline-flex; align-items:center; gap:8px;">
                                <span>Lire l'article complet</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('articles.index') }}" class="btn-outline-white">
                                Toutes les rubriques
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Colonne droite : 5 pistes de l'édition avec barre de défilement --}}
        <div style="flex:1 1 300px; display:flex; flex-direction:column; justify-content:center; border-left:1px solid rgba(255,255,255,0.1); padding-left:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:8px; border-bottom:2px dashed #4A4E5C; margin-bottom:8px;">
                <span class="kick" style="color:#AEB3C0; font-size:calc(11.5px * var(--echelle-texte));">Dans cette édition (01-05)</span>
                <span style="font-size:calc(11px * var(--echelle-texte)); font-weight:600; color:var(--yellow);">Sélectionner</span>
            </div>

            <div class="hero-onglets-liste" style="display:flex; flex-direction:column; gap:4px;">
                @foreach($articlesListe as $index => $article)
                    <button type="button" 
                            class="hero-onglet-bouton {{ $index === 0 ? 'actif' : '' }}" 
                            data-hero-index="{{ $index }}"
                            style="background:{{ $index === 0 ? 'rgba(255,255,255,0.08)' : 'transparent' }}; border:none; border-bottom:1px dashed rgba(255,255,255,0.15); text-align:left; color:#fff; cursor:pointer; padding:8px 10px; position:relative; overflow:hidden; transition:background .2s; width:100%;">
                        {{-- 7-Second Animated Progress Bar for the Active Tab --}}
                        <div class="hero-barre-progression" style="position:absolute; left:0; bottom:0; height:3px; background:var(--yellow); width:{{ $index === 0 ? '0%' : '0%' }};"></div>

                        <div style="display:flex; align-items:baseline; gap:10px;">
                            <b style="font-family:var(--police-titre); font-weight:900; font-size:calc(20px * var(--echelle-texte)); color:{{ $index === 0 ? 'var(--yellow)' : '#7C8191' }}; min-width:24px;">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </b>
                            <div style="display:flex; flex-direction:column; gap:1px;">
                                <span class="dsp" style="font-size:calc(14px * var(--echelle-texte)); line-height:1.25; color:{{ $index === 0 ? '#fff' : '#AEB3C0' }}; font-weight:{{ $index === 0 ? '800' : '600' }};">
                                    {{ Str::limit($article->titre, 60) }}
                                </span>
                                <span style="font-size:calc(11px * var(--echelle-texte)); color:#7C8191;">
                                    {{ $article->categorie->nom ?? 'Sector' }} · {{ $article->temps_lecture_minutes }}m
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Slider Script: Handles 7-Second Automatic Fade & Manual Selection --}}
@push('scripts')
<script src="{{ asset('js/journal/hero-diaporama.js') }}"></script>
@endpush
