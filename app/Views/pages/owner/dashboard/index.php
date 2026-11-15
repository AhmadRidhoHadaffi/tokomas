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
$salesToday = (float) ($stats['sales_today'] ?? 0);
$transToday = (int) ($stats['transactions_today'] ?? 0);
$activeOrders = (int) ($stats['active_custom_orders'] ?? 0);
$lowStock = (int) ($stats['low_stock_count'] ?? 0);
$totalProducts = (int) ($stats['total_products'] ?? 0);

// Real trend data (compared with yesterday)
$salesChange = $stats['sales_change_percent'] ?? null;
$salesTrend = $salesChange === null ? null : [
    'value' => number_format(abs((float) $salesChange), 1, ',', '.') . '%',
    'up' => (float) $salesChange >= 0,
];

$transYesterday = (int) ($stats['transactions_yesterday'] ?? 0);
$transTrend = null;
if ($transYesterday > 0) {
    $transChange = (($transToday - $transYesterday) / $transYesterday) * 100;
    $transTrend = [
        'value' => number_format(abs($transChange), 1, ',', '.') . '%',
        'up' => $transChange >= 0,
    ];
}

// Chart data
$salesChart = $stats['sales_last_7_days'] ?? ['labels' => [], 'values' => []];
$cashflowChart = $stats['cashflow_last_6_months'] ?? ['labels' => [], 'income' => [], 'expense' => []];
$salesWeekTotal = array_sum($salesChart['values'] ?? []);
$chartJsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

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
        <?= ui_button([
            'variant' => 'primary',
            'size' => 'md',
            'href' => base_url('owner/transaksi/baru'),
            'content' => icon('plus', 'h-4 w-4') . ' Catat Transaksi',
        ]) ?>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?= stat_card([
            'featured' => true,
            'icon' => 'wallet',
            'label' => 'Total Penjualan Hari Ini',
            'value' => rupiah($salesToday),
            'trend' => $salesTrend,
            'hint' => 'Dibanding kemarin',
        ]) ?>
        
        <?= stat_card([
            'featured' => false,
            'icon' => 'shopping-cart',
            'label' => 'Jumlah Transaksi',
            'value' => $transToday . ' transaksi',
            'trend' => $transTrend,
            'hint' => 'Hari ini',
        ]) ?>
        
        <?= stat_card([
            'featured' => false,
            'icon' => 'package',
            'label' => 'Stok Produk',
            'value' => $totalProducts . ' item',
            'trend' => null,
            'hint' => $lowStock . ' produk hampir/sudah habis',
        ]) ?>
        
        <?= stat_card([
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
                    <p class="text-sm text-charcoal/50">Total <?= rupiah($salesWeekTotal) ?></p>
                </div>
            </div>
            <div class="mt-4 h-72">
                <canvas id="salesChart" aria-label="Grafik penjualan tujuh hari terakhir" role="img"></canvas>
            </div>
        </section>

        <!-- Cashflow Bar Chart -->
        <section class="rounded-2xl border border-line bg-white p-5 shadow-card lg:col-span-2">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Pemasukan vs Pengeluaran</h2>
            <p class="text-sm text-charcoal/50">Enam bulan terakhir</p>
            <div class="mt-4 h-72">
                <canvas id="cashflowChart" aria-label="Grafik pemasukan dan pengeluaran enam bulan terakhir" role="img"></canvas>
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
                    'rows' => $transactionRows !== '' ? $transactionRows : empty_state([
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
                    <?= empty_state([
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

<?php $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        var sales = <?= json_encode($salesChart, $chartJsonFlags) ?>;
        var cashflow = <?= json_encode($cashflowChart, $chartJsonFlags) ?>;
        var formatRupiah = function (value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        };
        var compactRupiah = function (value) {
            if (Math.abs(value) >= 1000000) {
                return 'Rp ' + (value / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' jt';
            }
            if (Math.abs(value) >= 1000) {
                return 'Rp ' + (value / 1000).toLocaleString('id-ID', { maximumFractionDigits: 0 }) + ' rb';
            }
            return 'Rp ' + value;
        };

        Chart.defaults.font.family = "'Poppins', ui-sans-serif, system-ui, sans-serif";
        Chart.defaults.color = 'rgba(28, 28, 28, 0.55)';

        var salesCanvas = document.getElementById('salesChart');
        if (salesCanvas) {
            var salesContext = salesCanvas.getContext('2d');
            var gradient = salesContext.createLinearGradient(0, 0, 0, 280);
            gradient.addColorStop(0, 'rgba(212, 175, 55, 0.28)');
            gradient.addColorStop(1, 'rgba(212, 175, 55, 0.02)');

            new Chart(salesContext, {
                type: 'line',
                data: {
                    labels: sales.labels || [],
                    datasets: [{
                        label: 'Penjualan',
                        data: sales.values || [],
                        borderColor: '#D4AF37',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#D4AF37',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1C1C1C',
                            padding: 12,
                            callbacks: {
                                label: function (context) {
                                    return 'Penjualan: ' + formatRupiah(context.parsed.y || 0);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(28, 28, 28, 0.06)' },
                            border: { display: false },
                            ticks: { callback: function (value) { return compactRupiah(value); } }
                        }
                    }
                }
            });
        }

        var cashflowCanvas = document.getElementById('cashflowChart');
        if (cashflowCanvas) {
            new Chart(cashflowCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: cashflow.labels || [],
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: cashflow.income || [],
                            backgroundColor: '#D4AF37',
                            borderRadius: 6,
                            maxBarThickness: 22
                        },
                        {
                            label: 'Pengeluaran',
                            data: cashflow.expense || [],
                            backgroundColor: '#8B0000',
                            borderRadius: 6,
                            maxBarThickness: 22
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, boxWidth: 8, padding: 16 }
                        },
                        tooltip: {
                            backgroundColor: '#1C1C1C',
                            padding: 12,
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + formatRupiah(context.parsed.y || 0);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(28, 28, 28, 0.06)' },
                            border: { display: false },
                            ticks: { callback: function (value) { return compactRupiah(value); } }
                        }
                    }
                }
            });
        }
    })();
</script>
<?php $this->endSection(); ?>
