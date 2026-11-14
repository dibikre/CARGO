<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Authentication — CARGO</title>
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
                            yellow: '#FFC93C'
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
<body class="bg-cargo-dark text-white font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        {{-- Brand & Security Notice --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="bg-cargo-red text-white font-heading font-black tracking-wider text-2xl px-3 py-1">CARGO</span>
                <span class="text-xs uppercase tracking-widest text-cargo-yellow font-bold border-l border-gray-700 pl-3">Logistics Journal</span>
            </div>
            <h1 class="text-xl font-heading font-bold text-white tracking-tight">Security Gateway</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">Restricted Administration Access &bull; Strict Throttling</p>
        </div>

        {{-- Main Security Card --}}
        <div class="bg-cargo-surface border border-gray-800 rounded-lg shadow-2xl p-6 sm:p-8">
            @if (session('statut'))
                <div class="mb-5 bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-xs p-3 rounded">
                    {{ session('statut') }}
                </div>
            @endif

            @if (session('erreur'))
                <div class="mb-5 bg-red-950/80 border border-red-800 text-red-300 text-xs p-3 rounded">
                    {{ session('erreur') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 bg-red-950/80 border border-cargo-red text-red-300 text-xs p-3.5 rounded space-y-1">
                    @foreach ($errors->all() as $erreur)
                        <div>&bull; {{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.connexion.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Honeypot anti-bot --}}
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="jeton_securite" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">Corporate Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-cargo-dark border border-gray-700 rounded px-3.5 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cargo-red focus:ring-1 focus:ring-cargo-red transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">Admin Security Key / Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-cargo-dark border border-gray-700 rounded px-3.5 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cargo-red focus:ring-1 focus:ring-cargo-red transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-300">
                        <input type="checkbox" name="se_souvenir_de_moi" value="1" class="rounded bg-cargo-dark border-gray-700 text-cargo-red focus:ring-0">
                        <span>Remember current workstation</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-sm uppercase tracking-wider py-3 px-4 rounded transition shadow-lg shadow-red-950/40">
                    Authenticate Session
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-800 text-[11px] text-gray-500 text-center space-y-1">
                <div>Client IP: <span class="font-mono text-gray-400">{{ request()->ip() }}</span> (Monitored)</div>
                <div>5 failed attempts trigger an automatic 15-minute security lock.</div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('accueil') }}" class="text-xs text-gray-400 hover:text-white transition">Return to CARGO Public Edition</a>
        </div>
    </div>

</body>
</html>
