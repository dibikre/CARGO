{{-- Indicateurs clés de synthèse de la vidéothèque --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition hover:border-gray-300">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Embeds</span>
            <span class="w-2 h-2 rounded-full bg-cargo-dark"></span>
        </div>
        <div class="mt-2 flex items-baseline gap-2">
            <span id="kpi-total-videos" class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ $indicateurs['total'] ?? 0 }}</span>
            <span class="text-xs text-gray-400">multimedia assets</span>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition hover:border-gray-300">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Active On Homepage</span>
            <span class="w-2 h-2 rounded-full bg-cargo-red"></span>
        </div>
        <div class="mt-2 flex items-baseline gap-2">
            <span id="kpi-actifs-videos" class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ $indicateurs['actifs'] ?? 0 }}</span>
            <span class="text-xs text-gray-400">live in sidebar</span>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition hover:border-gray-300">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">YouTube Embeds</span>
            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
        </div>
        <div class="mt-2 flex items-baseline gap-2">
            <span id="kpi-youtube-videos" class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ $indicateurs['youtube'] ?? 0 }}</span>
            <span class="text-xs text-gray-400">google media stream</span>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition hover:border-gray-300">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Vimeo & Other</span>
            <span class="w-2 h-2 rounded-full bg-gray-300"></span>
        </div>
        <div class="mt-2 flex items-baseline gap-2">
            <span id="kpi-vimeo-videos" class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ ($indicateurs['vimeo'] ?? 0) + ($indicateurs['autres'] ?? 0) }}</span>
            <span class="text-xs text-gray-400">pro & direct embeds</span>
        </div>
    </div>
</div>
