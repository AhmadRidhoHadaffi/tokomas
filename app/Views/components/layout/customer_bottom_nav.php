<?php
/**
 * Customer Bottom Navigation (mobile only)
 *
 * @var int $unreadCount Number of unread notifications
 */

$unreadCount = (int) ($unreadCount ?? 0);
$navigation = new \Config\Navigation();
$items = $navigation->pelanggan ?? [];
$currentPath = trim(uri_string(), '/');
?>
<nav
    aria-label="Navigasi bawah"
    class="fixed inset-x-0 bottom-0 z-30 border-t border-line bg-white pb-[env(safe-area-inset-bottom)] lg:hidden"
>
    <ul class="grid grid-cols-5">
        <?php foreach ($items as $item): ?>
            <?php
            $path = trim($item['url'], '/');
            $isActive = !empty($item['end'])
                ? $currentPath === $path
                : ($currentPath === $path || str_starts_with($currentPath, $path . '/'));
            $linkClass = $isActive ? 'text-gold-dark' : 'text-charcoal/50 hover:text-charcoal/70';
            ?>
            <li>
                <a
                    href="<?= base_url($item['url']) ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                    class="relative flex min-h-[56px] flex-col items-center justify-center gap-1 py-2.5 text-[11px] font-medium transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/60 <?= esc($linkClass, 'attr') ?>"
                >
                    <?php if ($isActive): ?>
                        <span class="absolute inset-x-5 top-0 h-0.5 rounded-full bg-gold" aria-hidden="true"></span>
                    <?php endif; ?>
                    <span class="relative">
                        <?= icon($item['icon'], 'h-5 w-5', 20, $isActive ? 2 : 1.75) ?>
                        <?php if ($item['label'] === 'Notifikasi' && $unreadCount > 0): ?>
                            <span class="absolute -right-1.5 -top-1 h-2 w-2 rounded-full bg-crimson" aria-hidden="true"></span>
                        <?php endif; ?>
                    </span>
                    <?= esc($item['label']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
