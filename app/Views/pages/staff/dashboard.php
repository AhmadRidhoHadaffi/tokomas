<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$userName      = session('user_name') ?? 'Karyawan';
$hour          = (int) date('H');
$greeting      = $hour < 12 ? 'Selamat pagi' : ($hour < 18 ? 'Selamat siang' : 'Selamat malam');
$salesToday    = (float) ($stats['sales_today'] ?? 0);
$transToday    = (int) ($stats['transactions_today'] ?? 0);
$activeOrders  = (int) ($stats['active_custom_orders'] ?? 0);
$lowStock      = (int) ($stats['low_stock_count'] ?? 0);
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm text-charcoal/55"><?= format_date(date('Y-m-d'), 'long') ?></p>
            <h1 class="mt-1 font-serif text-2xl font-semibold text-charcoal sm:text-3xl">
                <?= esc($greeting) ?>, <?= esc($userName) ?>
            </h1>
            <p class="mt-2 text-sm text-charcoal/60">Ringkasan aktivitas toko hari ini.</p>
        </div>
        <?= ui_button([
            'href' => base_url('karyawan/transaksi/baru'),
            'content' => 'Catat Transaksi',
            'icon' => 'plus',
            'size' => 'md',
        ]) ?>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?= stat_card([
            'featured' => true,
            'icon' => 'wallet',
            'label' => 'Penjualan Hari Ini',
            'value' => rupiah($salesToday),
            'hint' => 'Total transaksi lunas hari ini',
        ]) ?>
        <?= stat_card([
            'icon' => 'shopping-cart',
            'label' => 'Transaksi Hari Ini',
            'value' => $transToday . ' transaksi',
            'hint' => 'Transaksi tercatat hari ini',
        ]) ?>
        <?= stat_card([
            'icon' => 'gem',
            'label' => 'Pesanan Custom Aktif',
            'value' => $activeOrders . ' pesanan',
            'hint' => 'Belum diselesaikan atau diambil',
        ]) ?>
        <?= stat_card([
            'icon' => 'package',
            'label' => 'Stok Menipis',
            'value' => $lowStock . ' produk',
            'hint' => 'Perlu segera ditambah',
        ]) ?>
    </div>

    <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <h2 class="font-serif text-lg font-semibold text-charcoal">Aksi Cepat</h2>
        <p class="mt-1 text-sm text-charcoal/55">Pintasan pekerjaan yang sering dilakukan.</p>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <?= ui_button([
                'href' => base_url('karyawan/transaksi/baru'),
                'content' => 'Catat Transaksi',
                'icon' => 'receipt-text',
                'variant' => 'light',
                'size' => 'lg',
                'align' => 'start',
                'fullWidth' => true,
                'iconClass' => 'h-5 w-5 text-gold-dark',
            ]) ?>
            <?= ui_button([
                'href' => base_url('karyawan/pesanan-custom/baru'),
                'content' => 'Tambah Pesanan Custom',
                'icon' => 'gem',
                'variant' => 'light',
                'size' => 'lg',
                'align' => 'start',
                'fullWidth' => true,
                'iconClass' => 'h-5 w-5 text-gold-dark',
            ]) ?>
            <?= ui_button([
                'href' => base_url('karyawan/produk'),
                'content' => 'Lihat Produk & Stok',
                'icon' => 'package',
                'variant' => 'light',
                'size' => 'lg',
                'align' => 'start',
                'fullWidth' => true,
                'iconClass' => 'h-5 w-5 text-gold-dark',
            ]) ?>
            <?= ui_button([
                'href' => base_url('karyawan/pembukuan/baru'),
                'content' => 'Tambah Catatan Pembukuan',
                'icon' => 'book-open',
                'variant' => 'light',
                'size' => 'lg',
                'align' => 'start',
                'fullWidth' => true,
                'iconClass' => 'h-5 w-5 text-gold-dark',
            ]) ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
