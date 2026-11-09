<?php $__env->startSection('titre', 'CARGO — Le Journal de la Logistique, du Fret et de la Supply Chain'); ?>

<?php $__env->startSection('contenu'); ?>

<?php echo $__env->make('journal.composants.hero-diaporama', ['articlesALaUne' => $articlesALaUne], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('journal.composants.bandeau-urgent', ['alertes' => $alertesUrgentes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="wrap" style="padding-top:36px; padding-bottom:56px;">
    <div style="display:flex; flex-wrap:wrap; gap:36px; align-items:flex-start;">
        
        
        <div style="flex:1 1 740px; min-width:0; display:flex; flex-direction:column; gap:36px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sectionsFret; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modeCle => $sectionData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php echo $__env->make('journal.composants.section-fret', [
                    'modeCle' => $modeCle,
                    'config' => $sectionData['config'],
                    'centre' => $sectionData['centre'],
                    'suivants' => $sectionData['suivants']
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <aside style="flex:0 0 350px; max-width:100%; display:flex; flex-direction:column; gap:32px; position:sticky; top:20px;">
            
            <?php echo $__env->make('journal.composants.bloc-top-semaine', ['articlesTopSemaine' => $articlesTopSemaine], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('journal.composants.bloc-videos-embed', ['videosLogistiques' => $videosLogistiques], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('journal.composants.bloc-meilleurs-tags', ['meilleursTags' => $meilleursTags], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

    </div>
</div>


<?php $dossierVedette = $dossiersVedettes->first(); ?>
<section style="background:var(--accent); color:#fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; align-items:center; padding-top:52px; padding-bottom:56px;">
        <div style="flex:1 1 420px; display:flex; flex-direction:column; gap:16px;">
            <span class="kick badge-alert" style="align-self:flex-start;">Dossier spécial</span>
            <h2 class="dsp" style="margin:0; font-weight:900; font-size:calc(46px * var(--echelle-texte)); line-height:1;">
                <?php echo e($dossierVedette?->titre ?? 'Ports et corridors : la carte des flux'); ?>

            </h2>
            <p style="margin:0; font-size:calc(17px * var(--echelle-texte)); line-height:1.55;">
                <?php echo e($dossierVedette?->chapeau ?? 'Analyses exclusives, entretiens avec les armateurs et cartes pour comprendre comment circulent les marchandises mondiales.'); ?>

            </p>
            <a href="<?php echo e($dossierVedette ? route('dossiers.show', $dossierVedette->slug) : route('dossiers.index')); ?>" class="btn-yellow" style="align-self:flex-start;">
                Explorer le dossier →
            </a>
        </div>

        <div style="flex:1 1 340px; display:flex; flex-direction:column; border-top:2px dashed rgba(255,255,255,.6);">
            <a href="#" class="lk">1 · Les grands hubs portuaires en mutation</a>
            <a href="#" class="lk">2 · Rail et fluvial : alternatives au tout-routier</a>
            <a href="#" class="lk">3 · Zones logistiques : implanter, équiper, connecter</a>
        </div>
    </div>
</section>


<section style="background:#fff; padding-top:56px; padding-bottom:56px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:36px; align-items:stretch;">
        <div style="flex:1 1 460px;">
            <?php echo $__env->make('journal.composants.bloc-newsletter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div style="flex:1 1 460px; background:#F8FAFC; border:1px solid #E2E5EB; padding:36px; box-sizing:border-box; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
            <div>
                <span class="kick" style="color:var(--accent);">Documentation & Études Métier</span>
                <h3 class="dsp" style="margin:6px 0 10px; font-size:calc(28px * var(--echelle-texte)); line-height:1.2;">Livres Blancs, Guides Tarifaires & Données Fret</h3>
                <p style="margin:0; color:var(--text-muted); font-size:calc(14.5px * var(--echelle-texte)); line-height:1.55;">
                    Téléchargez les dossiers techniques, grilles comparatives de douane et baromètres trimestriels de corridors logistiques rédigés par la rédaction CARGO.
                </p>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                <a href="<?php echo e(route('ressources.index')); ?>" style="background:var(--dark); color:#fff; font-family:var(--police-titre); font-weight:700; font-size:calc(13px * var(--echelle-texte)); padding:12px 20px; text-decoration:none; text-transform:uppercase; letter-spacing:0.05em;">
                    Consulter les livres blancs
                </a>
                <a href="<?php echo e(route('dossiers.index')); ?>" style="border:2px solid var(--dark); color:var(--dark); font-family:var(--police-titre); font-weight:700; font-size:calc(13px * var(--echelle-texte)); padding:10px 18px; text-decoration:none; text-transform:uppercase; letter-spacing:0.05em;">
                    Voir les dossiers spéciaux
                </a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.journal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/journal/accueil.blade.php ENDPATH**/ ?>