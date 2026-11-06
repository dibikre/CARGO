@extends('administration.layouts.admin')

@section('titre', 'Executive Dashboard')

@section('contenu')
    <div class="space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Executive Dashboard</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Logistics publication metrics, subscriber operations & security telemetry</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('redaction.articles.create') }}" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Article
                </a>
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            {{-- Articles KPI --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Total Articles</div>
                <div class="text-3xl font-heading font-black text-gray-900">{{ $totalArticles }}</div>
                <div class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                    <span class="text-emerald-700 font-semibold">{{ $articlesPublies }} Published</span>
                    <span>&bull;</span>
                    <span class="text-amber-700 font-semibold">{{ $articlesBrouillons }} Drafts</span>
                </div>
            </div>

            {{-- Urgent Alerts KPI --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Active Urgent Alerts</div>
                <div class="text-3xl font-heading font-black {{ $articlesAlerteUrgente > 0 ? 'text-cargo-red' : 'text-gray-900' }}">{{ $articlesAlerteUrgente }}</div>
                <div class="text-xs text-gray-500 mt-2">
                    {{ $articlesAlerteUrgente > 0 ? 'Broadcasting live ticker on portal' : 'No urgent alerts active' }}
                </div>
            </div>

            {{-- Subscribers KPI --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">PRO Subscribers</div>
                <div class="text-3xl font-heading font-black text-gray-900">{{ $totalAbonnesActifs }}</div>
                <div class="text-xs text-gray-500 mt-2">
                    Across <strong>{{ $totalUtilisateurs }}</strong> registered users
                </div>
            </div>

            {{-- Article views KPI --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Article Views (30 Days)</div>
                <div class="text-3xl font-heading font-black text-gray-900">{{ number_format($consultationsMois) }}</div>
                <div class="text-xs text-gray-500 mt-2">Estimated total readership</div>
            </div>

            {{-- Moderation Pending KPI --}}
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Pending Comments</div>
                <div class="text-3xl font-heading font-black {{ $commentairesEnAttente > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $commentairesEnAttente }}</div>
                <div class="text-xs mt-2">
                    @if ($commentairesEnAttente > 0)
                        <a href="{{ route('redaction.commentaires.index') }}" class="text-cargo-red hover:underline font-semibold">Review Queue &rarr;</a>
                    @else
                        <span class="text-gray-500">Moderation queue clean</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Two Columns: Freight Corridors Monitoring & Recent Articles --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Freight Corridors Operational Watch --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">Freight Corridors Status</h2>
                        <p class="text-xs text-gray-500">Real-time supply chain flow disruptions</p>
                    </div>
                    <a href="{{ route('admin.corridors.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">Manage Corridors</a>
                </div>

                <div class="space-y-3">
                    @forelse ($corridorsPerturbes as $corridor)
                        <div class="p-3 rounded border border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div>
                                <div class="font-heading font-bold text-sm text-gray-900">{{ $corridor->nom }}</div>
                                <div class="text-xs text-gray-500 uppercase">{{ $corridor->type_flux }} &bull; {{ $corridor->description ?? 'No incident report' }}</div>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded
                                @if($corridor->statut_operationnel === 'bloque') bg-red-100 text-red-800
                                @elseif($corridor->statut_operationnel === 'perturbe') bg-amber-100 text-amber-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $corridor->statut_operationnel }}
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-gray-500 py-4 text-center bg-emerald-50 rounded border border-emerald-100 text-emerald-800">
                            All freight corridors are currently operating normally (Fluid flow).
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Editorial Activity --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">Latest Dispatches</h2>
                        <p class="text-xs text-gray-500">Recent logistics journalism entries</p>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">View All</a>
                </div>

                <div class="divide-y divide-gray-100 text-sm">
                    @forelse ($derniersArticles as $article)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-heading font-bold text-xs truncate text-gray-900">{{ $article->titre }}</div>
                                <div class="text-[11px] text-gray-500">{{ $article->categorie->nom ?? 'General' }} &bull; By {{ $article->auteur->name ?? 'Staff' }}</div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if ($article->est_reserve_abonnes)
                                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">PRO</span>
                                @endif
                                @if ($article->est_urgent)
                                    <span class="text-[10px] font-bold bg-red-100 text-cargo-red px-1.5 py-0.5 rounded">URGENT</span>
                                @endif
                                <span class="text-[10px] font-semibold text-gray-600 uppercase">{{ $article->statut }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-500 py-4 text-center">No articles available.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Security Audit Feed --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-heading font-bold text-gray-900">Security & Operational Audit Feed</h2>
                    <p class="text-xs text-gray-500">Immutable trace of administrative events</p>
                </div>
                <a href="{{ route('admin.securite.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">Full Audit Trail &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <th class="py-2">Timestamp</th>
                            <th class="py-2">Event</th>
                            <th class="py-2">Description</th>
                            <th class="py-2">Actor</th>
                            <th class="py-2">Client IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($activitesRecentes as $activite)
                            <tr>
                                <td class="py-2.5 font-mono text-gray-500">{{ $activite->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $activite->log_name === 'securite' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $activite->event ?? $activite->log_name }}
                                    </span>
                                </td>
                                <td class="py-2.5 font-medium text-gray-900">{{ $activite->description }}</td>
                                <td class="py-2.5">{{ $activite->auteur->email ?? 'System / Anonymous' }}</td>
                                <td class="py-2.5 font-mono text-gray-500">{{ $activite->properties['ip'] ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">No security audit records logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
