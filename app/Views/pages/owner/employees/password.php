<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$employee = $employee ?? [];
$errors = session()->getFlashdata('errors') ?? [];
?>
<div class="mx-auto max-w-xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Reset Kata Sandi</h1>
        <p class="mt-2 text-sm text-charcoal/60">Atur kata sandi baru untuk <?= esc($employee['name'] ?? 'karyawan') ?>.</p>
    </div>

    <form method="post" action="<?= base_url('owner/karyawan/' . ($employee['id'] ?? 0) . '/kata-sandi') ?>" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <div class="rounded-lg bg-ivory px-4 py-3 text-sm">
            <p class="font-medium text-charcoal"><?= esc($employee['name'] ?? '-') ?></p>
            <p class="text-charcoal/60"><?= esc($employee['email'] ?? '-') ?></p>
        </div>

        <?= form_field([
            'type' => 'password', 'name' => 'password', 'label' => 'Kata Sandi Baru', 'required' => true,
            'placeholder' => 'Minimal 8 karakter', 'error' => $errors['password'] ?? '',
            'autocomplete' => 'new-password',
        ]) ?>
        <?= form_field([
            'type' => 'password', 'name' => 'password_confirm', 'label' => 'Konfirmasi Kata Sandi Baru', 'required' => true,
            'placeholder' => 'Ulangi kata sandi baru', 'error' => $errors['password_confirm'] ?? '',
            'autocomplete' => 'new-password',
        ]) ?>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/karyawan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Reset Kata Sandi', 'icon' => 'key-round', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
