<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$methods = ['Tunai' => 'Tunai', 'Transfer' => 'Transfer', 'QRIS' => 'QRIS'];
$statuses = ['Lunas' => 'Lunas', 'Menunggu' => 'Menunggu'];
?>
<div class="mx-auto max-w-3xl space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Catat Transaksi Baru',
        'description' => 'Simpan transaksi penjualan beserta item, diskon, dan metode pembayaran.',
    ]) ?>

    <form method="post" action="<?= base_url('karyawan/transaksi') ?>" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <?= form_field([
            'type' => 'select', 'name' => 'customer_id', 'label' => 'Pelanggan', 'required' => true,
            'value' => old('customer_id'),
            'placeholder' => 'Pilih pelanggan',
            'options' => array_column($customers, 'name', 'id'),
            'error' => $errors['customer_id'] ?? '',
        ]) ?>

        <?= form_field([
            'type' => 'textarea', 'name' => 'items', 'label' => 'Item Transaksi (JSON)', 'required' => true,
            'value' => old('items', '[]'),
            'rows' => '4',
            'placeholder' => '[{"product_id":1,"quantity":1}]',
            'hint' => 'Isi item sebagai array JSON berisi product_id dan quantity.',
            'error' => $errors['items'] ?? '',
            'inputClass' => 'font-mono text-xs',
        ]) ?>

        <div class="grid gap-4 sm:grid-cols-3">
            <?= form_field([
                'type' => 'number', 'name' => 'discount', 'label' => 'Diskon (Rp)',
                'value' => old('discount', '0'),
                'placeholder' => '0',
                'error' => $errors['discount'] ?? '',
                'extraAttrs' => 'step="1" min="0"',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'payment_method', 'label' => 'Metode Pembayaran', 'required' => true,
                'value' => old('payment_method', 'Tunai'),
                'options' => $methods,
                'error' => $errors['payment_method'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'payment_status', 'label' => 'Status Pembayaran', 'required' => true,
                'value' => old('payment_status', 'Lunas'),
                'options' => $statuses,
                'error' => $errors['payment_status'] ?? '',
            ]) ?>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('karyawan/transaksi'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Transaksi', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
