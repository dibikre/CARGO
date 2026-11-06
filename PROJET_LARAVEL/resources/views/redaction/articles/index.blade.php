@extends('layouts.journal')

@section('titre', 'Editorial Desk — Article Management — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <span class="kick" style="color:var(--yellow);">Editorial Desk & HTML Content Management</span>
            <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Articles Management</h1>
        </div>
        <a href="{{ route('redaction.articles.create') }}" style="background:var(--accent); color:#fff; font-weight:700; font-size:14px; padding:12px 24px; text-decoration:none; text-transform:uppercase; letter-spacing:0.05em; font-family:'Archivo',sans-serif; display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            Write New Article
        </a>
    </div>
</div>

<div class="wrap" style="padding-top:36px; padding-bottom:64px;">
    {{-- Filter by Status --}}
    <div style="display:flex; gap:10px; margin-bottom:24px; flex-wrap:wrap;">
        <a href="{{ route('redaction.articles.index') }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; {{ empty($statut) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">All</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'brouillon']) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; {{ $statut === 'brouillon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Drafts</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'en_relecture']) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; {{ $statut === 'en_relecture' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Under Review</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'publie']) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; {{ $statut === 'publie' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Published</a>
    </div>

    {{-- Articles Table --}}
    <div style="background:#fff; overflow-x:auto; border:1px solid #E2E5EB;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:var(--dark); color:#fff; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">
                    <th style="padding:14px 16px;">Title / Format</th>
                    <th style="padding:14px 16px;">Section</th>
                    <th style="padding:14px 16px;">Author</th>
                    <th style="padding:14px 16px;">Status</th>
                    <th style="padding:14px 16px;">Views</th>
                    <th style="padding:14px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                    <tr style="border-bottom:1px solid #E5E7EB;">
                        <td style="padding:14px 16px; font-weight:600;">
                            <div>
                                <a href="{{ route('redaction.articles.edit', $art->id) }}" style="color:var(--dark); font-weight:700;">{{ $art->titre }}</a>
                            </div>
                            <div style="display:flex; gap:6px; margin-top:4px;">
                                @if($art->est_a_la_une) <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">HEADLINE</span> @endif
                                @if($art->est_reserve_abonnes) <span style="background:var(--accent); color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">PRO ONLY</span> @endif
                                @if($art->est_urgent) <span style="background:#DC2626; color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">URGENT</span> @endif
                            </div>
                        </td>
                        <td style="padding:14px 16px; color:#4B505C; font-size:13px;">{{ $art->categorie->nom ?? 'General' }}</td>
                        <td style="padding:14px 16px; font-weight:600; font-size:13px;">{{ $art->auteur->name ?? 'Staff' }}</td>
                        <td style="padding:14px 16px;">
                            <span style="font-weight:700; text-transform:uppercase; font-size:11px; padding:3px 8px; border-radius:3px; {{ $art->statut === 'publie' ? 'background:#DCFCE7; color:#166534;' : 'background:#F3F4F6; color:#4B505C;' }}">
                                {{ $art->statut }}
                            </span>
                        </td>
                        <td style="padding:14px 16px; font-mono font-size:13px;">{{ number_format($art->vues_compteur) }}</td>
                        <td style="padding:14px 16px; text-align:right; white-space:nowrap;">
                            <a href="{{ route('redaction.articles.edit', $art->id) }}" style="color:var(--dark); font-weight:700; font-size:13px; margin-right:12px; text-decoration:underline;">
                                Edit HTML
                            </a>

                            @if($art->statut !== 'publie')
                                <form action="{{ route('redaction.articles.publier', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:#16A34A; font-weight:700; font-size:13px; cursor:pointer; margin-right:10px;">Publish</button>
                                </form>
                            @else
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" style="color:#2563EB; font-weight:600; font-size:13px; margin-right:10px;">
                                    View
                                </a>
                                <form action="{{ route('redaction.articles.archiver', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:600; font-size:13px; cursor:pointer; margin-right:10px;">Archive</button>
                                </form>
                            @endif

                            <form action="{{ route('redaction.articles.destroy', $art->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#DC2626; font-weight:700; font-size:13px; cursor:pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px; text-align:center; color:var(--text-muted); font-style:italic;">
                            No articles found in this queue.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:32px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
