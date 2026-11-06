<?php $__env->startSection('titre', 'Espace Rédaction — Gestion des Articles CARGO'); ?>

<?php $__env->startSection('contenu'); ?>
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <span class="kick" style="color:var(--yellow);">Administration éditoriale</span>
            <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Tableau de bord Rédaction</h1>
        </div>
        <a href="<?php echo e(route('redaction.articles.create')); ?>" class="btn-yellow" style="font-size:15px; padding:12px 24px;">
            + Nouvel article
        </a>
    </div>
</div>

<div class="wrap" style="padding-top:36px;">
    
    <div style="display:flex; gap:10px; margin-bottom:24px;">
        <a href="<?php echo e(route('redaction.articles.index')); ?>" style="padding:8px 16px; font-weight:600; font-size:13px; <?php echo e(empty($statut) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);'); ?>">Tous</a>
        <a href="<?php echo e(route('redaction.articles.index', ['statut' => 'brouillon'])); ?>" style="padding:8px 16px; font-weight:600; font-size:13px; <?php echo e($statut === 'brouillon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);'); ?>">Brouillons</a>
        <a href="<?php echo e(route('redaction.articles.index', ['statut' => 'en_relecture'])); ?>" style="padding:8px 16px; font-weight:600; font-size:13px; <?php echo e($statut === 'en_relecture' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);'); ?>">En relecture</a>
        <a href="<?php echo e(route('redaction.articles.index', ['statut' => 'publie'])); ?>" style="padding:8px 16px; font-weight:600; font-size:13px; <?php echo e($statut === 'publie' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);'); ?>">Publiés</a>
    </div>

    
    <div style="background:#fff; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:var(--dark); color:#fff; font-weight:600;">
                    <th style="padding:14px 16px;">Titre</th>
                    <th style="padding:14px 16px;">Rubrique</th>
                    <th style="padding:14px 16px;">Auteur</th>
                    <th style="padding:14px 16px;">Statut</th>
                    <th style="padding:14px 16px;">Vues</th>
                    <th style="padding:14px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid #E5E7EB;">
                        <td style="padding:14px 16px; font-weight:600;">
                            <?php echo e($art->titre); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->est_a_la_une): ?> <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-size:11px;">UNE</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->est_reserve_abonnes): ?> <span style="background:var(--accent); color:#fff; padding:2px 6px; font-size:11px;">PRO</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td style="padding:14px 16px;"><?php echo e($art->categorie->nom); ?></td>
                        <td style="padding:14px 16px;"><?php echo e($art->auteur->name); ?></td>
                        <td style="padding:14px 16px;">
                            <span style="font-weight:700; text-transform:uppercase; font-size:12px;"><?php echo e($art->statut); ?></span>
                        </td>
                        <td style="padding:14px 16px;"><?php echo e($art->vues_compteur); ?></td>
                        <td style="padding:14px 16px; text-align:right;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->statut !== 'publie'): ?>
                                <form action="<?php echo e(route('redaction.articles.publier', $art->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="background:none; border:none; color:#16A34A; font-weight:700; cursor:pointer;">Publier</button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo e(route('redaction.articles.archiver', $art->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:600; cursor:pointer;">Archiver</button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <form action="<?php echo e(route('redaction.articles.destroy', $art->id)); ?>" method="POST" style="display:inline; margin-left:8px;" onsubmit="return confirm('Confirmer la suppression ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" style="background:none; border:none; color:#DC2626; font-weight:700; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" style="padding:32px; text-align:center; color:var(--text-muted);">
                            Aucun article trouvé.
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:32px; display:flex; justify-content:center;">
        <?php echo e($articles->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.journal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/redaction/articles/index.blade.php ENDPATH**/ ?>