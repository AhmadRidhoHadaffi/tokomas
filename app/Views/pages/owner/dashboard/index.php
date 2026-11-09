<?php
/**
 * Owner Dashboard Page
 * 
 * @var array $stats Dashboard statistics
 * @var string $title Page title
 */

$this->extend('layouts/dashboard');
$this->section('title');
echo 'Dashboard';
$this->endSection();

$this->section('content');

// Get current date
$currentDate = date('l, d F Y');
$greeting = date('H') < 12 ? 'Selamat pagi' : (date('H') < 18 ? 'Selamat siang' : 'Selamat malam');
$userName = session('user_name') ?? 'Owner';

// Calculate totals
$salesToday = $stats['sales_today'] ?? 0;
$transToday = $stats['transactions_today'] ?? 0;
$activeOrders = $stats['active_custom_orders'] ?? 0;
$lowStock = $stats['low_stock_count'] ?? 0;
$totalProducts = $stats['total_products'] ?? 0;

$transactionRows = '';
foreach ($stats['recent_transactions'] ?? [] as $trx) {
    $transactionRows .= '<tr class="border-b border-line/70">'
        . '<td class="p-3 font-medium"><a class="text-gold-deep hover:underline" href="' . base_url('owner/transaksi/' . $trx['id']) . '">' . esc($trx['invoice_no']) . '</a></td>'
        . '<td class="p-3">' . esc($trx['customer_name'] ?? '-') . '</td>'
        . '<td class="p-3">Rp ' . number_format((float) $trx['total'], 0, ',', '.') . '</td>'
        . '<td class="p-3">' . view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $trx['payment_status']]) . '</td>'
        . '</tr>';
}
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm text-charcoal/55"><?= format_date(date('Y-m-d'), 'long') ?></p>
            <p class="mt-1 font-serif text-2xl font-semibold text-charcoal">
                <?= $greeting ?>, <?= esc($userName) ?>
            </p>
        </div>
        <?= view('components/ui/button', [
            'variant' => 'primary',
            'size' => 'md',
            'href' => base_url('owner/transaksi/baru'),
            'content' => icon('plus', 'h-4 w-4') . ' Catat Transaksi',
        ]) ?>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?= view('components/ui/stat_card', [
            'featured' => true,
            'icon' => 'wallet',
            'label' => 'Total Penjualan Hari Ini',
            'value' => rupiah($salesToday),
            'trend' => ['value' => '12,4%', 'up' => true],
            'hint' => 'Dibanding kemarin',
        ]) ?>
        
        <?= view('components/ui/stat_card', [
            'featured' => false,
            'icon' => 'shopping-cart',
            'label' => 'Jumlah Transaksi',
            'value' => $transToday . ' transaksi',
            'trend' => ['value' => '1', 'up' => true],
            'hint' => 'Hari ini',
        ]) ?>
        
        <?= view('components/ui/stat_card', [
            'featured' => false,
            'icon' => 'package',
            'label' => 'Stok Produk',
            'value' => $totalProducts . ' item',
            'trend' => null,
            'hint' => $lowStock . ' produk hampir/sudah habis',
        ]) ?>
        
        <?= view('components/ui/stat_card', [
            'featured' => false,
            'icon' => 'gem',
            'label' => 'Pesanan Custom Aktif',
            'value' => $activeOrders . ' pesanan',
            'trend' => null,
            'hint' => 'Belum diambil',
        ]) ?>
    </div>

    <!-- Charts Section -->
    <div class="grid gap-6 lg:grid-cols-5">
        <!-- Sales Line Chart -->
        <section class="rounded-2xl border border-line bg-white p-5 shadow-card lg:col-span-3">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-serif text-lg font-semibold text-charcoal">Grafik Penjualan 7 Hari Terakhir</h2>
                    <p class="text-sm text-charcoal/50">Total <?= rupiah(0) ?></p>
                </div>
            </div>
            <div class="mt-4 h-72 flex items-center justify-center bg-ivory/50 rounded-xl">
                <p class="text-sm text-charcoal/40">Chart.js akan diintegrasikan di sini</p>
            </div>
        </section>

        <!-- Cashflow Bar Chart -->
        <section class="rounded-2xl border border-line bg-white p-5 shadow-card lg:col-span-2">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Pemasukan vs Pengeluaran</h2>
            <p class="text-sm text-charcoal/50"><?= date('F Y') ?></p>
            <div class="mt-4 h-72 flex items-center justify-center bg-ivory/50 rounded-xl">
                <p class="text-sm text-charcoal/40">Chart.js akan diintegrasikan di sini</p>
            </div>
        </section>
    </div>

    <!-- Tables Section -->
    <div class="grid gap-6 xl:grid-cols-3">
        <!-- Recent Transactions -->
        <section class="xl:col-span-2">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="font-serif text-lg font-semibold text-charcoal">Transaksi Terbaru</h2>
                <a href="<?= base_url('owner/transaksi') ?>" class="text-xs font-medium text-gold-deep hover:underline">
                    Lihat semua
                </a>
            </div>
            <div class="rounded-2xl border border-line bg-white shadow-card">
                <?= view('components/ui/table', [
                    'headers' => [
                        ['label' => 'Invoice', 'class' => ''],
                        ['label' => 'Pelanggan', 'class' => ''],
                        ['label' => 'Total', 'class' => ''],
                        ['label' => 'Status', 'class' => ''],
                    ],
                    'rows' => $transactionRows !== '' ? $transactionRows : view('components/ui/empty_state', [
                        'icon' => 'receipt-text',
                        'title' => 'Belum ada transaksi',
                        'description' => 'Transaksi yang dicatat akan muncul di sini',
                    ]),
                ]) ?>
            </div>
        </section>

        <!-- Active Custom Orders -->
        <section>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="font-serif text-lg font-semibold text-charcoal">Pesanan Custom Aktif</h2>
                <a href="<?= base_url('owner/pesanan-custom') ?>" class="text-xs font-medium text-gold-deep hover:underline">
                    Lihat semua
                </a>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5 shadow-card">
                <?php if (! empty($stats['active_order_list'])): ?>
                    <ul class="divide-y divide-line">
                        <?php foreach ($stats['active_order_list'] as $order): ?>
                            <li class="py-3 first:pt-0 last:pb-0">
                                <a href="<?= base_url('owner/pesanan-custom/' . $order['id']) ?>" class="text-sm font-medium text-gold-deep hover:underline"><?= esc($order['order_no']) ?></a>
                                <p class="mt-1 text-sm text-charcoal/70"><?= esc($order['customer_name'] ?? '-') ?></p>
                                <div class="mt-1 flex items-center justify-between gap-2 text-xs text-charcoal/50">
                                    <span><?= esc($order['status']) ?></span>
                                    <span><?= format_date((string) $order['order_date']) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= view('components/ui/empty_state', [
                        'icon' => 'gem',
                        'title' => 'Belum ada pesanan aktif',
                        'description' => 'Pesanan custom yang belum selesai akan muncul di sini',
                    ]) ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php $this->endSection(); ?>
