<?php
/**
 * Register Page
 */

$this->extend('layouts/auth');

$this->section('title');
echo 'Daftar';
$this->endSection();

$this->section('auth_title');
echo 'Buat Akun Pelanggan';
$this->endSection();

$this->section('auth_subtitle');
echo 'Gratis — pantau pesanan dan dapatkan promo eksklusif.';
$this->endSection();

$this->section('content');

$errors = session('errors') ?? [];
$authInputClass = 'px-4 py-3.5';
?>

<form method="POST" action="<?= base_url('daftar') ?>" noValidate class="space-y-4">
    <?= csrf_field() ?>

    <!-- Name -->
    <?= view('components/ui/form_field', [
        'label' => 'Nama Lengkap',
        'name' => 'name',
        'id' => 'name',
        'placeholder' => 'Nama sesuai KTP',
        'value' => old('name'),
        'error' => $errors['name'] ?? '',
        'required' => true,
        'showRequired' => false,
        'autocomplete' => 'name',
        'inputClass' => $authInputClass,
    ]) ?>

    <!-- Phone & Email Grid -->
    <div class="grid gap-4 sm:grid-cols-2">
        <?= view('components/ui/form_field', [
            'label' => 'Nomor HP',
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'tel',
            'placeholder' => '0812xxxxxxxx',
            'value' => old('phone'),
            'error' => $errors['phone'] ?? '',
            'required' => true,
            'showRequired' => false,
            'autocomplete' => 'tel',
            'inputClass' => $authInputClass,
        ]) ?>

        <?= view('components/ui/form_field', [
            'label' => 'Email',
            'name' => 'email',
            'id' => 'email',
            'type' => 'email',
            'placeholder' => 'nama@email.com',
            'value' => old('email'),
            'error' => $errors['email'] ?? '',
            'required' => true,
            'showRequired' => false,
            'autocomplete' => 'email',
            'inputClass' => $authInputClass,
        ]) ?>
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            Kata Sandi
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
            <button
                type="button"
                @click="showPassword = !showPassword"
                aria-label="Tampilkan atau sembunyikan kata sandi"
                class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-charcoal/40 transition-colors duration-150 hover:bg-ivory hover:text-charcoal focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
            >
                <span x-show="!showPassword"><?= icon('eye', 'h-5 w-5') ?></span>
                <span x-show="showPassword" x-cloak><?= icon('eye-off', 'h-5 w-5') ?></span>
            </button>
        </div>
        <?php if (isset($errors['password'])): ?>
            <p class="mt-1.5 text-xs text-crimson"><?= esc($errors['password']) ?></p>
        <?php else: ?>
            <p class="mt-1.5 text-xs text-charcoal/50">Minimal 8 karakter.</p>
        <?php endif; ?>
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirm" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            Konfirmasi Kata Sandi
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
            <button
                type="button"
                @click="showPassword = !showPassword"
                aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-charcoal/40 transition-colors duration-150 hover:bg-ivory hover:text-charcoal focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
            >
                <span x-show="!showPassword"><?= icon('eye', 'h-5 w-5') ?></span>
                <span x-show="showPassword" x-cloak><?= icon('eye-off', 'h-5 w-5') ?></span>
            </button>
        </div>
        <?php if (isset($errors['password_confirm'])): ?>
            <p class="mt-1.5 text-xs text-crimson"><?= esc($errors['password_confirm']) ?></p>
        <?php endif; ?>
    </div>

    <!-- OTP Notice -->
    <p class="flex items-start gap-2 rounded-lg bg-ivory px-3.5 py-3 text-xs leading-relaxed text-charcoal/65">
        <?= icon('shield-check', 'mt-0.5 h-4 w-4 shrink-0 text-gold-dark') ?>
        Nomor HP akan diverifikasi oleh sistem melalui kode OTP.
    </p>

    <!-- Submit -->
    <?= view('components/ui/button', [
        'variant' => 'primary',
        'size' => 'lg',
        'type' => 'submit',
        'class' => 'h-[52px] w-full text-base',
        'content' => 'Daftar Sekarang',
    ]) ?>
</form>

<!-- Login Link -->
<p class="mt-6 text-center text-sm text-charcoal/60">
    Sudah punya akun?
    <a href="<?= base_url('masuk') ?>" class="font-semibold text-gold-deep hover:underline">
        Masuk
    </a>
</p>

<?php $this->endSection(); ?>
