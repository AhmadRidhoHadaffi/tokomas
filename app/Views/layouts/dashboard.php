<?php
/**
 * Dashboard Layout (Owner & Karyawan)
 * Uses: <?= $this->extend('layouts/dashboard') ?>
 */

$role = session('role') ?? 'owner';
$userName = session('user_name') ?? 'User';
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
    <?= $this->renderSection('styles') ?>
</head>
<body class="h-full" x-data="{ drawerOpen: false }">
    <div class="min-h-screen w-full bg-ivory">
        <!-- Desktop Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-[76px] flex-col bg-sidebar md:flex lg:w-[240px]">
            <?= view('components/layout/sidebar', ['role' => $role, 'compactOnTablet' => true]) ?>
        </aside>

        <!-- Mobile Drawer -->
        <div 
            x-show="drawerOpen"
            x-cloak
            class="fixed inset-0 z-50 lg:hidden"
        >
            <!-- Backdrop -->
            <div
                x-show="drawerOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="drawerOpen = false"
                class="absolute inset-0 bg-charcoal/60"
            ></div>

            <!-- Drawer Panel -->
            <aside
                x-show="drawerOpen"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="absolute inset-y-0 left-0 flex w-[260px] flex-col bg-sidebar"
            >
                <?= view('components/layout/sidebar', ['role' => $role, 'compactOnTablet' => false]) ?>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="md:pl-[76px] lg:pl-[240px]">
            <?= view('components/layout/header', [
                'title' => $this->renderSection('title', true) ?: 'Dashboard',
                'userName' => $userName,
                'userRole' => $role,
            ]) ?>

            <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <?= $this->include('partials/flash') ?>
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
