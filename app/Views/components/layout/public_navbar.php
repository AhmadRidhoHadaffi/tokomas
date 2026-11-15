<?php
/**
 * Public Navbar (Landing Page)
 *
 * Fixed, transparent over the hero and solid after scrolling.
 * The mobile menu mirrors the desktop links and include the auth actions.
 */

$navLinks = [
    ['label' => 'Beranda', 'href' => site_url('/'), 'path' => ''],
    ['label' => 'Produk', 'href' => site_url('produk'), 'path' => 'produk'],
    ['label' => 'Custom', 'href' => site_url('custom'), 'path' => 'custom'],
    ['label' => 'Tentang', 'href' => site_url('tentang'), 'path' => 'tentang'],
    ['label' => 'Kontak', 'href' => site_url('kontak'), 'path' => 'kontak'],
];

$currentPath = trim(uri_string(), '/');
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
                <?php $isActive = $currentPath === $link['path']; ?>
                <a
                    href="<?= esc($link['href'], 'attr') ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                    class="text-sm font-medium transition-colors duration-150 <?= $isActive ? 'text-gold' : 'text-white/80 hover:text-gold' ?>"
                >
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <?= ui_button([
                'href' => site_url('masuk'),
                'content' => 'Masuk',
                'variant' => 'outlineDark',
                'size' => 'sm',
            ]) ?>
            <?= ui_button([
                'href' => site_url('daftar'),
                'content' => 'Daftar',
                'variant' => 'primary',
                'size' => 'sm',
            ]) ?>
        </div>

        <?= ui_button([
            'type' => 'button',
            'icon' => '',
            'iconOnly' => true,
            'variant' => 'ghostDark',
            'class' => 'lg:hidden',
            'content' => '<span x-show="!open">' . icon('menu', 'h-6 w-6', 24, 1.75) . '</span>'
                . '<span x-show="open" x-cloak>' . icon('x', 'h-6 w-6', 24, 1.75) . '</span>',
            'attrs' => [
                '@click' => 'open = !open',
                ':aria-expanded' => 'open.toString()',
                ":aria-label" => "open ? 'Tutup menu' : 'Buka menu'",
            ],
        ]) ?>
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
            <?php $isActive = $currentPath === $link['path']; ?>
            <a
                href="<?= esc($link['href'], 'attr') ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>
                @click="open = false"
                class="block border-b border-white/5 py-3.5 text-[15px] transition-colors duration-150 <?= $isActive ? 'text-gold' : 'text-white/85 hover:text-gold' ?>"
            >
                <?= esc($link['label']) ?>
            </a>
        <?php endforeach; ?>
        <div class="mt-5 grid grid-cols-2 gap-3">
            <?= ui_button([
                'href' => site_url('masuk'),
                'content' => 'Masuk',
                'variant' => 'outlineDark',
                'size' => 'md',
                'fullWidth' => true,
            ]) ?>
            <?= ui_button([
                'href' => site_url('daftar'),
                'content' => 'Daftar',
                'variant' => 'primary',
                'size' => 'md',
                'fullWidth' => true,
            ]) ?>
        </div>
    </nav>
</header>
