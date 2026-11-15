<!-- ============ CONTACT ============ -->
<section id="kontak" class="scroll-mt-24 bg-charcoal py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-5 lg:px-8">
        <div class="reveal lg:col-span-2">
            <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl">Kunjungi Toko Kami</h2>
            <p class="mt-4 text-white/60">Kami tunggu kedatangan Anda di Pasar Raya Padang.</p>

            <ul class="mt-8 space-y-5 text-sm">
                <li class="flex gap-4">
                    <?= icon('map-pin', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storeAddress) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('phone', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storePhone) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('mail', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storeEmail) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('clock', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <div class="min-w-0 space-y-1">
                        <?php foreach ($hours as $hour): ?>
                            <p class="text-white/80">
                                <span class="inline-block w-28 text-white/50 sm:w-32"><?= esc($hour['day']) ?></span>
                                <?= esc($hour['time']) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </li>
            </ul>

            <?= ui_button([
                'href' => $waHref,
                'target' => '_blank',
                'rel' => 'noreferrer',
                'content' => 'Chat WhatsApp',
                'icon' => 'message-circle',
                'variant' => 'whatsapp',
                'size' => 'md',
                'class' => 'mt-8',
            ]) ?>
        </div>

        <div class="reveal min-w-0 lg:col-span-3" style="transition-delay: 60ms">
            <div class="overflow-hidden rounded-2xl border border-gold/30">
                <iframe
                    title="Peta lokasi Toko Mas Gembira di Pasar Raya Padang"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=100.3560%2C-0.9560%2C100.3680%2C-0.9440&amp;layer=mapnik&amp;marker=-0.9500%2C100.3620"
                    class="h-[320px] w-full sm:h-[380px] lg:h-[440px]"
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </div>
</section>
