<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <title>@yield('titre', 'CARGO — Le Journal du Transport, de la Logistique & Supply Chain')</title>
    <meta name="description" content="@yield('meta_description', 'L\'actualité de référence du fret maritime, aérien, routier, ferroviaire et de la logistique.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #D4201A;
            --accent-dark: #B3140F;
            --dark: #14151C;
            --dark-border: #2B2E3A;
            --yellow: #FFC93C;
            --bg-light: #EEF0F3;
            --text-muted: #4B505C;
            --dashed-border: #B8BDC8;
            --police-texte: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --police-titre: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            --echelle-texte: 0.995; /* Réduction uniforme de 0,5% de la taille de texte de l'ensemble des pages visiteurs */
        }
        html {
            font-size: 99.5%; /* Réduction de 0,5% du référentiel typographique global sans exclusion */
            -webkit-text-size-adjust: 99.5%;
            text-size-adjust: 99.5%;
        }
        body {
            margin: 0;
            background: var(--bg-light);
            color: var(--dark);
            font-family: var(--police-texte);
            font-size: 99.5%;
            letter-spacing: -0.011em;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        a { color: inherit; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 1240px; margin: 0 auto; padding: 0 32px; box-sizing: border-box; }
        .dsp { font-family: var(--police-titre); font-weight: 800; letter-spacing: -0.015em; }
        .kick { font: 700 calc(13px * var(--echelle-texte))/1 var(--police-texte); letter-spacing: .12em; text-transform: uppercase; }
        .card { flex: 1 1 260px; background: #fff; padding: 26px; box-sizing: border-box; display: flex; flex-direction: column; gap: 14px; transition: transform .15s ease; }
        .card:hover { transform: translateY(-2px); }
        .tile { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; }
        .rk { display: flex; gap: 18px; align-items: baseline; padding: 16px 0; border-bottom: 2px dashed #4A4E5C; }
        .rk b { font: 900 calc(34px * var(--echelle-texte))/1 var(--police-titre); color: var(--yellow); min-width: 40px; }
        .lk { padding: 18px 0; border-bottom: 2px dashed rgba(255,255,255,.6); font: 800 calc(24px * var(--echelle-texte))/1.2 var(--police-titre); display: block; }
        .rub { display: flex; justify-content: space-between; align-items: center; min-height: 54px; border-bottom: 3px dashed var(--dashed-border); font: 800 calc(24px * var(--echelle-texte))/1.1 var(--police-titre); }
        .btn-yellow { background: var(--yellow); color: var(--dark); font-weight: 700; font-family: var(--police-texte); font-size: calc(13.5px * var(--echelle-texte)); padding: 12px 20px; display: inline-block; cursor: pointer; border: 0; }
        .btn-yellow:hover { text-decoration: none; opacity: .95; }
        .btn-outline-white { border: 2px solid #fff; color: #fff; font-weight: 700; font-family: var(--police-texte); font-size: calc(13.5px * var(--echelle-texte)); padding: 12px 24px; display: inline-block; }
        .btn-outline-white:hover { text-decoration: none; background: rgba(255,255,255,.1); }
        .badge-alert { background: var(--dark); padding: 8px 12px; color: #fff; }
        .form-input { height: 48px; border: 2px solid var(--dark); background: #fff; padding: 0 14px; font: 400 calc(15.5px * var(--echelle-texte)) var(--police-texte); box-sizing: border-box; width: 100%; }
        .alert-box { padding: 14px 20px; margin-bottom: 20px; font-weight: 600; font-size: calc(14.5px * var(--echelle-texte)); font-family: var(--police-texte); }
        .alert-success { background: #DCFCE7; color: #166534; border-left: 4px solid #16A34A; }
        .alert-error { background: #FEE2E2; color: #991B1B; border-left: 4px solid #DC2626; }
        .article-html-body h1 { font-family: var(--police-titre); font-size: calc(31.5px * var(--echelle-texte)); font-weight: 900; margin: 32px 0 16px; color: var(--dark); line-height: 1.15; border-bottom: 2px solid var(--accent); padding-bottom: 8px; letter-spacing: -0.02em; }
        .article-html-body h2 { font-family: var(--police-titre); font-size: calc(25.5px * var(--echelle-texte)); font-weight: 800; margin: 28px 0 14px; color: var(--dark); line-height: 1.2; letter-spacing: -0.015em; }
        .article-html-body h3 { font-family: var(--police-titre); font-size: calc(19.5px * var(--echelle-texte)); font-weight: 700; margin: 22px 0 10px; color: var(--dark); line-height: 1.3; }
        .article-html-body p { margin: 0 0 18px; font-size: calc(16.5px * var(--echelle-texte)); line-height: 1.75; color: #1F2128; font-family: var(--police-texte); }
        .article-html-body blockquote { border-left: 4px solid var(--accent); padding: 14px 20px; margin: 24px 0; background: #F4F1EA; font-style: italic; font-size: calc(18.5px * var(--echelle-texte)); line-height: 1.6; color: var(--dark); font-family: var(--police-texte); }
        .article-html-body table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: calc(13.8px * var(--echelle-texte)); font-family: var(--police-texte); }
        .article-html-body th { background: var(--dark); color: #fff; padding: 10px 14px; text-align: left; }
        .article-html-body td { padding: 10px 14px; border-bottom: 1px solid #E5E7EB; }
        @media (max-width: 768px) {
            .wrap { padding: 0 16px; }
            .dsp-hero { font-size: calc(38px * var(--echelle-texte)) !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @if(isset($abonnementsActifs) && !$abonnementsActifs)
        <aside aria-label="Bandeau accès libre" style="background:#0F766E; color:#fff; font-size:calc(12.5px * var(--echelle-texte)); font-weight:700; text-align:center; padding:9px 16px; letter-spacing:0.04em;">
            CARGO ACCÈS LIBRE — Les formules d'abonnement sont temporairement suspendues par la rédaction. Toutes les analyses, enquêtes et dossiers stratégiques sont 100% en accès libre.
        </aside>
    @endif
    <header style="background: var(--dark); color: #fff; border-bottom: 1px solid var(--dark-border);">
        <div class="wrap" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 32px; padding-top:14px; padding-bottom:14px;">
            <a href="{{ route('accueil') }}" aria-label="CARGO, accueil" style="display:flex; align-items:center; gap:3px; font:900 calc(36px * var(--echelle-texte))/1 var(--police-titre); letter-spacing:.04em;">
                CARG<svg aria-hidden="true" width="32" height="32" viewBox="0 0 34 34"><circle cx="17" cy="17" r="16" fill="var(--accent)"></circle><circle cx="17" cy="17" r="6" fill="#14151C"></circle></svg>
            </a>
            <nav style="display:flex; flex-wrap:wrap; gap:0 24px; font:700 calc(13.5px * var(--echelle-texte)) var(--police-texte); text-transform:uppercase; letter-spacing:.08em;">
                @if(isset($categoriesMenu) && $categoriesMenu->isNotEmpty())
                    @foreach($categoriesMenu as $cat)
                        <a href="{{ route('categories.articles', $cat->slug) }}" style="padding:12px 0">{{ $cat->nom }}</a>
                    @endforeach
                @else
                    <a href="{{ route('categories.articles', 'transport-routier') }}" style="padding:12px 0">Transport</a>
                    <a href="{{ route('categories.articles', 'fret-maritime-ports') }}" style="padding:12px 0">Maritime</a>
                    <a href="{{ route('categories.articles', 'ferroviaire-intermodal') }}" style="padding:12px 0">Ferroviaire</a>
                    <a href="{{ route('categories.articles', 'supply-chain-tech') }}" style="padding:12px 0">Supply Chain</a>
                    <a href="{{ route('categories.articles', 'entreposage-logistique') }}" style="padding:12px 0">Entreposage</a>
                @endif
                <a href="{{ route('dossiers.index') }}" style="padding:12px 0">Dossiers</a>
                <a href="{{ route('evenements.index') }}" style="padding:12px 0">Agenda</a>
            </nav>
            <span style="display:flex; gap:12px; align-items:center;">
                @include('journal.composants.barre-recherche-extensible')
                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                    <a href="{{ route('accueil') }}" style="background:#0F766E; color:#fff; font-weight:700; padding:11px 16px; display:inline-block; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(11.8px * var(--echelle-texte)); letter-spacing:0.05em; border:none;">Accès Libre 100%</a>
                @else
                    <a href="{{ route('abonnements.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:11px 18px; display:inline-block; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); letter-spacing:0.05em; border:none;">S'abonner</a>
                @endif
                @auth
                    @if(auth()->user()->estMembreRedaction())
                        <a href="{{ route('redaction.articles.index') }}" style="background:#fff; color:var(--dark); font-weight:700; padding:11px 15px; font-size:calc(12.5px * var(--echelle-texte));">Espace Rédaction</a>
                    @else
                        <a href="{{ route('abonnements.espace') }}" style="color:#C9CCD6; font-size:calc(12.5px * var(--echelle-texte)); font-weight:600;">Mon Compte</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" style="color:#C9CCD6; font-size:calc(12.5px * var(--echelle-texte)); font-weight:600;">Se connecter</a>
                @endauth
            </span>
        </div>
        {{-- [TEMPORAIRE - CONCEPTION] Boutons d'accès rapide aux portails à retirer à la fin de la conception --}}
        <div style="background:#1A1C26; border-top:1px solid var(--dark-border);">
            <div class="wrap" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-end; gap:8px; padding-top:8px; padding-bottom:8px;">
                <span style="font-size:calc(11px * var(--echelle-texte)); font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#8E93A4; margin-right:auto;">Accès conception :</span>
                <a href="{{ route('admin.connexion') }}" style="background:var(--accent); color:#fff; font-family:var(--police-titre); font-weight:700; font-size:calc(11.5px * var(--echelle-texte)); text-transform:uppercase; letter-spacing:0.05em; padding:6px 12px; border:1px solid var(--accent); display:inline-block;">Connexion Admin</a>
                <a href="{{ route('login') }}" style="background:var(--yellow); color:var(--dark); font-family:var(--police-titre); font-weight:700; font-size:calc(11.5px * var(--echelle-texte)); text-transform:uppercase; letter-spacing:0.05em; padding:6px 12px; border:1px solid var(--yellow); display:inline-block;">Connexion Lecteur PRO</a>
                <a href="{{ route('redaction.connexion') }}" style="background:#262936; color:#fff; font-family:var(--police-titre); font-weight:700; font-size:calc(11.5px * var(--echelle-texte)); text-transform:uppercase; letter-spacing:0.05em; padding:6px 12px; border:1px solid #4A4E5C; display:inline-block;">Connexion Journaliste</a>
            </div>
        </div>
    </header>

    {{-- Bandeau defilant des matieres premieres brutes (non clicable, 20 produits max) --}}
    @include('journal.composants.bandeau-cours-matieres')

    @if(session('succes'))
        <div class="wrap" style="padding-top: 16px;">
            <div class="alert-box alert-success">{{ session('succes') }}</div>
        </div>
    @endif
    @if(session('erreur'))
        <div class="wrap" style="padding-top: 16px;">
            <div class="alert-box alert-error">{{ session('erreur') }}</div>
        </div>
    @endif

    <main>
        @yield('contenu')
    </main>

    <footer style="background: var(--dark); color: #fff; margin-top: 56px; border-top: 4px solid var(--accent);">
        <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; padding-top:44px; padding-bottom:28px;">
            <div style="flex:2 1 280px; display:flex; flex-direction:column; gap:12px;">
                <span style="font:900 calc(36px * var(--echelle-texte))/1 var(--police-titre); letter-spacing:.04em; color:var(--yellow);">CARGO</span>
                <span style="max-width:320px; line-height:1.5; color:#C9CCD6; font-size:calc(13.8px * var(--echelle-texte));">Le média de référence pour le fret maritime, routier, ferroviaire, aérien et la veille logistique stratégique.</span>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px; font-size:calc(13.8px * var(--echelle-texte));">
                <strong class="kick" style="color:var(--yellow);">Rubriques</strong>
                <a href="{{ route('categories.articles', 'transport-routier') }}">Transport Routier</a>
                <a href="{{ route('categories.articles', 'fret-maritime-ports') }}">Maritime & Ports</a>
                <a href="{{ route('categories.articles', 'ferroviaire-intermodal') }}">Ferroviaire & Rail</a>
                <a href="{{ route('categories.articles', 'supply-chain-tech') }}">Supply Chain Tech</a>
                <a href="{{ route('categories.articles', 'entreposage-logistique') }}">Entreposage & Hubs</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px; font-size:calc(13.8px * var(--echelle-texte));">
                <strong class="kick">Le journal</strong>
                <a href="{{ route('dossiers.index') }}">Dossiers Spéciaux</a>
                <a href="{{ route('evenements.index') }}">Agenda Salons</a>
                <a href="{{ route('ressources.index') }}">Livres Blancs</a>
                <a href="{{ route('abonnements.index') }}">Tarifs & Offres Pro</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px; font-size:calc(13.8px * var(--echelle-texte));">
                <strong class="kick">Informations</strong>
                <a href="#">Mentions légales</a>
                <a href="#">Confidentialité & RGPD</a>
                <a href="#">Gestion des cookies</a>
                <a href="#">Contact Rédaction</a>
            </div>
        </div>
        <div class="wrap" style="border-top:2px dashed rgba(255,255,255,.6); padding-top:18px; padding-bottom:22px; font-size:calc(13.5px * var(--echelle-texte));">
            © {{ date('Y') }} CARGO — Journal professionnel de la logistique. Tous droits réservés.
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
