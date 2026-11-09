<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="font-serif text-3xl font-semibold text-charcoal">Detail Transaksi</h1>
                <p class="mt-1 text-sm text-charcoal/60"><?= esc($transaction['invoice_no']) ?></p>
            </div>
            <?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $transaction['payment_status']]) ?>
        </div>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-charcoal/50">Pelanggan</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($transaction['customer_name'] ?: '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Kasir</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($transaction['cashier_name'] ?: '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Tanggal</dt><dd class="mt-1 font-medium text-charcoal"><?= format_datetime((string) $transaction['transaction_date']) ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Metode Pembayaran</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($transaction['payment_method']) ?></dd></div>
        </dl>
    </div>

    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h2 class="font-serif text-xl font-semibold text-charcoal">Item</h2>
        <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Kode</th><th class="p-3">Produk</th><th class="p-3">Harga</th><th class="p-3">Qty</th><th class="p-3">Subtotal</th></tr></thead><tbody><?php foreach ($transaction['items'] ?? [] as $item): ?><tr class="border-b border-line/70"><td class="p-3"><?= esc($item['code']) ?></td><td class="p-3"><?= esc($item['name']) ?></td><td class="p-3">Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></td><td class="p-3"><?= esc((string) $item['quantity']) ?></td><td class="p-3">Rp <?= number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div>
        <div class="mt-4 ml-auto max-w-xs space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-charcoal/60">Diskon</span><span>Rp <?= number_format((float) $transaction['discount'], 0, ',', '.') ?></span></div>
            <div class="flex justify-between border-t border-line pt-2 font-semibold"><span>Total</span><span>Rp <?= number_format((float) $transaction['total'], 0, ',', '.') ?></span></div>
        </div>
    </div>

    <a href="<?= base_url('karyawan/transaksi') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gold-deep hover:underline"><?= icon('arrow-left', 'h-4 w-4') ?> Kembali ke daftar transaksi</a>
</div>
<?= $this->endSection() ?>
