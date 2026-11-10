<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4"><div><h1 class="font-serif text-3xl font-semibold text-charcoal">Transaksi Penjualan</h1><p class="mt-2 text-sm text-charcoal/60">Daftar transaksi tersimpan.</p></div><?= ui_button(['href' => base_url('karyawan/transaksi/baru'), 'content' => 'Catat transaksi', 'icon' => 'plus']) ?></div>
    <form method="get" class="mt-6 flex flex-wrap gap-3"><input name="search" value="<?= esc((string) ($filters['search'] ?? '')) ?>" placeholder="Cari invoice/pelanggan" class="rounded-lg border border-line px-3 py-2"><?= ui_button(['type' => 'submit', 'content' => 'Cari', 'icon' => 'search', 'size' => 'md']) ?></form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Invoice</th><th class="p-3">Pelanggan</th><th class="p-3">Total</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($result['items'] as $item): ?><tr class="border-b border-line/70"><td class="p-3"><a class="text-gold-dark underline" href="<?= base_url('karyawan/transaksi/' . $item['id']) ?>"><?= esc($item['invoice_no']) ?></a></td><td class="p-3"><?= esc($item['customer_name']) ?></td><td class="p-3">Rp <?= esc(number_format((float) $item['total'], 0, ',', '.')) ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $item['payment_status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>

