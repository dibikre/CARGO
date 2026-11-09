<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['meilleursTags' => []]));

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

foreach (array_filter((['meilleursTags' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bloc-meilleurs-tags" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--accent); padding:24px 20px; box-sizing:border-box;">
    
    <div style="display:flex; justify-content:space-between; align-items:flex-end; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:16px;">
        <div>
            <span class="kick" style="color:var(--accent); font-size:calc(11.5px * var(--echelle-texte)); display:block; font-weight:700;">
                Mots-clés &amp; Acteurs majeurs
            </span>
            <h3 class="dsp" style="margin:3px 0 0; font-size:calc(21px * var(--echelle-texte)); line-height:1.15; color:var(--dark); font-weight:900;">
                Top 10 des Tags Fret
            </h3>
        </div>
        <span style="font-size:calc(10.5px * var(--echelle-texte)); font-weight:700; text-transform:uppercase; color:var(--text-muted); letter-spacing:0.04em;">
            Par volume
        </span>
    </div>

    
    <div style="display:flex; flex-direction:column; gap:8px;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $meilleursTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php $nombreArticles = $tag->articles_count ?? $tag->compteur_articles ?? $tag->articles()->count(); ?>
            <a href="<?php echo e(route('tags.articles', $tag->slug)); ?>" 
               class="tag-item-link"
               style="display:flex; justify-content:space-between; align-items:center; padding:9px 12px; background:#F8FAFC; border:1px solid #E2E8F0; text-decoration:none; transition:all 0.15s ease; border-radius:3px;">
                <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                    
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:2px; font-size:11px; font-weight:900; font-family:'Archivo',sans-serif; flex-shrink:0; <?php echo e($index < 3 ? 'background:var(--accent); color:#fff;' : 'background:#14151C; color:#fff;'); ?>">
                        <?php echo e($index + 1); ?>

                    </span>
                    <span style="font-weight:700; font-size:calc(13px * var(--echelle-texte)); color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?php echo e($tag->nom); ?>

                    </span>
                </div>
                
                <span style="font-size:calc(11px * var(--echelle-texte)); font-family:monospace; font-weight:700; color:var(--text-muted); background:#fff; border:1px solid #CBD5E1; padding:2px 7px; border-radius:2px; flex-shrink:0;">
                    <?php echo e($nombreArticles); ?> <?php echo e(Str::plural('art.', $nombreArticles)); ?>

                </span>
            </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div style="padding:16px; text-align:center; background:#F8FAFC; border:1px dashed #CBD5E1; color:var(--text-muted); font-size:12px;">
                Aucun tag enregistré pour le moment.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<style>
    .tag-item-link:hover {
        background: #fff !important;
        border-color: var(--accent) !important;
        transform: translateX(3px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .tag-item-link:hover span {
        color: var(--accent);
    }
</style>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/bloc-meilleurs-tags.blade.php ENDPATH**/ ?>