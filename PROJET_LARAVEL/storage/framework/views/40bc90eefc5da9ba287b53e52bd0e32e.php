<?php $__env->startSection('titre', 'Audience & Publication Analytics — CARGO Editorial'); ?>

<?php $__env->startSection('contenu'); ?>
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Editorial Performance & Readership</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Audience & Publications Analytics</h1>
        </div>

        
        <div class="flex items-center bg-white border border-gray-300 rounded p-1 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 px-3 uppercase">Period:</span>
            <a href="<?php echo e(route('redaction.statistiques.index', ['periode' => '7'])); ?>" class="px-3 py-1.5 text-xs font-bold rounded <?php echo e($periode === '7' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100'); ?>">Last 7 Days</a>
            <a href="<?php echo e(route('redaction.statistiques.index', ['periode' => '30'])); ?>" class="px-3 py-1.5 text-xs font-bold rounded <?php echo e($periode === '30' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100'); ?>">Last 30 Days</a>
            <a href="<?php echo e(route('redaction.statistiques.index', ['periode' => '90'])); ?>" class="px-3 py-1.5 text-xs font-bold rounded <?php echo e($periode === '90' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100'); ?>">Last 90 Days</a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cumulative Unique Views</div>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-2"><?php echo e(number_format($totalVuesCumulees)); ?></div>
            <div class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>Live IP-verified readings</span>
            </div>
            <div class="absolute right-3 top-3 text-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Published in Period</div>
            <div class="text-3xl font-heading font-black text-cargo-accent mt-2"><?php echo e($publicationsPeriode); ?></div>
            <div class="text-xs text-gray-500 font-semibold mt-1">Articles published in past <?php echo e($jours); ?> days</div>
            <div class="absolute right-3 top-3 text-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">All-Time Published</div>
            <div class="text-3xl font-heading font-black text-emerald-600 mt-2"><?php echo e($totalPublies); ?></div>
            <div class="text-xs text-gray-500 font-semibold mt-1"><?php echo e($totalArticles); ?> articles in newsroom database</div>
            <div class="absolute right-3 top-3 text-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Editorial Pipeline</div>
            <div class="text-3xl font-heading font-black text-amber-500 mt-2"><?php echo e($totalRelecture + $totalBrouillons); ?></div>
            <div class="text-xs text-gray-500 font-semibold mt-1"><?php echo e($totalRelecture); ?> under review &bull; <?php echo e($totalBrouillons); ?> drafts</div>
            <div class="absolute right-3 top-3 text-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-heading font-black text-cargo-dark">Daily Unique Views Trend</h2>
                    <p class="text-xs text-gray-500">Readership dynamics over the selected timeframe</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-cargo-yellow"></span>
                    Views
                </span>
            </div>

            <div class="h-64 w-full relative">
                <canvas id="graphiqueVues" class="w-full h-full"></canvas>
            </div>
        </div>

        
        <div class="bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-heading font-black text-cargo-dark">Publication Rhythm</h2>
                    <p class="text-xs text-gray-500">Daily published articles count</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-red-50 text-red-800 border border-red-200">
                    <span class="w-2 h-2 rounded-full bg-cargo-accent"></span>
                    Articles
                </span>
            </div>

            <div class="h-64 w-full relative">
                <canvas id="graphiquePublications" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
            <div>
                <h2 class="text-base font-heading font-black text-cargo-dark">Publications by Section</h2>
                <p class="text-xs text-gray-500">Distribution of articles across logistics verticals</p>
            </div>

            <div class="space-y-3 pt-2">
                <?php
                    $maxArticlesSection = max(1, $categoriesStats->max('articles_publies') ?? 1);
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categoriesStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $pourcentage = round(($catStat->articles_publies / $maxArticlesSection) * 100);
                    ?>
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-gray-800"><?php echo e($catStat->nom); ?></span>
                            <span class="text-gray-500 font-mono"><?php echo e($catStat->articles_publies); ?> published (<?php echo e($catStat->total_articles); ?> total)</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-cargo-accent rounded-full" style="width: <?php echo e($pourcentage); ?>%;"></div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <p class="text-xs text-gray-400 italic py-4">No published articles yet to calculate section ratios.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="lg:col-span-2 bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-heading font-black text-cargo-dark">Top 10 Most-Read Articles</h2>
                    <p class="text-xs text-gray-500">Ranked by real IP-verified reading counter</p>
                </div>
                <a href="<?php echo e(route('redaction.articles.index', ['tri' => 'vues', 'direction' => 'desc'])); ?>" class="text-xs text-cargo-accent hover:underline font-bold">
                    View full articles list &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-400 uppercase font-bold tracking-wider">
                            <th class="py-2 px-3">#</th>
                            <th class="py-2 px-3">Article Title</th>
                            <th class="py-2 px-3">Section</th>
                            <th class="py-2 px-3">Author</th>
                            <th class="py-2 px-3 text-right">Views</th>
                            <th class="py-2 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $topArt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-2.5 px-3 font-mono font-bold text-gray-400"><?php echo e($index + 1); ?></td>
                                <td class="py-2.5 px-3 font-semibold text-gray-900 max-w-xs truncate">
                                    <a href="<?php echo e(route('redaction.articles.edit', $topArt->id)); ?>" class="hover:text-cargo-accent">
                                        <?php echo e($topArt->titre); ?>

                                    </a>
                                </td>
                                <td class="py-2.5 px-3 text-gray-500"><?php echo e($topArt->categorie->nom ?? 'General'); ?></td>
                                <td class="py-2.5 px-3 text-gray-600"><?php echo e($topArt->auteur->name ?? 'Staff'); ?></td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-cargo-dark">
                                    <?php echo e(number_format($topArt->vues_compteur)); ?>

                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <a href="<?php echo e(route('articles.show', $topArt->slug)); ?>" target="_blank" class="text-blue-600 hover:underline font-semibold">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400 italic">No published articles with view records yet.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = <?php echo json_encode($labelsGraphique, 15, 512) ?>;
        const donneesVues = <?php echo json_encode($vuesGraphique, 15, 512) ?>;
        const donneesPubs = <?php echo json_encode($pubsGraphique, 15, 512) ?>;

        // 1. Chart: Views Evolution Line Chart
        const ctxVues = document.getElementById('graphiqueVues');
        if (ctxVues) {
            new Chart(ctxVues, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Unique Views',
                        data: donneesVues,
                        borderColor: '#FFC93C',
                        backgroundColor: 'rgba(255, 201, 60, 0.15)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#14151C',
                        pointBorderColor: '#FFC93C',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#14151C',
                            titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, maxRotation: 45 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: { precision: 0, font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // 2. Chart: Daily Publication Rhythm Bar Chart
        const ctxPubs = document.getElementById('graphiquePublications');
        if (ctxPubs) {
            new Chart(ctxPubs, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Articles Published',
                        data: donneesPubs,
                        backgroundColor: '#D4201A',
                        borderRadius: 3,
                        hoverBackgroundColor: '#B3140F'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#14151C',
                            titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, maxRotation: 45 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: { precision: 0, font: { size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('redaction.layouts.redaction', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/redaction/statistiques/index.blade.php ENDPATH**/ ?>