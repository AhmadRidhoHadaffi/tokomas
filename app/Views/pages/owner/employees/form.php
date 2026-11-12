<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$employee = $employee ?? null;
$isEdit = $employee !== null;
$errors = session()->getFlashdata('errors') ?? [];
$action = $isEdit ? base_url('owner/karyawan/' . $employee['id']) : base_url('owner/karyawan');
$isActive = (string) old('is_active', $employee['is_active'] ?? 1) === '1';
?>
<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl"><?= $isEdit ? 'Ubah Karyawan' : 'Tambah Karyawan' ?></h1>
        <p class="mt-2 text-sm text-charcoal/60">Karyawan dapat masuk dan mencatat transaksi toko.</p>
    </div>

    <form method="post" action="<?= esc($action, 'attr') ?>" class="space-y-6 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <?= csrf_field() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <?= view('components/ui/form_field', [
                'name' => 'name', 'label' => 'Nama Lengkap', 'required' => true,
                'value' => old('name', $employee['name'] ?? ''),
                'placeholder' => 'Nama karyawan', 'error' => $errors['name'] ?? '',
                'autocomplete' => 'name',
            ]) ?>
            <?= view('components/ui/form_field', [
                'type' => 'email', 'name' => 'email', 'label' => 'Email', 'required' => true,
                'value' => old('email', $employee['email'] ?? ''),
                'placeholder' => 'nama@tokogembira.id', 'error' => $errors['email'] ?? '',
                'autocomplete' => 'email',
            ]) ?>
        </div>

        <?php if (!$isEdit): ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <?= view('components/ui/form_field', [
                    'type' => 'password', 'name' => 'password', 'label' => 'Kata Sandi', 'required' => true,
                    'placeholder' => 'Minimal 8 karakter', 'error' => $errors['password'] ?? '',
                    'autocomplete' => 'new-password',
                ]) ?>
                <?= view('components/ui/form_field', [
                    'type' => 'password', 'name' => 'password_confirm', 'label' => 'Konfirmasi Kata Sandi', 'required' => true,
                    'placeholder' => 'Ulangi kata sandi', 'error' => $errors['password_confirm'] ?? '',
                    'autocomplete' => 'new-password',
                ]) ?>
            </div>
        <?php else: ?>
            <p class="rounded-lg bg-ivory px-4 py-3 text-sm text-charcoal/60">
                Untuk mengganti kata sandi, gunakan tombol <strong>Reset sandi</strong> pada daftar karyawan.
            </p>
        <?php endif; ?>

        <label class="flex items-start gap-3 rounded-lg border border-line px-4 py-3 text-sm">
            <input type="checkbox" name="is_active" value="1" class="mt-0.5 h-4 w-4 rounded border-line text-gold focus:ring-gold" <?= $isActive ? 'checked' : '' ?>>
            <span>
                <span class="font-medium text-charcoal">Akun aktif</span>
                <span class="mt-0.5 block text-xs text-charcoal/55">Karyawan nonaktif tidak dapat masuk ke aplikasi.</span>
            </span>
        </label>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/karyawan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => $isEdit ? 'Simpan Perubahan' : 'Simpan Karyawan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
