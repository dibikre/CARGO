<?php $__env->startSection('titre', 'Executive Dashboard'); ?>

<?php $__env->startSection('contenu'); ?>
    <div class="space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Executive Dashboard</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Logistics publication metrics, subscriber operations & security telemetry</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('redaction.articles.create')); ?>" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Article
                </a>
            </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Total Articles</div>
                <div class="text-3xl font-heading font-black text-gray-900"><?php echo e($totalArticles); ?></div>
                <div class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                    <span class="text-emerald-700 font-semibold"><?php echo e($articlesPublies); ?> Published</span>
                    <span>&bull;</span>
                    <span class="text-amber-700 font-semibold"><?php echo e($articlesBrouillons); ?> Drafts</span>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Active Urgent Alerts</div>
                <div class="text-3xl font-heading font-black <?php echo e($articlesAlerteUrgente > 0 ? 'text-cargo-red' : 'text-gray-900'); ?>"><?php echo e($articlesAlerteUrgente); ?></div>
                <div class="text-xs text-gray-500 mt-2">
                    <?php echo e($articlesAlerteUrgente > 0 ? 'Broadcasting live ticker on portal' : 'No urgent alerts active'); ?>

                </div>
            </div>

            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">PRO Subscribers</div>
                <div class="text-3xl font-heading font-black text-gray-900"><?php echo e($totalAbonnesActifs); ?></div>
                <div class="text-xs text-gray-500 mt-2">
                    Across <strong><?php echo e($totalUtilisateurs); ?></strong> registered users
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Article Views (30 Days)</div>
                <div class="text-3xl font-heading font-black text-gray-900"><?php echo e(number_format($consultationsMois)); ?></div>
                <div class="text-xs text-gray-500 mt-2">Estimated total readership</div>
            </div>

            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Pending Comments</div>
                <div class="text-3xl font-heading font-black <?php echo e($commentairesEnAttente > 0 ? 'text-amber-600' : 'text-gray-900'); ?>"><?php echo e($commentairesEnAttente); ?></div>
                <div class="text-xs mt-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commentairesEnAttente > 0): ?>
                        <a href="<?php echo e(route('redaction.commentaires.index')); ?>" class="text-cargo-red hover:underline font-semibold">Review Queue &rarr;</a>
                    <?php else: ?>
                        <span class="text-gray-500">Moderation queue clean</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">Freight Corridors Status</h2>
                        <p class="text-xs text-gray-500">Real-time supply chain flow disruptions</p>
                    </div>
                    <a href="<?php echo e(route('admin.corridors.index')); ?>" class="text-xs text-cargo-red hover:underline font-semibold">Manage Corridors</a>
                </div>

                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $corridorsPerturbes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corridor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="p-3 rounded border border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div>
                                <div class="font-heading font-bold text-sm text-gray-900"><?php echo e($corridor->nom); ?></div>
                                <div class="text-xs text-gray-500 uppercase"><?php echo e($corridor->type_flux); ?> &bull; <?php echo e($corridor->description ?? 'No incident report'); ?></div>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded
                                <?php if($corridor->statut_operationnel === 'bloque'): ?> bg-red-100 text-red-800
                                <?php elseif($corridor->statut_operationnel === 'perturbe'): ?> bg-amber-100 text-amber-800
                                <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                <?php echo e($corridor->statut_operationnel); ?>

                            </span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="text-xs text-gray-500 py-4 text-center bg-emerald-50 rounded border border-emerald-100 text-emerald-800">
                            All freight corridors are currently operating normally (Fluid flow).
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">Latest Dispatches</h2>
                        <p class="text-xs text-gray-500">Recent logistics journalism entries</p>
                    </div>
                    <a href="<?php echo e(route('admin.articles.index')); ?>" class="text-xs text-cargo-red hover:underline font-semibold">View All</a>
                </div>

                <div class="divide-y divide-gray-100 text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $derniersArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-heading font-bold text-xs truncate text-gray-900"><?php echo e($article->titre); ?></div>
                                <div class="text-[11px] text-gray-500"><?php echo e($article->categorie->nom ?? 'General'); ?> &bull; By <?php echo e($article->auteur->name ?? 'Staff'); ?></div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->est_reserve_abonnes): ?>
                                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">PRO</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->est_urgent): ?>
                                    <span class="text-[10px] font-bold bg-red-100 text-cargo-red px-1.5 py-0.5 rounded">URGENT</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[10px] font-semibold text-gray-600 uppercase"><?php echo e($article->statut); ?></span>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="text-xs text-gray-500 py-4 text-center">No articles available.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-heading font-bold text-gray-900">Security & Operational Audit Feed</h2>
                    <p class="text-xs text-gray-500">Immutable trace of administrative events</p>
                </div>
                <a href="<?php echo e(route('admin.securite.index')); ?>" class="text-xs text-cargo-red hover:underline font-semibold">Full Audit Trail &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <th class="py-2">Timestamp</th>
                            <th class="py-2">Event</th>
                            <th class="py-2">Description</th>
                            <th class="py-2">Actor</th>
                            <th class="py-2">Client IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activitesRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr>
                                <td class="py-2.5 font-mono text-gray-500"><?php echo e($activite->created_at->format('Y-m-d H:i')); ?></td>
                                <td class="py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        <?php echo e($activite->log_name === 'securite' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e($activite->event ?? $activite->log_name); ?>

                                    </span>
                                </td>
                                <td class="py-2.5 font-medium text-gray-900"><?php echo e($activite->description); ?></td>
                                <td class="py-2.5"><?php echo e($activite->auteur->email ?? 'System / Anonymous'); ?></td>
                                <td class="py-2.5 font-mono text-gray-500"><?php echo e($activite->properties['ip'] ?? '—'); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">No security audit records logged yet.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administration.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/administration/tableau-bord.blade.php ENDPATH**/ ?>