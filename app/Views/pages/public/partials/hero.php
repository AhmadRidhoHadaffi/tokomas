<!-- ============ HERO ============ -->
<section id="beranda" class="relative overflow-hidden bg-charcoal pb-14 pt-32 lg:pt-40">
    <div class="bg-arabesque absolute inset-0" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12">
            <div class="reveal lg:col-span-7">
                <h1 class="font-serif text-4xl font-semibold leading-[1.1] text-white sm:text-5xl lg:text-[64px]">
                    Perhiasan <span class="text-gold">Emas &amp; Perak</span> Terbaik di Padang
                </h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/70 sm:text-lg">
                    Pesan custom, pantau progres pengerjaan, dan nikmati promo eksklusif — semua dalam satu platform.
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <?= ui_button([
                        'href' => site_url('produk'),
                        'content' => 'Lihat Produk Kami',
                        'variant' => 'primary',
                        'size' => 'lg',
                        'fullWidth' => true,
                        'class' => 'sm:w-auto',
                    ]) ?>
                    <?= ui_button([
                        'href' => site_url('daftar'),
                        'content' => 'Buat Akun Gratis',
                        'variant' => 'outlineLight',
                        'size' => 'lg',
                        'fullWidth' => true,
                        'class' => 'sm:w-auto',
                    ]) ?>
                </div>
            </div>

            <div class="reveal relative mx-auto w-full max-w-sm lg:col-span-5 lg:max-w-none" style="transition-delay: 80ms">
                <div class="rounded-t-[999px] border border-gold/40 p-3">
                    <img
                        src="<?= $imageBase ?>cincin-emas.jpg"
                        alt="Cincin emas filigree khas Minang di atas beludru gelap"
                        class="aspect-[4/5] w-full rounded-t-[999px] object-cover"
                        width="800"
                        height="1000"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>
                <div class="absolute -bottom-5 left-4 right-4 rounded-xl border border-gold/30 bg-charcoal/95 px-5 py-4 backdrop-blur sm:left-auto sm:right-[-12px] sm:w-64">
                    <p class="text-xs text-white/55">Harga emas 22K hari ini</p>
                    <p class="mt-0.5 font-serif text-xl font-semibold text-gold"><?= rupiah($goldPrice) ?> / gr</p>
                    <p class="mt-1 text-[11px] text-white/40">Diperbarui <?= esc($goldUpdated) ?></p>
                </div>
            </div>
        </div>

        <ul class="reveal mt-20 grid grid-cols-2 gap-x-6 gap-y-5 border-t border-white/10 pt-8 sm:grid-cols-3 lg:grid-cols-5">
            <?php foreach ($trustBadges as $badge): ?>
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gold/40 text-gold">
                        <?= icon($badge['icon'], 'h-4 w-4', 16, 1.75) ?>
                    </span>
                    <span class="text-sm text-white/80"><?= esc($badge['label']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
