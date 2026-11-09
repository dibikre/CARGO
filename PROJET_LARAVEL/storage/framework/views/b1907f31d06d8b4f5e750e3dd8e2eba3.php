<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['modeCle', 'config', 'centre', 'suivants']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['modeCle', 'config', 'centre', 'suivants']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section style="padding-bottom: 36px; border-bottom: 2px dashed var(--dashed-border); width: 100%; box-sizing: border-box;">
    <div>
        
        <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:24px;">
            <div style="display:flex; align-items:center; gap:14px;">
                <span class="tile" style="background:var(--accent); color:#fff; width:48px; height:48px; flex-shrink:0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($modeCle === 'maritime'): ?>
                        
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 17l2 4h16l2-4H2z"></path>
                            <path d="M6 17V8h12v9"></path>
                            <path d="M9 8V4h6v4"></path>
                            <path d="M4 12h16"></path>
                        </svg>
                    <?php elseif($modeCle === 'routier'): ?>
                        
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="1" y="5" width="14" height="11" rx="1"></rect>
                            <path d="M15 9h4l3 3v4h-7V9z"></path>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="17.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    <?php elseif($modeCle === 'ferroviaire'): ?>
                        
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="3" width="16" height="15" rx="2"></rect>
                            <path d="M4 11h16M12 3v8M8 18l-3 3M16 18l3 3M8 15h.01M16 15h.01"></path>
                        </svg>
                    <?php elseif($modeCle === 'aerien'): ?>
                        
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21 4 19.5 2.5S16 2 14.5 3.5L11 7 2.8 5.2c-.5-.1-.9.1-1.2.4l-.5.5c-.4.4-.3 1.1.2 1.4L7 12l-2 3H2l-1 2 3.5 1 1 3.5 2-1v-3l3-2 4.5 5.7c.3.5 1 .6 1.4.2l.5-.5c.3-.3.5-.7.4-1.2z"></path>
                        </svg>
                    <?php else: ?>
                        
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
                <div>
                    <span class="kick" style="color:var(--accent-dark); font-size:calc(11px * var(--echelle-texte));"><?php echo e($config['badge'] ?? 'FREIGHT DESK'); ?></span>
                    <h2 class="dsp" style="margin:2px 0 0; font-size:calc(26px * var(--echelle-texte)); line-height:1.1; letter-spacing:-.01em;">
                        <?php echo e($config['titre']); ?>

                    </h2>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:14px;">
                <p style="margin:0; max-width:380px; font-size:calc(13px * var(--echelle-texte)); line-height:1.4; color:var(--text-muted);">
                    <?php echo e($config['description']); ?>

                </p>
                <a href="<?php echo e(route('categories.articles', $config['slug'] ?? 'transport-routier')); ?>" class="kick" style="color:var(--dark); text-decoration:none; display:inline-flex; align-items:center; gap:5px; font-weight:700; white-space:nowrap; border-bottom:2px solid var(--accent); padding-bottom:2px; font-size:calc(11.5px * var(--echelle-texte));">
                    Voir tout
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        
        <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:stretch;">
            
            <div style="flex:1 1 350px; min-width:0; display:flex; flex-direction:column; background:#fff; border-top:4px solid var(--accent); padding:24px; box-sizing:border-box;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($centre): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($centre->image_principale): ?>
                        <div style="margin: -24px -24px 16px -24px; overflow:hidden; max-height:180px; border-bottom:3px solid var(--accent);">
                            <img src="<?php echo e($centre->url_image); ?>" alt="<?php echo e($centre->titre); ?>" style="width:100%; height:180px; object-fit:cover; display:block;">
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span class="kick" style="color:var(--accent-dark); font-size:calc(11.5px * var(--echelle-texte));">
                            <?php echo e($centre->categorie->nom ?? 'Analyse'); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($centre->est_reserve_abonnes): ?>
                                · <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-weight:700;">PRO</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <span style="font-size:calc(12px * var(--echelle-texte)); color:var(--text-muted);">
                            <?php echo e($centre->date_publication ? $centre->date_publication->translatedFormat('d M Y') : now()->translatedFormat('d M Y')); ?>

                        </span>
                    </div>

                    <h3 class="dsp" style="margin:0 0 12px; font-size:calc(24px * var(--echelle-texte)); line-height:1.18; letter-spacing:-.01em;">
                        <a href="<?php echo e(route('articles.show', $centre->slug)); ?>" style="color:var(--dark);">
                            <?php echo e($centre->titre); ?>

                        </a>
                    </h3>

                    <p style="margin:0 0 18px; font-size:calc(14.5px * var(--echelle-texte)); line-height:1.55; color:#4B505C;">
                        <?php echo e($centre->chapeau ?? \Illuminate\Support\Str::limit(strip_tags($centre->contenu), 150)); ?>

                    </p>

                    <div style="margin-top:auto; padding-top:16px; border-top:1px solid #E6E8EC; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px;">
                        <span style="font-size:calc(12px * var(--echelle-texte)); color:var(--text-muted); font-weight:500;">
                            Par <?php echo e($centre->auteur->name ?? 'Rédaction CARGO'); ?> · <?php echo e($centre->temps_lecture_minutes ?? 5); ?> min de lecture
                        </span>
                        <a href="<?php echo e(route('articles.show', $centre->slug)); ?>" style="background:var(--accent); color:#fff; font-family:var(--police-titre); font-size:calc(12px * var(--echelle-texte)); font-weight:700; text-transform:uppercase; letter-spacing:.05em; padding:8px 16px; display:inline-flex; align-items:center; gap:6px;">
                            Lire
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                        </a>
                    </div>
                <?php else: ?>
                    <p style="color:var(--text-muted); font-style:italic;">Aucune analyse majeure actuellement enregistrée dans cette rubrique.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div style="flex:1 1 330px; min-width:0; display:flex; flex-direction:column; justify-content:space-between; background:#fff; padding:20px 24px; box-sizing:border-box;">
                <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:10px; border-bottom:2px solid var(--dark); margin-bottom:10px;">
                    <span class="kick" style="color:var(--dark); font-weight:700; font-size:calc(11.5px * var(--echelle-texte));">Dépêches du secteur</span>
                    <span style="font-size:calc(11px * var(--echelle-texte)); color:var(--text-muted); text-transform:uppercase; font-weight:600;">En continu</span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $suivants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $artSuivant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <article style="padding: 10px 0; <?php echo e(!$loop->last ? 'border-bottom: 1px dashed var(--dashed-border);' : ''); ?>">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                            <span class="kick" style="color:var(--accent-dark); font-size:calc(10.5px * var(--echelle-texte));">
                                <?php echo e($artSuivant->categorie->nom ?? 'Dépêches'); ?>

                            </span>
                            <span style="color:var(--dashed-border);">·</span>
                            <span style="font-size:calc(11.5px * var(--echelle-texte)); color:var(--text-muted);">
                                <?php echo e($artSuivant->date_publication ? $artSuivant->date_publication->translatedFormat('d M Y') : now()->translatedFormat('d M Y')); ?>

                            </span>
                        </div>
                        <h4 class="dsp" style="margin:0 0 4px; font-size:calc(15px * var(--echelle-texte)); line-height:1.25;">
                            <a href="<?php echo e(route('articles.show', $artSuivant->slug)); ?>" style="color:var(--dark);">
                                <?php echo e($artSuivant->titre); ?>

                            </a>
                        </h4>
                        <div style="display:flex; align-items:center; gap:6px; font-size:calc(11.5px * var(--echelle-texte)); color:var(--text-muted);">
                            <span><?php echo e($artSuivant->temps_lecture_minutes ?? 4); ?> min de lecture</span>
                            <span>·</span>
                            <span><?php echo e($artSuivant->auteur->name ?? 'Correspondant Rédaction'); ?></span>
                        </div>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div style="padding: 16px 0; color:var(--text-muted); font-style:italic;">
                        Dépêches complémentaires en cours de préparation par nos correspondants.
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/section-fret.blade.php ENDPATH**/ ?>