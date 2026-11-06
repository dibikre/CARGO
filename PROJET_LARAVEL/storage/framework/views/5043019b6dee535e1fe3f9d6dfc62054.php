<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('titre', 'Security Console'); ?> — CARGO Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cargo: {
                            dark: '#14151C',
                            surface: '#1D1E28',
                            red: '#D4201A',
                            redDark: '#B01813',
                            yellow: '#FFC93C',
                            grayLight: '#F3F4F6'
                        }
                    },
                    fontFamily: {
                        sans: ['IBM Plex Sans', 'sans-serif'],
                        heading: ['Archivo', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    
    <header class="bg-cargo-dark text-white border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.tableau-bord')); ?>" class="flex items-center gap-2">
                    <span class="bg-cargo-red text-white font-heading font-black tracking-wider text-xl px-2.5 py-1">CARGO</span>
                    <span class="text-xs uppercase tracking-widest text-cargo-yellow font-semibold border-l border-gray-700 pl-3">Security Console</span>
                </a>

                <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                    <a href="<?php echo e(route('admin.tableau-bord')); ?>" class="px-3 py-2 rounded-md transition <?php echo e(request()->routeIs('admin.tableau-bord') ? 'bg-cargo-surface text-white' : 'text-gray-300 hover:text-white'); ?>">Dashboard</a>
                    <a href="<?php echo e(route('admin.articles.index')); ?>" class="px-3 py-2 rounded-md transition <?php echo e(request()->routeIs('admin.articles.*') ? 'bg-cargo-surface text-white' : 'text-gray-300 hover:text-white'); ?>">Articles & Alerts</a>
                    <a href="<?php echo e(route('admin.corridors.index')); ?>" class="px-3 py-2 rounded-md transition <?php echo e(request()->routeIs('admin.corridors.*') ? 'bg-cargo-surface text-white' : 'text-gray-300 hover:text-white'); ?>">Corridors</a>
                    <a href="<?php echo e(route('admin.utilisateurs.index')); ?>" class="px-3 py-2 rounded-md transition <?php echo e(request()->routeIs('admin.utilisateurs.*') ? 'bg-cargo-surface text-white' : 'text-gray-300 hover:text-white'); ?>">Users & RBAC</a>
                    <a href="<?php echo e(route('admin.securite.index')); ?>" class="px-3 py-2 rounded-md transition <?php echo e(request()->routeIs('admin.securite.*') ? 'bg-cargo-surface text-white' : 'text-gray-300 hover:text-white'); ?>">Audit Logs</a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('accueil')); ?>" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-gray-300 hover:text-white bg-gray-800 px-3 py-1.5 rounded border border-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Live Portal
                </a>

                <div class="flex items-center gap-3 border-l border-gray-700 pl-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-white"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></div>
                        <div class="text-[10px] uppercase font-semibold text-cargo-yellow"><?php echo e(Auth::user()->role ?? 'Admin'); ?></div>
                    </div>

                    <form action="<?php echo e(route('admin.deconnexion')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-gray-800 hover:bg-cargo-red text-white p-2 rounded transition" title="Sign Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    
    <div class="bg-yellow-50 border-b border-yellow-200 px-4 py-1.5 text-xs text-yellow-800 font-medium flex items-center justify-between max-w-7xl mx-auto w-full">
        <div class="flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Security Session Active: IP <strong><?php echo e(request()->ip()); ?></strong> verified. Clickjacking & CSRF shields enabled.</span>
        </div>
        <span class="hidden md:inline text-yellow-700 text-[11px]"><?php echo e(now()->toRfc850String()); ?></span>
    </div>

    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('succes')): ?>
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded text-emerald-800 text-sm flex items-center justify-between">
                <span><?php echo e(session('succes')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('erreur')): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-cargo-red p-4 rounded text-red-800 text-sm flex items-center justify-between">
                <span><?php echo e(session('erreur')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php echo $__env->yieldContent('contenu'); ?>
    </main>

    
    <footer class="bg-white border-t border-gray-200 py-4 text-xs text-gray-500 text-center">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>CARGO Logistics Media — Administrative Control Plane v2.4 (High Security Profile)</div>
            <div>Shared Hosting Hardened &bull; Database Session &bull; Throttled Access</div>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\laragon\www\journal\resources\views/administration/layouts/admin.blade.php ENDPATH**/ ?>