<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titre', 'CARGO — Le Journal du Transport, de la Logistique & Supply Chain')</title>
    <meta name="description" content="@yield('meta_description', 'L\'actualité de référence du fret maritime, aérien, routier, ferroviaire et de la logistique.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;800;900&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
        }
        body { margin: 0; background: var(--bg-light); color: var(--dark); font-family: 'IBM Plex Sans', Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 1240px; margin: 0 auto; padding: 0 32px; box-sizing: border-box; }
        .dsp { font-family: 'Archivo', sans-serif; font-weight: 800; }
        .kick { font: 600 13px/1 'IBM Plex Sans', sans-serif; letter-spacing: .14em; text-transform: uppercase; }
        .card { flex: 1 1 260px; background: #fff; padding: 28px; box-sizing: border-box; display: flex; flex-direction: column; gap: 14px; transition: transform .15s ease; }
        .card:hover { transform: translateY(-2px); }
        .tile { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
        .rk { display: flex; gap: 18px; align-items: baseline; padding: 16px 0; border-bottom: 2px dashed #4A4E5C; }
        .rk b { font: 900 34px/1 'Archivo', sans-serif; color: var(--yellow); min-width: 40px; }
        .lk { padding: 18px 0; border-bottom: 2px dashed rgba(255,255,255,.6); font: 800 24px/1.2 'Archivo', sans-serif; display: block; }
        .rub { display: flex; justify-content: space-between; align-items: center; min-height: 56px; border-bottom: 3px dashed var(--dashed-border); font: 800 24px/1.1 'Archivo', sans-serif; }
        .btn-yellow { background: var(--yellow); color: var(--dark); font-weight: 600; padding: 13px 20px; display: inline-block; cursor: pointer; border: 0; }
        .btn-yellow:hover { text-decoration: none; opacity: .95; }
        .btn-outline-white { border: 2px solid #fff; color: #fff; font-weight: 600; padding: 13px 24px; display: inline-block; }
        .btn-outline-white:hover { text-decoration: none; background: rgba(255,255,255,.1); }
        .badge-alert { background: var(--dark); padding: 9px 12px; color: #fff; }
        .form-input { height: 50px; border: 2px solid var(--dark); background: #fff; padding: 0 14px; font: 400 16px 'IBM Plex Sans', sans-serif; box-sizing: border-box; width: 100%; }
        .alert-box { padding: 14px 20px; margin-bottom: 20px; font-weight: 600; font-size: 15px; }
        .alert-success { background: #DCFCE7; color: #166534; border-left: 4px solid #16A34A; }
        .alert-error { background: #FEE2E2; color: #991B1B; border-left: 4px solid #DC2626; }
        .article-html-body h1 { font-family: 'Archivo', sans-serif; font-size: 32px; font-weight: 900; margin: 32px 0 16px; color: var(--dark); line-height: 1.15; border-bottom: 2px solid var(--accent); padding-bottom: 8px; }
        .article-html-body h2 { font-family: 'Archivo', sans-serif; font-size: 26px; font-weight: 800; margin: 28px 0 14px; color: var(--dark); line-height: 1.2; }
        .article-html-body h3 { font-family: 'Archivo', sans-serif; font-size: 20px; font-weight: 700; margin: 22px 0 10px; color: var(--dark); line-height: 1.3; }
        .article-html-body p { margin: 0 0 18px; line-height: 1.8; color: #1F2128; }
        .article-html-body blockquote { border-left: 4px solid var(--accent); padding: 12px 20px; margin: 24px 0; background: #F4F1EA; font-style: italic; font-size: 19px; color: var(--dark); }
        .article-html-body table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
        .article-html-body th { background: var(--dark); color: #fff; padding: 10px 14px; text-align: left; }
        .article-html-body td { padding: 10px 14px; border-bottom: 1px solid #E5E7EB; }
        @media (max-width: 768px) {
            .wrap { padding: 0 16px; }
            .dsp-hero { font-size: 40px !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @if(isset($abonnementsActifs) && !$abonnementsActifs)
        <aside aria-label="Open access banner" style="background:#0F766E; color:#fff; font-size:13px; font-weight:700; text-align:center; padding:10px 16px; letter-spacing:0.04em;">
            CARGO OPEN ACCESS — Subscriptions are currently disabled by administration. All premium intelligence briefings, data dossiers, and analyses are 100% free for all readers.
        </aside>
    @endif
    <header style="background: var(--dark); color: #fff; border-bottom: 1px solid var(--dark-border);">
        <div class="wrap" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 32px; padding-top:16px; padding-bottom:16px;">
            <a href="{{ route('accueil') }}" aria-label="CARGO, accueil" style="display:flex; align-items:center; gap:3px; font:900 38px/1 'Archivo',sans-serif; letter-spacing:.05em;">
                CARG<svg aria-hidden="true" width="34" height="34" viewBox="0 0 34 34"><circle cx="17" cy="17" r="16" fill="var(--accent)"></circle><circle cx="17" cy="17" r="6" fill="#14151C"></circle></svg>
            </a>
            <nav style="display:flex; flex-wrap:wrap; gap:0 24px; font:600 14px 'IBM Plex Sans',sans-serif; text-transform:uppercase; letter-spacing:.08em;">
                @if(isset($categoriesMenu) && $categoriesMenu->isNotEmpty())
                    @foreach($categoriesMenu as $cat)
                        <a href="{{ route('categories.articles', $cat->slug) }}" style="padding:14px 0">{{ $cat->nom }}</a>
                    @endforeach
                @else
                    <a href="{{ route('categories.articles', 'transport-routier') }}" style="padding:14px 0">Transport</a>
                    <a href="{{ route('categories.articles', 'fret-maritime-ports') }}" style="padding:14px 0">Maritime</a>
                    <a href="{{ route('categories.articles', 'ferroviaire-intermodal') }}" style="padding:14px 0">Ferroviaire</a>
                    <a href="{{ route('categories.articles', 'supply-chain-tech') }}" style="padding:14px 0">Supply Chain</a>
                    <a href="{{ route('categories.articles', 'entreposage-logistique') }}" style="padding:14px 0">Entreposage</a>
                @endif
                <a href="{{ route('dossiers.index') }}" style="padding:14px 0">Dossiers</a>
                <a href="{{ route('evenements.index') }}" style="padding:14px 0">Agenda</a>
            </nav>
            <span style="display:flex; gap:12px; align-items:center;">
                <a href="{{ route('recherche') }}" aria-label="Search" style="width:46px; height:46px; border:1px solid #4A4E5C; display:flex; align-items:center; justify-content:center;">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-4-4"></path></svg>
                </a>
                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                    <a href="{{ route('accueil') }}" style="background:#0F766E; color:#fff; font-weight:700; padding:12px 18px; display:inline-block; font-family:'Archivo',sans-serif; text-transform:uppercase; font-size:12px; letter-spacing:0.05em; border:none;">100% Free Access</a>
                @else
                    <a href="{{ route('abonnements.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 20px; display:inline-block; font-family:'Archivo',sans-serif; text-transform:uppercase; font-size:13px; letter-spacing:0.05em; border:none;">Subscribe</a>
                @endif
                @auth
                    @if(auth()->user()->estMembreRedaction())
                        <a href="{{ route('redaction.articles.index') }}" style="background:#fff; color:var(--dark); font-weight:600; padding:12px 16px; font-size:13px;">Staff Desk</a>
                    @else
                        <a href="{{ route('abonnements.espace') }}" style="color:#C9CCD6; font-size:13px; font-weight:600;">My Account</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" style="color:#C9CCD6; font-size:13px; font-weight:600;">Sign In</a>
                @endauth
            </span>
        </div>
    </header>

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

    <footer style="background: var(--dark); color: #fff; margin-top: 64px; border-top: 4px solid var(--accent);">
        <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; padding-top:48px; padding-bottom:32px;">
            <div style="flex:2 1 280px; display:flex; flex-direction:column; gap:12px;">
                <span style="font:900 38px/1 'Archivo',sans-serif; letter-spacing:.05em; color:var(--yellow);">CARGO</span>
                <span style="max-width:320px; line-height:1.5; color:#C9CCD6;">Global freight, shipping, multimodal logistics, and supply chain intelligence.</span>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick" style="color:var(--yellow);">Sections</strong>
                <a href="{{ route('categories.articles', 'transport-routier') }}">Road Freight</a>
                <a href="{{ route('categories.articles', 'fret-maritime-ports') }}">Maritime & Ports</a>
                <a href="{{ route('categories.articles', 'ferroviaire-intermodal') }}">Rail Intermodal</a>
                <a href="{{ route('categories.articles', 'supply-chain-tech') }}">Supply Chain Tech</a>
                <a href="{{ route('categories.articles', 'entreposage-logistique') }}">Warehousing</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick">Le journal</strong>
                <a href="{{ route('dossiers.index') }}">Dossiers Spéciaux</a>
                <a href="{{ route('evenements.index') }}">Agenda Salons</a>
                <a href="{{ route('ressources.index') }}">Livres Blancs</a>
                <a href="{{ route('abonnements.index') }}">Tarifs & Offres Pro</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick">Informations</strong>
                <a href="#">Mentions légales</a>
                <a href="#">Confidentialité & RGPD</a>
                <a href="#">Gestion des cookies</a>
                <a href="#">Contact Rédaction</a>
            </div>
        </div>
        <div class="wrap" style="border-top:2px dashed rgba(255,255,255,.6); padding-top:20px; padding-bottom:24px; font-size:14px;">
            © {{ date('Y') }} CARGO — Journal professionnel de la logistique. Tous droits réservés.
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
