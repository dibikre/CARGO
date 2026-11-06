@extends('layouts.journal')

@section('titre', 'CARGO — Le Journal de la Logistique, du Fret et de la Supply Chain')

@section('contenu')
{{-- Section Hero animé avec transition en fondu (fade) toutes les 7 secondes entre les 5 articles à la une --}}
@include('journal.composants.hero-diaporama', ['articlesALaUne' => $articlesALaUne])

    {{-- Illustration camion CARGO de transition --}}
    <div style="background:var(--dark); padding-bottom:16px;">
        <div class="wrap" style="display:flex; justify-content:flex-end;">
            <svg viewBox="0 0 600 150" aria-hidden="true" style="display:block; width:min(100%,780px);">
                <g fill="var(--accent)"><rect x="10" y="22" width="390" height="128"></rect><path d="M410 150V54Q410 46 420 46H492L546 96Q558 108 558 122V150Z"></path></g>
                <path d="M10 118h390" stroke="rgba(0,0,0,.2)" stroke-width="6"></path>
                <text x="38" y="100" font-family="Archivo,sans-serif" font-weight="900" font-size="52" letter-spacing="3" fill="#fff">CARGO</text>
                <rect x="412" y="12" width="9" height="40" fill="#14151C"></rect>
                <path d="M428 58h60l38 36H428z" fill="#14151C"></path>
                <rect x="548" y="114" width="10" height="10" fill="#FFC93C"></rect>
                <path d="M64 132h44M452 132h60" stroke="#14151C" stroke-width="36" stroke-linecap="round"></path>
                <path d="M64 132h44M452 132h60" stroke="#E6E8EC" stroke-width="12" stroke-linecap="round" stroke-dasharray="0 44"></path>
            </svg>
        </div>
    </div>

{{-- Bandeau En Bref --}}
@include('journal.composants.bandeau-urgent', ['alertes' => $alertesUrgentes])

{{-- 5 Pôles Sectoriels de Fret : Maritime, Routier, Ferroviaire, Aérien, Mondial --}}
{{-- Chacun avec son SVG vectoriel dédié, son article majeur centré à gauche, et les 4 suivants récents à droite --}}
@foreach($sectionsFret as $modeCle => $sectionData)
    @include('journal.composants.section-fret', [
        'modeCle' => $modeCle,
        'config' => $sectionData['config'],
        'centre' => $sectionData['centre'],
        'suivants' => $sectionData['suivants']
    ])
@endforeach

{{-- Illustration train de fret conteneurs intermodal --}}
<div class="wrap" style="display:flex; justify-content:flex-start; margin-top:40px; margin-bottom:40px;">
    <svg viewBox="0 0 800 170" aria-hidden="true" style="display:block; width:min(100%,940px);">
        <g fill="var(--accent)"><path d="M20 112H736L784 82L704 170H72Z"></path><rect x="46" y="38" width="76" height="76"></rect><rect x="60" y="14" width="26" height="26"></rect></g>
        <path d="M60 14h26v8H60z" fill="#FFC93C"></path>
        <path d="M104 38V6" stroke="#14151C" stroke-width="3"></path>
        <path d="M56 54h56M56 76h58M56 94h58" stroke="#14151C" stroke-width="7" stroke-dasharray="9 6"></path>
        <path d="M44 130H730" stroke="rgba(0,0,0,.2)" stroke-width="5"></path>
        <path fill="#14151C" d="M136 90h54v22h-54zM316 90h54v22h-54zM496 90h54v22h-54zM196 68h54v22h-54zM376 68h54v22h-54zM256 46h54v22h-54zM436 46h54v22h-54z"></path>
        <path fill="#FFC93C" d="M196 90h54v22h-54zM436 90h54v22h-54zM256 68h54v22h-54zM496 68h54v22h-54zM316 46h54v22h-54z"></path>
        <path fill="#F2F2F2" d="M256 90h54v22h-54zM376 90h54v22h-54zM316 68h54v22h-54zM436 68h54v22h-54zM376 46h54v22h-54z"></path>
        <path fill="#7E100B" d="M556 90h54v22h-54z"></path>
    </svg>
</div>

{{-- Section Dossier Spécial (Fond Rouge CARGO) --}}
@php $dossierVedette = $dossiersVedettes->first(); @endphp
<section style="background:var(--accent); color:#fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; align-items:center; padding-top:56px; padding-bottom:64px;">
        <div style="flex:1 1 420px; display:flex; flex-direction:column; gap:18px;">
            <span class="kick badge-alert" style="align-self:flex-start;">Dossier spécial</span>
            <h2 class="dsp" style="margin:0; font-weight:900; font-size:52px; line-height:1;">
                {{ $dossierVedette?->titre ?? 'Ports et corridors : la carte des flux' }}
            </h2>
            <p style="margin:0; font-size:18px; line-height:1.55;">
                {{ $dossierVedette?->chapeau ?? 'Analyses exclusives, entretiens avec les armateurs et cartes pour comprendre comment circulent les marchandises mondiales.' }}
            </p>
            <a href="{{ $dossierVedette ? route('dossiers.show', $dossierVedette->slug) : route('dossiers.index') }}" class="btn-yellow" style="align-self:flex-start;">
                Explorer le dossier →
            </a>
        </div>

        <div style="flex:1 1 340px; display:flex; flex-direction:column; border-top:2px dashed rgba(255,255,255,.6);">
            <a href="#" class="lk">1 · Les grands hubs portuaires en mutation</a>
            <a href="#" class="lk">2 · Rail et fluvial : alternatives au tout-routier</a>
            <a href="#" class="lk">3 · Zones logistiques : implanter, équiper, connecter</a>
        </div>
    </div>
</section>

{{-- Section Les plus lus + Le courrier du lundi --}}
<section style="background:#fff; padding-top:64px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:32px;">
        <div style="flex:3 1 460px;">
            @include('journal.composants.classement-plus-lus', ['articlesPlusLus' => $articlesRecents->sortByDesc('vues_compteur')->take(4)])
        </div>
        <div style="flex:2 1 320px;">
            @include('journal.composants.bloc-newsletter')
        </div>
    </div>

    {{-- Illustration semi-remorques à quai de la maquette --}}
    <div class="wrap" style="display:flex; justify-content:center; margin-top:56px;">
        <svg viewBox="0 0 760 150" aria-hidden="true" style="display:block; width:min(100%,860px);">
            <g fill="var(--accent)"><path d="M474 150V34H690Q730 34 742 78V150Z"></path><rect x="12" y="52" width="216" height="98"></rect><rect x="240" y="52" width="216" height="98"></rect></g>
            <path d="M12 128h216M240 128h216" stroke="rgba(0,0,0,.2)" stroke-width="6"></path>
            <text x="30" y="106" font-family="Archivo,sans-serif" font-weight="900" font-size="48" letter-spacing="3" fill="#fff">CARGO</text>
            <text x="258" y="106" font-family="Archivo,sans-serif" font-weight="900" font-size="48" letter-spacing="3" fill="#fff">CARGO</text>
            <path d="M228 118h12M456 118h18" stroke="#14151C" stroke-width="6"></path>
            <path d="M494 58h60v26h-60z" fill="#14151C"></path>
            <path d="M640 46h46q20 4 28 30h-74z" fill="#14151C"></path>
            <path d="M474 104h268v10H474z" fill="#FFC93C"></path>
            <rect x="732" y="120" width="10" height="10" fill="#FFE08A"></rect>
            <path d="M566 34L590 12H624M624 12L600 34" stroke="#14151C" stroke-width="3" fill="none"></path>
            <path d="M40 138h36M164 138h36M268 138h36M392 138h36M502 138h36M664 138h36" stroke="#14151C" stroke-width="24" stroke-linecap="round"></path>
            <path d="M40 138h36M164 138h36M268 138h36M392 138h36M502 138h36M664 138h36" stroke="#E6E8EC" stroke-width="8" stroke-linecap="round" stroke-dasharray="0 36"></path>
        </svg>
    </div>
</section>
@endsection
