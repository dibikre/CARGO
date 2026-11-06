@extends('administration.layouts.admin')

@section('titre', 'Discussion & Comment Moderation')

@section('contenu')
<div class="space-y-6">
    {{-- Header & Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Total Comments</span>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-1">{{ number_format($metriques['total']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-amber-600">Pending Review</span>
            <div class="text-3xl font-heading font-black text-amber-600 mt-1">{{ number_format($metriques['en_attente']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-emerald-600">Published / Approved</span>
            <div class="text-3xl font-heading font-black text-emerald-600 mt-1">{{ number_format($metriques['approuve']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-red-600">Flagged Spam</span>
            <div class="text-3xl font-heading font-black text-red-600 mt-1">{{ number_format($metriques['spam']) }}</div>
        </div>
    </div>

    {{-- Filter Nav --}}
    <div class="bg-white p-3 rounded-lg border border-gray-200 flex flex-wrap gap-2 text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('admin.commentaires.index') }}" class="px-3 py-1.5 rounded transition {{ empty($statut) ? 'bg-cargo-dark text-white' : 'text-gray-600 hover:bg-gray-100' }}">All</a>
        <a href="{{ route('admin.commentaires.index', ['statut' => 'en_attente']) }}" class="px-3 py-1.5 rounded transition {{ $statut === 'en_attente' ? 'bg-amber-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Pending</a>
        <a href="{{ route('admin.commentaires.index', ['statut' => 'approuve']) }}" class="px-3 py-1.5 rounded transition {{ $statut === 'approuve' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Approved</a>
        <a href="{{ route('admin.commentaires.index', ['statut' => 'spam']) }}" class="px-3 py-1.5 rounded transition {{ $statut === 'spam' ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">Spam</a>
    </div>

    {{-- Comments Feed --}}
    <div class="space-y-4">
        @forelse($commentaires as $com)
            <div class="bg-white p-5 rounded-lg border border-gray-200 flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex-1 space-y-2">
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <strong class="text-cargo-dark font-bold text-sm">{{ $com->auteur_nom }}</strong>
                        <span class="text-gray-400 font-mono">{{ $com->auteur_email }}</span>
                        <span class="text-gray-300">&bull;</span>
                        <span class="text-gray-500">{{ $com->created_at?->diffForHumans() }}</span>
                        @if($com->statut === 'en_attente')
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold uppercase px-2 py-0.5 rounded">Pending</span>
                        @elseif($com->statut === 'approuve')
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase px-2 py-0.5 rounded">Live</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-bold uppercase px-2 py-0.5 rounded">{{ $com->statut }}</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-800 leading-relaxed bg-gray-50 p-3 rounded border border-gray-100">
                        {{ $com->contenu }}
                    </p>

                    @if($com->article)
                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                            <span class="font-semibold uppercase tracking-wider text-[10px]">Article:</span>
                            <a href="{{ route('articles.show', $com->article->slug) }}" target="_blank" class="text-cargo-red hover:underline font-medium">
                                {{ $com->article->titre }} &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Action Controls --}}
                <div class="flex items-center gap-2 self-end md:self-start">
                    @if($com->statut !== 'approuve')
                        <form action="{{ route('admin.commentaires.approuver', $com->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider px-3 py-1.5 rounded transition">
                                Approve
                            </button>
                        </form>
                    @endif

                    @if($com->statut !== 'spam')
                        <form action="{{ route('admin.commentaires.spam', $com->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider px-3 py-1.5 rounded transition">
                                Spam
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.commentaires.destroy', $com->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider px-3 py-1.5 rounded transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 text-center text-gray-400 italic rounded-lg border border-gray-200">
                No discussion comments found in this view.
            </div>
        @endforelse
    </div>

    <div>
        {{ $commentaires->links() }}
    </div>
</div>
@endsection
