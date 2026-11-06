<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['article', 'theme' => 'clair']));

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

foreach (array_filter((['article', 'theme' => 'clair']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $estSombre = $theme === 'sombre';
    $mode = $article->mode_transport_principal ?? 'multimodal';
?>

<article class="card">
    <span class="tile" style="background: <?php echo e($estSombre ? '#14151C' : 'var(--accent)'); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mode === 'maritime'): ?>
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="<?php echo e($estSombre ? 'var(--yellow)' : '#fff'); ?>" stroke-width="3" stroke-linejoin="round">
                <path d="M3 30h42l-6 10H9zM10 30V20h16v10M28 30V14h6v16"></path>
            </svg>
        <?php elseif($mode === 'ferroviaire'): ?>
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="<?php echo e($estSombre ? 'var(--yellow)' : '#fff'); ?>" stroke-width="3" stroke-linejoin="round">
                <path d="M8 8h32v26H8zM8 24h32M16 40l-4 6M32 40l4 6"></path><circle cx="16" cy="30" r="2"></circle><circle cx="32" cy="30" r="2"></circle>
            </svg>
        <?php elseif($mode === 'e-commerce' || $mode === 'entreposage'): ?>
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="<?php echo e($estSombre ? 'var(--yellow)' : '#fff'); ?>" stroke-width="3" stroke-linejoin="round">
                <path d="M6 14l18-8 18 8v20l-18 8-18-8zM6 14l18 8 18-8M24 22v20"></path>
            </svg>
        <?php else: ?>
            <svg aria-hidden="true" width="36" height="36" viewBox="0 0 48 48" fill="none" stroke="<?php echo e($estSombre ? 'var(--yellow)' : '#fff'); ?>" stroke-width="3" stroke-linejoin="round">
                <path d="M2 14h26v18H2zM28 20h10l6 7v5H28z"></path><circle cx="12" cy="35" r="4"></circle><circle cx="36" cy="35" r="4"></circle>
            </svg>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </span>

    <span class="kick" style="color: var(--accent-dark);">
        <?php echo e($article->categorie->nom ?? 'Logistique'); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->est_reserve_abonnes): ?>
            · <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-weight:700;">PRO</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </span>

    <h3 class="dsp" style="margin:0; font-size:24px; line-height:1.15;">
        <a href="<?php echo e(route('articles.show', $article->slug)); ?>"><?php echo e($article->titre); ?></a>
    </h3>

    <span style="font-size:14px; color:var(--text-muted); margin-top:auto;">
        Par <?php echo e($article->auteur->name ?? 'La Rédaction'); ?> · <?php echo e($article->temps_lecture_minutes); ?> min de lecture
    </span>
</article>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/carte-article.blade.php ENDPATH**/ ?>