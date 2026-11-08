<?php
/**
 * Sidebar Navigation Component
 *
 * @var string $role owner|karyawan|pelanggan
 * @var bool $compactOnTablet Compact on tablet breakpoint
 */

$role = $role ?? 'owner';
$compactOnTablet = $compactOnTablet ?? false;

$navigation = new \Config\Navigation();
$items = $items ?? ($navigation->{$role} ?? []);

$currentPath = trim(uri_string(), '/');
$labelClass = $compactOnTablet ? 'md:hidden lg:inline' : '';
$centerClass = $compactOnTablet ? 'md:justify-center lg:justify-start' : '';
$paddingClass = $compactOnTablet ? 'md:justify-center md:px-0 lg:justify-start lg:px-5' : '';
?>
<!-- Logo -->
<a
    href="<?= base_url('/') ?>"
    class="flex h-20 shrink-0 items-center gap-3 rounded-none border-b border-white/5 px-5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/60 <?= esc($paddingClass, 'attr') ?>"
    aria-label="Toko Mas Gembira - Beranda"
>
    <svg viewBox="0 0 100 100" class="h-9 w-9 shrink-0" role="img" aria-label="Logo Toko Mas Gembira">
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
    <span class="whitespace-nowrap font-serif text-base font-semibold text-white <?= esc($labelClass, 'attr') ?>">
        Toko Mas Gembira
    </span>
</a>

<!-- Navigation Menu -->
<nav aria-label="Menu dashboard" class="scrollbar-thin flex-1 overflow-y-auto px-3 py-5">
    <ul class="space-y-1">
        <?php foreach ($items as $item): ?>
            <?php
            $url = $item['url'];
            $path = trim($url, '/');
            $isActive = !empty($item['end'])
                ? $currentPath === $path
                : ($currentPath === $path || str_starts_with($currentPath, $path . '/'));
            $activeClass = $isActive
                ? 'bg-white/[0.06] text-gold'
                : 'text-white/65 hover:bg-white/[0.04] hover:text-white';
            ?>
            <li>
                <a
                    href="<?= base_url($url) ?>"
                    title="<?= esc($item['label'], 'attr') ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                    @click="drawerOpen = false"
                    class="relative flex min-h-10 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/60 <?= esc($centerClass . ' ' . $activeClass, 'attr') ?>"
                >
                    <?php if ($isActive): ?>
                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 rounded-r-full bg-gold" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?= icon($item['icon'], 'h-[18px] w-[18px] shrink-0', 18, 1.75) ?>
                    <span class="whitespace-nowrap <?= esc($labelClass, 'attr') ?>"><?= esc($item['label']) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<!-- Logout -->
<div class="border-t border-white/5 p-3">
    <form method="post" action="<?= base_url('keluar') ?>">
        <?= csrf_field() ?>
        <button
            type="submit"
            title="Keluar"
            class="flex min-h-10 w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-white/55 transition-colors duration-150 hover:bg-white/[0.04] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/60 <?= esc($centerClass, 'attr') ?>"
        >
            <?= icon('log-out', 'h-[18px] w-[18px] shrink-0', 18, 1.75) ?>
            <span class="whitespace-nowrap <?= esc($labelClass, 'attr') ?>">Keluar</span>
        </button>
    </form>
</div>
