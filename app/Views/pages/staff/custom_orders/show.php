<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card"><h1 class="font-serif text-3xl">Detail Pesanan</h1><p class="mt-2">Order No: <?= esc($order['order_no']) ?></p><p>Customer: <?= esc($order['customer_name']) ?></p><p>Status: <?= esc($order['status']) ?></p></div>
<?= $this->endSection() ?>
