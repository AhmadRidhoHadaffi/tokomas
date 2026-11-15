<?php
/**
 * OTP Verification Page (registration)
 */

$this->extend('layouts/auth');

$this->section('title');
echo 'Verifikasi OTP';
$this->endSection();

$this->section('auth_title');
echo 'Verifikasi Nomor HP';
$this->endSection();

$this->section('auth_subtitle');
echo 'Masukkan 6 digit kode OTP yang dikirim ke ' . esc($phone) . '.';
$this->endSection();

$this->section('content');

$errors = session('errors') ?? [];
$authInputClass = 'px-4 py-3.5';
?>

<?= $this->include('components/auth/flash') ?>
<?= $this->include('components/auth/debug_code', ['debugCode' => $debugCode ?? null]) ?>

<form method="POST" action="<?= base_url('verifikasi-otp') ?>" noValidate class="mt-4 space-y-4">
    <?= csrf_field() ?>

    <?= form_field([
        'label' => 'Kode OTP',
        'name' => 'code',
        'id' => 'code',
        'inputMode' => 'numeric',
        'placeholder' => '······',
        'value' => old('code'),
        'error' => $errors['code'] ?? '',
        'required' => true,
        'showRequired' => false,
        'autocomplete' => 'one-time-code',
        'inputClass' => $authInputClass . ' text-center font-mono text-xl tracking-[0.45em]',
        'extraAttrs' => 'inputmode="numeric" maxlength="6" pattern="[0-9]*"',
    ]) ?>

    <?= ui_button([
        'variant' => 'primary',
        'size' => 'none',
        'type' => 'submit',
        'class' => 'h-[52px] w-full px-6 text-base',
        'content' => 'Verifikasi &amp; Masuk',
    ]) ?>
</form>

<div
    class="mt-5 rounded-lg bg-ivory px-3.5 py-3 text-center text-sm text-charcoal/65"
    x-data="{ left: <?= (int) $cooldown ?> }"
    x-init="if (left > 0) { const t = setInterval(() => { left--; if (left <= 0) clearInterval(t); }, 1000); }"
>
    <span x-show="left > 0">Kirim ulang kode dalam <span x-text="left"></span> detik.</span>
    <form x-show="left <= 0" x-cloak method="POST" action="<?= base_url('kirim-ulang-otp') ?>">
        <?= csrf_field() ?>
        <?= ui_button([
            'type' => 'submit',
            'variant' => 'ghost',
            'size' => 'none',
            'class' => 'mx-auto min-h-[40px] px-3 text-sm font-semibold text-gold-deep hover:underline',
            'content' => 'Kirim ulang kode OTP',
        ]) ?>
    </form>
</div>

<p class="mt-6 text-center text-sm text-charcoal/60">
    Sudah punya akun?
    <a href="<?= base_url('masuk') ?>" class="font-semibold text-gold-deep hover:underline">
        Masuk
    </a>
</p>

<?php $this->endSection(); ?>
