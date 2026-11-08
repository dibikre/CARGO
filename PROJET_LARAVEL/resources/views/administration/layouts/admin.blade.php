<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titre', 'Administration') — CARGO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cargo: {
                            dark: '#14151C',
                            surface: '#1D1E28',
                            red: '#D4201A',
                            redDark: '#B01813',
                            yellow: '#FFC93C',
                            grayLight: '#F3F4F6'
                        }
                    },
                    fontFamily: {
                        sans: ['IBM Plex Sans', 'sans-serif'],
                        heading: ['Archivo', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    {{-- Menu Latéral (Sidebar) en 3 Groupes Fonctionnels --}}
    <aside class="w-full md:w-64 bg-cargo-dark text-white shrink-0 flex flex-col justify-between border-r border-gray-800 z-40 md:min-h-screen">
        <div>
            {{-- En-tête de la Sidebar --}}
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <a href="{{ route('admin.tableau-bord') }}" class="flex items-center gap-2">
                    <span class="bg-cargo-red text-white font-heading font-black tracking-wider text-lg px-2 py-0.5">CARGO</span>
                    <span class="text-xs uppercase tracking-wider text-gray-300 font-semibold border-l border-gray-700 pl-2">Rédaction</span>
                </a>
                <a href="{{ route('accueil') }}" target="_blank" class="text-xs font-semibold text-gray-400 hover:text-white flex items-center gap-1 bg-gray-800 px-2 py-1 rounded border border-gray-700" title="Ouvrir le portail public dans un nouvel onglet">
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Voir le site</span>
                </a>
            </div>

            {{-- Lien Tableau de Bord Général --}}
            <div class="px-3 pt-3">
                <a href="{{ route('admin.tableau-bord') }}" class="flex items-center gap-2.5 px-3 py-2 rounded text-xs font-bold transition {{ request()->routeIs('admin.tableau-bord') ? 'bg-cargo-red text-white' : 'text-gray-300 hover:bg-cargo-surface hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Tableau de bord</span>
                </a>
            </div>

            {{-- Navigation Groupée --}}
            <nav class="px-3 py-2 space-y-4 text-xs">
                {{-- Groupe 1 : Contenu --}}
                <div>
                    <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Contenu</div>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.articles.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Articles</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.categories.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Sections (Rubriques)</span>
                        </a>
                        <a href="{{ route('admin.corridors.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.corridors.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            <span>Corridors logistiques</span>
                        </a>
                        <a href="{{ route('admin.matieres-premieres.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.matieres-premieres.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <span>Matières Premières</span>
                        </a>
                        <a href="{{ route('admin.evenements.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.evenements.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Agenda & Salons</span>
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.documents.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Documents & Études</span>
                        </a>
                    </div>
                </div>

                {{-- Groupe 2 : Audience --}}
                <div>
                    <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Audience</div>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.newsletter.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.newsletter.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Newsletter</span>
                        </a>
                        <a href="{{ route('admin.abonnements.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.abonnements.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Abonnés & Formules</span>
                        </a>
                        <a href="{{ route('admin.commentaires.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.commentaires.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span>Modération</span>
                        </a>
                    </div>
                </div>

                {{-- Groupe 3 : Administration --}}
                <div>
                    <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Administration</div>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.utilisateurs.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.utilisateurs.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Rôles & Accès (RBAC)</span>
                        </a>
                        <a href="{{ route('admin.securite.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded transition {{ request()->routeIs('admin.securite.*') ? 'bg-cargo-surface text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Journal d'audit</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        {{-- Bas de la Sidebar : Profil Utilisateur & Indicateur de Sécurité Discret --}}
        <div class="p-3 border-t border-gray-800 bg-cargo-surface">
            @php
                $roleLabel = match(Auth::user()->role ?? '') {
                    'super_admin', 'SUPER_ADMIN' => 'Super Administrateur',
                    'administrateur', 'admin' => 'Administrateur',
                    'redacteur_en_chef' => 'Rédacteur en Chef',
                    'journaliste' => 'Journaliste',
                    'redacteur' => 'Rédacteur',
                    'abonne' => 'Abonné PRO',
                    default => 'Membre Rédaction'
                };
            @endphp
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrateur' }}</div>
                    <div class="text-[10px] text-cargo-yellow font-semibold truncate">{{ $roleLabel }}</div>
                    {{-- Petit indicateur discret de sécurité (remplace le bandeau jaune permanent) --}}
                    <div class="flex items-center gap-1.5 mt-1 text-[10px] text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="truncate">Session sécurisée</span>
                    </div>
                </div>

                <form action="{{ route('admin.deconnexion') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-cargo-red text-white p-2 rounded transition" title="Se déconnecter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Zone Principale de Contenu --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1">
            {{-- Notifications Flash --}}
            @if (session('succes'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded text-emerald-800 text-sm flex items-center justify-between">
                    <span>{{ session('succes') }}</span>
                </div>
            @endif

            @if (session('erreur'))
                <div class="mb-6 bg-red-50 border-l-4 border-cargo-red p-4 rounded text-red-800 text-sm flex items-center justify-between">
                    <span>{{ session('erreur') }}</span>
                </div>
            @endif

            @yield('contenu')
        </main>

        {{-- Pied de Page sobre et francisé --}}
        <footer class="bg-white border-t border-gray-200 py-3 text-xs text-gray-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>CARGO Média Logistique & Fret International — Console d'Administration</div>
                <div>Session sécurisée &bull; Journalisation d'audit active</div>
            </div>
        </footer>
    </div>

</body>
</html>

</body>
</html>
