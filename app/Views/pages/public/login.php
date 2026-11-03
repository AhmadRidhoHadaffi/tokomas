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

$error = session('error');
$oldIdentifier = old('identifier');
?>

<form method="POST" action="<?= base_url('masuk') ?>" noValidate class="space-y-4">
    <?= csrf_field() ?>
    
    <!-- Identifier Field -->
    <?= view('components/ui/form_field', [
        'label' => 'Nomor HP / Email',
        'name' => 'identifier',
        'id' => 'identifier',
        'type' => 'text',
        'placeholder' => '0812xxxx atau nama@email.com',
        'value' => $oldIdentifier,
        'required' => true,
    ]) ?>

    <!-- Password Field -->
    <div>
        <label for="password" class="mb-1.5 block text-sm font-medium text-charcoal/80">
            Kata Sandi<span class="text-crimson">*</span>
        </label>
        <div x-data="{ showPassword: false }" class="relative">
            <input 
                :type="showPassword ? 'text' : 'password'"
                name="password"
                id="password"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
                class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 pr-10 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20"
            />
            <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-charcoal/40 hover:text-charcoal"
            >
                <span x-show="!showPassword"><?= icon('eye', 'h-5 w-5') ?></span>
                <span x-show="showPassword" x-cloak><?= icon('eye-off', 'h-5 w-5') ?></span>
            </button>
        </div>
    </div>

    <!-- Forgot Password -->
    <div class="flex justify-end">
        <button type="button" class="text-xs font-medium text-gold-deep hover:underline">
            Lupa kata sandi?
        </button>
    </div>

    <!-- Error Message -->
    <?php if ($error): ?>
        <p role="alert" class="rounded-lg bg-crimson/5 px-3 py-2.5 text-sm text-crimson">
            <?= esc($error) ?>
        </p>
    <?php endif; ?>

    <!-- Submit Button -->
    <?= view('components/ui/button', [
        'variant' => 'primary',
        'size' => 'lg',
        'type' => 'submit',
        'class' => 'w-full',
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

<!-- Demo Accounts -->
<div class="mt-7 rounded-xl bg-ivory p-4">
    <p class="text-xs text-charcoal/60">
        Peran terdeteksi otomatis dari akun. Coba akun demo (kata sandi bebas, min. 6 karakter):
    </p>
    <div class="mt-3 flex flex-wrap gap-2">
        <button 
            type="button"
            onclick="document.getElementById('identifier').value='owner@tokomasgembira.id'; document.getElementById('password').value='gembira123';"
            class="rounded-full border border-gold/40 bg-white px-3 py-1.5 text-xs font-medium text-charcoal transition-colors duration-150 hover:border-gold hover:bg-gold/10"
        >
            Owner
        </button>
        <button 
            type="button"
            onclick="document.getElementById('identifier').value='rina@tokomasgembira.id'; document.getElementById('password').value='gembira123';"
            class="rounded-full border border-gold/40 bg-white px-3 py-1.5 text-xs font-medium text-charcoal transition-colors duration-150 hover:border-gold hover:bg-gold/10"
        >
            Karyawan
        </button>
        <button 
            type="button"
            onclick="document.getElementById('identifier').value='siti.rahma@gmail.com'; document.getElementById('password').value='gembira123';"
            class="rounded-full border border-gold/40 bg-white px-3 py-1.5 text-xs font-medium text-charcoal transition-colors duration-150 hover:border-gold hover:bg-gold/10"
        >
            Pelanggan
        </button>
    </div>
</div>

<?php $this->endSection(); ?>
