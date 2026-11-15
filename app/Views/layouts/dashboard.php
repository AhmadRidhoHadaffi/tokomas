<?php
/**
 * Dashboard Layout (Owner & Karyawan)
 * Uses: <?= $this->extend('layouts/dashboard') ?>
 */

$role = session('role') ?? 'owner';
$userName = session('user_name') ?? 'User';
$currentPath = trim(uri_string(), '/');

// Page title: explicit section wins, otherwise derive it from the active menu item.
$sectionTitle = trim((string) $this->renderSection('title'));
$navigation = new \Config\Navigation();
$navItems = $navigation->{$role} ?? [];
$navTitle = 'Dashboard';
$matchedPath = '';
foreach ($navItems as $item) {
    $path = trim($item['url'], '/');
    if ($currentPath === $path || str_starts_with($currentPath, $path . '/')) {
        if (strlen($path) > strlen($matchedPath)) {
            $matchedPath = $path;
            $navTitle = $item['label'];
        }
    }
}
if ($currentPath === 'owner/transaksi/baru' || $currentPath === 'karyawan/transaksi/baru') {
    $navTitle = 'Catat Transaksi';
}
$pageTitle = $sectionTitle !== '' ? $sectionTitle : $navTitle;
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> - Toko Mas Gembira</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="h-full" x-data="{ drawerOpen: false }" :class="drawerOpen ? 'overflow-hidden' : ''">
    <div class="min-h-screen w-full bg-ivory">
        <!-- Desktop Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-[76px] flex-col bg-sidebar md:flex lg:w-[240px]">
            <?= view('components/layout/sidebar', ['role' => $role, 'compactOnTablet' => true]) ?>
        </aside>

        <!-- Mobile Drawer -->
        <div
            x-show="drawerOpen"
            x-cloak
            @keydown.escape.window="drawerOpen = false"
            class="fixed inset-0 z-50 lg:hidden"
            role="dialog"
            aria-modal="true"
            aria-label="Menu navigasi"
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
                aria-hidden="true"
            ></div>

            <!-- Drawer Panel -->
            <aside
                x-show="drawerOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="absolute inset-y-0 left-0 flex w-[260px] max-w-[85vw] flex-col bg-sidebar shadow-2xl"
            >
                <?= view('components/layout/sidebar', ['role' => $role, 'compactOnTablet' => false]) ?>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="md:pl-[76px] lg:pl-[240px]">
            <?= view('components/layout/header', [
                'title' => $pageTitle,
                'userName' => $userName,
                'userRole' => $role,
            ]) ?>

            <main class="min-w-0 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
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
