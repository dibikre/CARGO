@extends('redaction.layouts.redaction')

@section('titre', 'Video Embeds Management — CARGO Newsroom')

@section('contenu')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Newsroom Desk &bull; Multimedia Intelligence</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Embedded Videos Management</h1>
        </div>
        <a href="{{ route('redaction.articles.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Articles</span>
        </a>
    </div>

    <div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:36px; align-items:start;">
        
        {{-- Formulaire d'ajout rapide pour le publicateur --}}
        <div style="background:#fff; border-top:4px solid var(--accent); padding:32px; box-sizing:border-box; border:1px solid #E2E5EB;">
            <div class="kick" style="color:var(--accent); margin-bottom:8px;">Publisher Tool</div>
            <h2 class="dsp" style="margin:0 0 16px; font-size:24px;">Add New Embedded Video</h2>
            <p style="color:var(--text-muted); font-size:14px; margin-bottom:24px; line-height:1.5;">
                Add any YouTube, Vimeo, or iframe embed link to display in the homepage intelligence sidebar.
            </p>

            <form action="{{ route('redaction.videos.store') }}" method="POST" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                <div>
                    <label style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Video Title *</label>
                    <input type="text" name="titre" required placeholder="e.g. Megaship Docking at Rotterdam Terminal" class="form-input">
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Embed URL or Video Link *</label>
                    <input type="text" name="url_embed" required placeholder="e.g. https://www.youtube.com/watch?v=... or embed URL" class="form-input">
                    <span style="font-size:12px; color:var(--text-muted); display:block; margin-top:4px;">Accepts YouTube, Vimeo, or standard embed iframe links.</span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Source / Channel</label>
                        <input type="text" name="source" placeholder="e.g. Port of Rotterdam" class="form-input">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Duration</label>
                        <input type="text" name="duree" placeholder="e.g. 04:30" class="form-input">
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Short Synopsis / Context</label>
                    <textarea name="description" rows="3" placeholder="Brief explanation of the operations shown in this report..." class="form-input" style="height:auto; padding:10px;"></textarea>
                </div>

                <div>
                    <button type="submit" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; border:none; cursor:pointer; font-family:var(--police-titre); text-transform:uppercase; letter-spacing:0.05em; font-size:13px; width:100%;">
                        Publish Video Embed to Homepage
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des vidéos actuelles --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            <h2 class="dsp" style="margin:0; font-size:24px;">Active Homepage Video Embeds ({{ $videos->total() }})</h2>

            @forelse($videos as $video)
                <div style="background:#fff; border:1px solid #E2E5EB; padding:20px; display:flex; flex-direction:column; gap:12px;">
                    <div style="position:relative; padding-top:56.25%; background:#000; overflow:hidden;">
                        <iframe src="{{ $video->url_iframe_formattee }}" title="{{ $video->titre }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" allowfullscreen loading="lazy"></iframe>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                        <div>
                            <span class="kick" style="color:var(--accent); font-size:11px;">{{ $video->source ?: 'Editorial Field Video' }} @if($video->duree) · {{ $video->duree }} @endif</span>
                            <h3 class="dsp" style="margin:4px 0; font-size:18px; line-height:1.2;">{{ $video->titre }}</h3>
                            @if($video->description)
                                <p style="margin:4px 0 0; font-size:13px; color:var(--text-muted); line-height:1.4;">{{ $video->description }}</p>
                            @endif
                        </div>

                        <form action="{{ route('redaction.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Remove this embedded video?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#FEE2E2; color:#DC2626; border:1px solid #FCA5A5; padding:6px 10px; font-weight:700; font-size:12px; cursor:pointer;">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="background:#fff; border:1px dashed var(--dashed-border); padding:32px; text-align:center; color:var(--text-muted);">
                    No video embeds published yet. Add your first video report using the form.
                </div>
            @endforelse

            <div style="margin-top:16px;">
                {{ $videos->links('composants.pagination-cargo') }}
            </div>
        </div>

    </div>
    </div>
</div>
@endsection
