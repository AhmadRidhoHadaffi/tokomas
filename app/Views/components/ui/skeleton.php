<?php
/**
 * Skeleton Loader Component
 * 
 * @var string $class Additional classes
 * @var string $type line|circle|rect
 */

$class = $class ?? '';
$type = $type ?? 'line';

$baseClass = 'animate-pulse bg-charcoal/10';
$typeClass = match($type) {
    'circle' => 'rounded-full',
    'rect' => 'rounded-lg',
    default => 'h-4 rounded',
};
?>
<div class="<?= esc($baseClass . ' ' . $typeClass . ' ' . $class, 'attr') ?>"></div>
