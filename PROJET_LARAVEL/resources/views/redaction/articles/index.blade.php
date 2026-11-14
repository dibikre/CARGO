@extends('redaction.layouts.redaction')

@section('titre', 'Gestion des articles — CARGO Rédaction')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Espace Rédaction &bull; Contenu Éditorial</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Gestion des articles</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('redaction.statistiques.index') }}" class="bg-white hover:bg-gray-50 text-gray-800 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-cargo-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Audience &amp; Statistiques</span>
            </a>
            <a href="{{ route('redaction.articles.create') }}" class="bg-cargo-accent hover:bg-cargo-accentDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Rédiger un article</span>
            </a>
        </div>
    </div>
    {{-- Filtres par statut & barre de recherche --}}
    <div style="display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('redaction.articles.index', array_filter(['recherche' => $recherche, 'tri' => $tri, 'direction' => $direction])) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; {{ empty($statut) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Tous</a>
            <a href="{{ route('redaction.articles.index', array_filter(['statut' => 'brouillon', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction])) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; {{ $statut === 'brouillon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Brouillons</a>
            <a href="{{ route('redaction.articles.index', array_filter(['statut' => 'en_relecture', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction])) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; {{ $statut === 'en_relecture' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">En relecture</a>
            <a href="{{ route('redaction.articles.index', array_filter(['statut' => 'publie', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction])) }}" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; {{ $statut === 'publie' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;' }}">Publiés</a>
        </div>

        {{-- Champ de recherche aligné à droite --}}
        <form method="GET" action="{{ route('redaction.articles.index') }}" style="display:flex; align-items:center; gap:0; margin-left:auto; min-width:280px; max-width:420px; width:100%; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            @if(!empty($statut))
                <input type="hidden" name="statut" value="{{ $statut }}">
            @endif
            @if(!empty($tri))
                <input type="hidden" name="tri" value="{{ $tri }}">
            @endif
            @if(!empty($direction))
                <input type="hidden" name="direction" value="{{ $direction }}">
            @endif
            <div style="position:relative; flex:1;">
                <input type="text" name="recherche" value="{{ $recherche }}" placeholder="Rechercher par titre, auteur, rubrique..." style="width:100%; padding:9px 34px 9px 12px; font-size:13px; border:1px solid #D5D8E0; border-right:none; outline:none; background:#fff; font-family:var(--police-texte, sans-serif);">
                @if(!empty($recherche))
                    <a href="{{ route('redaction.articles.index', array_filter(['statut' => $statut, 'tri' => $tri, 'direction' => $direction])) }}" title="Effacer la recherche" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9CA3AF; text-decoration:none; display:inline-flex; align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
            <button type="submit" style="background:var(--dark); color:#fff; border:none; padding:9px 18px; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; cursor:pointer; font-family:var(--police-titre, sans-serif); white-space:nowrap;">
                Rechercher
            </button>
        </form>
    </div>

    {{-- Helper for column sorting url & arrow rendering --}}
    @php
        $lienTri = function(string $colonne) use ($statut, $recherche, $tri, $direction) {
            $nouvelleDirection = ($tri === $colonne && $direction === 'asc') ? 'desc' : 'asc';
            return route('redaction.articles.index', array_filter([
                'statut' => $statut,
                'recherche' => $recherche,
                'tri' => $colonne,
                'direction' => $nouvelleDirection,
            ]));
        };
    @endphp

    {{-- Tableau des articles --}}
    <div style="background:#fff; overflow-x:auto; border:1px solid #E2E5EB;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:var(--dark); color:#fff; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">
                    {{-- Titre / Format --}}
                    <th style="padding:14px 16px;">
                        <a href="{{ $lienTri('titre') }}" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Titre / Format</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'titre' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'titre' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    {{-- Rubrique --}}
                    <th style="padding:14px 16px;">
                        <a href="{{ $lienTri('categorie') }}" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Rubrique</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'categorie' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'categorie' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    {{-- Auteur --}}
                    <th style="padding:14px 16px;">
                        <a href="{{ $lienTri('auteur') }}" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Auteur</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'auteur' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'auteur' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    {{-- Statut --}}
                    <th style="padding:14px 16px;">
                        <a href="{{ $lienTri('statut') }}" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Statut</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'statut' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'statut' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    {{-- Vues --}}
                    <th style="padding:14px 16px;">
                        <a href="{{ $lienTri('vues') }}" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Vues</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'vues' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $tri === 'vues' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280' }}" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    {{-- Actions --}}
                    <th style="padding:14px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                    @php
                        $libelleStatut = match($art->statut) {
                            'brouillon' => 'Brouillon',
                            'en_relecture' => 'En relecture',
                            'approuve' => 'Approuvé',
                            'publie' => 'Publié',
                            'archive' => 'Archivé',
                            default => $art->statut,
                        };
                    @endphp
                    <tr style="border-bottom:1px solid #E5E7EB;">
                        <td style="padding:14px 16px; font-weight:600;">
                            <div>
                                <a href="{{ route('redaction.articles.edit', $art->id) }}" style="color:var(--dark); font-weight:700;">{{ $art->titre }}</a>
                            </div>
                            <div style="display:flex; gap:6px; margin-top:4px;">
                                @if($art->est_a_la_une) <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">À LA UNE</span> @endif
                                @if($art->est_reserve_abonnes) <span style="background:var(--accent); color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">RÉSERVÉ PRO</span> @endif
                                @if($art->est_urgent) <span style="background:#DC2626; color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">URGENT</span> @endif
                            </div>
                        </td>
                        <td style="padding:14px 16px; color:#4B505C; font-size:13px;">{{ $art->categorie->nom ?? 'Général' }}</td>
                        <td style="padding:14px 16px; font-weight:600; font-size:13px;">{{ $art->auteur->name ?? 'Rédaction' }}</td>
                        <td style="padding:14px 16px;">
                            <span style="font-weight:700; text-transform:uppercase; font-size:11px; padding:3px 8px; border-radius:3px; {{ $art->statut === 'publie' ? 'background:#DCFCE7; color:#166534;' : 'background:#F3F4F6; color:#4B505C;' }}">
                                {{ $libelleStatut }}
                            </span>
                        </td>
                        <td style="padding:14px 16px; font-mono font-size:13px;">{{ number_format($art->vues_compteur) }}</td>
                        <td style="padding:14px 16px; text-align:right; white-space:nowrap;">
                            <a href="{{ route('redaction.articles.edit', $art->id) }}" style="color:var(--dark); font-weight:700; font-size:13px; margin-right:12px; text-decoration:underline;">
                                Modifier HTML
                            </a>

                            @if($art->statut !== 'publie')
                                <form action="{{ route('redaction.articles.publier', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:#16A34A; font-weight:700; font-size:13px; cursor:pointer; margin-right:10px;">Publier</button>
                                </form>
                            @else
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" style="color:#2563EB; font-weight:600; font-size:13px; margin-right:10px;">
                                    Voir
                                </a>
                                <form action="{{ route('redaction.articles.archiver', $art->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:600; font-size:13px; cursor:pointer; margin-right:10px;">Archiver</button>
                                </form>
                            @endif

                            <form action="{{ route('redaction.articles.destroy', $art->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer définitivement cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#DC2626; font-weight:700; font-size:13px; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px; text-align:center; color:var(--text-muted); font-style:italic;">
                            Aucun article trouvé dans cette file.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:32px; display:flex; justify-content:center;">
        {{ $articles->links('composants.pagination-cargo') }}
    </div>
</div>
@endsection
