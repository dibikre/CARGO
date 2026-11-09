<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['videosLogistiques' => []]));

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

foreach (array_filter((['videosLogistiques' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bloc-videos-embed" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--dark); padding:28px 24px; box-sizing:border-box;">
    <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:18px;">
        <div>
            <span class="kick" style="color:var(--dark); font-size:calc(11.5px * var(--echelle-texte)); display:block;">Reportages & Veille Terrain</span>
            <h3 class="dsp" style="margin:2px 0 0; font-size:calc(22px * var(--echelle-texte)); line-height:1.15;">Vidéos Logistiques</h3>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->estMembreRedaction()): ?>
                <a href="<?php echo e(route('redaction.videos.index')); ?>" title="Action rédaction : Ajouter une vidéo embarquée" style="background:var(--accent); color:#fff; font-size:calc(11px * var(--echelle-texte)); font-weight:700; padding:5px 10px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; text-transform:uppercase; font-family:var(--police-titre);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Ajouter
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div style="display:flex; flex-direction:column; gap:20px;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $videosLogistiques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div style="display:flex; flex-direction:column; gap:8px; padding-bottom:16px; <?php echo e(!$loop->last ? 'border-bottom:1px dashed var(--dashed-border);' : ''); ?>">
                
                <div style="position:relative; width:100%; padding-top:56.25%; background:#14151C; overflow:hidden; border:1px solid var(--dark-border);">
                    <iframe src="<?php echo e($video->url_iframe_formattee); ?>" 
                            title="<?php echo e($video->titre); ?>" 
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen 
                            loading="lazy">
                    </iframe>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <span class="kick" style="color:var(--accent); font-size:calc(10.5px * var(--echelle-texte));">
                            <?php echo e($video->source ?: 'Vidéo terrain CARGO'); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video->duree): ?>
                            <span style="background:#14151C; color:#fff; font-size:calc(10px * var(--echelle-texte)); font-weight:700; padding:1px 6px; border-radius:2px;">
                                <?php echo e($video->duree); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <h4 class="dsp" style="margin:2px 0 0; font-size:calc(14.5px * var(--echelle-texte)); line-height:1.25; font-weight:700; color:var(--dark);">
                        <?php echo e($video->titre); ?>

                    </h4>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video->description): ?>
                        <p style="margin:2px 0 0; font-size:calc(12px * var(--echelle-texte)); color:var(--text-muted); line-height:1.45;">
                            <?php echo e(Str::limit($video->description, 110)); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div style="padding:24px 16px; text-align:center; background:#F8FAFC; border:1px dashed var(--dashed-border); color:var(--text-muted); font-size:calc(13px * var(--echelle-texte));">
                <p style="margin:0 0 10px;">Aucun reportage vidéo intégré pour le moment.</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->estMembreRedaction()): ?>
                        <a href="<?php echo e(route('redaction.videos.index')); ?>" style="background:var(--accent); color:#fff; font-size:12px; font-weight:700; padding:6px 14px; text-decoration:none; display:inline-block; text-transform:uppercase;">
                            Publier la première vidéo
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/bloc-videos-embed.blade.php ENDPATH**/ ?>