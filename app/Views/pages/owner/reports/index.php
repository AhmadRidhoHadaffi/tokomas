<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <h1 class="font-serif text-3xl font-semibold text-charcoal">Laporan</h1><p class="mt-2 text-sm text-charcoal/60">Ringkasan keuangan dan operasional.</p>
    <form method="get" class="mt-6 flex flex-wrap items-end gap-3">
        <label class="text-sm text-charcoal/70">Dari
            <input type="date" name="start_date" value="<?= esc((string) ($startDate ?? '')) ?>" class="mt-1 block rounded-lg border border-line px-3 py-2">
        </label>
        <label class="text-sm text-charcoal/70">Sampai
            <input type="date" name="end_date" value="<?= esc((string) ($endDate ?? '')) ?>" class="mt-1 block rounded-lg border border-line px-3 py-2">
        </label>
        <?= ui_button(['type' => 'submit', 'content' => 'Tampilkan', 'icon' => 'search', 'size' => 'md']) ?>
    </form>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Total Penjualan</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal">Rp <?= number_format((float) ($sales['summary']['total_sales'] ?? 0), 0, ',', '.') ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Total Transaksi</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($sales['summary']['total_transactions'] ?? 0)) ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Custom Order</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($customOrders['summary']['total_orders'] ?? 0)) ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Stok Menipis</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($stock['summary']['low_stock'] ?? 0)) ?></p></div>
    </div>

    <h2 class="mt-8 font-serif text-xl font-semibold text-charcoal">Ringkasan Pembukuan</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-green-50 p-4 text-sm text-charcoal/70">Pemasukan<br><strong class="text-lg text-charcoal">Rp <?= number_format((float) ($finances['summary']['income'] ?? 0), 0, ',', '.') ?></strong></div>
        <div class="rounded-xl bg-red-50 p-4 text-sm text-charcoal/70">Pengeluaran<br><strong class="text-lg text-charcoal">Rp <?= number_format((float) ($finances['summary']['expense'] ?? 0), 0, ',', '.') ?></strong></div>
        <div class="rounded-xl bg-gold-light p-4 text-sm text-charcoal/70">Saldo<br><strong class="text-lg text-charcoal">Rp <?= number_format((float) ($finances['summary']['balance'] ?? 0), 0, ',', '.') ?></strong></div>
    </div>

    <h2 class="mt-8 font-serif text-xl font-semibold text-charcoal">Transaksi pada Periode</h2>
    <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Invoice</th><th class="p-3">Tanggal</th><th class="p-3">Pelanggan</th><th class="p-3">Total</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($sales['details'] ?? [] as $item): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($item['invoice_no']) ?></td><td class="p-3"><?= format_date((string) $item['transaction_date']) ?></td><td class="p-3"><?= esc($item['customer_name'] ?? '-') ?></td><td class="p-3">Rp <?= number_format((float) $item['total'], 0, ',', '.') ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $item['payment_status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php if (empty($sales['details'])): ?>
        <p class="mt-4 rounded-lg bg-ivory px-4 py-3 text-sm text-charcoal/60">Tidak ada transaksi pada periode ini.</p>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
