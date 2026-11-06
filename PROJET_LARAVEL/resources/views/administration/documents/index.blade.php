@extends('administration.layouts.admin')

@section('titre', 'Documents & Whitepapers')

@section('contenu')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-lg border border-gray-200">
        <div>
            <h1 class="text-2xl font-heading font-black text-cargo-dark">Technical Documents & Freight Barometers</h1>
            <p class="text-sm text-gray-500 mt-1">Manage downloadable whitepapers, market reports, and subscriber-exclusive PDFs.</p>
        </div>
    </div>

    {{-- Layout: Form + Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Creation Form --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 h-fit">
            <h2 class="text-lg font-heading font-bold text-cargo-dark mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-cargo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Publish Document
            </h2>

            <form action="{{ route('admin.documents.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Document Title *</label>
                    <input type="text" name="titre" required placeholder="e.g. Q1 Global Freight Index" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Document Classification *</label>
                    <select name="type_document" required class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                        <option value="livre_blanc">Whitepaper / In-Depth Study</option>
                        <option value="barometre">Freight Rate Barometer</option>
                        <option value="fiche_technique">Technical Compliance Guide</option>
                        <option value="rapport_annuel">Annual Industry Report</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Target Filename *</label>
                    <input type="text" name="nom_fichier" required placeholder="cargo-freight-index-2026.pdf" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark font-mono text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Executive Summary</label>
                    <textarea name="description" rows="3" placeholder="Key takeaways and methodology..." class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark"></textarea>
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="est_reserve_abonnes" value="1" checked class="rounded border-gray-300 text-cargo-red focus:ring-0">
                        <span class="text-xs font-semibold text-gray-700">Lock for CARGO PRO Subscribers</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold uppercase tracking-wider text-xs py-2.5 px-4 rounded transition">
                    Publish Resource
                </button>
            </form>
        </div>

        {{-- Table List --}}
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Document Repository</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Title & Classification</th>
                            <th class="py-3 px-4">File</th>
                            <th class="py-3 px-4 text-center">Downloads</th>
                            <th class="py-3 px-4 text-center">Access Gate</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-cargo-dark">{{ $doc->titre }}</div>
                                    <div class="text-[11px] uppercase font-bold text-gray-400">{{ str_replace('_', ' ', $doc->type_document) }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-gray-500">
                                    {{ $doc->nom_fichier }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded font-bold font-mono">
                                        {{ number_format($doc->telechargements_compteur) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.documents.pro', $doc->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded font-bold uppercase transition {{ $doc->est_reserve_abonnes ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $doc->est_reserve_abonnes ? 'PRO Only' : 'Free Public' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Remove this document?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 italic">No whitepapers published yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
