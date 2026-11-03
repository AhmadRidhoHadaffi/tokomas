<?php
/**
 * Logo Component
 * 
 * @var string $tone light|dark
 * @var bool $showTagline Show tagline
 * @var string $markClass Logo mark classes
 * @var string $href Link URL (default: /)
 */

$tone = $tone ?? 'dark';
$showTagline = $showTagline ?? false;
$markClass = $markClass ?? 'h-10 w-10';
$href = $href ?? '/';

$nameColor = $tone === 'light' ? 'text-white' : 'text-charcoal';
$tagColor = $tone === 'light' ? 'text-gold' : 'text-gold-deep';
?>
<a href="<?= esc($href, 'attr') ?>" class="flex items-center gap-3">
    <!-- Logo Mark -->
    <svg viewBox="0 0 100 100" class="<?= esc($markClass, 'attr') ?> shrink-0" role="img" aria-label="Logo Toko Mas Gembira">
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
    
    <!-- Name & Tagline -->
    <span class="flex flex-col leading-tight">
        <span class="whitespace-nowrap font-serif text-lg font-semibold <?= esc($nameColor, 'attr') ?>">
            Toko Mas Gembira
        </span>
        <?php if ($showTagline): ?>
            <span class="text-[11px] font-medium tracking-wide <?= esc($tagColor, 'attr') ?>">
                Perhiasan Emas &amp; Perak Pilihan Terbaik
            </span>
        <?php endif; ?>
    </span>
</a>
