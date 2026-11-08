<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Transaksi</h1><p class="mt-2 text-sm text-charcoal/60">Riwayat transaksi penjualan.</p></div>
        <a href="<?= site_url('staff/transaksi/buat') ?>" class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal">Transaksi Baru</a>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <input type="date" name="date" value="<?= esc((string) ($date ?? '')) ?>" class="rounded-lg border border-line px-3 py-2">
        <button class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal" type="submit">Filter</button>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Invoice</th><th class="p-3">Tanggal</th><th class="p-3">Pelanggan</th><th class="p-3">Total</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($transactions ?? [] as $tx): ?><tr class="border-b border-line/70"><td class="p-3"><a href="<?= site_url('staff/transaksi/' . $tx['id']) ?>" class="font-medium text-gold-deep hover:underline"><?= esc($tx['invoice_number']) ?></a></td><td class="p-3"><?= format_date($tx['transaction_date']) ?></td><td class="p-3"><?= esc($tx['customer_name'] ?? '-') ?></td><td class="p-3">Rp <?= number_format($tx['total_amount'], 0, ',', '.') ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $tx['status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
