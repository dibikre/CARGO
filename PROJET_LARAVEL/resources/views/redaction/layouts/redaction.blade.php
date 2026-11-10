<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titre', 'Editorial Desk') — CARGO Newsroom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cargo: {
                            dark: '#14151C',
                            surface: '#1D1E28',
                            surfaceLight: '#262835',
                            accent: '#D4201A',
                            accentDark: '#B3140F',
                            red: '#D4201A',
                            redDark: '#B3140F',
                            yellow: '#FFC93C',
                            muted: '#4B505C',
                            border: '#2B2E3A',
                            borderLight: '#E2E5EB',
                            bg: '#F5F6F8'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --accent: #D4201A;
            --accent-dark: #B3140F;
            --dark: #14151C;
            --dark-border: #2B2E3A;
            --yellow: #FFC93C;
            --bg-light: #F5F6F8;
            --text-muted: #4B505C;
            --dashed-border: #B8BDC8;
            --police-texte: 'Plus Jakarta Sans', -apple-system, sans-serif;
            --police-titre: 'Outfit', sans-serif;
        }
        .dsp { font-family: var(--police-titre); font-weight: 800; letter-spacing: -0.015em; }
        .kick { font: 700 12px/1 var(--police-texte); letter-spacing: .12em; text-transform: uppercase; }
        .form-input { height: 44px; border: 1px solid #D5D8E0; background: #fff; padding: 0 14px; font: 400 14px var(--police-texte); box-sizing: border-box; width: 100%; border-radius: 4px; }
        .form-input:focus { outline: none; border-color: var(--accent); }
    </style>
    @stack('styles')
</head>
<body class="bg-cargo-bg text-cargo-dark font-sans antialiased h-full overflow-hidden flex flex-col md:flex-row">

    {{-- Mobile Header with Drawer Toggle --}}
    <header class="md:hidden bg-cargo-dark text-white px-4 py-3 flex items-center justify-between border-b border-gray-800 shrink-0 z-30">
        <a href="{{ route('redaction.articles.index') }}" class="flex items-center gap-2">
            <span class="bg-cargo-accent text-white font-heading font-black tracking-wider text-base px-2 py-0.5">CARGO</span>
            <span class="text-[11px] uppercase tracking-wider text-gray-300 font-semibold border-l border-gray-700 pl-2">Desk</span>
        </a>
        <button id="boutonMenuMobile" type="button" class="p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded focus:outline-none" aria-label="Open navigation menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </header>

    {{-- Dark Backdrop for Mobile Navigation --}}
    <div id="voileMenuMobile" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden transition-opacity opacity-0" aria-hidden="true"></div>

    {{-- Left Navigation Sidebar (Dedicated to Editorial Staff) --}}
    <aside id="barreLaterale" class="fixed inset-y-0 left-0 z-50 w-64 bg-cargo-dark text-white flex flex-col justify-between border-r border-gray-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out h-full overflow-hidden">
        {{-- Scrollable Top Section --}}
        <div class="flex-1 overflow-y-auto">
            {{-- Brand / Editorial Logo --}}
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <a href="{{ route('redaction.articles.index') }}" class="flex items-center gap-2">
                    <span class="bg-cargo-accent text-white font-heading font-black tracking-wider text-lg px-2.5 py-1">CARGO</span>
                    <span class="text-xs uppercase tracking-wider text-cargo-yellow font-bold border-l border-gray-700 pl-2">Newsroom Desk</span>
                </a>
                <button id="boutonFermerMobile" type="button" class="md:hidden text-gray-400 hover:text-white p-1 rounded hover:bg-gray-800" aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Quick Action: New Article --}}
            <div class="p-3">
                <a href="{{ route('redaction.articles.create') }}" class="w-full bg-cargo-accent hover:bg-cargo-accentDark text-white font-heading font-bold text-xs uppercase tracking-wider py-2.5 px-3 rounded flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Write Article</span>
                </a>
            </div>

            {{-- Editorial Navigation Items --}}
            <nav class="px-3 py-2 space-y-4 text-xs">
                {{-- Group: Editorial Desk --}}
                <div>
                    <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Editorial Operations</div>
                    <div class="space-y-1">
                        {{-- Articles Management --}}
                        <a href="{{ route('redaction.articles.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded font-semibold transition {{ request()->routeIs('redaction.articles.*') ? 'bg-cargo-surface text-white border-l-2 border-cargo-accent' : 'text-gray-300 hover:bg-cargo-surface/60 hover:text-white' }}">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Articles Management</span>
                        </a>

                        {{-- Statistics & Analytics --}}
                        <a href="{{ route('redaction.statistiques.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded font-semibold transition {{ request()->routeIs('redaction.statistiques.*') ? 'bg-cargo-surface text-white border-l-2 border-cargo-yellow' : 'text-gray-300 hover:bg-cargo-surface/60 hover:text-white' }}">
                            <svg class="w-4 h-4 text-cargo-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Audience & Stats</span>
                        </a>

                        {{-- Videos Management --}}
                        <a href="{{ route('redaction.videos.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded font-semibold transition {{ request()->routeIs('redaction.videos.*') ? 'bg-cargo-surface text-white border-l-2 border-cargo-accent' : 'text-gray-300 hover:bg-cargo-surface/60 hover:text-white' }}">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="12" x="3" y="6" rx="2" stroke-width="2"/><polygon points="10 9 15 12 10 15 10 9" stroke-width="2"/></svg>
                            <span>Video Embeds</span>
                        </a>

                        {{-- Comments Moderation --}}
                        <a href="{{ route('redaction.commentaires.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded font-semibold transition {{ request()->routeIs('redaction.commentaires.*') ? 'bg-cargo-surface text-white border-l-2 border-cargo-accent' : 'text-gray-300 hover:bg-cargo-surface/60 hover:text-white' }}">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span>Reader Comments</span>
                        </a>

                        {{-- Account Settings & Security --}}
                        <a href="{{ route('redaction.parametres.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded font-semibold transition {{ request()->routeIs('redaction.parametres.*') ? 'bg-cargo-surface text-white border-l-2 border-cargo-accent' : 'text-gray-300 hover:bg-cargo-surface/60 hover:text-white' }}">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                            <span>Settings & Account</span>
                        </a>
                    </div>
                </div>

                {{-- Administrative Access if Applicable --}}
                @if(Auth::user() && Auth::user()->estAdministrateur())
                <div>
                    <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Executive Console</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.tableau-bord') }}" class="flex items-center gap-2.5 px-3 py-2 rounded text-gray-300 hover:bg-cargo-surface hover:text-white transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                            <span>Super Admin Console</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>
        </div>

        {{-- Sidebar Footer: Public Site Link, Staff Profile & Logout --}}
        <div class="border-t border-gray-800 bg-cargo-surface shrink-0">
            {{-- Link to Public Front --}}
            <div class="p-3 border-b border-gray-800/80">
                <a href="{{ route('accueil') }}" target="_blank" class="w-full text-xs font-semibold text-gray-200 hover:text-white flex items-center justify-center gap-2 bg-gray-800/90 hover:bg-gray-700/90 py-2 px-3 rounded border border-gray-700 transition" title="Open CARGO public portal in a new tab">
                    <svg class="w-3.5 h-3.5 text-cargo-yellow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Public Site</span>
                </a>
            </div>

            {{-- Staff Profile & Logout --}}
            <div class="p-3">
                @php
                    $roleLabel = match(Auth::user()->role ?? '') {
                        'super_admin', 'SUPER_ADMIN' => 'Editor-in-Chief / Admin',
                        'administrateur', 'admin' => 'Senior Editor',
                        'redacteur_en_chef' => 'Editor-in-Chief',
                        'journaliste' => 'Staff Journalist',
                        'redacteur' => 'Editorial Writer',
                        default => 'Editorial Staff'
                    };
                @endphp
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Journalist' }}</div>
                        <div class="text-[10px] text-cargo-yellow font-semibold truncate">{{ $roleLabel }}</div>
                        <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="truncate">Desk Active</span>
                        </div>
                    </div>

                    <form action="{{ route('redaction.deconnexion') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="bg-gray-800 hover:bg-cargo-accent text-white p-2 rounded transition" title="Log out from Newsroom Desk">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Workspace Area with Left Offset on Desktop (md:pl-64) --}}
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto md:pl-64">
        <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1">
            {{-- Flash Notifications --}}
            @if (session('succes'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                    <span>{{ session('succes') }}</span>
                </div>
            @endif

            @if (session('erreur'))
                <div class="mb-6 bg-red-50 border-l-4 border-cargo-accent p-4 rounded text-red-800 text-sm flex items-center justify-between shadow-sm">
                    <span>{{ session('erreur') }}</span>
                </div>
            @endif

            @yield('contenu')
        </main>

        {{-- Editorial Desk Footer --}}
        <footer class="bg-white border-t border-gray-200 py-3 text-xs text-gray-500 mt-auto">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>CARGO Newsroom Desk &bull; Multimodal Freight & Logistics Publishing</div>
                <div>Encrypted session &bull; Editorial attribution active</div>
            </div>
        </footer>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnOuvrir = document.getElementById('boutonMenuMobile');
            const btnFermer = document.getElementById('boutonFermerMobile');
            const voile = document.getElementById('voileMenuMobile');
            const nav = document.getElementById('barreLaterale');
            const fermer = () => { if (nav && voile) { nav.classList.add('-translate-x-full'); voile.classList.add('hidden'); } };
            const ouvrir = () => { if (nav && voile) { nav.classList.remove('-translate-x-full'); voile.classList.remove('hidden'); } };
            if (btnOuvrir) btnOuvrir.addEventListener('click', ouvrir);
            if (btnFermer) btnFermer.addEventListener('click', fermer);
            if (voile) voile.addEventListener('click', fermer);
        });
    </script>
    @stack('scripts')
</body>
</html>
