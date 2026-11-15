<?php
/**
 * Product showcase section.
 *
 * @var string $sectionHeading
 * @var string $sectionDescription
 * @var bool   $showAllLink
 */
$sectionHeading = $sectionHeading ?? 'Koleksi Pilihan Kami';
$sectionDescription = $sectionDescription ?? 'Berat, kadar, dan ukuran tertera jelas. Semua koleksi tersedia di toko — pembelian dilakukan langsung di toko atau tanya ketersediaan via WhatsApp.';
$showAllLink = $showAllLink ?? false;
?>
<!-- ============ PRODUCT SHOWCASE ============ -->
<section
    id="produk"
    class="scroll-mt-24 bg-charcoal py-20 lg:py-28"
    x-data="<?= esc('{ selected: null, products: ' . $showcaseJson . ' }', 'attr') ?>"
    @keydown.escape.window="selected = null"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', !!selected)"
>
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl"><?= esc($sectionHeading) ?></h2>
                <p class="mt-3 max-w-lg text-white/60">
                    <?= esc($sectionDescription) ?>
                </p>
            </div>
            <?php if ($showAllLink): ?>
                <a href="<?= site_url('produk') ?>" class="inline-flex min-h-[40px] items-center text-sm font-medium text-gold hover:underline">
                    Lihat semua koleksi →
                </a>
            <?php else: ?>
                <span class="inline-flex min-h-[40px] items-center rounded-full border border-gold/40 px-4 text-sm font-medium text-gold">
                    <?= count($showcaseProducts) ?> koleksi tersedia
                </span>
            <?php endif; ?>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($showcaseProducts as $index => $product): ?>
                <div class="reveal h-full" style="transition-delay: <?= $index * 40 ?>ms">
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-charcoal-soft transition-[box-shadow,border-color,transform] duration-200 ease-out hover:-translate-y-1 hover:border-gold/50 hover:shadow-gold-glow">
                        <div class="relative overflow-hidden">
                            <img
                                src="<?= esc($product['image'], 'attr') ?>"
                                alt="<?= esc($product['name'], 'attr') ?>"
                                class="aspect-[4/3] w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                width="800"
                                height="600"
                                loading="lazy"
                            >
                            <span class="absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-semibold <?= $product['metal'] === 'Emas' ? 'bg-gold text-charcoal' : 'bg-gray-200 text-charcoal' ?>">
                                <?= esc($product['metal']) ?>
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="font-serif text-xl font-semibold text-white"><?= esc($product['name']) ?></h3>
                            <p class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-white/55">
                                <?php foreach ($product['detail'] as $detailIndex => $detail): ?>
                                    <?php if ($detailIndex > 0): ?>
                                        <span aria-hidden="true">·</span>
                                    <?php endif; ?>
                                    <span><?= esc($detail) ?></span>
                                <?php endforeach; ?>
                            </p>
                            <p class="mt-4 text-sm text-white/50">
                                Mulai <span class="font-semibold text-gold"><?= rupiah($product['price']) ?></span>
                            </p>
                            <p class="mt-1 text-xs text-white/40">Pembelian langsung di toko</p>
                            <?= ui_button([
                                'type' => 'button',
                                'content' => 'Lihat Detail',
                                'variant' => 'outlineDark',
                                'size' => 'md',
                                'fullWidth' => true,
                                'class' => 'mt-5',
                                'attrs' => ['@click' => 'selected = products[' . (int) $index . ']'],
                            ]) ?>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Product detail modal -->
    <div
        x-show="selected"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] flex items-end justify-center p-0 sm:items-center sm:p-4"
        role="dialog"
        aria-modal="true"
        :aria-label="selected ? selected.name : 'Detail produk'"
    >
        <div class="absolute inset-0 bg-charcoal/70 backdrop-blur-sm" @click="selected = null" aria-hidden="true"></div>

        <div
            x-show="selected"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative max-h-[90dvh] w-full max-w-2xl overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl sm:rounded-2xl sm:p-6"
        >
            <?= ui_button([
                'type' => 'button',
                'icon' => 'x',
                'iconOnly' => true,
                'variant' => 'light',
                'class' => 'absolute right-4 top-4 z-10 shadow-sm',
                'ariaLabel' => 'Tutup detail produk',
                'attrs' => ['@click' => 'selected = null'],
            ]) ?>

            <div class="grid gap-6 sm:grid-cols-2">
                <img
                    :src="selected ? selected.image : ''"
                    :alt="selected ? selected.name : ''"
                    class="aspect-square w-full rounded-xl object-cover"
                    width="600"
                    height="600"
                >
                <div class="flex min-w-0 flex-col">
                    <span class="w-fit rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-deep" x-text="selected ? selected.metal : ''"></span>
                    <h3 class="mt-3 font-serif text-2xl font-semibold text-charcoal" x-text="selected ? selected.name : ''"></h3>
                    <p class="mt-3 text-sm leading-relaxed text-charcoal/70" x-text="selected ? selected.description : ''"></p>

                    <dl class="mt-5 divide-y divide-line rounded-xl border border-line">
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Berat</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[0] : ''"></dd>
                        </div>
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Kadar</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[1] : ''"></dd>
                        </div>
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Ukuran</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[2] : ''"></dd>
                        </div>
                    </dl>

                    <p class="mt-5 text-sm text-charcoal/55">Harga mulai</p>
                    <p class="font-serif text-2xl font-semibold text-charcoal" x-text="selected ? selected.price_label : ''"></p>

                    <div class="mt-auto pt-6">
                        <div class="flex flex-col gap-2">
                            <?= ui_button([
                                'href' => $waHref,
                                'target' => '_blank',
                                'rel' => 'noreferrer',
                                'content' => 'Tanya via WhatsApp',
                                'icon' => 'message-circle',
                                'variant' => 'primary',
                                'size' => 'md',
                                'fullWidth' => true,
                            ]) ?>
                            <?= ui_button([
                                'href' => site_url('custom'),
                                'content' => 'Ajukan Pesanan Custom',
                                'variant' => 'light',
                                'size' => 'md',
                                'fullWidth' => true,
                            ]) ?>
                        </div>
                        <p class="mt-4 rounded-xl bg-ivory px-4 py-3 text-xs leading-relaxed text-charcoal/65">
                            Pembelian perhiasan dilakukan langsung di toko. Tanya ketersediaan lewat WhatsApp,
                            atau ajukan pesanan custom dari akun Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
