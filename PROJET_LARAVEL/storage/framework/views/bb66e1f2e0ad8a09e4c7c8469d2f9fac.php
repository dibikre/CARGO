<?php $__env->startSection('titre', 'Users & RBAC Permissions'); ?>

<?php $__env->startSection('contenu'); ?>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Users & Access Control (RBAC)</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Account lifecycle, role privileges & credential management</p>
            </div>
        </div>

        
        <form method="GET" action="<?php echo e(route('admin.utilisateurs.index')); ?>" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-4 items-center justify-between">
            <div class="flex-1 min-w-[240px] flex gap-2">
                <input type="text" name="recherche" value="<?php echo e($recherche); ?>" placeholder="Search by name or email..."
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <select name="role" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="">All Roles</option>
                    <option value="super_admin" <?php echo e($roleFiltre === 'super_admin' ? 'selected' : ''); ?>>Super Admin</option>
                    <option value="administrateur" <?php echo e($roleFiltre === 'administrateur' ? 'selected' : ''); ?>>Administrator</option>
                    <option value="redacteur_en_chef" <?php echo e($roleFiltre === 'redacteur_en_chef' ? 'selected' : ''); ?>>Editor-in-Chief</option>
                    <option value="journaliste" <?php echo e($roleFiltre === 'journaliste' ? 'selected' : ''); ?>>Journalist</option>
                    <option value="redacteur" <?php echo e($roleFiltre === 'redacteur' ? 'selected' : ''); ?>>Writer</option>
                    <option value="abonne" <?php echo e($roleFiltre === 'abonne' ? 'selected' : ''); ?>>PRO Subscriber</option>
                    <option value="lecteur" <?php echo e($roleFiltre === 'lecteur' ? 'selected' : ''); ?>>Reader</option>
                </select>
                <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                    Filter
                </button>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recherche || $roleFiltre): ?>
                <a href="<?php echo e(route('admin.utilisateurs.index')); ?>" class="text-xs text-gray-500 hover:text-gray-900 underline">Reset Filters</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>

        
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Role</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Last Login</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $utilisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5">
                                    <div class="font-heading font-bold text-gray-900"><?php echo e($user->name); ?></div>
                                    <div class="text-[11px] text-gray-500 font-mono"><?php echo e($user->email); ?></div>
                                </td>
                                <td class="p-3.5">
                                    <form action="<?php echo e(route('admin.utilisateurs.update', $user->id)); ?>" method="POST" class="flex items-center gap-1.5">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="est_actif" value="<?php echo e($user->est_actif ? '1' : '0'); ?>">
                                        <select name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 rounded px-2 py-1 text-[11px] font-semibold">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['super_admin', 'administrateur', 'redacteur_en_chef', 'journaliste', 'redacteur', 'abonne', 'lecteur']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($r); ?>" <?php echo e($user->role === $r ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('_', ' ', $r))); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-3.5">
                                    <form action="<?php echo e(route('admin.utilisateurs.update', $user->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="role" value="<?php echo e($user->role); ?>">
                                        <input type="hidden" name="est_actif" value="<?php echo e($user->est_actif ? '0' : '1'); ?>">
                                        <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition <?php echo e($user->est_actif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200'); ?>">
                                            <?php echo e($user->est_actif ? 'Active' : 'Disabled'); ?>

                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 font-mono text-[11px] text-gray-500">
                                    <?php echo e($user->derniere_connexion_a ? $user->derniere_connexion_a->format('Y-m-d H:i') : 'Never'); ?>

                                </td>
                                <td class="p-3.5 text-right">
                                    <details class="relative inline-block text-left">
                                        <summary class="cursor-pointer text-xs font-semibold text-gray-600 hover:text-cargo-red list-none">Reset Key &darr;</summary>
                                        <div class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded shadow-xl p-3 z-30">
                                            <form action="<?php echo e(route('admin.utilisateurs.mdp', $user->id)); ?>" method="POST" class="space-y-2">
                                                <?php echo csrf_field(); ?>
                                                <div class="text-[11px] font-bold text-gray-800">Set New Security Password</div>
                                                <input type="password" name="nouveau_mot_de_passe" placeholder="Min 10 chars + symbols" required
                                                       class="w-full bg-gray-50 border border-gray-300 rounded p-1.5 text-xs">
                                                <button type="submit" class="w-full bg-cargo-red text-white py-1 rounded text-[11px] font-bold uppercase">
                                                    Update Password
                                                </button>
                                            </form>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">No users match your query.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($utilisateurs->hasPages()): ?>
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <?php echo e($utilisateurs->links()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administration.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/administration/utilisateurs/index.blade.php ENDPATH**/ ?>