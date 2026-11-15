<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$type = (string) old('type', 'Pemasukan');
?>
<div class="mx-auto max-w-2xl space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Tambah Catatan Pembukuan',
        'description' => 'Catat pemasukan atau pengeluaran toko.',
    ]) ?>

    <form method="post" action="<?= base_url('karyawan/pembukuan') ?>" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'type' => 'select', 'name' => 'type', 'label' => 'Tipe', 'required' => true,
                'value' => $type,
                'options' => ['Pemasukan' => 'Pemasukan', 'Pengeluaran' => 'Pengeluaran'],
                'error' => $errors['type'] ?? '',
            ]) ?>
            <?= form_field([
                'type' => 'select', 'name' => 'category', 'label' => 'Kategori', 'required' => true,
                'value' => old('category'),
                'placeholder' => 'Pilih kategori',
                'options' => (static function () use ($categories): array {
                    $flat = [];
                    foreach (($categories ?? []) as $group) {
                        foreach ($group as $option) {
                            $flat[$option] = $option;
                        }
                    }
                    return $flat;
                })(),
                'error' => $errors['category'] ?? '',
            ]) ?>
        </div>

        <?= form_field([
            'name' => 'description', 'label' => 'Deskripsi', 'required' => true,
            'value' => old('description'),
            'placeholder' => 'Contoh: Pembelian bahan baku perak',
            'error' => $errors['description'] ?? '',
        ]) ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'type' => 'number', 'name' => 'amount', 'label' => 'Jumlah (Rp)', 'required' => true,
                'value' => old('amount'),
                'placeholder' => 'Contoh: 500000',
                'error' => $errors['amount'] ?? '',
                'extraAttrs' => 'step="1" min="1"',
            ]) ?>
            <?= form_field([
                'type' => 'date', 'name' => 'entry_date', 'label' => 'Tanggal',
                'value' => old('entry_date', date('Y-m-d')),
                'error' => $errors['entry_date'] ?? '',
            ]) ?>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('karyawan/pembukuan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Catatan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
