<!-- ============ PROMO ============ -->
<section class="relative overflow-hidden bg-crimson py-20 lg:py-24">
    <div class="bg-arabesque absolute inset-0 opacity-40" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-2xl">
            <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl">Selalu Tahu Kabar Pesanan &amp; Promo Terbaru</h2>
            <p class="mt-4 text-white/75">
                Dengan akun pelanggan, semua informasi penting sampai langsung ke Anda — tanpa perlu bolak-balik ke toko.
            </p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
            <?php foreach ($promoFeatures as $index => $feature): ?>
                <div class="reveal h-full" style="transition-delay: <?= $index * 50 ?>ms">
                    <div class="h-full rounded-2xl border border-white/15 bg-white/[0.06] p-6">
                        <?= icon($feature['icon'], 'h-7 w-7 text-gold', 28, 1.5) ?>
                        <h3 class="mt-5 font-serif text-xl font-semibold text-white"><?= esc($feature['title']) ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70"><?= esc($feature['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="reveal mt-10">
            <?= ui_button([
                'href' => site_url('daftar'),
                'content' => 'Daftar Sekarang',
                'variant' => 'primary',
                'size' => 'lg',
            ]) ?>
        </div>
    </div>
</section>
