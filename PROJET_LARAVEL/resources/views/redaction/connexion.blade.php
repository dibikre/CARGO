<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial Newsroom Login — CARGO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;800;900&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                            bg: '#EEF0F3'
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
<body class="bg-cargo-bg text-cargo-dark font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        {{-- En-tête Rédaction & Titre du Desk --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="bg-cargo-red text-white font-heading font-black tracking-wider text-2xl px-3 py-1">CARGO</span>
                <span class="text-xs uppercase tracking-widest text-cargo-dark font-bold border-l-2 border-gray-400 pl-3">Editorial Desk</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Newsroom Authentication</h1>
            <p class="text-xs text-gray-600 mt-1 uppercase tracking-wider font-semibold">Reserved for Accredited Journalists, Editors &amp; Contributors</p>
        </div>

        {{-- Carte de connexion éditoriale --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-xl p-6 sm:p-8">
            @if (session('statut'))
                <div class="mb-5 bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs p-3 rounded font-medium">
                    {{ session('statut') }}
                </div>
            @endif

            @if (session('erreur'))
                <div class="mb-5 bg-red-50 border border-red-300 text-red-800 text-xs p-3 rounded font-medium">
                    {{ session('erreur') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-cargo-red text-red-800 text-xs p-3.5 rounded space-y-1">
                    @foreach ($errors->all() as $erreur)
                        <div>&bull; {{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('redaction.connexion.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Champ piège honeypot anti-robot --}}
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="jeton_securite" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Editorial Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="journaliste@cargo-logistics.com"
                           class="w-full bg-gray-50 border border-gray-300 rounded px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-cargo-red focus:ring-1 focus:ring-cargo-red transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Editorial Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••••••"
                           class="w-full bg-gray-50 border border-gray-300 rounded px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-cargo-red focus:ring-1 focus:ring-cargo-red transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-700 font-medium">
                        <input type="checkbox" name="se_souvenir_de_moi" value="1" class="rounded border-gray-300 text-cargo-red focus:ring-0">
                        <span>Remember current editorial workstation</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-sm uppercase tracking-wider py-3 px-4 rounded transition shadow-md">
                    Open Newsroom Desk
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-200 text-xs text-gray-500 space-y-2 text-center">
                <div class="text-[11px] text-gray-400">
                    Direct access to article writing, editorial calendar, and moderation.
                </div>
            </div>
        </div>

        {{-- Liens de séparation claire entre les espaces --}}
        <div class="mt-6 bg-white/70 border border-gray-200 rounded-lg p-4 text-xs text-gray-600 space-y-2">
            <div class="font-bold uppercase tracking-wider text-[11px] text-gray-500 mb-1">Other Access Portals:</div>
            <div class="flex items-center justify-between">
                <span>Are you a Subscriber or Reader?</span>
                <a href="{{ route('login') }}" class="text-cargo-red font-bold hover:underline">PRO Reader Portal &rarr;</a>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2">
                <span>Platform Administrator?</span>
                <a href="{{ route('admin.connexion') }}" class="text-gray-900 font-bold hover:underline">Security Gateway &rarr;</a>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('accueil') }}" class="text-xs text-gray-500 hover:text-gray-900 transition font-medium">
                &larr; Return to CARGO Public Edition
            </a>
        </div>
    </div>

</body>
</html>
