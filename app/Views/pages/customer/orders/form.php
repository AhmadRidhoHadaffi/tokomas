<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$order = $order ?? null;
$isEdit = $order !== null;
$errors = session()->getFlashdata('errors') ?? [];
$action = $isEdit ? base_url('pelanggan/pesanan/' . $order['id']) : base_url('pelanggan/pesanan');
?>
<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <a href="<?= base_url('pelanggan/pesanan') ?>" class="inline-flex min-h-10 items-center gap-2 rounded-lg px-2 text-sm font-medium text-charcoal/60 hover:bg-white hover:text-charcoal focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
            <?= icon('arrow-left', 'h-4 w-4', 16, 2) ?>
            Kembali ke Pesanan
        </a>
        <h1 class="mt-3 font-serif text-2xl font-semibold text-charcoal sm:text-3xl"><?= $isEdit ? 'Ubah Pesanan Custom' : 'Ajukan Pesanan Custom' ?></h1>
        <p class="mt-2 text-sm text-charcoal/60">Isi detail perhiasan impian Anda. Tim kami akan menghubungi Anda untuk konfirmasi.</p>
    </div>

    <form method="post" action="<?= esc($action, 'attr') ?>" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>
        <input type="hidden" name="order_date" value="<?= esc((string) old('order_date', $order['order_date'] ?? date('Y-m-d')), 'attr') ?>">

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'type' => 'select', 'name' => 'jewelry_type', 'label' => 'Jenis Perhiasan', 'required' => true,
                'value' => old('jewelry_type', $order['jewelry_type'] ?? ''),
                'placeholder' => 'Pilih jenis',
                'options' => ['Cincin' => 'Cincin', 'Kalung' => 'Kalung', 'Gelang' => 'Gelang', 'Anting' => 'Anting', 'Liontin' => 'Liontin'],
                'error' => $errors['jewelry_type'] ?? '',
            ]) ?>
            <?= form_field([
                'name' => 'model', 'label' => 'Model', 'required' => true,
                'value' => old('model', $order['model'] ?? ''),
                'placeholder' => 'Contoh: Cincin solitaire',
                'error' => $errors['model'] ?? '',
            ]) ?>
            <?= form_field([
                'name' => 'engraving_name', 'label' => 'Nama Ukiran',
                'value' => old('engraving_name', $order['engraving_name'] ?? ''),
                'placeholder' => 'Opsional, maksimal 50 karakter',
                'error' => $errors['engraving_name'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'purity', 'label' => 'Kadar', 'required' => true,
                'value' => old('purity', $order['purity'] ?? ''),
                'placeholder' => 'Pilih kadar',
                'options' => ['24K' => '24K', '22K' => '22K', '18K' => '18K', '17K' => '17K', 'Perak 925' => 'Perak 925'],
                'error' => $errors['purity'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'weight', 'label' => 'Berat Estimasi (gram)',
                'value' => old('weight', $order['weight'] ?? ''),
                'placeholder' => 'Contoh: 2.5', 'error' => $errors['weight'] ?? '',
                'extraAttrs' => 'step="0.01" min="0.01"',
            ]) ?>
            <?= form_field([
                'type' => 'number', 'name' => 'estimated_price', 'label' => 'Estimasi Harga (Rp)', 'required' => true,
                'value' => old('estimated_price', $order['estimated_price'] ?? ''),
                'placeholder' => 'Contoh: 3000000', 'error' => $errors['estimated_price'] ?? '',
                'extraAttrs' => 'step="1" min="1"',
            ]) ?>
            <?= form_field([
                'type' => 'date', 'name' => 'due_date', 'label' => 'Target Selesai',
                'value' => old('due_date', $order['due_date'] ?? ''),
                'error' => $errors['due_date'] ?? '',
            ]) ?>
        </div>

        <?= form_field([
            'type' => 'textarea', 'name' => 'notes', 'label' => 'Catatan',
            'value' => old('notes', $order['notes'] ?? ''), 'rows' => '3',
            'placeholder' => 'Detail tambahan, ukuran, atau permintaan khusus.',
            'error' => $errors['notes'] ?? '',
        ]) ?>

        <div>
            <?= form_field([
                'type' => 'file', 'name' => 'reference_image', 'label' => 'Foto Referensi',
                'accept' => 'image/jpeg,image/png,image/webp',
                'hint' => 'Opsional. JPG, PNG, atau WEBP maksimal 2 MB.',
            ]) ?>
            <?php if ($isEdit && !empty($order['reference_image_url'])): ?>
                <img src="<?= esc($order['reference_image_url'], 'attr') ?>" alt="Foto referensi saat ini" class="mt-3 h-28 w-28 rounded-xl border border-line object-cover">
            <?php endif; ?>
        </div>

        <div class="rounded-lg bg-ivory px-4 py-3 text-xs text-charcoal/60">
            DP dan pembayaran dilakukan di toko setelah pesanan dikonfirmasi. Pesanan hanya dapat diubah selama status masih <strong>Masuk</strong>.
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('pelanggan/pesanan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => $isEdit ? 'Simpan Perubahan' : 'Ajukan Pesanan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
