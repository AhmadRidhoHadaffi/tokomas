<?php
/**
 * Login Page
 */

$this->extend('layouts/auth');

$this->section('title');
echo 'Masuk';
$this->endSection();

$this->section('auth_title');
echo 'Masuk ke Akun';
$this->endSection();

$this->section('auth_subtitle');
echo 'Akses dashboard sesuai peran akun Anda.';
$this->endSection();

$this->section('content');

$errors = session('errors') ?? [];
$oldIdentifier = old('email');
$authInputClass = 'px-4 py-3.5';
?>

<?= $this->include('components/auth/flash') ?>

<form method="POST" action="<?= base_url('masuk') ?>" noValidate class="space-y-4">
    <?= csrf_field() ?>

    <!-- Identifier Field -->
    <?= form_field([
        'label' => 'Email',
        'name' => 'email',
        'id' => 'identifier',
        'type' => 'email',
        'placeholder' => 'nama@email.com',
        'value' => $oldIdentifier,
        'error' => $errors['email'] ?? '',
        'required' => true,
        'showRequired' => false,
        'autocomplete' => 'username',
        'inputClass' => $authInputClass,
    ]) ?>

    <!-- Password Field -->
    <div>
        <div class="mb-1.5 flex items-center justify-between gap-3">
            <label for="password" class="block text-sm font-medium text-charcoal/80">
                Kata Sandi
            </label>
            <a href="<?= base_url('lupa-sandi') ?>" class="text-xs font-semibold text-gold-deep hover:underline">
                Lupa kata sandi?
            </a>
        </div>
        <div x-data="{ showPassword: false }" class="relative">
            <input
                :type="showPassword ? 'text' : 'password'"
                name="password"
                id="password"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
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
        <?php endif; ?>
    </div>

    <!-- Submit Button -->
    <?= ui_button([
        'variant' => 'primary',
        'size' => 'none',
        'type' => 'submit',
        'class' => 'h-[52px] w-full px-6 text-base',
        'content' => 'Masuk',
    ]) ?>
</form>

<!-- Register Link -->
<p class="mt-6 text-center text-sm text-charcoal/60">
    Belum punya akun?
    <a href="<?= base_url('daftar') ?>" class="font-semibold text-gold-deep hover:underline">
        Daftar di sini
    </a>
</p>

<?php $this->endSection(); ?>
