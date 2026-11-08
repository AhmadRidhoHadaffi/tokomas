<?php
/**
 * Public Navbar (Landing Page)
 *
 * Fixed, transparent over the hero and solid after scrolling.
 * The mobile menu mirrors the desktop links and include the auth actions.
 */

$navLinks = [
    ['label' => 'Beranda', 'href' => '#beranda'],
    ['label' => 'Produk', 'href' => '#produk'],
    ['label' => 'Custom', 'href' => '#custom'],
    ['label' => 'Tentang', 'href' => '#tentang'],
    ['label' => 'Kontak', 'href' => '#kontak'],
];
?>
<header
    x-data="{ scrolled: false, open: false }"
    x-init="scrolled = window.scrollY > 24"
    @scroll.window.passive="scrolled = window.scrollY > 24"
    @keydown.escape.window="open = false"
    :class="(scrolled || open) ? 'bg-charcoal/95 shadow-lg shadow-black/20 backdrop-blur' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-[background-color,box-shadow] duration-200 ease-out"
>
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-5 lg:px-8">
        <div class="shrink-0">
            <?= view('components/brand/logo', [
                'tone' => 'light',
                'markClass' => 'h-10 w-10',
                'href' => base_url('/'),
            ]) ?>
        </div>

        <nav aria-label="Navigasi utama" class="hidden items-center gap-8 lg:flex">
            <?php foreach ($navLinks as $link): ?>
                <a
                    href="<?= esc($link['href'], 'attr') ?>"
                    class="text-sm font-medium text-white/80 transition-colors duration-150 hover:text-gold"
                >
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="<?= site_url('masuk') ?>" class="inline-flex h-9 items-center justify-center whitespace-nowrap rounded-lg border border-gold/80 bg-transparent px-5 text-xs font-medium text-gold transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:border-gold hover:bg-gold/10 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Masuk
            </a>
            <a href="<?= site_url('daftar') ?>" class="inline-flex h-9 items-center justify-center whitespace-nowrap rounded-lg bg-gold-gradient px-5 text-xs font-medium text-charcoal shadow-sm transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:brightness-110 hover:shadow-gold-glow active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Daftar
            </a>
        </div>

        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open.toString()"
            :aria-label="open ? 'Tutup menu' : 'Buka menu'"
            class="rounded-lg p-2 text-white transition-colors duration-150 hover:bg-white/10 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30 lg:hidden"
        >
            <span x-show="!open"><?= icon('menu', 'h-6 w-6', 24, 1.75) ?></span>
            <span x-show="open" x-cloak><?= icon('x', 'h-6 w-6', 24, 1.75) ?></span>
        </button>
    </div>

    <nav
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        aria-label="Navigasi seluler"
        class="max-h-[calc(100dvh-5rem)] overflow-y-auto border-t border-white/10 bg-charcoal px-5 pb-6 pt-2 lg:hidden"
    >
        <?php foreach ($navLinks as $link): ?>
            <a
                href="<?= esc($link['href'], 'attr') ?>"
                @click="open = false"
                class="block border-b border-white/5 py-3.5 text-[15px] text-white/85 transition-colors duration-150 hover:text-gold"
            >
                <?= esc($link['label']) ?>
            </a>
        <?php endforeach; ?>
        <div class="mt-5 grid grid-cols-2 gap-3">
            <a href="<?= site_url('masuk') ?>" class="inline-flex h-11 items-center justify-center whitespace-nowrap rounded-lg border border-gold/80 bg-transparent px-5 text-sm font-medium text-gold transition-colors duration-150 hover:bg-gold/10 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Masuk
            </a>
            <a href="<?= site_url('daftar') ?>" class="inline-flex h-11 items-center justify-center whitespace-nowrap rounded-lg bg-gold-gradient px-5 text-sm font-medium text-charcoal shadow-sm transition-[filter,box-shadow] duration-150 hover:brightness-110 hover:shadow-gold-glow focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Daftar
            </a>
        </div>
    </nav>
</header>
