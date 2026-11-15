<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$items         = $result['items'] ?? [];
$search        = (string) ($filters['search'] ?? '');
$paymentStatus = (string) ($filters['payment_status'] ?? '');
$paymentMethod = (string) ($filters['payment_method'] ?? '');
$startDate     = (string) ($filters['start_date'] ?? '');
$endDate       = (string) ($filters['end_date'] ?? '');
$total         = (int) ($result['total'] ?? 0);
$hasFilter     = $search !== '' || $paymentStatus !== '' || $paymentMethod !== '' || $startDate !== '' || $endDate !== '';
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Transaksi Penjualan',
        'description' => 'Riwayat transaksi penjualan toko beserta metode dan status pembayaran.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc((string) $total) . ' transaksi</span>'
            . ui_button([
                'href' => base_url('karyawan/transaksi/baru'),
                'content' => 'Catat Transaksi',
                'icon' => 'plus',
                'size' => 'md',
            ]),
    ]) ?>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="No. invoice atau nama pelanggan" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <label class="text-sm text-charcoal/70">Status
                <select name="payment_status" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
                    <option value="">Semua status</option>
                    <option value="Lunas" <?= $paymentStatus === 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                    <option value="Menunggu" <?= $paymentStatus === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Metode
                <select name="payment_method" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-36">
                    <option value="">Semua metode</option>
                    <option value="Tunai" <?= $paymentMethod === 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                    <option value="Transfer" <?= $paymentMethod === 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                    <option value="QRIS" <?= $paymentMethod === 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Dari
                <input type="date" name="start_date" value="<?= esc($startDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <label class="text-sm text-charcoal/70">Sampai
                <input type="date" name="end_date" value="<?= esc($endDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($hasFilter): ?>
                    <?= ui_button(['href' => base_url('karyawan/transaksi'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'receipt-text',
                'title' => 'Belum ada transaksi',
                'description' => 'Transaksi penjualan yang dicatat akan muncul di sini.',
                'action' => ui_button([
                    'href' => base_url('karyawan/transaksi/baru'),
                    'content' => 'Catat Transaksi',
                    'icon' => 'plus',
                    'size' => 'sm',
                ]),
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[940px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Invoice</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Kasir</th>
                            <th class="px-4 py-3">Metode</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <a href="<?= base_url('karyawan/transaksi/' . $item['id']) ?>" class="font-medium text-gold-deep hover:underline">
                                        <?= esc($item['invoice_no']) ?>
                                    </a>
                                </td>
                                <td class="px-4 py-3.5 text-charcoal"><?= esc($item['customer_name'] ?? '-') ?></td>
                                <td class="px-4 py-3.5 text-charcoal/70"><?= esc($item['cashier_name'] ?? '-') ?></td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-ivory px-2.5 py-1 text-xs font-medium text-charcoal/70">
                                        <?= esc($item['payment_method'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-charcoal"><?= rupiah((float) ($item['total'] ?? 0)) ?></td>
                                <td class="px-4 py-3.5"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $item['payment_status']]) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= format_date((string) ($item['transaction_date'] ?? '')) ?></td>
                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end">
                                        <?= ui_button([
                                            'href' => base_url('karyawan/transaksi/' . $item['id']),
                                            'content' => 'Detail',
                                            'icon' => 'eye',
                                            'variant' => 'light',
                                            'size' => 'sm',
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if (($result['total_pages'] ?? 0) > 1): ?>
        <?= view('components/ui/pagination', [
            'currentPage' => (int) ($result['page'] ?? 1),
            'totalPages' => (int) $result['total_pages'],
            'total' => $total,
            'perPage' => (int) ($result['per_page'] ?? 20),
            'baseUrl' => base_url('karyawan/transaksi'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
