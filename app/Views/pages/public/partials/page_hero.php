<section class="relative overflow-hidden bg-charcoal pb-14 pt-32 lg:pt-36">
    <div class="bg-arabesque absolute inset-0" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-gold">Toko Mas Gembira</p>
        <h1 class="mt-4 max-w-3xl font-serif text-4xl font-semibold leading-tight text-white sm:text-5xl">
            <?= esc($pageTitle ?? '') ?>
        </h1>
        <?php if (! empty($pageSubtitle)): ?>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/65 sm:text-lg">
                <?= esc($pageSubtitle) ?>
            </p>
        <?php endif; ?>
    </div>
</section>
