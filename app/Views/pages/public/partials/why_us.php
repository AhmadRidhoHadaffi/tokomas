<!-- ============ WHY US ============ -->
<section class="bg-ivory py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <h2 class="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Mengapa Memilih Toko Mas Gembira</h2>
            <p class="mt-4 text-charcoal/65">Empat alasan pelanggan kembali kepada kami, dari generasi ke generasi.</p>
        </div>
        <div class="reveal mt-12">
            <div class="grid overflow-hidden rounded-2xl border border-line bg-white shadow-card sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($whyUs as $index => $item): ?>
                    <?php
                    $borderClass = '';
                    if ($index > 0) {
                        $borderClass .= ' border-t border-line sm:border-t-0';
                    }
                    if ($index % 2 === 1) {
                        $borderClass .= ' sm:border-l';
                    }
                    if ($index >= 2) {
                        $borderClass .= ' sm:border-t lg:border-t-0';
                    }
                    if ($index === 2) {
                        $borderClass .= ' lg:border-l';
                    }
                    ?>
                    <div class="border-line p-7 <?= trim($borderClass) ?>">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-ivory text-gold-dark">
                            <?= icon($item['icon'], 'h-6 w-6', 24, 1.5) ?>
                        </span>
                        <h3 class="mt-5 font-serif text-lg font-semibold text-charcoal"><?= esc($item['title']) ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal/60"><?= esc($item['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
