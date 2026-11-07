{{-- Bandeau defilant des matieres premieres brutes (non clicable, 20 produits max) --}}
@if(isset($matieresPremieres) && $matieresPremieres->isNotEmpty())
    <section class="bandeau-matieres-conteneur" aria-label="Raw Commodities Market Ticker">
        <style>
            .bandeau-matieres-conteneur {
                background: #0D0E13;
                border-bottom: 1px solid #232632;
                color: #FFFFFF;
                overflow: hidden;
                position: relative;
                width: 100%;
                user-select: none;
                -webkit-user-select: none;
                font-family: var(--police-texte, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
            }
            .bandeau-matieres-etiquette {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 10;
                background: #C41C13;
                color: #FFFFFF;
                font-family: var(--police-titre, 'Montserrat', sans-serif);
                font-size: 10.5px;
                font-weight: 900;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 0 14px;
                box-shadow: 4px 0 12px rgba(0,0,0,0.4);
                white-space: nowrap;
            }
            .bandeau-matieres-defilement {
                display: flex;
                width: max-content;
                animation: defilementTicker 48s linear infinite;
                padding-left: 150px;
            }
            .bandeau-matieres-defilement:hover {
                animation-play-state: paused;
            }
            @keyframes defilementTicker {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .matiere-item {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 7px 18px;
                border-right: 1px solid #1E212B;
                pointer-events: none; /* Rendre explicitement non clicable */
                cursor: default;
                white-space: nowrap;
            }
            .matiere-icone {
                width: 24px;
                height: 24px;
                object-fit: contain;
                display: block;
                filter: drop-shadow(0 1px 2px rgba(0,0,0,0.5));
            }
            .matiere-infos {
                display: flex;
                flex-direction: column;
                line-height: 1.15;
            }
            .matiere-nom {
                font-size: 11px;
                font-weight: 700;
                color: #C9CCD6;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .matiere-prix {
                font-size: 12px;
                font-weight: 800;
                font-family: monospace;
                color: #FFFFFF;
            }
            .matiere-devise {
                font-size: 9px;
                color: #8C91A0;
                font-weight: 600;
            }
            .matiere-courbe {
                width: 60px;
                height: 20px;
                overflow: visible;
                display: block;
            }
            .matiere-ecart {
                font-size: 11px;
                font-weight: 800;
                font-family: monospace;
                padding: 2px 6px;
                border-radius: 3px;
                display: inline-flex;
                align-items: center;
            }
            .ecart-hausse {
                color: #34D399;
                background: rgba(16, 185, 129, 0.12);
            }
            .ecart-baisse {
                color: #F87171;
                background: rgba(239, 68, 68, 0.12);
            }
            @media (max-width: 640px) {
                .bandeau-matieres-etiquette {
                    display: none;
                }
                .bandeau-matieres-defilement {
                    padding-left: 0;
                }
            }
        </style>

        {{-- Badge fixe a gauche --}}
        <div class="bandeau-matieres-etiquette" aria-hidden="true">
            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#FFF; animation: pulse 1.5s infinite;"></span>
            <span>Commodities</span>
        </div>

        {{-- Bandeau defilant boucle en continu --}}
        <div class="bandeau-matieres-defilement" role="marquee" aria-live="off">
            {{-- Boucle 1 --}}
            @foreach($matieresPremieres as $matiere)
                <div class="matiere-item" role="group" aria-label="{{ $matiere->nom }}: {{ $matiere->prix_formate }} {{ $matiere->devise }}">
                    {{-- Image PNG sans arriere-plan --}}
                    <img src="{{ asset($matiere->image_png) }}" alt="" class="matiere-icone" />

                    {{-- Nom & Prix actuel --}}
                    <div class="matiere-infos">
                        <span class="matiere-nom">{{ $matiere->nom }}</span>
                        <span class="matiere-prix">
                            {{ $matiere->prix_formate }}
                            <span class="matiere-devise">{{ $matiere->devise }}</span>
                        </span>
                    </div>

                    {{-- Tracee SVG de la courbe (5 a 10 points) --}}
                    <svg class="matiere-courbe" viewBox="0 0 60 20" aria-hidden="true">
                        <path d="{{ $matiere->genererTraceSvg(60, 20) }}" fill="none" stroke="{{ $matiere->couleur_tendance }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    {{-- Ecart entre l'entree et la sortie (-7%, etc.) --}}
                    <span class="matiere-ecart {{ $matiere->pourcentage_variation >= 0 ? 'ecart-hausse' : 'ecart-baisse' }}">
                        {{ $matiere->pourcentage_variation >= 0 ? '+' : '' }}{{ $matiere->pourcentage_variation }}%
                    </span>
                </div>
            @endforeach

            {{-- Boucle 2 pour continuite fluide du defilement --}}
            @foreach($matieresPremieres as $matiere)
                <div class="matiere-item" aria-hidden="true">
                    <img src="{{ asset($matiere->image_png) }}" alt="" class="matiere-icone" />
                    <div class="matiere-infos">
                        <span class="matiere-nom">{{ $matiere->nom }}</span>
                        <span class="matiere-prix">
                            {{ $matiere->prix_formate }}
                            <span class="matiere-devise">{{ $matiere->devise }}</span>
                        </span>
                    </div>
                    <svg class="matiere-courbe" viewBox="0 0 60 20" aria-hidden="true">
                        <path d="{{ $matiere->genererTraceSvg(60, 20) }}" fill="none" stroke="{{ $matiere->couleur_tendance }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="matiere-ecart {{ $matiere->pourcentage_variation >= 0 ? 'ecart-hausse' : 'ecart-baisse' }}">
                        {{ $matiere->pourcentage_variation >= 0 ? '+' : '' }}{{ $matiere->pourcentage_variation }}%
                    </span>
                </div>
            @endforeach
        </div>
    </section>
@endif
