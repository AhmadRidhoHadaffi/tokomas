<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card"><h1 class="font-serif text-3xl font-semibold">Detail Transaksi</h1><p class="mt-2">Invoice: <?= esc($transaction['invoice_no']) ?></p><p>Pelanggan: <?= esc($transaction['customer_name']) ?></p><p>Total: Rp <?= esc(number_format((float) $transaction['total'], 0, ',', '.')) ?></p></div>
<?= $this->endSection() ?>
