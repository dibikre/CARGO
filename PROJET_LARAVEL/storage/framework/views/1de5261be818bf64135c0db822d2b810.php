<?php $__env->startSection('titre', 'Articles Management — CARGO Newsroom'); ?>

<?php $__env->startSection('contenu'); ?>
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Newsroom Desk &bull; Editorial Content</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Articles Management</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('redaction.statistiques.index')); ?>" class="bg-white hover:bg-gray-50 text-gray-800 font-bold text-xs uppercase tracking-wider px-3.5 py-2.5 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-cargo-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Audience & Stats</span>
            </a>
            <a href="<?php echo e(route('redaction.articles.create')); ?>" class="bg-cargo-accent hover:bg-cargo-accentDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Write Article</span>
            </a>
        </div>
    </div>
    
    <div style="display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <a href="<?php echo e(route('redaction.articles.index', array_filter(['recherche' => $recherche, 'tri' => $tri, 'direction' => $direction]))); ?>" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; <?php echo e(empty($statut) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;'); ?>">All</a>
            <a href="<?php echo e(route('redaction.articles.index', array_filter(['statut' => 'brouillon', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction]))); ?>" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; <?php echo e($statut === 'brouillon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;'); ?>">Drafts</a>
            <a href="<?php echo e(route('redaction.articles.index', array_filter(['statut' => 'en_relecture', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction]))); ?>" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; <?php echo e($statut === 'en_relecture' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;'); ?>">Under Review</a>
            <a href="<?php echo e(route('redaction.articles.index', array_filter(['statut' => 'publie', 'recherche' => $recherche, 'tri' => $tri, 'direction' => $direction]))); ?>" style="padding:8px 16px; font-weight:700; font-size:13px; text-transform:uppercase; text-decoration:none; <?php echo e($statut === 'publie' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark); border:1px solid #D5D8E0;'); ?>">Published</a>
        </div>

        
        <form method="GET" action="<?php echo e(route('redaction.articles.index')); ?>" style="display:flex; align-items:center; gap:0; margin-left:auto; min-width:280px; max-width:420px; width:100%; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($statut)): ?>
                <input type="hidden" name="statut" value="<?php echo e($statut); ?>">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($tri)): ?>
                <input type="hidden" name="tri" value="<?php echo e($tri); ?>">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($direction)): ?>
                <input type="hidden" name="direction" value="<?php echo e($direction); ?>">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div style="position:relative; flex:1;">
                <input type="text" name="recherche" value="<?php echo e($recherche); ?>" placeholder="Search title, author, section..." style="width:100%; padding:9px 34px 9px 12px; font-size:13px; border:1px solid #D5D8E0; border-right:none; outline:none; background:#fff; font-family:var(--police-texte, sans-serif);">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($recherche)): ?>
                    <a href="<?php echo e(route('redaction.articles.index', array_filter(['statut' => $statut, 'tri' => $tri, 'direction' => $direction]))); ?>" title="Clear search" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9CA3AF; text-decoration:none; font-size:14px; line-height:1; font-weight:bold;">✕</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <button type="submit" style="background:var(--dark); color:#fff; border:none; padding:9px 18px; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; cursor:pointer; font-family:var(--police-titre, sans-serif); white-space:nowrap;">
                Search
            </button>
        </form>
    </div>

    
    <?php
        $lienTri = function(string $colonne) use ($statut, $recherche, $tri, $direction) {
            $nouvelleDirection = ($tri === $colonne && $direction === 'asc') ? 'desc' : 'asc';
            return route('redaction.articles.index', array_filter([
                'statut' => $statut,
                'recherche' => $recherche,
                'tri' => $colonne,
                'direction' => $nouvelleDirection,
            ]));
        };
    ?>

    
    <div style="background:#fff; overflow-x:auto; border:1px solid #E2E5EB;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:var(--dark); color:#fff; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">
                    
                    <th style="padding:14px 16px;">
                        <a href="<?php echo e($lienTri('titre')); ?>" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Title / Format</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'titre' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'titre' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    
                    <th style="padding:14px 16px;">
                        <a href="<?php echo e($lienTri('categorie')); ?>" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Section</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'categorie' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'categorie' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    
                    <th style="padding:14px 16px;">
                        <a href="<?php echo e($lienTri('auteur')); ?>" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Author</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'auteur' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'auteur' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    
                    <th style="padding:14px 16px;">
                        <a href="<?php echo e($lienTri('statut')); ?>" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Status</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'statut' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'statut' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    
                    <th style="padding:14px 16px;">
                        <a href="<?php echo e($lienTri('vues')); ?>" style="color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>Views</span>
                            <span style="display:inline-flex; flex-direction:column; line-height:1;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'vues' && $direction === 'asc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block; margin-bottom:-2px;"><path d="M18 15l-6-6-6 6"/></svg>
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($tri === 'vues' && $direction === 'desc' ? 'var(--yellow)' : '#6B7280'); ?>" stroke-width="3" style="display:block;"><path d="M6 9l6 6 6-6"/></svg>
                            </span>
                        </a>
                    </th>
                    
                    <th style="padding:14px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid #E5E7EB;">
                        <td style="padding:14px 16px; font-weight:600;">
                            <div>
                                <a href="<?php echo e(route('redaction.articles.edit', $art->id)); ?>" style="color:var(--dark); font-weight:700;"><?php echo e($art->titre); ?></a>
                            </div>
                            <div style="display:flex; gap:6px; margin-top:4px;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->est_a_la_une): ?> <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">HEADLINE</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->est_reserve_abonnes): ?> <span style="background:var(--accent); color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">PRO ONLY</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->est_urgent): ?> <span style="background:#DC2626; color:#fff; padding:2px 6px; font-size:10px; font-weight:800; text-transform:uppercase;">URGENT</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </td>
                        <td style="padding:14px 16px; color:#4B505C; font-size:13px;"><?php echo e($art->categorie->nom ?? 'General'); ?></td>
                        <td style="padding:14px 16px; font-weight:600; font-size:13px;"><?php echo e($art->auteur->name ?? 'Staff'); ?></td>
                        <td style="padding:14px 16px;">
                            <span style="font-weight:700; text-transform:uppercase; font-size:11px; padding:3px 8px; border-radius:3px; <?php echo e($art->statut === 'publie' ? 'background:#DCFCE7; color:#166534;' : 'background:#F3F4F6; color:#4B505C;'); ?>">
                                <?php echo e($art->statut); ?>

                            </span>
                        </td>
                        <td style="padding:14px 16px; font-mono font-size:13px;"><?php echo e(number_format($art->vues_compteur)); ?></td>
                        <td style="padding:14px 16px; text-align:right; white-space:nowrap;">
                            <a href="<?php echo e(route('redaction.articles.edit', $art->id)); ?>" style="color:var(--dark); font-weight:700; font-size:13px; margin-right:12px; text-decoration:underline;">
                                Edit HTML
                            </a>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->statut !== 'publie'): ?>
                                <form action="<?php echo e(route('redaction.articles.publier', $art->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="background:none; border:none; color:#16A34A; font-weight:700; font-size:13px; cursor:pointer; margin-right:10px;">Publish</button>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('articles.show', $art->slug)); ?>" target="_blank" style="color:#2563EB; font-weight:600; font-size:13px; margin-right:10px;">
                                    View
                                </a>
                                <form action="<?php echo e(route('redaction.articles.archiver', $art->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:600; font-size:13px; cursor:pointer; margin-right:10px;">Archive</button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <form action="<?php echo e(route('redaction.articles.destroy', $art->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this article?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" style="background:none; border:none; color:#DC2626; font-weight:700; font-size:13px; cursor:pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" style="padding:40px; text-align:center; color:var(--text-muted); font-style:italic;">
                            No articles found in this queue.
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:32px; display:flex; justify-content:center;">
        <?php echo e($articles->links('composants.pagination-cargo')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('redaction.layouts.redaction', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/redaction/articles/index.blade.php ENDPATH**/ ?>