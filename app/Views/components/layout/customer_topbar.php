<?php
/**
 * Customer Topbar
 *
 * @var int $unreadCount Number of unread notifications
 */

$unreadCount = (int) ($unreadCount ?? 0);
$userName = session('user_name') ?? 'Pelanggan';
$navigation = new \Config\Navigation();
$items = $navigation->pelanggan ?? [];
$currentPath = trim(uri_string(), '/');

$nameParts = preg_split('/\s+/', trim($userName)) ?: [];
$initials = '';
foreach (array_slice($nameParts, 0, 2) as $part) {
    $initials .= mb_substr($part, 0, 1);
}
$initials = mb_strtoupper($initials ?: 'P');
$badgeText = $unreadCount > 99 ? '99+' : (string) $unreadCount;
?>
<header class="sticky top-0 z-30 bg-gold-gradient shadow-sm">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
        <div class="shrink-0 rounded-lg focus-within:ring-4 focus-within:ring-charcoal/20">
            <?= view('components/brand/logo', [
                'tone' => 'dark',
                'markClass' => 'h-10 w-10',
                'href' => base_url('pelanggan'),
            ]) ?>
        </div>

        <nav aria-label="Menu pelanggan" class="hidden items-center gap-1 lg:flex">
            <?php foreach ($items as $item): ?>
                <?php
                $path = trim($item['url'], '/');
                $isActive = !empty($item['end'])
                    ? $currentPath === $path
                    : ($currentPath === $path || str_starts_with($currentPath, $path . '/'));
                $linkClass = $isActive
                    ? 'bg-charcoal text-gold'
                    : 'text-charcoal/80 hover:bg-charcoal/10 hover:text-charcoal';
                ?>
                <a
                    href="<?= base_url($item['url']) ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                    class="inline-flex min-h-10 items-center rounded-lg px-3.5 py-2 text-sm font-medium transition-colors duration-150 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-charcoal/20 <?= esc($linkClass, 'attr') ?>"
                >
                    <?= esc($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="flex items-center gap-2">
            <a
                href="<?= base_url('pelanggan/notifikasi') ?>"
                aria-label="Notifikasi, <?= esc($badgeText, 'attr') ?> belum dibaca"
                class="relative flex h-10 w-10 items-center justify-center rounded-lg text-charcoal transition-colors duration-150 hover:bg-charcoal/10 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-charcoal/20"
            >
                <?= icon('bell', 'h-5 w-5', 20, 1.75) ?>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute right-0.5 top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-crimson px-1 text-[10px] font-semibold text-white">
                        <?= esc($badgeText) ?>
                    </span>
                <?php endif; ?>
            </a>

            <a
                href="<?= base_url('pelanggan/profil') ?>"
                aria-label="Profil"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-charcoal text-xs font-semibold text-gold transition-transform duration-150 hover:scale-105 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-charcoal/20"
            >
                <?= esc($initials) ?>
            </a>

            <form method="post" action="<?= base_url('keluar') ?>" class="hidden lg:block">
                <?= csrf_field() ?>
                <?= ui_button([
                    'type' => 'submit',
                    'icon' => 'log-out',
                    'iconOnly' => true,
                    'variant' => 'ghostDark',
                    'ariaLabel' => 'Keluar',
                    'title' => 'Keluar',
                    'iconClass' => 'h-5 w-5',
                ]) ?>
            </form>
        </div>
    </div>
</header>
