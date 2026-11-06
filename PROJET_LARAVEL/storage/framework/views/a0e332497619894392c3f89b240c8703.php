<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['articlesPlusLus' => []]));

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

foreach (array_filter((['articlesPlusLus' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div style="background:var(--dark); color:#fff; padding:36px 40px; box-sizing:border-box;">
    <h2 class="dsp" style="margin:0 0 12px; font-weight:900; font-size:36px;">Les plus lus</h2>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articlesPlusLus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="rk">
            <b><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></b>
            <span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;"><?php echo e($article->titre); ?></span>
        </a>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <a href="#" class="rk"><b>01</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Incoterms 2026 : les erreurs fréquentes dans les contrats de vente</span></a>
        <a href="#" class="rk"><b>02</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Choisir son WMS : critères comparatifs et pièges à éviter</span></a>
        <a href="#" class="rk"><b>03</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Transport multimodal : bâtir une chaîne d'approvisionnement résiliente</span></a>
        <a href="#" class="rk"><b>04</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Retours e-commerce et logistique inverse : les nouveaux modèles</span></a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/classement-plus-lus.blade.php ENDPATH**/ ?>