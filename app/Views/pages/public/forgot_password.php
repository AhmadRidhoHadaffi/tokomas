<?php
/**
 * Forgot Password Page
 */

$this->extend('layouts/auth');

$this->section('title');
echo 'Lupa Kata Sandi';
$this->endSection();

$this->section('auth_title');
echo 'Lupa Kata Sandi';
$this->endSection();

$this->section('auth_subtitle');
echo 'Masukkan email atau nomor HP terdaftar. Kami akan mengirim kode OTP untuk mengatur ulang kata sandi.';
$this->endSection();

$this->section('content');

$errors = session('errors') ?? [];
$authInputClass = 'px-4 py-3.5';
?>

<?= $this->include('components/auth/flash') ?>

<form method="POST" action="<?= base_url('lupa-sandi') ?>" noValidate class="space-y-4">
    <?= csrf_field() ?>

    <?= form_field([
        'label' => 'Email atau Nomor HP',
        'name' => 'identifier',
        'id' => 'identifier',
        'placeholder' => 'nama@email.com atau 0812xxxxxxxx',
        'value' => old('identifier'),
        'error' => $errors['identifier'] ?? '',
        'required' => true,
        'showRequired' => false,
        'autocomplete' => 'username',
        'inputClass' => $authInputClass,
    ]) ?>

    <?= ui_button([
        'variant' => 'primary',
        'size' => 'none',
        'type' => 'submit',
        'class' => 'h-[52px] w-full px-6 text-base',
        'content' => 'Kirim Kode OTP',
    ]) ?>
</form>

<p class="mt-6 text-center text-sm text-charcoal/60">
    Ingat kata sandi Anda?
    <a href="<?= base_url('masuk') ?>" class="font-semibold text-gold-deep hover:underline">
        Kembali masuk
    </a>
</p>

<?php $this->endSection(); ?>
