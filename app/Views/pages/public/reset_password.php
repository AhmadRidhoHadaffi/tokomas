<?php
/**
 * Reset Password Page (OTP + new password)
 */

$this->extend('layouts/auth');

$this->section('title');
echo 'Reset Kata Sandi';
$this->endSection();

$this->section('auth_title');
echo 'Atur Kata Sandi Baru';
$this->endSection();

$this->section('auth_subtitle');
echo 'Masukkan kode OTP yang dikirim ke ' . esc($phone) . ', lalu buat kata sandi baru.';
$this->endSection();

$this->section('content');

$errors = session('errors') ?? [];
$authInputClass = 'px-4 py-3.5';
?>

<?= $this->include('components/auth/flash') ?>
<?= $this->include('components/auth/debug_code', ['debugCode' => $debugCode ?? null]) ?>

<form method="POST" action="<?= base_url('reset-sandi') ?>" noValidate class="mt-4 space-y-4">
    <?= csrf_field() ?>

    <?= form_field([
        'label' => 'Kode OTP',
        'name' => 'code',
        'id' => 'code',
        'placeholder' => '······',
        'value' => old('code'),
        'error' => $errors['code'] ?? '',
        'required' => true,
        'showRequired' => false,
        'autocomplete' => 'one-time-code',
        'inputClass' => $authInputClass . ' text-center font-mono text-xl tracking-[0.45em]',
        'extraAttrs' => 'inputmode="numeric" maxlength="6" pattern="[0-9]*"',
    ]) ?>

    <!-- New password -->
    <div>
        <label for="password" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            Kata Sandi Baru
        </label>
        <div x-data="{ showPassword: false }" class="relative">
            <input
                :type="showPassword ? 'text' : 'password'"
                name="password"
                id="password"
                autocomplete="new-password"
                required
                class="w-full rounded-lg border bg-white px-4 py-3.5 pr-12 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20 <?= isset($errors['password']) ? 'border-crimson' : 'border-line' ?>"
            >
            <?= ui_button([
                'type' => 'button',
                'icon' => '',
                'iconOnly' => true,
                'variant' => 'ghostSubtle',
                'class' => 'absolute right-2 top-1/2 -translate-y-1/2',
                'content' => '<span x-show="!showPassword">' . icon('eye', 'h-5 w-5') . '</span>'
                    . '<span x-show="showPassword" x-cloak>' . icon('eye-off', 'h-5 w-5') . '</span>',
                'ariaLabel' => 'Tampilkan atau sembunyikan kata sandi',
                'attrs' => ['@click' => 'showPassword = !showPassword'],
            ]) ?>
        </div>
        <?php if (isset($errors['password'])): ?>
            <p class="mt-1.5 text-xs text-crimson"><?= esc($errors['password']) ?></p>
        <?php else: ?>
            <p class="mt-1.5 text-xs text-charcoal/50">Minimal 8 karakter.</p>
        <?php endif; ?>
    </div>

    <!-- Confirm new password -->
    <div>
        <label for="password_confirm" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            Konfirmasi Kata Sandi Baru
        </label>
        <div x-data="{ showPassword: false }" class="relative">
            <input
                :type="showPassword ? 'text' : 'password'"
                name="password_confirm"
                id="password_confirm"
                autocomplete="new-password"
                required
                class="w-full rounded-lg border bg-white px-4 py-3.5 pr-12 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20 <?= isset($errors['password_confirm']) ? 'border-crimson' : 'border-line' ?>"
            >
            <?= ui_button([
                'type' => 'button',
                'icon' => '',
                'iconOnly' => true,
                'variant' => 'ghostSubtle',
                'class' => 'absolute right-2 top-1/2 -translate-y-1/2',
                'content' => '<span x-show="!showPassword">' . icon('eye', 'h-5 w-5') . '</span>'
                    . '<span x-show="showPassword" x-cloak>' . icon('eye-off', 'h-5 w-5') . '</span>',
                'ariaLabel' => 'Tampilkan atau sembunyikan konfirmasi kata sandi',
                'attrs' => ['@click' => 'showPassword = !showPassword'],
            ]) ?>
        </div>
        <?php if (isset($errors['password_confirm'])): ?>
            <p class="mt-1.5 text-xs text-crimson"><?= esc($errors['password_confirm']) ?></p>
        <?php endif; ?>
    </div>

    <?= ui_button([
        'variant' => 'primary',
        'size' => 'none',
        'type' => 'submit',
        'class' => 'h-[52px] w-full px-6 text-base',
        'content' => 'Simpan Kata Sandi Baru',
    ]) ?>
</form>

<div
    class="mt-5 rounded-lg bg-ivory px-3.5 py-3 text-center text-sm text-charcoal/65"
    x-data="{ left: <?= (int) $cooldown ?> }"
    x-init="if (left > 0) { const t = setInterval(() => { left--; if (left <= 0) clearInterval(t); }, 1000); }"
>
    <span x-show="left > 0">Kirim ulang kode dalam <span x-text="left"></span> detik.</span>
    <form x-show="left <= 0" x-cloak method="POST" action="<?= base_url('kirim-ulang-reset') ?>">
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
    <a href="<?= base_url('lupa-sandi') ?>" class="font-semibold text-gold-deep hover:underline">
        Ganti email atau nomor HP
    </a>
</p>

<?php $this->endSection(); ?>
