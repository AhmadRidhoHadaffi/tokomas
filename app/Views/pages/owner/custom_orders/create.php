<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$jewelryTypes = ['Cincin', 'Kalung', 'Gelang', 'Anting', 'Liontin'];
$purities = ['24K', '22K', '18K', '17K', 'Perak 925'];
?>
<div class="mx-auto max-w-3xl space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Tambah Pesanan Custom',
        'description' => 'Catat detail pesanan perhiasan custom beserta estimasi harga dan DP.',
    ]) ?>

    <form method="post" action="<?= base_url('owner/pesanan-custom') ?>" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <?= form_field([
            'type' => 'select', 'name' => 'customer_id', 'label' => 'Pelanggan', 'required' => true,
            'value' => old('customer_id'),
            'placeholder' => 'Pilih pelanggan',
            'options' => array_column($customers, 'name', 'id'),
            'error' => $errors['customer_id'] ?? '',
        ]) ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'type' => 'select', 'name' => 'jewelry_type', 'label' => 'Jenis Perhiasan', 'required' => true,
                'value' => old('jewelry_type', 'Cincin'),
                'options' => array_combine($jewelryTypes, $jewelryTypes),
                'error' => $errors['jewelry_type'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'purity', 'label' => 'Kadar', 'required' => true,
                'value' => old('purity', '24K'),
                'options' => array_combine($purities, $purities),
                'error' => $errors['purity'] ?? '',
            ]) ?>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'name' => 'model', 'label' => 'Model', 'required' => true,
                'value' => old('model'),
                'placeholder' => 'Contoh: Cincin Ukir Nama',
                'error' => $errors['model'] ?? '',
            ]) ?>
            <?= form_field([
                'name' => 'engraving_name', 'label' => 'Ukiran Nama',
                'value' => old('engraving_name'),
                'placeholder' => 'Opsional',
                'error' => $errors['engraving_name'] ?? '',
            ]) ?>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <?= form_field([
                'type' => 'number', 'name' => 'estimated_price', 'label' => 'Estimasi Harga (Rp)', 'required' => true,
                'value' => old('estimated_price'),
                'placeholder' => 'Contoh: 7500000',
                'error' => $errors['estimated_price'] ?? '',
                'extraAttrs' => 'step="1" min="1"',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'dp', 'label' => 'DP (Rp)',
                'value' => old('dp', '0'),
                'placeholder' => 'Contoh: 2000000',
                'error' => $errors['dp'] ?? '',
                'extraAttrs' => 'step="1" min="0"',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'weight', 'label' => 'Berat (gram)',
                'value' => old('weight'),
                'placeholder' => 'Contoh: 5.5',
                'error' => $errors['weight'] ?? '',
                'extraAttrs' => 'step="0.01" min="0"',
            ]) ?>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'type' => 'date', 'name' => 'order_date', 'label' => 'Tanggal Pesanan',
                'value' => old('order_date', date('Y-m-d')),
                'error' => $errors['order_date'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'date', 'name' => 'due_date', 'label' => 'Estimasi Selesai',
                'value' => old('due_date'),
                'error' => $errors['due_date'] ?? '',
            ]) ?>
        </div>

        <?= form_field([
            'type' => 'textarea', 'name' => 'notes', 'label' => 'Catatan',
            'value' => old('notes'),
            'placeholder' => 'Detail tambahan pesanan, misalnya ukuran atau preferensi pelanggan.',
        ]) ?>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/pesanan-custom'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Pesanan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
