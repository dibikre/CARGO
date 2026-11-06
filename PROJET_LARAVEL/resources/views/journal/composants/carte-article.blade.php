@props(['article', 'theme' => 'clair'])

@php
    $estSombre = $theme === 'sombre';
    $mode = $article->mode_transport_principal ?? 'multimodal';
@endphp

<article class="card">
    <span class="tile" style="background: {{ $estSombre ? '#14151C' : 'var(--accent)' }}">
        @if($mode === 'maritime')
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                <path d="M3 30h42l-6 10H9zM10 30V20h16v10M28 30V14h6v16"></path>
            </svg>
        @elseif($mode === 'ferroviaire')
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                <path d="M8 8h32v26H8zM8 24h32M16 40l-4 6M32 40l4 6"></path><circle cx="16" cy="30" r="2"></circle><circle cx="32" cy="30" r="2"></circle>
            </svg>
        @elseif($mode === 'e-commerce' || $mode === 'entreposage')
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                <path d="M6 14l18-8 18 8v20l-18 8-18-8zM6 14l18 8 18-8M24 22v20"></path>
            </svg>
        @else
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                <path d="M2 14h26v18H2zM28 20h10l6 7v5H28z"></path><circle cx="12" cy="35" r="4"></circle><circle cx="36" cy="35" r="4"></circle>
            </svg>
        @endif
    </span>

    <span class="kick" style="color: var(--accent-dark);">
        {{ $article->categorie->nom ?? 'Logistique' }}
        @if($article->est_reserve_abonnes)
            · <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-weight:700;">PRO</span>
        @endif
    </span>

    <h3 class="dsp" style="margin:0; font-size:24px; line-height:1.15;">
        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->titre }}</a>
    </h3>

    <span style="font-size:14px; color:var(--text-muted); margin-top:auto;">
        Par {{ $article->auteur->name ?? 'La Rédaction' }} · {{ $article->temps_lecture_minutes }} min de lecture
    </span>
</article>
