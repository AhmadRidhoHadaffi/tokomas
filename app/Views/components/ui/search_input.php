<?php
/**
 * Search Input Component
 * 
 * @var string $name Input name
 * @var string $value Current value
 * @var string $placeholder Placeholder text
 * @var string $class Additional classes
 */

$name = $name ?? 'search';
$value = $value ?? '';
$placeholder = $placeholder ?? 'Cari...';
$class = $class ?? '';
?>
<div class="relative <?= esc($class, 'attr') ?>">
    <?= icon('search', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-charcoal/40') ?>
    <input
        type="search"
        name="<?= esc($name, 'attr') ?>"
        value="<?= esc($value, 'attr') ?>"
        placeholder="<?= esc($placeholder, 'attr') ?>"
        aria-label="<?= esc($placeholder, 'attr') ?>"
        class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 pl-10 text-sm text-charcoal placeholder:text-charcoal/40 outline-none transition-[border-color,box-shadow] duration-150 focus:border-gold focus:ring-4 focus:ring-gold/20"
    />
</div>
