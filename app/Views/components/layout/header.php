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
    'karyawan' => 'Karyawan/Kasir',
    'pelanggan' => 'Pelanggan',
];
$roleLabel = $roleLabels[$userRole] ?? $userRole;

// Latest activity for the notification dropdown.
$notifications = [];
try {
    $notifications = \Config\Database::connect()
        ->table('notifications as n')
        ->select('n.type, n.title, n.message, n.is_read, n.created_at, c.name as customer_name')
        ->join('customers as c', 'c.id = n.customer_id', 'left')
        ->orderBy('n.created_at', 'DESC')
        ->limit(4)
        ->get()
        ->getResultArray();
} catch (\Throwable $e) {
    $notifications = [];
}

$hasUnread = false;
foreach ($notifications as $notification) {
    if (empty($notification['is_read'])) {
        $hasUnread = true;
        break;
    }
}

$kindTones = [
    'Status Pesanan' => 'blue',
    'Pembayaran' => 'orange',
    'Promosi' => 'gold',
];
?>
<header class="sticky top-0 z-20 border-b border-line bg-white/90 backdrop-blur">
    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <!-- Mobile Menu Button -->
        <?= ui_button([
            'type' => 'button',
            'icon' => 'menu',
            'iconOnly' => true,
            'variant' => 'ghost',
            'ariaLabel' => 'Buka menu',
            'iconClass' => 'h-5 w-5',
            'class' => '-ml-1 lg:hidden',
            'attrs' => ['@click' => 'drawerOpen = true'],
        ]) ?>

        <!-- Page Title -->
        <h1 class="min-w-0 flex-1 truncate font-serif text-lg font-semibold text-charcoal sm:text-xl">
            <?= esc($title) ?>
        </h1>

        <!-- Header Actions -->
        <div class="flex items-center gap-1.5 sm:gap-3">
            <!-- Notifications Dropdown -->
            <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false" @keydown.escape.window="notifOpen = false">
                <?= ui_button([
                    'type' => 'button',
                    'icon' => 'bell',
                    'iconOnly' => true,
                    'variant' => 'ghost',
                    'ariaLabel' => 'Notifikasi',
                    'iconClass' => 'h-5 w-5',
                    'class' => 'relative',
                    'content' => $hasUnread
                        ? '<span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-crimson ring-2 ring-white" aria-hidden="true"></span>'
                        : '',
                    'attrs' => [
                        '@click' => 'notifOpen = !notifOpen',
                        ':aria-expanded' => 'notifOpen',
                    ],
                ]) ?>

                <!-- Dropdown -->
                <div
                    x-show="notifOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="fixed left-4 right-4 top-[72px] z-40 w-auto origin-top-right overflow-hidden rounded-xl border border-line bg-white shadow-xl sm:absolute sm:left-auto sm:right-0 sm:top-12 sm:w-80 sm:max-w-[calc(100vw-2rem)]"
                    style="display: none;"
                >
                    <p class="border-b border-line px-4 py-3 text-sm font-semibold text-charcoal">Aktivitas terbaru</p>
                    <?php if ($notifications): ?>
                        <ul class="max-h-80 divide-y divide-line overflow-y-auto">
                            <?php foreach ($notifications as $notification): ?>
                                <?php $tone = $kindTones[$notification['type']] ?? 'grey'; ?>
                                <li class="px-4 py-3">
                                    <?= badge([
                                        'tone' => $tone,
                                        'text' => $notification['type'],
                                    ]) ?>
                                    <p class="mt-1.5 text-sm text-charcoal/80"><?= esc($notification['message']) ?></p>
                                    <p class="mt-1 text-xs text-charcoal/45">
                                        <?= esc($notification['customer_name'] ?: 'Semua pelanggan') ?>
                                        &middot;
                                        <?= esc(format_datetime((string) $notification['created_at'])) ?>
                                    </p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="px-4 py-6 text-center text-sm text-charcoal/50">Belum ada notifikasi</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Profile -->
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold-gradient text-xs font-semibold text-charcoal" aria-hidden="true">
                    <?= esc($initials) ?>
                </span>
                <div class="hidden min-w-0 leading-tight sm:block">
                    <p class="truncate text-sm font-semibold text-charcoal"><?= esc($userName) ?></p>
                    <p class="truncate text-xs text-charcoal/50"><?= esc($roleLabel) ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
