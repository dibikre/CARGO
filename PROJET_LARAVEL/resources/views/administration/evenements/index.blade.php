@extends('administration.layouts.admin')

@section('titre', 'Logistics Agenda & Events')

@section('contenu')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-lg border border-gray-200">
        <div>
            <h1 class="text-2xl font-heading font-black text-cargo-dark">Industry Events & Fairs</h1>
            <p class="text-sm text-gray-500 mt-1">Manage global transport exhibitions, supply chain summits, and maritime conventions.</p>
        </div>
    </div>

    {{-- Layout: Form + Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Creation Form --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 h-fit">
            <h2 class="text-lg font-heading font-bold text-cargo-dark mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-cargo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Schedule Event
            </h2>

            <form action="{{ route('admin.evenements.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Event Title *</label>
                    <input type="text" name="titre" required placeholder="e.g. SITL Paris 2026" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Event Type *</label>
                    <select name="type_evenement" required class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                        <option value="salon">Trade Fair / Exhibition</option>
                        <option value="conference">Executive Conference</option>
                        <option value="sommet">Global Summit</option>
                        <option value="webinaire">Webinar / Virtual Briefing</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Start Date *</label>
                        <input type="date" name="date_debut" required class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">End Date *</label>
                        <input type="date" name="date_fin" required class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Venue & Location *</label>
                    <input type="text" name="lieu" required placeholder="e.g. Paris Nord Villepinte" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">City *</label>
                        <input type="text" name="ville" required placeholder="Paris" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Country *</label>
                        <input type="text" name="pays" required placeholder="France" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Official Website URL</label>
                    <input type="url" name="url_site_web" placeholder="https://..." class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="est_en_vedette" value="1" class="rounded border-gray-300 text-cargo-red focus:ring-0">
                        <span class="text-xs font-semibold text-gray-700">Feature on Homepage Banner</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold uppercase tracking-wider text-xs py-2.5 px-4 rounded transition">
                    Publish Event
                </button>
            </form>
        </div>

        {{-- Table List --}}
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Scheduled Industry Events</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Dates</th>
                            <th class="py-3 px-4">Event / Location</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4 text-center">Featured</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($evenements as $evt)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4 font-mono text-xs text-gray-600 whitespace-nowrap">
                                    {{ $evt->date_debut?->format('M d') }} - {{ $evt->date_fin?->format('d, Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-cargo-dark">{{ $evt->titre }}</div>
                                    <div class="text-xs text-gray-500">{{ $evt->lieu }}, {{ $evt->ville }} ({{ $evt->pays }})</div>
                                </td>
                                <td class="py-3 px-4 text-xs font-semibold uppercase text-gray-600">
                                    {{ $evt->type_evenement }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="{{ route('admin.evenements.vedette', $evt->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2 py-0.5 rounded font-bold uppercase transition {{ $evt->est_en_vedette ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $evt->est_en_vedette ? 'Featured' : 'Standard' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($evt->url_site_web)
                                            <a href="{{ $evt->url_site_web }}" target="_blank" class="p-1 text-gray-400 hover:text-cargo-dark transition" title="Visit External Site">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.evenements.destroy', $evt->id) }}" method="POST" onsubmit="return confirm('Delete this event?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 italic">No logistics events recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $evenements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
