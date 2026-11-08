@props(['article', 'theme' => 'clair'])

@php
    $estSombre = $theme === 'sombre';
    $mode = $article->mode_transport_principal ?? 'multimodal';
@endphp

<article class="card">
    @if($article->image_principale)
        <div style="margin: -28px -28px 14px -28px; overflow:hidden; max-height:140px; border-bottom:2px solid var(--accent);">
            <img src="{{ $article->url_image }}" alt="{{ $article->titre }}" style="width:100%; height:140px; object-fit:cover; display:block;">
        </div>
    @else
        <span class="tile" style="background: {{ $estSombre ? '#14151C' : 'var(--accent)' }}">
            @if($mode === 'maritime')
                <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                    <path d="M3 30h42l-6 10H9zM10 30V20h16v10M28 30V14h6v16"></path>
                </svg>
            @elseif($mode === 'ferroviaire')
                <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="3" stroke-linejoin="round">
                    <path d="M8 8h32v26H8zM8 24h32M16 40l-4 6M32 40l4 6"></path><circle cx="16" cy="30" r="2"></circle><circle cx="32" cy="30" r="2"></circle>
                </svg>
            @elseif($mode === 'aerien')
                <svg aria-hidden="true" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $estSombre ? 'var(--yellow)' : '#fff' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21 4 19.5 2.5S16 2 14.5 3.5L11 7 2.8 5.2c-.5-.1-.9.1-1.2.4l-.5.5c-.4.4-.3 1.1.2 1.4L7 12l-2 3H2l-1 2 3.5 1 1 3.5 2-1v-3l3-2 4.5 5.7c.3.5 1 .6 1.4.2l.5-.5c.3-.3.5-.7.4-1.2z"></path>
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
    @endif

    <span class="kick" style="color: var(--accent-dark);">
        {{ $article->categorie->nom ?? 'Logistique' }}
        @if($article->est_reserve_abonnes)
            @if(isset($abonnementsActifs) && !$abonnementsActifs)
                · <span style="background:#0F766E; color:#fff; padding:2px 6px; font-weight:700;">ACCÈS LIBRE</span>
            @else
                · <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-weight:700;">PRO</span>
            @endif
        @endif
    </span>

    <h3 class="dsp" style="margin:0; font-size:24px; line-height:1.15;">
        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->titre }}</a>
    </h3>

    <span style="font-size:14px; color:var(--text-muted); margin-top:auto;">
        Par {{ $article->auteur->name ?? 'La Rédaction' }} · {{ $article->temps_lecture_minutes }} min de lecture
    </span>
</article>
