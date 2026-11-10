<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Custom Order</h1><p class="mt-2 text-sm text-charcoal/60">Pesanan perhiasan custom.</p></div>
        <a href="<?= site_url('staff/custom-order/buat') ?>" class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal">Order Baru</a>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <select name="status" class="rounded-lg border border-line px-3 py-2"><option value="">Semua Status</option><option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option><option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Disetujui</option><option value="production" <?= ($status ?? '') === 'production' ? 'selected' : '' ?>>Produksi</option><option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option></select>
        <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'size' => 'md']) ?>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Kode</th><th class="p-3">Pelanggan</th><th class="p-3">Deskripsi</th><th class="p-3">Estimasi</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($orders ?? [] as $order): ?><tr class="border-b border-line/70"><td class="p-3"><a href="<?= site_url('staff/custom-order/' . $order['id']) ?>" class="font-medium text-gold-deep hover:underline"><?= esc($order['order_code']) ?></a></td><td class="p-3"><?= esc($order['customer_name']) ?></td><td class="p-3 max-w-xs truncate"><?= esc($order['description']) ?></td><td class="p-3">Rp <?= number_format($order['estimated_price'], 0, ',', '.') ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $order['status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
