<?php
/**
 * Auth Layout (Login, Register)
 * Uses: <?= $this->extend('layouts/auth') ?>
 */
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', true) ? esc($this->renderSection('title', true)) . ' - ' : '' ?>Toko Mas Gembira</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full">
    <div class="relative flex min-h-screen w-full items-center justify-center px-4 py-12" style="background: linear-gradient(160deg, #1C1C1C 0%, #1C1C1C 50%, #4A3812 100%);">
        <!-- Background Pattern -->
        <div class="bg-arabesque absolute inset-0" aria-hidden="true"></div>
        
        <!-- Back to Home -->
        <a href="<?= base_url('/') ?>" class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-white/70 transition-colors duration-150 hover:bg-white/10 hover:text-white">
            <?= icon('arrow-left', 'h-4 w-4') ?>
            Beranda
        </a>

        <!-- Auth Card -->
        <main class="relative w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl shadow-black/40 sm:p-9">
            <!-- Logo & Title -->
            <div class="flex flex-col items-center text-center">
                <!-- Logo Mark -->
                <svg viewBox="0 0 100 100" class="h-16 w-16" role="img" aria-label="Logo">
                    <circle cx="50" cy="50" r="49" fill="#FDF8F0" />
                    <circle cx="50" cy="50" r="47" fill="none" stroke="#D4AF37" stroke-width="1.2" opacity="0.6" />
                    <path d="M30 32 L26 15 L38 23 L44 10 L50 21 L56 10 L62 23 L74 15 L70 32 Z" fill="#8B0000" />
                    <circle cx="26" cy="14" r="2.4" fill="#8B0000" />
                    <circle cx="44" cy="9" r="2.4" fill="#8B0000" />
                    <circle cx="56" cy="9" r="2.4" fill="#8B0000" />
                    <circle cx="74" cy="14" r="2.4" fill="#8B0000" />
                    <rect x="30" y="31" width="40" height="4" rx="1" fill="#8B0000" />
                    <circle cx="40" cy="28" r="1.4" fill="#FDF8F0" />
                    <circle cx="50" cy="28" r="1.4" fill="#FDF8F0" />
                    <circle cx="60" cy="28" r="1.4" fill="#FDF8F0" />
                    <circle cx="50" cy="63" r="25" fill="none" stroke="#8B0000" stroke-width="3.2" />
                    <circle cx="50" cy="63" r="20" fill="none" stroke="#8B0000" stroke-width="1.4" />
                    <text x="50" y="73.5" text-anchor="middle" font-family="'Playfair Display', Georgia, serif" font-weight="700" font-size="29" fill="#8B0000">G</text>
                </svg>
                
                <p class="mt-3 font-serif text-lg font-semibold text-charcoal">Toko Mas Gembira</p>
                <div class="mx-auto mt-5 h-px w-12 bg-gold" aria-hidden="true"></div>
                <h1 class="mt-5 font-serif text-2xl font-semibold text-charcoal">
                    <?= $this->renderSection('auth_title') ?>
                </h1>
                <p class="mt-1.5 text-sm text-charcoal/60">
                    <?= $this->renderSection('auth_subtitle') ?>
                </p>
            </div>

            <!-- Content -->
            <div class="mt-7">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
