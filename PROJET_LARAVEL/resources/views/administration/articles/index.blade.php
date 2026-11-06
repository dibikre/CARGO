@extends('administration.layouts.admin')

@section('titre', 'Articles & Logistics Alerts')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Articles & Real-Time Alerts Control</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Supervise editorial dispatches, urgent breaking banners & paywall flags</p>
            </div>
            <a href="{{ route('redaction.articles.create') }}" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow transition">
                + Create Article
            </a>
        </div>

        {{-- Filters Bar --}}
        <form method="GET" action="{{ route('admin.articles.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-4 items-center justify-between">
            <div class="flex-1 min-w-[240px] flex gap-2">
                <input type="text" name="recherche" value="{{ $recherche }}" placeholder="Search articles by title..."
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <select name="statut" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="publie" {{ $statutFiltre === 'publie' ? 'selected' : '' }}>Published</option>
                    <option value="brouillon" {{ $statutFiltre === 'brouillon' ? 'selected' : '' }}>Draft</option>
                    <option value="archive" {{ $statutFiltre === 'archive' ? 'selected' : '' }}>Archived</option>
                </select>
                <select name="categorie_id" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categorieFiltre == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                    Filter
                </button>
            </div>
        </form>

        {{-- Articles Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">Title & Author</th>
                            <th class="p-3.5">Category</th>
                            <th class="p-3.5 text-center">Urgent Alert</th>
                            <th class="p-3.5 text-center">PRO Paywall</th>
                            <th class="p-3.5 text-center">Featured</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($articles as $article)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5 max-w-xs">
                                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="font-heading font-bold text-gray-900 hover:text-cargo-red line-clamp-1">
                                        {{ $article->titre }}
                                    </a>
                                    <div class="text-[11px] text-gray-500">By {{ $article->auteur->name ?? 'Staff' }} &bull; {{ $article->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-[10px] font-semibold">
                                        {{ $article->categorie->nom ?? 'General' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.alerte', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_urgent ? 'bg-red-600 text-white hover:bg-red-700 shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_urgent ? 'LIVE FLASH' : 'Off' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.pro', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_reserve_abonnes ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_reserve_abonnes ? 'PRO ONLY' : 'Free' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.alaune', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_a_la_une ? 'bg-cargo-dark text-white hover:bg-black shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_a_la_une ? 'FEATURED' : 'Standard' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $article->statut === 'publie' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $article->statut }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right space-x-2">
                                    <a href="{{ route('redaction.articles.edit', $article->id) }}" class="text-xs font-semibold text-gray-600 hover:text-cargo-red">Edit</a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirm deletion of this article?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">No articles matched your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($articles->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
