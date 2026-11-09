{{-- 
  Composant Blade : Cartes des métriques clés (KPIs) en haut du tableau de bord rédaction
--}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Card 1: Total Views --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cumulative Unique Views</div>
        <div class="text-3xl font-heading font-black text-cargo-dark mt-2">{{ number_format($totalVuesCumulees) }}</div>
        <div class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <span>Live IP-verified readings</span>
        </div>
        <div class="absolute right-3 top-3 text-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
    </div>

    {{-- Card 2: Period Publications --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Published in Period</div>
        <div class="text-3xl font-heading font-black text-cargo-accent mt-2">{{ $publicationsPeriode }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1">Articles published in past {{ $jours }} days</div>
        <div class="absolute right-3 top-3 text-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
    </div>

    {{-- Card 3: Total Published Articles --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">All-Time Published</div>
        <div class="text-3xl font-heading font-black text-emerald-600 mt-2">{{ $totalPublies }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1">{{ $totalArticles }} articles in newsroom database</div>
        <div class="absolute right-3 top-3 text-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>

    {{-- Card 4: Editorial Pipeline --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Editorial Pipeline</div>
        <div class="text-3xl font-heading font-black text-amber-500 mt-2">{{ $totalRelecture + $totalBrouillons }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1">{{ $totalRelecture }} under review &bull; {{ $totalBrouillons }} drafts</div>
        <div class="absolute right-3 top-3 text-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
    </div>
</div>
