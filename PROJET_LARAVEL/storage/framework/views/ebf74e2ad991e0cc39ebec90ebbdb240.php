<div style="background:var(--yellow); padding:36px; box-sizing:border-box; display:flex; flex-direction:column; gap:14px; align-self:stretch;">
    <h2 class="dsp" style="margin:0; font-weight:900; font-size:calc(38px * var(--echelle-texte)); line-height:1;">Le courrier du lundi</h2>
    <p style="margin:0; font-size:calc(16.5px * var(--echelle-texte)); line-height:1.5;">L’essentiel de la logistique, des cours de fret et de la supply chain chaque semaine dans votre boîte mail.</p>
    
    <form action="<?php echo e(route('newsletter.inscrire')); ?>" method="POST" style="display:flex; flex-direction:column; gap:12px;">
        <?php echo csrf_field(); ?>
        <label for="nl-email" style="font-weight:700; font-size:calc(13.8px * var(--echelle-texte));">Adresse e-mail professionnelle</label>
        <input id="nl-email" name="email" type="email" required placeholder="vous@entreprise.com" class="form-input">
        <input type="hidden" name="frequence" value="hebdomadaire">
        <button type="submit" style="height:48px; background:var(--dark); color:#fff; border:0; font:700 calc(15.5px * var(--echelle-texte)) var(--police-texte); cursor:pointer;">
            Je m’abonne
        </button>
    </form>
</div>
<?php /**PATH C:\laragon\www\journal\resources\views/journal/composants/bloc-newsletter.blade.php ENDPATH**/ ?>