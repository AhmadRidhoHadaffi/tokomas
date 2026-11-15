<!-- ============ CUSTOM ORDER ============ -->
<section id="custom" class="scroll-mt-24 bg-ivory py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal lg:sticky lg:top-28 lg:self-start">
            <h2 class="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
                Wujudkan Perhiasan Impian Anda
            </h2>
            <p class="mt-5 max-w-lg leading-relaxed text-charcoal/70">
                Cincin nikah dengan ukiran nama, liontin inisial, atau gelang bermotif Rumah Gadang — pengrajin kami siap
                membuatnya sesuai keinginan. Ajukan pesanan dari akun Anda, bayar DP, dan pantau prosesnya hingga selesai.
            </p>
            <?= ui_button([
                'href' => site_url('daftar'),
                'content' => 'Pesan Custom Sekarang',
                'variant' => 'primary',
                'size' => 'lg',
                'class' => 'mt-8',
            ]) ?>
        </div>

        <ol class="relative">
            <?php foreach ($customSteps as $index => $step): ?>
                <li class="reveal relative flex gap-5 pb-10 last:pb-0" style="transition-delay: <?= $index * 50 ?>ms">
                    <?php if ($index < count($customSteps) - 1): ?>
                        <span class="absolute left-[23px] top-12 h-[calc(100%-48px)] w-px bg-gold/50" aria-hidden="true"></span>
                    <?php endif; ?>
                    <span class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-2 border-gold bg-white text-gold-dark">
                        <?= icon($step['icon'], 'h-5 w-5', 20, 1.75) ?>
                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-crimson text-[10px] font-semibold text-white">
                            <?= $index + 1 ?>
                        </span>
                    </span>
                    <div class="min-w-0 pt-1.5">
                        <p class="font-semibold text-charcoal"><?= esc($step['title']) ?></p>
                        <p class="mt-1 text-sm leading-relaxed text-charcoal/60"><?= esc($step['text']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
