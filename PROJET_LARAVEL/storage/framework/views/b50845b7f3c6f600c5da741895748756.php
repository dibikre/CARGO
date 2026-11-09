
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($matieresPremieres) && $matieresPremieres->isNotEmpty()): ?>
    <section class="bandeau-matieres-conteneur" aria-label="Raw Commodities Market Ticker">
        <style>
            .bandeau-matieres-conteneur {
                background: #FFFFFF;
                border-bottom: 1px solid #E5E7EB;
                border-top: 1px solid #E5E7EB;
                color: #111827;
                overflow: hidden;
                position: relative;
                width: 100%;
                user-select: none;
                -webkit-user-select: none;
                font-family: var(--police-texte, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
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
                box-shadow: 3px 0 10px rgba(0,0,0,0.12);
                white-space: nowrap;
            }
            .bandeau-matieres-defilement {
                display: flex;
                width: max-content;
                animation: defilementTicker 48s linear infinite;
                padding-left: 150px;
                background: #FFFFFF;
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
                padding: 8px 20px;
                border-right: 1px solid #F1F3F5;
                pointer-events: none; /* Rendre explicitement non clicable */
                cursor: default;
                white-space: nowrap;
                background: #FFFFFF;
            }
            .matiere-icone {
                width: 26px;
                height: 26px;
                object-fit: contain;
                display: block;
                filter: drop-shadow(0 1px 2px rgba(0,0,0,0.12));
            }
            .matiere-infos {
                display: flex;
                flex-direction: column;
                line-height: 1.15;
            }
            .matiere-nom {
                font-size: 11px;
                font-weight: 700;
                color: #374151;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .matiere-prix {
                font-size: 12px;
                font-weight: 800;
                font-family: monospace;
                color: #111827;
            }
            .matiere-devise {
                font-size: 9px;
                color: #6B7280;
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
                border-radius: 4px;
                display: inline-flex;
                align-items: center;
            }
            .ecart-hausse {
                color: #047857;
                background: #ECFDF5;
                border: 1px solid #D1FAE5;
            }
            .ecart-baisse {
                color: #B91C1C;
                background: #FEF2F2;
                border: 1px solid #FEE2E2;
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

        
        <div class="bandeau-matieres-etiquette" aria-hidden="true">
            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#FFF; animation: pulse 1.5s infinite;"></span>
            <span>Matières Premières</span>
        </div>

        
        <div class="bandeau-matieres-defilement" role="marquee" aria-live="off">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $matieresPremieres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matiere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="matiere-item" role="group" aria-label="<?php echo e($matiere->nom); ?>: <?php echo e($matiere->prix_formate); ?> <?php echo e($matiere->devise); ?>">
                    
                    <img src="<?php echo e(asset($matiere->image_png)); ?>" alt="" class="matiere-icone" />

                    
                    <div class="matiere-infos">
                        <span class="matiere-nom"><?php echo e($matiere->nom); ?></span>
                        <span class="matiere-prix">
                            <?php echo e($matiere->prix_formate); ?>

                            <span class="matiere-devise"><?php echo e($matiere->devise); ?></span>
                        </span>
                    </div>

                    
                    <svg class="matiere-courbe" viewBox="0 0 60 20" aria-hidden="true">
                        <path d="<?php echo e($matiere->genererTraceSvg(60, 20)); ?>" fill="none" stroke="<?php echo e($matiere->couleur_tendance); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    
                    <span class="matiere-ecart <?php echo e($matiere->pourcentage_variation >= 0 ? 'ecart-hausse' : 'ecart-baisse'); ?>">
                        <?php echo e($matiere->pourcentage_variation >= 0 ? '+' : ''); ?><?php echo e($matiere->pourcentage_variation); ?>%
                    </span>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $matieresPremieres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matiere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="matiere-item" aria-hidden="true">
                    <img src="<?php echo e(asset($matiere->image_png)); ?>" alt="" class="matiere-icone" />
                    <div class="matiere-infos">
                        <span class="matiere-nom"><?php echo e($matiere->nom); ?></span>
                        <span class="matiere-prix">
                            <?php echo e($matiere->prix_formate); ?>

                            <span class="matiere-devise"><?php echo e($matiere->devise); ?></span>
                        </span>
                    </div>
                    <svg class="matiere-courbe" viewBox="0 0 60 20" aria-hidden="true">
                        <path d="<?php echo e($matiere->genererTraceSvg(60, 20)); ?>" fill="none" stroke="<?php echo e($matiere->couleur_tendance); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="matiere-ecart <?php echo e($matiere->pourcentage_variation >= 0 ? 'ecart-hausse' : 'ecart-baisse'); ?>">
                        <?php echo e($matiere->pourcentage_variation >= 0 ? '+' : ''); ?><?php echo e($matiere->pourcentage_variation); ?>%
                    </span>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/bandeau-cours-matieres.blade.php ENDPATH**/ ?>