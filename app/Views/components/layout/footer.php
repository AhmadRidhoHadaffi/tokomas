<?php
/**
 * Public Footer (Landing Page)
 */

$navLinks = [
    ['label' => 'Beranda', 'href' => site_url('/')],
    ['label' => 'Produk', 'href' => site_url('produk')],
    ['label' => 'Custom', 'href' => site_url('custom')],
    ['label' => 'Tentang', 'href' => site_url('tentang')],
    ['label' => 'Kontak', 'href' => site_url('kontak')],
];

$storeName = $store['store_name'] ?? 'Toko Mas Gembira';
?>
<footer class="bg-[#141414]">
    <div class="h-px bg-gold/50" aria-hidden="true"></div>

    <div class="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-12 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <?= view('components/brand/logo', [
            'tone' => 'light',
            'showTagline' => true,
            'markClass' => 'h-12 w-12',
            'href' => base_url('/'),
        ]) ?>

        <nav aria-label="Navigasi footer" class="flex flex-wrap items-center gap-x-7">
            <?php foreach ($navLinks as $link): ?>
                <a
                    href="<?= esc($link['href'], 'attr') ?>"
                    class="inline-flex min-h-[40px] items-center text-sm text-white/65 transition-colors duration-150 hover:text-gold"
                >
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="mx-auto max-w-7xl border-t border-white/10 px-5 pb-24 pt-6 text-xs text-white/45 md:pb-6 lg:px-8">
        &copy; <?= date('Y') ?> <?= esc($storeName) ?> &middot; Pasar Raya Padang, Sumatera Barat. Hak cipta dilindungi.
    </div>
</footer>
