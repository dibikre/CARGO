@extends('layouts.journal')

@section('titre', 'Modération des Commentaires — CARGO Rédaction')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Interactivité & Modération</span>
        <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Modération des Commentaires</h1>
    </div>
</div>

<div class="wrap" style="padding-top:36px;">
    <div style="display:flex; gap:10px; margin-bottom:24px;">
        <a href="{{ route('redaction.commentaires.index', ['statut' => 'en_attente']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'en_attente' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            En attente
        </a>
        <a href="{{ route('redaction.commentaires.index', ['statut' => 'approuve']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'approuve' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Approuvés
        </a>
        <a href="{{ route('redaction.commentaires.index', ['statut' => 'rejete']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'rejete' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Rejetés
        </a>
        <a href="{{ route('redaction.commentaires.index', ['statut' => 'spam']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'spam' ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Spam
        </a>
    </div>

    <div style="display:flex; flex-direction:column; gap:16px;">
        @forelse($commentaires as $com)
            <div style="background:#fff; padding:24px; display:flex; flex-direction:column; gap:12px; border-left:4px solid {{ $com->statut === 'approuve' ? '#22C55E' : 'var(--accent)' }};">
                <div style="display:flex; justify-content:space-between; font-size:14px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <strong>{{ $com->auteur_nom }}</strong> ({{ $com->auteur_email ?? 'Membre connecté' }})
                        · sur l'article : <em>{{ $com->article->titre }}</em>
                    </div>
                    <span style="color:var(--text-muted);">{{ $com->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <p style="margin:0; font-size:15px; line-height:1.5;">{{ $com->contenu }}</p>

                <div style="display:flex; gap:12px; padding-top:12px; border-top:1px dashed var(--dashed-border);">
                    @if($com->statut !== 'approuve')
                        <form action="{{ route('redaction.commentaires.approuver', $com->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background:#22C55E; color:#fff; border:0; padding:6px 14px; font-weight:700; cursor:pointer; font-size:13px;">
                                Approuver
                            </button>
                        </form>
                    @endif
                    @if($com->statut !== 'rejete')
                        <form action="{{ route('redaction.commentaires.rejeter', $com->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background:#E5E7EB; color:#14151C; border:0; padding:6px 14px; font-weight:600; cursor:pointer; font-size:13px;">
                                Rejeter
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('redaction.commentaires.spam', $com->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--text-muted); font-size:13px; cursor:pointer;">
                            Marquer spam
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="background:#fff; padding:48px; text-align:center;">
                <p style="color:var(--text-muted); margin:0;">Aucun commentaire dans cette file.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:32px; display:flex; justify-content:center;">
        {{ $commentaires->links() }}
    </div>
</div>
@endsection
