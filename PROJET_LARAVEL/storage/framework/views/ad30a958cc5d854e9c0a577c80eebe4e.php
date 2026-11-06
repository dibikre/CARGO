<?php $__env->startSection('titre', 'Logistics Corridors Supervision'); ?>

<?php $__env->startSection('contenu'); ?>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Freight Corridors & Disruption Dispatch</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Live supply chain disruption management across maritime, rail, road & air corridors</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm h-fit">
                <h2 class="text-sm font-heading font-bold text-gray-900 mb-4">Register New Freight Corridor</h2>
                <form action="<?php echo e(route('admin.corridors.store')); ?>" method="POST" class="space-y-3.5">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Corridor Name</label>
                        <input type="text" name="nom" required placeholder="e.g. Rotterdam - Genoa Rail Link"
                               class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Transport Mode</label>
                        <select name="type_flux" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="maritime">Maritime / Deep Sea</option>
                            <option value="ferroviaire">Rail Freight</option>
                            <option value="routier">Road Haulage</option>
                            <option value="aerien">Air Cargo</option>
                            <option value="multimodal">Multimodal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Initial Status</label>
                        <select name="statut_operationnel" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs font-semibold">
                            <option value="normal">Fluide (Normal Flow)</option>
                            <option value="ralenti">Ralenti (Slowed Down)</option>
                            <option value="perturbe">Perturbe (Disrupted)</option>
                            <option value="congestionne">Congestionne (Congested)</option>
                            <option value="bloque">Bloque (Blocked / Closed)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Status Description / Warning</label>
                        <textarea name="description" rows="2" placeholder="Optional incident bulletin..."
                                  class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-cargo-red hover:bg-cargo-redDark text-white py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                        Add Corridor
                    </button>
                </form>
            </div>

            
            <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <span class="text-xs font-heading font-bold text-gray-800 uppercase tracking-wider">Monitored Freight Lines</span>
                    <span class="text-xs text-gray-500"><?php echo e(count($corridors)); ?> Corridors Configured</span>
                </div>

                <div class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $corridors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corridor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-heading font-bold text-sm text-gray-900"><?php echo e($corridor->nom); ?></span>
                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-700"><?php echo e($corridor->type_flux); ?></span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($corridor->description ?? 'No specific incident logged'); ?></div>
                            </div>

                            <form action="<?php echo e(route('admin.corridors.statut', $corridor->id)); ?>" method="POST" class="flex items-center gap-2 shrink-0">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <select name="statut_operationnel" onchange="this.form.submit()" class="text-xs font-bold rounded p-1.5 border border-gray-300
                                    <?php if($corridor->statut_operationnel === 'bloque'): ?> bg-red-100 text-red-800
                                    <?php elseif($corridor->statut_operationnel === 'perturbe'): ?> bg-amber-100 text-amber-800
                                    <?php elseif($corridor->statut_operationnel === 'congestionne'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($corridor->statut_operationnel === 'ralenti'): ?> bg-blue-100 text-blue-800
                                    <?php else: ?> bg-emerald-100 text-emerald-800 <?php endif; ?>">
                                    <option value="normal" <?php echo e(in_array($corridor->statut_operationnel, ['normal', 'fluide'], true) ? 'selected' : ''); ?>>Fluide</option>
                                    <option value="ralenti" <?php echo e($corridor->statut_operationnel === 'ralenti' ? 'selected' : ''); ?>>Ralenti</option>
                                    <option value="perturbe" <?php echo e($corridor->statut_operationnel === 'perturbe' ? 'selected' : ''); ?>>Perturbe</option>
                                    <option value="congestionne" <?php echo e($corridor->statut_operationnel === 'congestionne' ? 'selected' : ''); ?>>Congestionne</option>
                                    <option value="bloque" <?php echo e($corridor->statut_operationnel === 'bloque' ? 'selected' : ''); ?>>Bloque</option>
                                </select>
                            </form>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="p-8 text-center text-xs text-gray-500">No freight corridors registered yet.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administration.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/administration/corridors/index.blade.php ENDPATH**/ ?>