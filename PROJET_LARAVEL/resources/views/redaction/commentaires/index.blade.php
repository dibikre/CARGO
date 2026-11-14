@extends('redaction.layouts.redaction')

@section('titre', 'Modération des commentaires — CARGO Rédaction')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Espace Rédaction &bull; Communauté Lecteurs</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Modération des commentaires</h1>
        </div>
        <a href="{{ route('redaction.articles.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour aux articles</span>
        </a>
    </div>

    <div>
        <div style="display:flex; gap:10px; margin-bottom:24px; flex-wrap:wrap;">
            <a href="{{ route('redaction.commentaires.index', ['statut' => 'en_attente']) }}" style="padding:8px 16px; font-weight:700; font-size:12px; text-transform:uppercase; {{ $statut === 'en_attente' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">
                En attente
            </a>
            <a href="{{ route('redaction.commentaires.index', ['statut' => 'approuve']) }}" style="padding:8px 16px; font-weight:700; font-size:12px; text-transform:uppercase; {{ $statut === 'approuve' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">
                Approuvés
            </a>
            <a href="{{ route('redaction.commentaires.index', ['statut' => 'rejete']) }}" style="padding:8px 16px; font-weight:700; font-size:12px; text-transform:uppercase; {{ $statut === 'rejete' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">
                Rejetés
            </a>
            <a href="{{ route('redaction.commentaires.index', ['statut' => 'spam']) }}" style="padding:8px 16px; font-weight:700; font-size:12px; text-transform:uppercase; {{ $statut === 'spam' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">
                Spam
            </a>
        </div>

    <div style="display:flex; flex-direction:column; gap:16px;">
        @forelse($commentaires as $com)
            <div style="background:#fff; padding:24px; display:flex; flex-direction:column; gap:12px; border-left:4px solid {{ $com->statut === 'approuve' ? '#22C55E' : 'var(--accent)' }};">
                <div style="display:flex; justify-content:space-between; font-size:14px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <strong>{{ $com->auteur_nom }}</strong> ({{ $com->auteur_email ?? 'Membre connecté' }})
                        @if($com->auteur_site_web)
                            &bull;
                            @if($com->site_web_est_cliquable)
                                <a href="{{ $com->url_site_web_securisee }}" target="_blank" rel="nofollow noopener noreferrer" style="color:var(--accent); text-decoration:underline; font-size:12px;">{{ $com->nom_domaine_site_web }} &nearr;</a>
                            @else
                                <span style="background:#F3F4F6; color:var(--text-muted); padding:2px 6px; font-size:11px; border-radius:3px;">{{ $com->nom_domaine_site_web }} (texte brut)</span>
                            @endif
                        @endif
                        · sur l'article : <em>{{ $com->article?->titre }}</em>
                    </div>
                    <span style="color:var(--text-muted);">{{ $com->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div style="margin:0; font-size:15px; line-height:1.5; word-break:break-word;">{!! $com->contenu_formate !!}</div>

                <div style="display:flex; gap:12px; padding-top:12px; border-top:1px dashed var(--dashed-border);">
                    @if($com->statut !== 'approuve')
                        <form action="{{ route('redaction.commentaires.approuver', $com->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#22C55E; color:#fff; border:0; padding:6px 14px; font-weight:700; cursor:pointer; font-size:13px;">
                                Approuver
                            </button>
                        </form>
                    @endif
                    @if($com->statut !== 'rejete')
                        <form action="{{ route('redaction.commentaires.rejeter', $com->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#E5E7EB; color:#14151C; border:0; padding:6px 14px; font-weight:600; cursor:pointer; font-size:13px;">
                                Rejeter
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('redaction.commentaires.spam', $com->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--text-muted); font-size:13px; font-weight:600; cursor:pointer;">
                            Marquer comme spam
                        </button>
                    </form>
                    </div>
                </div>
            @empty
                <div style="background:#fff; border:1px dashed var(--dashed-border); padding:48px; text-align:center;">
                    <p style="color:var(--text-muted); margin:0; font-style:italic;">Aucun commentaire lecteur dans cette file de modération.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top:32px; display:flex; justify-content:center;">
            {{ $commentaires->links('composants.pagination-cargo') }}
        </div>
    </div>
</div>
@endsection
