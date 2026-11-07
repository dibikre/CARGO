@props(['videosLogistiques' => []])

<div class="bloc-videos-embed" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--dark); padding:28px 24px; box-sizing:border-box;">
    <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:18px;">
        <div>
            <span class="kick" style="color:var(--dark); font-size:calc(11.5px * var(--echelle-texte)); display:block;">Field Reports & Intelligence</span>
            <h3 class="dsp" style="margin:2px 0 0; font-size:calc(22px * var(--echelle-texte)); line-height:1.15;">Logistics Video Briefs</h3>
        </div>

        @auth
            @if(auth()->user()->estMembreRedaction())
                <a href="{{ route('redaction.videos.index') }}" title="Publisher action: Add new embedded video" style="background:var(--accent); color:#fff; font-size:calc(11px * var(--echelle-texte)); font-weight:700; padding:5px 10px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; text-transform:uppercase; font-family:var(--police-titre);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Add Video
                </a>
            @endif
        @endauth
    </div>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse($videosLogistiques as $video)
            <div style="display:flex; flex-direction:column; gap:8px; padding-bottom:16px; {{ !$loop->last ? 'border-bottom:1px dashed var(--dashed-border);' : '' }}">
                {{-- Responsive 16:9 Video Container --}}
                <div style="position:relative; width:100%; padding-top:56.25%; background:#14151C; overflow:hidden; border:1px solid var(--dark-border);">
                    <iframe src="{{ $video->url_iframe_formattee }}" 
                            title="{{ $video->titre }}" 
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen 
                            loading="lazy">
                    </iframe>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <span class="kick" style="color:var(--accent); font-size:calc(10.5px * var(--echelle-texte));">
                            {{ $video->source ?: 'Editorial Field Video' }}
                        </span>
                        @if($video->duree)
                            <span style="background:#14151C; color:#fff; font-size:calc(10px * var(--echelle-texte)); font-weight:700; padding:1px 6px; border-radius:2px;">
                                {{ $video->duree }}
                            </span>
                        @endif
                    </div>

                    <h4 class="dsp" style="margin:2px 0 0; font-size:calc(14.5px * var(--echelle-texte)); line-height:1.25; font-weight:700; color:var(--dark);">
                        {{ $video->titre }}
                    </h4>

                    @if($video->description)
                        <p style="margin:2px 0 0; font-size:calc(12px * var(--echelle-texte)); color:var(--text-muted); line-height:1.45;">
                            {{ Str::limit($video->description, 110) }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding:24px 16px; text-align:center; background:#F8FAFC; border:1px dashed var(--dashed-border); color:var(--text-muted); font-size:calc(13px * var(--echelle-texte));">
                <p style="margin:0 0 10px;">No video reports embedded yet.</p>
                @auth
                    @if(auth()->user()->estMembreRedaction())
                        <a href="{{ route('redaction.videos.index') }}" style="background:var(--accent); color:#fff; font-size:12px; font-weight:700; padding:6px 14px; text-decoration:none; display:inline-block; text-transform:uppercase;">
                            Publish First Video Embed
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>
</div>
