<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$product = $product ?? null;
$isEdit = $product !== null;
$errors = session()->getFlashdata('errors') ?? [];
$action = $isEdit ? base_url('owner/produk/' . $product['id']) : base_url('owner/produk');

$metal = old('metal', $product['metal'] ?? 'Emas');
$karat = old('karat', $product['karat'] ?? '24K');
?>
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl"><?= $isEdit ? 'Ubah Produk' : 'Tambah Produk' ?></h1>
        <p class="mt-2 text-sm text-charcoal/60">Lengkapi data produk. Kolom bertanda bintang wajib diisi.</p>
    </div>

    <form method="post" action="<?= esc($action, 'attr') ?>" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'name' => 'code', 'label' => 'Kode Produk', 'required' => true,
                'value' => old('code', $product['code'] ?? ''),
                'placeholder' => 'Contoh: EM-001', 'error' => $errors['code'] ?? '',
                'inputClass' => 'uppercase',
            ]) ?>
            <?= form_field([
                'name' => 'name', 'label' => 'Nama Produk', 'required' => true,
                'value' => old('name', $product['name'] ?? ''),
                'placeholder' => 'Contoh: Cincin Emas Polos', 'error' => $errors['name'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'category', 'label' => 'Kategori', 'required' => true,
                'value' => old('category', $product['category'] ?? ''),
                'placeholder' => 'Pilih kategori',
                'options' => ['Cincin' => 'Cincin', 'Kalung' => 'Kalung', 'Gelang' => 'Gelang', 'Anting' => 'Anting', 'Liontin' => 'Liontin'],
                'error' => $errors['category'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'metal', 'label' => 'Jenis Logam', 'required' => true,
                'value' => $metal,
                'options' => ['Emas' => 'Emas', 'Perak' => 'Perak'],
                'error' => $errors['metal'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'karat', 'label' => 'Kadar', 'required' => true,
                'value' => $karat,
                'options' => ['24K' => '24K', '22K' => '22K', '18K' => '18K', '17K' => '17K', '925' => '925 (Perak)'],
                'error' => $errors['karat'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'weight', 'label' => 'Berat (gram)', 'required' => true,
                'value' => old('weight', $product['weight'] ?? ''),
                'placeholder' => 'Contoh: 2.5', 'error' => $errors['weight'] ?? '',
                'inputClass' => 'no-spinner', 'extraAttrs' => 'step="0.01" min="0.01"',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'price', 'label' => 'Harga (Rp)', 'required' => true,
                'value' => old('price', $product['price'] ?? ''),
                'placeholder' => 'Contoh: 1500000', 'error' => $errors['price'] ?? '',
                'inputClass' => 'no-spinner', 'extraAttrs' => 'step="1" min="1"',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'stock', 'label' => 'Stok', 'required' => true,
                'value' => old('stock', $product['stock'] ?? 0),
                'error' => $errors['stock'] ?? '', 'inputClass' => 'no-spinner', 'extraAttrs' => 'step="1" min="0"',
            ]) ?>
            <?= form_field([
                'name' => 'size', 'label' => 'Ukuran',
                'value' => old('size', $product['size'] ?? ''),
                'placeholder' => 'Contoh: 18', 'error' => $errors['size'] ?? '',
            ]) ?>
            <?= form_field([
                'name' => 'model', 'label' => 'Model',
                'value' => old('model', $product['model'] ?? ''),
                'placeholder' => 'Contoh: Polos', 'error' => $errors['model'] ?? '',
            ]) ?>
        </div>

        <div>
            <?= form_field([
                'type' => 'file', 'name' => 'image', 'label' => 'Foto Produk',
                'accept' => 'image/jpeg,image/png,image/webp',
                'hint' => 'Opsional. JPG, PNG, atau WEBP maksimal 2 MB.',
            ]) ?>
            <?php if ($isEdit && !empty($product['image_url'])): ?>
                <img src="<?= esc($product['image_url'], 'attr') ?>" alt="Foto <?= esc($product['name'], 'attr') ?>" class="mt-3 h-28 w-28 rounded-xl border border-line object-cover">
            <?php endif; ?>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/produk'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => $isEdit ? 'Simpan Perubahan' : 'Simpan Produk', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
