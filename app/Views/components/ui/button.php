<?php
/**
 * Button Component
 * 
 * @var string $variant primary|outline|outlineDark|outlineLight|ghost|danger|dark|light
 * @var string $size sm|md|lg
 * @var string $type button|submit|reset
 * @var string $class Additional classes
 * @var string $id Button ID
 * @var bool $disabled
 * @var string $href If set, renders as <a> tag
 * @var string $onclick
 * @var string $content Button content (text/HTML)
 */

$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$type = $type ?? 'button';
$class = $class ?? '';
$id = $id ?? '';
$disabled = $disabled ?? false;
$href = $href ?? '';
$onclick = $onclick ?? '';
$content = $content ?? '';

$variantClasses = [
    'primary' => 'bg-gold-gradient text-charcoal shadow-sm hover:brightness-110 hover:shadow-gold-glow',
    'outline' => 'border border-gold text-gold-deep bg-transparent hover:bg-gold/10',
    'outlineDark' => 'border border-gold/80 text-gold bg-transparent hover:bg-gold/10 hover:border-gold',
    'outlineLight' => 'border border-white/70 text-white bg-transparent hover:bg-white/10',
    'ghost' => 'text-charcoal/70 hover:bg-ivory-dark hover:text-charcoal',
    'danger' => 'bg-crimson text-white hover:bg-crimson-dark',
    'dark' => 'bg-charcoal text-white hover:bg-charcoal-soft',
    'light' => 'bg-white text-charcoal border border-line hover:border-gold/60 hover:bg-ivory',
];

$sizeClasses = [
    'sm' => 'h-9 px-3.5 text-xs gap-1.5',
    'md' => 'h-11 px-5 text-sm gap-2',
    'lg' => 'h-12 px-6 text-[15px] gap-2',
];

$baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-lg font-medium transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30';

$finalClass = trim($baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . $class);

$attrs = [];
if ($id) $attrs[] = 'id="' . esc($id, 'attr') . '"';
if ($disabled) $attrs[] = 'disabled';
if ($onclick) $attrs[] = 'onclick="' . esc($onclick, 'attr') . '"';

$attrString = implode(' ', $attrs);

if ($href): ?>
    <a href="<?= esc($href, 'attr') ?>" class="<?= esc($finalClass, 'attr') ?>" <?= $attrString ?>><?= $content ?></a>
<?php else: ?>
    <button type="<?= esc($type, 'attr') ?>" class="<?= esc($finalClass, 'attr') ?>" <?= $attrString ?>><?= $content ?></button>
<?php endif; ?>
