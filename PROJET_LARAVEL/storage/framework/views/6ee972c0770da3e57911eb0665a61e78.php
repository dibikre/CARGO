<?php $__env->startSection('titre', $categorie->meta_titre ?? 'Rubrique ' . $categorie->nom . ' — CARGO'); ?>
<?php $__env->startSection('meta_description', $categorie->meta_description ?? 'Toutes les actualités sur la rubrique ' . $categorie->nom); ?>

<?php $__env->startSection('contenu'); ?>
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Rubrique thématique</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;"><?php echo e($categorie->nom); ?></h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categorie->description): ?>
            <p style="color:#C9CCD6; font-size:18px; margin-top:8px; max-width:700px;"><?php echo e($categorie->description); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php echo $__env->make('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun article dans cette rubrique actuellement</h3>
                <a href="<?php echo e(route('articles.index')); ?>" class="btn-yellow" style="margin-top:16px;">Consulter tous les articles</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        <?php echo e($articles->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.journal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/journal/articles/categorie.blade.php ENDPATH**/ ?>