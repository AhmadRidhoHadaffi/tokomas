<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$salesDetails = $sales['details'] ?? [];
$startLabel = format_date((string) ($startDate ?? ''), 'long');
$endLabel = format_date((string) ($endDate ?? ''), 'long');
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Laporan',
        'description' => 'Ringkasan keuangan dan operasional toko pada periode yang dipilih.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc($startLabel) . ' &ndash; ' . esc($endLabel) . '</span>',
    ]) ?>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="text-sm text-charcoal/70">Dari
                <input type="date" name="start_date" value="<?= esc((string) ($startDate ?? ''), 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-44">
            </label>
            <label class="text-sm text-charcoal/70">Sampai
                <input type="date" name="end_date" value="<?= esc((string) ($endDate ?? ''), 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-44">
            </label>
            <?= ui_button(['type' => 'submit', 'content' => 'Tampilkan', 'icon' => 'search', 'size' => 'md']) ?>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?= stat_card([
            'featured' => true,
            'icon' => 'wallet',
            'label' => 'Total Penjualan',
            'value' => rupiah((float) ($sales['summary']['total_sales'] ?? 0)),
            'hint' => 'Transaksi lunas pada periode ini',
        ]) ?>
        <?= stat_card([
            'icon' => 'receipt-text',
            'label' => 'Total Transaksi',
            'value' => (string) ((int) ($sales['summary']['total_transactions'] ?? 0)) . ' transaksi',
            'hint' => 'Semua transaksi tercatat',
        ]) ?>
        <?= stat_card([
            'icon' => 'gem',
            'label' => 'Pesanan Custom',
            'value' => (string) ((int) ($customOrders['summary']['total_orders'] ?? 0)) . ' pesanan',
            'hint' => 'Dibuat pada periode ini',
        ]) ?>
        <?= stat_card([
            'icon' => 'package',
            'label' => 'Stok Menipis',
            'value' => (string) ((int) ($stock['summary']['low_stock'] ?? 0)) . ' produk',
            'hint' => (string) ((int) ($stock['summary']['out_of_stock'] ?? 0)) . ' produk habis',
        ]) ?>
    </div>

    <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <h2 class="font-serif text-lg font-semibold text-charcoal">Ringkasan Pembukuan</h2>
        <p class="mt-1 text-sm text-charcoal/55">Pemasukan, pengeluaran, dan saldo pada periode ini.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                <p class="text-sm text-emerald-700">Pemasukan</p>
                <p class="mt-1 font-serif text-xl font-semibold text-emerald-800"><?= rupiah((float) ($finances['summary']['income'] ?? 0)) ?></p>
            </div>
            <div class="rounded-xl border border-red-100 bg-red-50 p-4">
                <p class="text-sm text-red-700">Pengeluaran</p>
                <p class="mt-1 font-serif text-xl font-semibold text-red-800"><?= rupiah((float) ($finances['summary']['expense'] ?? 0)) ?></p>
            </div>
            <div class="rounded-xl border border-gold/30 bg-gold-light p-4">
                <p class="text-sm text-charcoal/70">Saldo</p>
                <p class="mt-1 font-serif text-xl font-semibold text-charcoal"><?= rupiah((float) ($finances['summary']['balance'] ?? 0)) ?></p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-line bg-white shadow-card">
        <div class="border-b border-line px-5 py-4 sm:px-6">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Transaksi pada Periode</h2>
        </div>
        <?php if ($salesDetails === []): ?>
            <?= empty_state([
                'icon' => 'receipt-text',
                'title' => 'Tidak ada transaksi',
                'description' => 'Belum ada transaksi penjualan pada periode yang dipilih.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Invoice</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salesDetails as $index => $item): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="px-4 py-3.5 font-medium text-charcoal"><?= esc($item['invoice_no']) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= format_date((string) $item['transaction_date']) ?></td>
                                <td class="px-4 py-3.5 text-charcoal"><?= esc($item['customer_name'] ?? '-') ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-charcoal"><?= rupiah((float) $item['total']) ?></td>
                                <td class="px-4 py-3.5"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $item['payment_status']]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
<?= $this->endSection() ?>
