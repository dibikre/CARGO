@extends('layouts.journal')

@section('titre', 'CARGO — Le Journal de la Logistique, du Fret et de la Supply Chain')

@section('contenu')
{{-- Section Hero animé avec transition en fondu (fade) toutes les 7 secondes entre les 5 articles à la une --}}
@include('journal.composants.hero-diaporama', ['articlesALaUne' => $articlesALaUne])

{{-- Bandeau En Bref --}}
@include('journal.composants.bandeau-urgent', ['alertes' => $alertesUrgentes])

{{-- Disposition Principale avec Colonne Gauche et Aside à Droite --}}
<div class="wrap" style="padding-top:36px; padding-bottom:56px;">
    <div style="display:flex; flex-wrap:wrap; gap:36px; align-items:flex-start;">
        
        {{-- Colonne Principale Gauche : Pôles Sectoriels de Fret --}}
        <div style="flex:1 1 740px; min-width:0; display:flex; flex-direction:column; gap:36px;">
            @foreach($sectionsFret as $modeCle => $sectionData)
                @include('journal.composants.section-fret', [
                    'modeCle' => $modeCle,
                    'config' => $sectionData['config'],
                    'centre' => $sectionData['centre'],
                    'suivants' => $sectionData['suivants']
                ])
            @endforeach
        </div>

        {{-- Aside Droite : Top 6 Meilleurs Articles de la Semaine + Vidéos Logistiques Embarquées --}}
        <aside style="flex:0 0 350px; max-width:100%; display:flex; flex-direction:column; gap:32px; position:sticky; top:20px;">
            {{-- Bloc Haut : Les 6 meilleurs articles de la semaine (les plus vus) --}}
            @include('journal.composants.bloc-top-semaine', ['articlesTopSemaine' => $articlesTopSemaine])

            {{-- Bloc Bas : Vidéos logistiques embarquées (embeds modifiables par les publicateurs) --}}
            @include('journal.composants.bloc-videos-embed', ['videosLogistiques' => $videosLogistiques])

            {{-- Bloc 10 Meilleurs Tags : Mots-clés ayant le plus d'articles associés --}}
            @include('journal.composants.bloc-meilleurs-tags', ['meilleursTags' => $meilleursTags])
        </aside>

    </div>
</div>

{{-- Section Dossier Spécial (Fond Rouge CARGO) --}}
@php $dossierVedette = $dossiersVedettes->first(); @endphp
<section style="background:var(--accent); color:#fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; align-items:center; padding-top:52px; padding-bottom:56px;">
        <div style="flex:1 1 420px; display:flex; flex-direction:column; gap:16px;">
            <span class="kick badge-alert" style="align-self:flex-start;">Dossier spécial</span>
            <h2 class="dsp" style="margin:0; font-weight:900; font-size:calc(46px * var(--echelle-texte)); line-height:1;">
                {{ $dossierVedette?->titre ?? 'Ports et corridors : la carte des flux' }}
            </h2>
            <p style="margin:0; font-size:calc(17px * var(--echelle-texte)); line-height:1.55;">
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

{{-- Section Newsletter & Documentation Technique Téléchargeable --}}
<section style="background:#fff; padding-top:56px; padding-bottom:56px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:36px; align-items:stretch;">
        <div style="flex:1 1 460px;">
            @include('journal.composants.bloc-newsletter')
        </div>
        <div style="flex:1 1 460px; background:#F8FAFC; border:1px solid #E2E5EB; padding:36px; box-sizing:border-box; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
            <div>
                <span class="kick" style="color:var(--accent);">Documentation & Études Métier</span>
                <h3 class="dsp" style="margin:6px 0 10px; font-size:calc(28px * var(--echelle-texte)); line-height:1.2;">Livres Blancs, Guides Tarifaires & Données Fret</h3>
                <p style="margin:0; color:var(--text-muted); font-size:calc(14.5px * var(--echelle-texte)); line-height:1.55;">
                    Téléchargez les dossiers techniques, grilles comparatives de douane et baromètres trimestriels de corridors logistiques rédigés par la rédaction CARGO.
                </p>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                <a href="{{ route('ressources.index') }}" style="background:var(--dark); color:#fff; font-family:var(--police-titre); font-weight:700; font-size:calc(13px * var(--echelle-texte)); padding:12px 20px; text-decoration:none; text-transform:uppercase; letter-spacing:0.05em;">
                    Consulter les livres blancs
                </a>
                <a href="{{ route('dossiers.index') }}" style="border:2px solid var(--dark); color:var(--dark); font-family:var(--police-titre); font-weight:700; font-size:calc(13px * var(--echelle-texte)); padding:10px 18px; text-decoration:none; text-transform:uppercase; letter-spacing:0.05em;">
                    Voir les dossiers spéciaux
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
