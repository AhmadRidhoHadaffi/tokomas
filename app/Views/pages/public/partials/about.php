<!-- ============ ABOUT ============ -->
<section id="tentang" class="scroll-mt-24 bg-ivory py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal">
            <div class="relative">
                <img
                    src="<?= $imageBase ?>toko.jpg"
                    alt="Etalase perhiasan emas di Toko Mas Gembira, Pasar Raya Padang"
                    class="aspect-[4/3] w-full rounded-2xl object-cover shadow-card"
                    width="900"
                    height="675"
                    loading="lazy"
                >
                <div class="absolute -bottom-6 right-6 max-w-[220px] rounded-xl bg-crimson px-5 py-4 text-white shadow-lg">
                    <p class="font-serif text-3xl font-semibold">27 tahun</p>
                    <p class="text-xs text-white/80">melayani warga Padang sejak 1998</p>
                </div>
            </div>
        </div>

        <div class="reveal" style="transition-delay: 60ms">
            <h2 class="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
                Toko Perhiasan Terpercaya di Padang Barat
            </h2>
            <p class="mt-5 leading-relaxed text-charcoal/70">
                Berawal dari kios kecil di Pasar Raya Padang, <?= esc($storeName) ?> telah menjadi pilihan keluarga Minang untuk
                perhiasan pernikahan, hadiah, hingga simpanan masa depan. Kami menjual emas dan perak dengan kadar yang jelas,
                menerima pesanan custom, serta membeli kembali emas Anda dengan harga yang adil.
            </p>
            <ul class="mt-8 space-y-6">
                <?php foreach ($aboutHighlights as $highlight): ?>
                    <li class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-gold-dark shadow-card">
                            <?= icon($highlight['icon'], 'h-5 w-5', 20, 1.75) ?>
                        </span>
                        <div class="min-w-0">
                            <p class="font-semibold text-charcoal"><?= esc($highlight['title']) ?></p>
                            <p class="mt-0.5 text-sm text-charcoal/60"><?= esc($highlight['text']) ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
