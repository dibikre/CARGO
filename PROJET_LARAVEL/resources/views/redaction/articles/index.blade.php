@extends('layouts.journal')

@section('titre', 'Espace Rédaction — Gestion des Articles CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <span class="kick" style="color:var(--yellow);">Administration éditoriale</span>
            <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Tableau de bord Rédaction</h1>
        </div>
        <a href="{{ route('redaction.articles.create') }}" class="btn-yellow" style="font-size:15px; padding:12px 24px;">
            + Nouvel article
        </a>
    </div>
</div>

<div class="wrap" style="padding-top:36px;">
    {{-- Filtre par statut --}}
    <div style="display:flex; gap:10px; margin-bottom:24px;">
        <a href="{{ route('redaction.articles.index') }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ empty($statut) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">Tous</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'brouillon']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'brouillon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">Brouillons</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'en_relecture']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'en_relecture' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">En relecture</a>
        <a href="{{ route('redaction.articles.index', ['statut' => 'publie']) }}" style="padding:8px 16px; font-weight:600; font-size:13px; {{ $statut === 'publie' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">Publiés</a>
    </div>

    {{-- Tableau des articles --}}
    <div style="background:#fff; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:var(--dark); color:#fff; font-weight:600;">
                    <th style="padding:14px 16px;">Titre</th>
                    <th style="padding:14px 16px;">Rubrique</th>
                    <th style="padding:14px 16px;">Auteur</th>
                    <th style="padding:14px 16px;">Statut</th>
                    <th style="padding:14px 16px;">Vues</th>
                    <th style="padding:14px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                    <tr style="border-bottom:1px solid #E5E7EB;">
                        <td style="padding:14px 16px; font-weight:600;">
                            {{ $art->titre }}
                            @if($art->est_a_la_une) <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-size:11px;">UNE</span> @endif
                            @if($art->est_reserve_abonnes) <span style="background:var(--accent); color:#fff; padding:2px 6px; font-size:11px;">PRO</span> @endif
                        </td>
                        <td style="padding:14px 16px;">{{ $art->categorie->nom }}</td>
                        <td style="padding:14px 16px;">{{ $art->auteur->name }}</td>
                        <td style="padding:14px 16px;">
                            <span style="font-weight:700; text-transform:uppercase; font-size:12px;">{{ $art->statut }}</span>
                        </td>
                        <td style="padding:14px 16px;">{{ $art->vues_compteur }}</td>
                        <td style="padding:14px 16px; text-align:right;">
                            @if($art->statut !== 'publie')
                                <form action="{{ route('redaction.articles.publier', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:#16A34A; font-weight:700; cursor:pointer;">Publier</button>
                                </form>
                            @else
                                <form action="{{ route('redaction.articles.archiver', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:600; cursor:pointer;">Archiver</button>
                                </form>
                            @endif
                            <form action="{{ route('redaction.articles.destroy', $art->id) }}" method="POST" style="display:inline; margin-left:8px;" onsubmit="return confirm('Confirmer la suppression ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#DC2626; font-weight:700; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:32px; text-align:center; color:var(--text-muted);">
                            Aucun article trouvé.
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
