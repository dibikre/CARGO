@extends('administration.layouts.admin')

@section('titre', 'Editorial Categories & Sections')

@section('contenu')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-lg border border-gray-200">
        <div>
            <h1 class="text-2xl font-heading font-black text-cargo-dark">Editorial Sections & Taxonomy</h1>
            <p class="text-sm text-gray-500 mt-1">Configure logistics categories, display ordering, and real-time header navigation presence.</p>
        </div>
        <div class="text-sm font-semibold text-gray-600 bg-gray-50 px-4 py-2 rounded border border-gray-200">
            Total Sections: <strong class="text-cargo-dark font-black">{{ $categories->count() }}</strong>
        </div>
    </div>

    {{-- Grid Layout: Add New + List --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Create Form Card --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 h-fit">
            <h2 class="text-lg font-heading font-bold text-cargo-dark mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-cargo-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Section
            </h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Section Name *</label>
                    <input type="text" name="nom" required placeholder="e.g. Maritime Freight & Ports" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">URL Slug (Optional)</label>
                    <input type="text" name="slug" placeholder="e.g. fret-maritime-ports" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    <span class="text-[11px] text-gray-400">Auto-generated from title if left empty</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Editorial scope and topic focus..." class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Order Priority</label>
                        <input type="number" name="ordre_affichage" value="0" min="0" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Accent Color</label>
                        <input type="text" name="couleur" value="#D4201A" class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="afficher_dans_menu" value="1" checked class="rounded border-gray-300 text-cargo-red focus:ring-0">
                        <span class="text-xs font-semibold text-gray-700">Display in Public Header Menu</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold uppercase tracking-wider text-xs py-2.5 px-4 rounded transition">
                    Save New Section
                </button>
            </form>
        </div>

        {{-- Table List --}}
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Active Taxonomy Table</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Order</th>
                            <th class="py-3 px-4">Section / Slug</th>
                            <th class="py-3 px-4 text-center">Articles</th>
                            <th class="py-3 px-4 text-center">Menu Presence</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-500">
                                    #{{ $cat->ordre_affichage }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-cargo-dark">{{ $cat->nom }}</div>
                                    <div class="font-mono text-xs text-gray-400">/rubriques/{{ $cat->slug }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded font-bold font-mono">
                                        {{ $cat->articles_count }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.categories.menu', $cat->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider transition {{ $cat->afficher_dans_menu ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $cat->afficher_dans_menu ? 'In Menu' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('categories.articles', $cat->slug) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-cargo-dark transition" title="Preview Public Page">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        @if($cat->articles_count === 0)
                                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this section permanently?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 transition" title="Delete Section">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 italic">No editorial sections defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
