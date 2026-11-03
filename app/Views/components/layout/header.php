<?php
/**
 * Header Component (Dashboard)
 * 
 * @var string $title Page title
 * @var string $userName User name
 * @var string $userRole Role label
 */

$title = $title ?? 'Dashboard';
$userName = $userName ?? session('user_name') ?? 'User';
$userRole = $userRole ?? session('role') ?? 'user';

// Get user initials
$nameParts = explode(' ', $userName);
$initials = '';
foreach (array_slice($nameParts, 0, 2) as $part) {
    $initials .= mb_substr($part, 0, 1);
}
$initials = mb_strtoupper($initials);

$roleLabels = [
    'owner' => 'Pemilik Toko',
    'karyawan' => 'Karyawan',
    'pelanggan' => 'Pelanggan',
];
$roleLabel = $roleLabels[$userRole] ?? $userRole;
?>
<header class="sticky top-0 z-20 border-b border-line bg-white/90 backdrop-blur">
    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <!-- Mobile Menu Button -->
        <button
            type="button"
            @click="drawerOpen = true"
            aria-label="Buka menu"
            class="rounded-lg p-2 text-charcoal/70 transition-colors duration-150 hover:bg-ivory lg:hidden"
        >
            <?= icon('menu', 'h-5 w-5') ?>
        </button>

        <!-- Page Title -->
        <h1 class="min-w-0 flex-1 truncate font-serif text-xl font-semibold text-charcoal">
            <?= esc($title) ?>
        </h1>

        <!-- Header Actions -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Notifications Dropdown -->
            <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                <button
                    type="button"
                    @click="notifOpen = !notifOpen"
                    aria-label="Notifikasi"
                    :aria-expanded="notifOpen"
                    class="relative rounded-lg p-2 text-charcoal/65 transition-colors duration-150 hover:bg-ivory hover:text-charcoal"
                >
                    <?= icon('bell', 'h-5 w-5', 20, 1.75) ?>
                    <!-- Notification Badge -->
                    <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-crimson ring-2 ring-white"></span>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="notifOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-160"
                    x-transition:enter-start="opacity-0 scale-97 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-120"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-97 -translate-y-1"
                    class="absolute right-0 top-12 z-40 w-80 origin-top-right overflow-hidden rounded-xl border border-line bg-white shadow-xl"
                    style="display: none;"
                >
                    <p class="border-b border-line px-4 py-3 text-sm font-semibold text-charcoal">Aktivitas terbaru</p>
                    <ul class="max-h-80 divide-y divide-line overflow-y-auto">
                        <li class="px-4 py-3">
                            <p class="text-sm text-charcoal/60">Belum ada notifikasi</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- User Profile -->
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold-gradient text-xs font-semibold text-charcoal">
                    <?= esc($initials) ?>
                </span>
                <div class="hidden leading-tight sm:block">
                    <p class="text-sm font-semibold text-charcoal"><?= esc($userName) ?></p>
                    <p class="text-xs text-charcoal/50"><?= esc($roleLabel) ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
