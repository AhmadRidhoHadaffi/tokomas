<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <h1 class="font-serif text-3xl font-semibold text-charcoal">Laporan</h1><p class="mt-2 text-sm text-charcoal/60">Ringkasan keuangan dan operasional.</p>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <input type="date" name="start" value="<?= esc((string) ($start ?? '')) ?>" class="rounded-lg border border-line px-3 py-2">
        <input type="date" name="end" value="<?= esc((string) ($end ?? '')) ?>" class="rounded-lg border border-line px-3 py-2">
        <button class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal" type="submit">Tampilkan</button>
    </form>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Total Penjualan</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal">Rp <?= number_format($report['total_sales'] ?? 0, 0, ',', '.') ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Total Transaksi</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($report['transaction_count'] ?? 0)) ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Custom Order</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($report['custom_order_count'] ?? 0)) ?></p></div>
        <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Pelanggan Baru</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($report['new_customers'] ?? 0)) ?></p></div>
    </div>
    <div class="mt-8"><h2 class="font-serif text-xl font-semibold text-charcoal">Produk Terlaris</h2><div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Produk</th><th class="p-3">Terjual</th><th class="p-3">Total</th></tr></thead><tbody><?php foreach ($report['top_products'] ?? [] as $p): ?><tr class="border-b border-line/70"><td class="p-3"><?= esc($p['name']) ?></td><td class="p-3"><?= esc((string) $p['sold']) ?></td><td class="p-3">Rp <?= number_format($p['revenue'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?= $this->endSection() ?>
