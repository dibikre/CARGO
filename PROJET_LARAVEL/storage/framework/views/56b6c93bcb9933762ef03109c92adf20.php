<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['articlesTopSemaine' => []]));

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

foreach (array_filter((['articlesTopSemaine' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bloc-top-semaine" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--accent); padding:28px 24px; box-sizing:border-box;">
    <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:18px;">
        <div>
            <span class="kick" style="color:var(--accent); font-size:calc(11.5px * var(--echelle-texte)); display:block;">Les plus lus de la semaine</span>
            <h3 class="dsp" style="margin:2px 0 0; font-size:calc(22px * var(--echelle-texte)); line-height:1.15;">Top 6 des analyses</h3>
        </div>
        <span style="background:var(--yellow); color:#14151C; font-weight:900; font-size:calc(11px * var(--echelle-texte)); padding:3px 8px; text-transform:uppercase; letter-spacing:0.06em;">
            7 Jours
        </span>
    </div>

    <div style="display:flex; flex-direction:column; gap:16px;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articlesTopSemaine; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <article style="display:flex; gap:14px; align-items:flex-start; padding-bottom:14px; <?php echo e(!$loop->last ? 'border-bottom:1px dashed var(--dashed-border);' : ''); ?>">
                <b style="font-family:var(--police-titre); font-weight:900; font-size:calc(24px * var(--echelle-texte)); color:var(--accent); line-height:1; min-width:32px;">
                    <?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?>

                </b>
                <div style="display:flex; flex-direction:column; gap:4px; min-width:0;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="kick" style="color:var(--accent-dark); font-size:calc(10.5px * var(--echelle-texte));">
                            <?php echo e($article->categorie->nom ?? 'Logistique'); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->est_reserve_abonnes): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($abonnementsActifs) && !$abonnementsActifs): ?>
                                <span style="background:#0F766E; color:#fff; font-size:calc(9.5px * var(--echelle-texte)); font-weight:800; padding:1px 5px; text-transform:uppercase;">LIBRE</span>
                            <?php else: ?>
                                <span style="background:var(--yellow); color:#14151C; font-size:calc(9.5px * var(--echelle-texte)); font-weight:800; padding:1px 5px; text-transform:uppercase;">PRO</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <h4 class="dsp" style="margin:0; font-size:calc(15px * var(--echelle-texte)); line-height:1.25; font-weight:700;">
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" style="color:var(--dark); text-decoration:none;">
                            <?php echo e($article->titre); ?>

                        </a>
                    </h4>

                    <div style="display:flex; align-items:center; gap:8px; font-size:calc(11.5px * var(--echelle-texte)); color:var(--text-muted); margin-top:2px;">
                        <span><?php echo e(number_format($article->vues_compteur)); ?> lectures</span>
                        <span>·</span>
                        <span><?php echo e($article->temps_lecture_minutes); ?> min</span>
                    </div>
                </div>
            </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p style="color:var(--text-muted); font-size:13px; font-style:italic; margin:0;">
                Le classement hebdomadaire est en cours de consolidation.
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/bloc-top-semaine.blade.php ENDPATH**/ ?>