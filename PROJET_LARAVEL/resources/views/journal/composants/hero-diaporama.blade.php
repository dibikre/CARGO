@props(['articlesALaUne'])

@php
    $articlesListe = $articlesALaUne->take(5);
    $totalArticles = $articlesListe->count();
@endphp

<section id="diaporama-hero-section" class="hero-diaporama" aria-label="Featured logistics intelligence briefings" style="position:relative; background:var(--dark); color:#fff; overflow:hidden; min-height:560px;">
    {{-- Diaporama Background Images with 7-Second Cross-Fade --}}
    <div class="hero-fonds-conteneur" style="position:absolute; inset:0; pointer-events:none; z-index:1;">
        @foreach($articlesListe as $index => $article)
            <div class="hero-fond-slide {{ $index === 0 ? 'actif' : '' }}" 
                 data-hero-fond="{{ $index }}"
                 style="position:absolute; inset:0; background-image:url('{{ $article->url_image }}'); background-size:cover; background-position:center; opacity:{{ $index === 0 ? '1' : '0' }}; transition:opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1); transform:scale({{ $index === 0 ? '1' : '1.04' }}); transition:opacity 1.2s ease, transform 8s ease-out;">
            </div>
        @endforeach
    </div>

    {{-- Dark Contrast Scrim Overlays to Guarantee 100% Text Readability (Rule 11 & Anti-Glitch) --}}
    <div style="position:absolute; inset:0; z-index:2; pointer-events:none; background:linear-gradient(90deg, rgba(20,21,28,0.96) 0%, rgba(20,21,28,0.88) 55%, rgba(20,21,28,0.60) 100%), linear-gradient(180deg, rgba(20,21,28,0.35) 0%, rgba(20,21,28,0.95) 100%);"></div>

    <div class="wrap" style="position:relative; z-index:3; display:flex; flex-wrap:wrap; gap:40px; padding-top:48px; padding-bottom:36px; min-height:480px; align-items:stretch;">
        {{-- Left Column: Active Article Content Presentation --}}
        <div style="flex:3 1 520px; display:flex; flex-direction:column; justify-content:space-between; gap:20px;">
            <div style="position:relative; min-height:300px;">
                @foreach($articlesListe as $index => $article)
                    <div class="hero-texte-slide {{ $index === 0 ? 'actif' : '' }}"
                         data-hero-texte="{{ $index }}"
                         style="display:{{ $index === 0 ? 'flex' : 'none' }}; flex-direction:column; gap:16px; opacity:{{ $index === 0 ? '1' : '0' }}; transition:opacity 0.6s ease; width:100%;">
                        
                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px;">
                            <span class="kick" style="color:var(--yellow); background:rgba(20,21,28,0.7); padding:4px 8px; border-left:3px solid var(--accent);">
                                Featured Intelligence · {{ $article->categorie->nom ?? 'Logistics' }} · {{ strtoupper($article->mode_transport_principal) }}
                            </span>
                            @if($article->est_reserve_abonnes)
                                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                                    <span style="background:#0F766E; color:#fff; font-size:11px; font-weight:700; padding:3px 8px; text-transform:uppercase;">Open Access</span>
                                @else
                                    <span style="background:var(--yellow); color:#14151C; font-size:11px; font-weight:800; padding:3px 8px; text-transform:uppercase;">PRO</span>
                                @endif
                            @endif
                        </div>

                        <h1 class="dsp dsp-hero" style="margin:0; font-weight:900; font-size:clamp(32px, 4.2vw, 56px); line-height:1.02; letter-spacing:-.015em;">
                            <a href="{{ route('articles.show', $article->slug) }}" style="color:#fff; text-shadow:0 2px 14px rgba(0,0,0,0.85);">
                                {{ $article->titre }}
                            </a>
                        </h1>

                        <p style="margin:0; max-width:620px; font-size:18px; line-height:1.55; color:#D2D6E0; text-shadow:0 1px 6px rgba(0,0,0,0.75);">
                            {{ Str::limit($article->chapeau ?? strip_tags($article->contenu), 175) }}
                        </p>

                        <div style="display:flex; flex-wrap:wrap; gap:14px; margin-top:8px; align-items:center;">
                            <a href="{{ route('articles.show', $article->slug) }}" class="btn-yellow" style="display:inline-flex; align-items:center; gap:8px;">
                                <span>Read Full Briefing</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('articles.index') }}" class="btn-outline-white">
                                All Logistics Sections
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Slider Controls & 7-Second Progress Indicator --}}
            <div style="display:flex; align-items:center; gap:16px; padding-top:16px; border-top:1px solid rgba(255,255,255,0.15);">
                <div style="display:flex; gap:8px;">
                    <button type="button" id="hero-btn-precedent" aria-label="Previous briefing" style="width:38px; height:38px; border:1px solid rgba(255,255,255,0.3); background:rgba(0,0,0,0.4); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button type="button" id="hero-btn-suivant" aria-label="Next briefing" style="width:38px; height:38px; border:1px solid rgba(255,255,255,0.3); background:rgba(0,0,0,0.4); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                    <button type="button" id="hero-btn-pause" aria-label="Pause automatic slide transition" style="width:38px; height:38px; border:1px solid rgba(255,255,255,0.3); background:rgba(0,0,0,0.4); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;">
                        <svg id="hero-icone-pause" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                        <svg id="hero-icone-play" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="display:none;"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </button>
                </div>
                <div style="font-size:12px; font-weight:700; color:#AEB3C0; letter-spacing:0.08em; text-transform:uppercase;">
                    <span id="hero-compteur-actuel" style="color:var(--yellow); font-size:14px;">01</span> / {{ str_pad($totalArticles, 2, '0', STR_PAD_LEFT) }} · 7s Fade
                </div>
            </div>
        </div>

        {{-- Right Column: 5 Numbered Edition Tracks with Live Progress Fill --}}
        <div style="flex:1 1 300px; display:flex; flex-direction:column; justify-content:center; border-left:1px solid rgba(255,255,255,0.1); padding-left:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:2px dashed #4A4E5C; margin-bottom:12px;">
                <span class="kick" style="color:#AEB3C0;">In This Edition (01-05)</span>
                <span style="font-size:11px; font-weight:600; color:var(--yellow);">Select to View</span>
            </div>

            <div class="hero-onglets-liste" style="display:flex; flex-direction:column; gap:6px;">
                @foreach($articlesListe as $index => $article)
                    <button type="button" 
                            class="hero-onglet-bouton {{ $index === 0 ? 'actif' : '' }}" 
                            data-hero-index="{{ $index }}"
                            style="background:{{ $index === 0 ? 'rgba(255,255,255,0.08)' : 'transparent' }}; border:none; border-bottom:1px dashed rgba(255,255,255,0.15); text-align:left; color:#fff; cursor:pointer; padding:10px 12px; position:relative; overflow:hidden; transition:background .2s; width:100%;">
                        {{-- 7-Second Animated Progress Bar for the Active Tab --}}
                        <div class="hero-barre-progression" style="position:absolute; left:0; bottom:0; height:3px; background:var(--yellow); width:{{ $index === 0 ? '0%' : '0%' }};"></div>

                        <div style="display:flex; align-items:baseline; gap:12px;">
                            <b style="font-family:'Archivo',sans-serif; font-weight:900; font-size:22px; color:{{ $index === 0 ? 'var(--yellow)' : '#7C8191' }}; min-width:28px;">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </b>
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                <span class="dsp" style="font-size:15px; line-height:1.25; color:{{ $index === 0 ? '#fff' : '#AEB3C0' }}; font-weight:{{ $index === 0 ? '800' : '600' }};">
                                    {{ Str::limit($article->titre, 62) }}
                                </span>
                                <span style="font-size:11px; color:#7C8191;">
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
