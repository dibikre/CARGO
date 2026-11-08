@extends('layouts.journal')

@section('titre', ($article->meta_titre ?? $article->titre) . ' — CARGO Journal')
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->chapeau ?? $article->contenu), 160))

@section('contenu')
<article style="padding: 40px 0 64px; background: var(--bg-light);">
    <div class="wrap" style="max-width: 880px;">
        {{-- Navigation & Category Meta --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px;">
            <a href="{{ route('categories.articles', $article->categorie->slug) }}" class="kick" style="color: var(--accent); font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                {{ strtoupper($article->categorie->nom) }}
            </a>
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em;">
                <span style="background: #E2E5EB; padding: 4px 8px; border-radius: 2px;">{{ strtoupper($article->mode_transport_principal) }}</span>
                <span>·</span>
                <span>{{ $article->date_publication?->translatedFormat('d F Y') ?? 'Récent' }}</span>
            </div>
        </div>

        {{-- Urgent or Sponsored Badges --}}
        @if($article->est_urgent)
            <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--accent); color: #fff; padding: 6px 14px; font-weight: 700; font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Alerte Fret Urgente
            </div>
        @elseif($article->est_sponsorise)
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #2B2E3A; color: var(--yellow); padding: 5px 12px; font-weight: 700; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 16px;">
                Contenu Partenaire {{ $article->nom_partenaire ? '· ' . $article->nom_partenaire : '' }}
            </div>
        @elseif($article->est_reserve_abonnes && isset($abonnementsActifs) && !$abonnementsActifs)
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #0F766E; color: #fff; padding: 5px 12px; font-weight: 700; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 16px;">
                Accès Libre · Analyse Ouverte (Abonnements Désactivés)
            </div>
        @endif

        {{-- Headline --}}
        <h1 class="dsp" style="font-size: clamp(32px, 4.5vw, 48px); line-height: 1.08; margin: 0 0 20px; font-weight: 900; color: var(--dark); letter-spacing: -0.02em;">
            {{ $article->titre }}
        </h1>

        {{-- Author & Editorial Metadata Bar --}}
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; border-top: 1px solid var(--dashed-border); border-bottom: 2px solid var(--dark); padding: 14px 0; margin-bottom: 28px; font-size: 14px; color: var(--text-muted);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                    {{ strtoupper(substr($article->auteur->name ?? 'C', 0, 1)) }}
                </div>
                <div>
                    <span style="color: var(--dark); font-weight: 700;">Par {{ $article->auteur->name ?? 'La Rédaction CARGO' }}</span>
                    <span style="font-size: 12px; display: block; color: var(--text-muted);">Pôle Analyse & Fret International</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 18px; font-size: 13px; font-weight: 600;">
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $article->temps_lecture_minutes }} min de lecture
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    {{ number_format($article->vues_compteur) }} lectures
                </span>
            </div>
        </div>

        {{-- Lead Standfirst --}}
        @if($article->chapeau)
            <div style="background: #fff; border-left: 5px solid var(--accent); padding: 22px 28px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <p style="font-size: 20px; line-height: 1.55; font-weight: 500; color: #1F2128; margin: 0;">
                    {{ $article->chapeau }}
                </p>
            </div>
        @endif

        {{-- Main Feature Image --}}
        @if($article->image_principale)
            <figure style="margin: 0 0 36px; padding: 0;">
                <img src="{{ $article->url_image }}" alt="{{ $article->titre }}" style="width: 100%; height: auto; max-height: 520px; object-fit: cover; display: block; border: 1px solid #D5D8E0;">
                @if($article->legende_image || $article->credit_photo)
                    <figcaption style="font-size: 12px; line-height: 1.4; color: var(--text-muted); padding: 10px 4px 0; display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px dashed var(--dashed-border); padding-bottom: 8px;">
                        <span>{{ $article->legende_image }}</span>
                        @if($article->credit_photo)
                            <span style="font-weight: 600;">Photo: {{ $article->credit_photo }}</span>
                        @endif
                    </figcaption>
                @endif
            </figure>
        @endif

        {{-- Article Body Content with Full HTML, CSS & Scripting Support --}}
        @if($aAccesComplet)
            <div class="article-html-body" style="font-size: 18px; line-height: 1.8; color: #1F2128; background: #fff; padding: 36px; border: 1px solid #E2E5EB;">
                @if(Str::contains($article->contenu, ['<p>', '<div', '<table', '<figure', '<style', '<script', '<h1', '<h2', '<h3', '<ul', '<ol', '<blockquote']))
                    {!! $article->contenu !!}
                @else
                    {!! nl2br(e($article->contenu)) !!}
                @endif
            </div>
        @else
            <div style="position: relative; background: #fff; padding: 36px 36px 80px; border: 1px solid #E2E5EB;">
                <div class="article-html-body" style="font-size: 18px; line-height: 1.8; color: #1F2128; mask-image: linear-gradient(to bottom, black 40%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);">
                    @if(Str::contains($article->contenu, ['<p>', '<div', '<table', '<figure', '<style', '<script', '<h1', '<h2', '<h3', '<ul', '<ol', '<blockquote']))
                        {!! Str::limit(strip_tags($article->contenu, '<p><div><h1><h2><h3><h4><br><strong><em><b><i>'), 450) !!}
                    @else
                        {!! nl2br(e(Str::limit($article->contenu, 450))) !!}
                    @endif
                </div>

                {{-- Paywall Éditorial --}}
                <div style="margin-top: -30px; background: var(--dark); color: #fff; padding: 40px; text-align: center; border-top: 4px solid var(--accent); box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.08); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <span class="kick" style="color: var(--yellow); font-size: 12px; display: block; margin-bottom: 8px;">Contenu Éditorial Réservé</span>
                    <h3 class="dsp" style="font-size: 28px; line-height: 1.15; margin: 0 0 14px; color: #fff;">
                        Cette enquête approfondie est réservée aux abonnés CARGO PRO
                    </h3>
                    <p style="color: #C9CCD6; max-width: 540px; margin: 0 auto 24px; font-size: 15px; line-height: 1.6;">
                        Accédez à l'intégralité de l'article, aux grilles comparatives de taux de fret, aux prévisions de congestion et aux données logistiques exclusives.
                    </p>
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 16px;">
                        <a href="{{ route('abonnements.index') }}" style="background: var(--accent); color: #fff; font-weight: 700; padding: 14px 32px; display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--police-titre); font-size: calc(13.5px * var(--echelle-texte));">
                            S'abonner à CARGO PRO
                        </a>
                        <a href="{{ route('login') }}" style="color: #fff; font-weight: 600; font-size: 14px; text-decoration: underline; padding: 8px 12px;">
                            Connexion Abonné
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tags & Supply Chain Corridors --}}
        <div style="margin-top: 40px; padding: 24px; background: #fff; border: 1px solid #E2E5EB;">
            <div class="kick" style="color: var(--dark); margin-bottom: 12px; font-size: 11px;">
                {{ ($corridorsActifs ?? true) && $article->corridors->isNotEmpty() ? 'Mots-clés & Corridors sous surveillance' : 'Mots-clés de l\'article' }}
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach($article->tags as $tag)
                    <a href="{{ route('tags.articles', $tag->slug) }}" style="background: #F1F3F7; border: 1px solid #D1D5DB; padding: 6px 14px; font-size: 12px; font-weight: 600; color: var(--dark);">
                        #{{ $tag->nom }}
                    </a>
                @endforeach
                @if($corridorsActifs ?? true)
                    @foreach($article->corridors as $corridor)
                        <a href="{{ route('corridors.articles', $corridor->slug) }}" style="background: var(--yellow); color: #14151C; padding: 6px 14px; font-size: 12px; font-weight: 700;">
                            Corridor : {{ $corridor->nom }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Section Commentaires --}}
        <section style="margin-top: 56px; border-top: 3px solid var(--dark); padding-top: 36px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 class="dsp" style="font-size: 26px; margin: 0; color: var(--dark);">
                    Retours terrain & Débats ({{ $commentaires->count() }})
                </h2>
            </div>

            @if(session('succes'))
                <div class="alert-box alert-success" style="margin-bottom: 24px;">
                    {{ session('succes') }}
                </div>
            @endif

            {{-- Formulaire de commentaire --}}
            <form action="{{ route('commentaires.store') }}" method="POST" style="background: #fff; padding: 28px; border: 1px solid #E2E5EB; margin-bottom: 36px; display: flex; flex-direction: column; gap: 16px;">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">

                @guest
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Nom complet / Organisation *</label>
                            <input type="text" name="auteur_nom" required placeholder="ex: Jean Dupont (CMA CGM)" class="form-input">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">E-mail professionnel *</label>
                            <input type="email" name="auteur_email" required placeholder="nom@entreprise.com" class="form-input">
                        </div>
                    </div>
                @endguest

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Votre retour ou analyse opérationnelle *</label>
                    <textarea name="contenu" required rows="4" placeholder="Partagez vos retours sur la situation portuaire, les coûts de passage ou vos contraintes logistiques..." class="form-input" style="height: auto; padding: 12px; font-family: inherit;"></textarea>
                </div>

                <div>
                    <button type="submit" style="background: var(--accent); color: #fff; font-weight: 700; padding: 12px 26px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--police-titre); font-size: calc(13px * var(--echelle-texte));">
                        Publier le commentaire
                    </button>
                </div>
            </form>

            {{-- Fil des commentaires --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($commentaires as $com)
                    <div style="background: #fff; padding: 22px 24px; border: 1px solid #E2E5EB; border-left: 4px solid var(--dark);">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 14px; margin-bottom: 10px;">
                            <strong style="color: var(--dark); font-size: 15px;">{{ $com->auteur_nom }}</strong>
                            <span style="color: var(--text-muted); font-size: 12px;">{{ $com->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="margin: 0 0 14px; font-size: 15px; line-height: 1.6; color: #2B2E3A;">{{ $com->contenu }}</p>
                        <form action="{{ route('commentaires.voter', $com->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" style="background: #F4F1EA; border: 1px solid #D5D8E0; padding: 5px 12px; cursor: pointer; font-size: 12px; font-weight: 600; color: var(--dark); display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>
                                Pertinent ({{ $com->votes_positifs }})
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="background: #fff; padding: 32px; text-align: center; border: 1px dashed var(--dashed-border); color: var(--text-muted);">
                        Aucun commentaire publié pour le moment. Soyez le premier à partager votre éclairage sur cet article.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Articles Similaires --}}
        @if($articlesSimilaires->isNotEmpty())
            <section style="margin-top: 64px; border-top: 3px dashed var(--dashed-border); padding-top: 36px;">
                <div class="kick" style="color: var(--accent); margin-bottom: 8px;">Lectures recommandées</div>
                <h2 class="dsp" style="font-size: 26px; margin: 0 0 24px; color: var(--dark);">Sur le même sujet logistique</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                    @foreach($articlesSimilaires as $similaire)
                        <a href="{{ route('articles.show', $similaire->slug) }}" class="card" style="border: 1px solid #E2E5EB; text-decoration: none;">
                            <span class="kick" style="color: var(--accent); font-size: 11px;">{{ $similaire->categorie->nom }}</span>
                            <h3 class="dsp" style="font-size: 18px; line-height: 1.25; margin: 0; color: var(--dark);">{{ $similaire->titre }}</h3>
                            <div style="margin-top: auto; font-size: 12px; color: var(--text-muted); display: flex; justify-content: space-between;">
                                <span>{{ $similaire->temps_lecture_minutes }} min de lecture</span>
                                <span>{{ $similaire->date_publication?->translatedFormat('d M Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</article>
@endsection
