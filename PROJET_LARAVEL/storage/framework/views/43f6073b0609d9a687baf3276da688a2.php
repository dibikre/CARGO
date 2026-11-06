<?php $__env->startSection('titre', 'CARGO — Le Journal de la Logistique, du Fret et de la Supply Chain'); ?>

<?php $__env->startSection('contenu'); ?>
<?php
    $articlePrincipal = $articlesALaUne->first();
    $articlesSecondaires = $articlesALaUne->slice(1, 3);
?>


<section style="background:var(--dark); color:#fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; padding-top:56px; padding-bottom:36px;">
        <div style="flex:3 1 520px; display:flex; flex-direction:column; gap:20px;">
            <span class="kick" style="color:var(--yellow);">
                À la une · <?php echo e($articlePrincipal?->categorie?->nom ?? 'Supply chain'); ?> · <?php echo e(now()->translatedFormat('l j F Y')); ?>

            </span>
            <h1 class="dsp dsp-hero" style="margin:0; font-weight:900; font-size:64px; line-height:.98; letter-spacing:-.01em;">
                <a href="<?php echo e($articlePrincipal ? route('articles.show', $articlePrincipal->slug) : '#'); ?>" style="color:#fff;">
                    <?php echo e($articlePrincipal?->titre ?? 'Chaînes d’approvisionnement : la résilience prend la route'); ?>

                </a>
            </h1>
            <p style="margin:0; max-width:600px; font-size:19px; line-height:1.55; color:#C9CCD6;">
                <?php echo e($articlePrincipal?->chapeau ?? 'Diversification des fournisseurs, stocks tampons, visibilité de bout en bout : le point complet sur les pratiques des opérateurs logistiques.'); ?>

            </p>
            <span style="display:flex; flex-wrap:wrap; gap:14px; margin-top:8px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articlePrincipal): ?>
                    <a href="<?php echo e(route('articles.show', $articlePrincipal->slug)); ?>" class="btn-yellow">Lire l’article →</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e(route('articles.index')); ?>" class="btn-outline-white">Toutes les rubriques</a>
            </span>
        </div>

        <div style="flex:1 1 280px; display:flex; flex-direction:column; align-self:flex-end;">
            <span class="kick" style="color:#AEB3C0; padding-bottom:12px; border-bottom:2px dashed #4A4E5C;">Dans ce numéro</span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $articlesSecondaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $artSec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route('articles.show', $artSec->slug)); ?>" class="rk">
                    <b><?php echo e(str_pad($idx + 1, 2, '0', STR_PAD_LEFT)); ?></b>
                    <span class="dsp" style="font-size:20px; line-height:1.2;"><?php echo e($artSec->titre); ?></span>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articlesSecondaires->isEmpty()): ?>
                <a href="#" class="rk"><b>01</b><span class="dsp" style="font-size:20px; line-height:1.2;">Corridors maritimes : les lignes se réorganisent</span></a>
                <a href="#" class="rk"><b>02</b><span class="dsp" style="font-size:20px; line-height:1.2;">Dédouanement numérique : ce qui change</span></a>
                <a href="#" class="rk"><b>03</b><span class="dsp" style="font-size:20px; line-height:1.2;">Entrepôts : l’automatisation par étapes</span></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="wrap" style="display:flex; justify-content:flex-end;">
        <svg viewBox="0 0 600 150" aria-hidden="true" style="display:block; width:min(100%,780px);">
            <g fill="var(--accent)"><rect x="10" y="22" width="390" height="128"></rect><path d="M410 150V54Q410 46 420 46H492L546 96Q558 108 558 122V150Z"></path></g>
            <path d="M10 118h390" stroke="rgba(0,0,0,.2)" stroke-width="6"></path>
            <text x="38" y="100" font-family="Archivo,sans-serif" font-weight="900" font-size="52" letter-spacing="3" fill="#fff">CARGO</text>
            <rect x="412" y="12" width="9" height="40" fill="#14151C"></rect>
            <path d="M428 58h60l38 36H428z" fill="#14151C"></path>
            <rect x="548" y="114" width="10" height="10" fill="#FFC93C"></rect>
            <path d="M64 132h44M452 132h60" stroke="#14151C" stroke-width="36" stroke-linecap="round"></path>
            <path d="M64 132h44M452 132h60" stroke="#E6E8EC" stroke-width="12" stroke-linecap="round" stroke-dasharray="0 44"></path>
        </svg>
    </div>
</section>


<?php echo $__env->make('journal.composants.bandeau-urgent', ['alertes' => $alertesUrgentes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<section style="padding-top:64px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:48px;">
        <div style="flex:1 1 240px;">
            <span class="kick" style="color:var(--accent-dark);">Sur la route</span>
            <h2 class="dsp" style="margin:12px 0 24px; font-weight:900; font-size:44px; line-height:1;">Choisissez votre itinéraire</h2>
            <a href="<?php echo e(route('categories.articles', 'transport-routier')); ?>" class="rub" style="border-top:3px dashed var(--dashed-border)">Routier <span aria-hidden="true">→</span></a>
            <a href="<?php echo e(route('categories.articles', 'maritime')); ?>" class="rub">Maritime <span aria-hidden="true">→</span></a>
            <a href="<?php echo e(route('categories.articles', 'ferroviaire')); ?>" class="rub">Ferroviaire <span aria-hidden="true">→</span></a>
            <a href="<?php echo e(route('categories.articles', 'entreposage')); ?>" class="rub">Entreposage <span aria-hidden="true">→</span></a>
            <a href="<?php echo e(route('categories.articles', 'douane')); ?>" class="rub">Douane <span aria-hidden="true">→</span></a>
        </div>

        <div style="flex:3 1 560px; display:flex; flex-wrap:wrap; gap:24px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articlesRecents->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php echo $__env->make('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p>Aucun article disponible pour le moment.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="wrap" style="display:flex; justify-content:flex-start; margin-top:56px;">
        <svg viewBox="0 0 800 170" aria-hidden="true" style="display:block; width:min(100%,940px);">
            <g fill="var(--accent)"><path d="M20 112H736L784 82L704 170H72Z"></path><rect x="46" y="38" width="76" height="76"></rect><rect x="60" y="14" width="26" height="26"></rect></g>
            <path d="M60 14h26v8H60z" fill="#FFC93C"></path>
            <path d="M104 38V6" stroke="#14151C" stroke-width="3"></path>
            <path d="M56 54h56M56 76h58M56 94h58" stroke="#14151C" stroke-width="7" stroke-dasharray="9 6"></path>
            <path d="M44 130H730" stroke="rgba(0,0,0,.2)" stroke-width="5"></path>
            <path fill="#14151C" d="M136 90h54v22h-54zM316 90h54v22h-54zM496 90h54v22h-54zM196 68h54v22h-54zM376 68h54v22h-54zM256 46h54v22h-54zM436 46h54v22h-54z"></path>
            <path fill="#FFC93C" d="M196 90h54v22h-54zM436 90h54v22h-54zM256 68h54v22h-54zM496 68h54v22h-54zM316 46h54v22h-54z"></path>
            <path fill="#F2F2F2" d="M256 90h54v22h-54zM376 90h54v22h-54zM316 68h54v22h-54zM436 68h54v22h-54zM376 46h54v22h-54z"></path>
            <path fill="#7E100B" d="M556 90h54v22h-54z"></path>
        </svg>
    </div>
</section>


<?php $dossierVedette = $dossiersVedettes->first(); ?>
<section style="background:var(--accent); color:#fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; align-items:center; padding-top:56px; padding-bottom:64px;">
        <div style="flex:1 1 420px; display:flex; flex-direction:column; gap:18px;">
            <span class="kick badge-alert" style="align-self:flex-start;">Dossier spécial</span>
            <h2 class="dsp" style="margin:0; font-weight:900; font-size:52px; line-height:1;">
                <?php echo e($dossierVedette?->titre ?? 'Ports et corridors : la carte des flux'); ?>

            </h2>
            <p style="margin:0; font-size:18px; line-height:1.55;">
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


<section style="background:#fff; padding-top:64px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:32px;">
        <div style="flex:3 1 460px;">
            <?php echo $__env->make('journal.composants.classement-plus-lus', ['articlesPlusLus' => $articlesRecents->sortByDesc('vues_compteur')->take(4)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div style="flex:2 1 320px;">
            <?php echo $__env->make('journal.composants.bloc-newsletter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    <div class="wrap" style="display:flex; justify-content:center; margin-top:56px;">
        <svg viewBox="0 0 760 150" aria-hidden="true" style="display:block; width:min(100%,860px);">
            <g fill="var(--accent)"><path d="M474 150V34H690Q730 34 742 78V150Z"></path><rect x="12" y="52" width="216" height="98"></rect><rect x="240" y="52" width="216" height="98"></rect></g>
            <path d="M12 128h216M240 128h216" stroke="rgba(0,0,0,.2)" stroke-width="6"></path>
            <text x="30" y="106" font-family="Archivo,sans-serif" font-weight="900" font-size="48" letter-spacing="3" fill="#fff">CARGO</text>
            <text x="258" y="106" font-family="Archivo,sans-serif" font-weight="900" font-size="48" letter-spacing="3" fill="#fff">CARGO</text>
            <path d="M228 118h12M456 118h18" stroke="#14151C" stroke-width="6"></path>
            <path d="M494 58h60v26h-60z" fill="#14151C"></path>
            <path d="M640 46h46q20 4 28 30h-74z" fill="#14151C"></path>
            <path d="M474 104h268v10H474z" fill="#FFC93C"></path>
            <rect x="732" y="120" width="10" height="10" fill="#FFE08A"></rect>
            <path d="M566 34L590 12H624M624 12L600 34" stroke="#14151C" stroke-width="3" fill="none"></path>
            <path d="M40 138h36M164 138h36M268 138h36M392 138h36M502 138h36M664 138h36" stroke="#14151C" stroke-width="24" stroke-linecap="round"></path>
            <path d="M40 138h36M164 138h36M268 138h36M392 138h36M502 138h36M664 138h36" stroke="#E6E8EC" stroke-width="8" stroke-linecap="round" stroke-dasharray="0 36"></path>
        </svg>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.journal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\journal\resources\views/journal/accueil.blade.php ENDPATH**/ ?>