<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$customer = $customer ?? [];
$errors = session()->getFlashdata('errors') ?? [];
?>
<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Ubah Pelanggan</h1>
        <p class="mt-2 text-sm text-charcoal/60">Perbarui data kontak pelanggan.</p>
    </div>

    <form method="post" action="<?= base_url('owner/pelanggan/' . ($customer['id'] ?? 0)) ?>" class="space-y-6 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= form_field([
                'name' => 'name', 'label' => 'Nama Lengkap', 'required' => true,
                'value' => old('name', $customer['name'] ?? ''),
                'error' => $errors['name'] ?? '', 'autocomplete' => 'name',
            ]) ?>
            <?= form_field([
                'type' => 'tel', 'name' => 'phone', 'label' => 'Nomor HP', 'required' => true,
                'value' => old('phone', $customer['phone'] ?? ''),
                'error' => $errors['phone'] ?? '', 'autocomplete' => 'tel',
            ]) ?>
            <?= form_field([
                'type' => 'email', 'name' => 'email', 'label' => 'Email',
                'value' => old('email', $customer['email'] ?? ''),
                'error' => $errors['email'] ?? '', 'autocomplete' => 'email',
            ]) ?>
            <?= form_field([
                'type' => 'textarea', 'name' => 'address', 'label' => 'Alamat', 'class' => 'sm:col-span-2',
                'value' => old('address', $customer['address'] ?? ''),
                'error' => $errors['address'] ?? '', 'rows' => '3',
            ]) ?>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/pelanggan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Perubahan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
