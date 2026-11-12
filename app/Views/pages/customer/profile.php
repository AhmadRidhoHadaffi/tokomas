<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$customer = $customer ?? [];
$customerName = (string) ($customer['name'] ?? session('user_name') ?? 'Pelanggan');
$customerPhone = (string) ($customer['phone'] ?? '');
$customerEmail = (string) ($customer['email'] ?? session('user_email') ?? '');
$errors = session()->getFlashdata('errors') ?? [];

$nameParts = preg_split('/\s+/', trim($customerName)) ?: [];
$initials = '';
foreach (array_slice($nameParts, 0, 2) as $part) {
    $initials .= mb_substr($part, 0, 1);
}
$initials = mb_strtoupper($initials ?: 'P');

$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$createdTimestamp = strtotime((string) ($customer['created_at'] ?? ''));
$since = $createdTimestamp ? $months[(int) date('n', $createdTimestamp) - 1] . ' ' . date('Y', $createdTimestamp) : '-';
?>
<div class="mx-auto max-w-2xl space-y-6">
    <section class="rounded-2xl border border-line bg-white p-6 text-center sm:p-8">
        <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gold-gradient font-serif text-2xl font-semibold text-charcoal ring-4 ring-gold/20">
            <?= esc($initials) ?>
        </span>
        <h1 class="mt-4 break-words font-serif text-2xl font-semibold text-charcoal"><?= esc($customerName) ?></h1>
        <p class="text-sm text-charcoal/50">Pelanggan sejak <?= esc($since) ?></p>

        <div class="mt-5 flex flex-col items-center justify-center gap-2 text-sm text-charcoal/70 sm:flex-row sm:gap-6">
            <span class="flex min-w-0 items-center gap-2">
                <?= icon('phone', 'h-4 w-4 shrink-0 text-gold-dark', 16, 2) ?>
                <span class="break-all"><?= esc($customerPhone !== '' ? $customerPhone : '-') ?></span>
            </span>
            <span class="flex min-w-0 items-center gap-2">
                <?= icon('mail', 'h-4 w-4 shrink-0 text-gold-dark', 16, 2) ?>
                <span class="break-all"><?= esc($customerEmail !== '' ? $customerEmail : '-') ?></span>
            </span>
        </div>
    </section>

    <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <h2 class="font-serif text-lg font-semibold text-charcoal">Data Profil</h2>
        <p class="mt-1 text-sm text-charcoal/55">Perubahan nama dan email juga memperbarui akun masuk Anda.</p>

        <form method="post" action="<?= base_url('pelanggan/profil') ?>" class="mt-5 space-y-4">
            <?= csrf_field() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <?= view('components/ui/form_field', [
                    'name' => 'name', 'label' => 'Nama Lengkap', 'required' => true,
                    'value' => old('name', $customerName),
                    'error' => $errors['name'] ?? '', 'autocomplete' => 'name',
                ]) ?>
                <?= view('components/ui/form_field', [
                    'type' => 'tel', 'name' => 'phone', 'label' => 'Nomor HP', 'required' => true,
                    'value' => old('phone', $customerPhone),
                    'error' => $errors['phone'] ?? '', 'autocomplete' => 'tel',
                ]) ?>
                <?= view('components/ui/form_field', [
                    'type' => 'email', 'name' => 'email', 'label' => 'Email', 'required' => true,
                    'value' => old('email', $customerEmail),
                    'error' => $errors['email'] ?? '', 'autocomplete' => 'email',
                ]) ?>
                <?= view('components/ui/form_field', [
                    'type' => 'textarea', 'name' => 'address', 'label' => 'Alamat', 'class' => 'sm:col-span-2',
                    'value' => old('address', $customer['address'] ?? ''),
                    'error' => $errors['address'] ?? '', 'rows' => '3',
                ]) ?>
            </div>
            <div class="flex justify-end">
                <?= ui_button(['type' => 'submit', 'content' => 'Simpan Profil', 'icon' => 'check', 'size' => 'md']) ?>
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <h2 class="font-serif text-lg font-semibold text-charcoal">Ganti Kata Sandi</h2>
        <p class="mt-1 text-sm text-charcoal/55">Gunakan kata sandi yang kuat dan tidak dipakai di tempat lain.</p>

        <form method="post" action="<?= base_url('pelanggan/profil/kata-sandi') ?>" class="mt-5 space-y-4">
            <?= csrf_field() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <?= view('components/ui/form_field', [
                    'type' => 'password', 'name' => 'current_password', 'label' => 'Kata Sandi Saat Ini', 'required' => true,
                    'error' => $errors['current_password'] ?? '', 'autocomplete' => 'current-password',
                ]) ?>
                <?= view('components/ui/form_field', [
                    'type' => 'password', 'name' => 'new_password', 'label' => 'Kata Sandi Baru', 'required' => true,
                    'hint' => 'Minimal 8 karakter.', 'error' => $errors['new_password'] ?? '',
                    'autocomplete' => 'new-password',
                ]) ?>
                <div class="sm:col-span-2">
                    <?= view('components/ui/form_field', [
                        'type' => 'password', 'name' => 'new_password_confirm', 'label' => 'Konfirmasi Kata Sandi Baru', 'required' => true,
                        'error' => $errors['new_password_confirm'] ?? '', 'autocomplete' => 'new-password',
                    ]) ?>
                </div>
            </div>
            <div class="flex justify-end">
                <?= ui_button(['type' => 'submit', 'content' => 'Perbarui Kata Sandi', 'icon' => 'key-round', 'size' => 'md']) ?>
            </div>
        </form>
    </section>

    <form method="post" action="<?= base_url('keluar') ?>" class="lg:hidden">
        <?= csrf_field() ?>
        <?= ui_button([
            'type' => 'submit',
            'content' => 'Keluar',
            'icon' => 'log-out',
            'variant' => 'ghostDanger',
            'size' => 'md',
            'fullWidth' => true,
        ]) ?>
    </form>
</div>
<?= $this->endSection() ?>
