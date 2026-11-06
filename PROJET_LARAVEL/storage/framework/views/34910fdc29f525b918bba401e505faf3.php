<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('titre', 'CARGO — Le Journal du Transport, de la Logistique & Supply Chain'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'L\'actualité de référence du fret maritime, aérien, routier, ferroviaire et de la logistique.'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;800;900&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #D4201A;
            --accent-dark: #B3140F;
            --dark: #14151C;
            --dark-border: #2B2E3A;
            --yellow: #FFC93C;
            --bg-light: #EEF0F3;
            --text-muted: #4B505C;
            --dashed-border: #B8BDC8;
        }
        body { margin: 0; background: var(--bg-light); color: var(--dark); font-family: 'IBM Plex Sans', Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 1240px; margin: 0 auto; padding: 0 32px; box-sizing: border-box; }
        .dsp { font-family: 'Archivo', sans-serif; font-weight: 800; }
        .kick { font: 600 13px/1 'IBM Plex Sans', sans-serif; letter-spacing: .14em; text-transform: uppercase; }
        .card { flex: 1 1 260px; background: #fff; padding: 28px; box-sizing: border-box; display: flex; flex-direction: column; gap: 14px; transition: transform .15s ease; }
        .card:hover { transform: translateY(-2px); }
        .tile { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
        .rk { display: flex; gap: 18px; align-items: baseline; padding: 16px 0; border-bottom: 2px dashed #4A4E5C; }
        .rk b { font: 900 34px/1 'Archivo', sans-serif; color: var(--yellow); min-width: 40px; }
        .lk { padding: 18px 0; border-bottom: 2px dashed rgba(255,255,255,.6); font: 800 24px/1.2 'Archivo', sans-serif; display: block; }
        .rub { display: flex; justify-content: space-between; align-items: center; min-height: 56px; border-bottom: 3px dashed var(--dashed-border); font: 800 24px/1.1 'Archivo', sans-serif; }
        .btn-yellow { background: var(--yellow); color: var(--dark); font-weight: 600; padding: 13px 20px; display: inline-block; cursor: pointer; border: 0; }
        .btn-yellow:hover { text-decoration: none; opacity: .95; }
        .btn-outline-white { border: 2px solid #fff; color: #fff; font-weight: 600; padding: 13px 24px; display: inline-block; }
        .btn-outline-white:hover { text-decoration: none; background: rgba(255,255,255,.1); }
        .badge-alert { background: var(--dark); padding: 9px 12px; color: #fff; }
        .form-input { height: 50px; border: 2px solid var(--dark); background: #fff; padding: 0 14px; font: 400 16px 'IBM Plex Sans', sans-serif; box-sizing: border-box; width: 100%; }
        .alert-box { padding: 14px 20px; margin-bottom: 20px; font-weight: 600; font-size: 15px; }
        .alert-success { background: #DCFCE7; color: #166534; border-left: 4px solid #16A34A; }
        .alert-error { background: #FEE2E2; color: #991B1B; border-left: 4px solid #DC2626; }
        @media (max-width: 768px) {
            .wrap { padding: 0 16px; }
            .dsp-hero { font-size: 40px !important; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <header style="background: var(--dark); color: #fff; border-bottom: 1px solid var(--dark-border);">
        <div class="wrap" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px 32px; padding-top:16px; padding-bottom:16px;">
            <a href="<?php echo e(route('accueil')); ?>" aria-label="CARGO, accueil" style="display:flex; align-items:center; gap:3px; font:900 38px/1 'Archivo',sans-serif; letter-spacing:.05em;">
                CARG<svg aria-hidden="true" width="34" height="34" viewBox="0 0 34 34"><circle cx="17" cy="17" r="16" fill="var(--accent)"></circle><circle cx="17" cy="17" r="6" fill="#14151C"></circle></svg>
            </a>
            <nav style="display:flex; flex-wrap:wrap; gap:0 24px; font:600 14px 'IBM Plex Sans',sans-serif; text-transform:uppercase; letter-spacing:.08em;">
                <a href="<?php echo e(route('categories.articles', 'transport-routier')); ?>" style="padding:14px 0">Transport</a>
                <a href="<?php echo e(route('categories.articles', 'entreposage')); ?>" style="padding:14px 0">Entreposage</a>
                <a href="<?php echo e(route('categories.articles', 'douane')); ?>" style="padding:14px 0">Douane</a>
                <a href="<?php echo e(route('categories.articles', 'supply-chain')); ?>" style="padding:14px 0">Supply chain</a>
                <a href="<?php echo e(route('categories.articles', 'e-commerce')); ?>" style="padding:14px 0">E-commerce</a>
                <a href="<?php echo e(route('dossiers.index')); ?>" style="padding:14px 0">Dossiers</a>
                <a href="<?php echo e(route('evenements.index')); ?>" style="padding:14px 0">Agenda</a>
            </nav>
            <span style="display:flex; gap:12px; align-items:center;">
                <a href="<?php echo e(route('recherche')); ?>" aria-label="Rechercher" style="width:46px; height:46px; border:1px solid #4A4E5C; display:flex; align-items:center; justify-content:center;">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-4-4"></path></svg>
                </a>
                <a href="<?php echo e(route('abonnements.index')); ?>" class="btn-yellow">S’abonner</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->estMembreRedaction()): ?>
                        <a href="<?php echo e(route('redaction.articles.index')); ?>" style="background:#fff; color:var(--dark); font-weight:600; padding:13px 16px; font-size:13px;">Rédaction</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('abonnements.espace')); ?>" style="color:#C9CCD6; font-size:13px; font-weight:600;">Mon compte</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" style="color:#C9CCD6; font-size:13px; font-weight:600;">Connexion</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </span>
        </div>
    </header>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('succes')): ?>
        <div class="wrap" style="padding-top: 16px;">
            <div class="alert-box alert-success"><?php echo e(session('succes')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('erreur')): ?>
        <div class="wrap" style="padding-top: 16px;">
            <div class="alert-box alert-error"><?php echo e(session('erreur')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('contenu'); ?>
    </main>

    <footer style="background: var(--accent); color: #fff; margin-top: 64px;">
        <div class="wrap" style="display:flex; flex-wrap:wrap; gap:40px; padding-top:48px; padding-bottom:32px;">
            <div style="flex:2 1 280px; display:flex; flex-direction:column; gap:12px;">
                <span style="font:900 38px/1 'Archivo',sans-serif; letter-spacing:.05em;">CARGO</span>
                <span style="max-width:320px; line-height:1.5;">L’actualité du transport, de l’entreposage et de la supply chain internationale.</span>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick">Rubriques</strong>
                <a href="<?php echo e(route('categories.articles', 'transport-routier')); ?>">Transport Routier</a>
                <a href="<?php echo e(route('categories.articles', 'maritime')); ?>">Fret Maritime</a>
                <a href="<?php echo e(route('categories.articles', 'aerien')); ?>">Fret Aérien</a>
                <a href="<?php echo e(route('categories.articles', 'ferroviaire')); ?>">Ferroviaire</a>
                <a href="<?php echo e(route('categories.articles', 'entreposage')); ?>">Entreposage</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick">Le journal</strong>
                <a href="<?php echo e(route('dossiers.index')); ?>">Dossiers Spéciaux</a>
                <a href="<?php echo e(route('evenements.index')); ?>">Agenda Salons</a>
                <a href="<?php echo e(route('ressources.index')); ?>">Livres Blancs</a>
                <a href="<?php echo e(route('abonnements.index')); ?>">Tarifs & Offres Pro</a>
            </div>
            <div style="flex:1 1 160px; display:flex; flex-direction:column; gap:10px;">
                <strong class="kick">Informations</strong>
                <a href="#">Mentions légales</a>
                <a href="#">Confidentialité & RGPD</a>
                <a href="#">Gestion des cookies</a>
                <a href="#">Contact Rédaction</a>
            </div>
        </div>
        <div class="wrap" style="border-top:2px dashed rgba(255,255,255,.6); padding-top:20px; padding-bottom:24px; font-size:14px;">
            © <?php echo e(date('Y')); ?> CARGO — Journal professionnel de la logistique. Tous droits réservés.
        </div>
    </footer>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\journal\resources\views/layouts/journal.blade.php ENDPATH**/ ?>