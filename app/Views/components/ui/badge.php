<?php
/**
 * Badge Component
 * 
 * @var string $tone blue|yellow|orange|green|grey|red|gold|crimson
 * @var string $text Badge text
 * @var bool $dot Show indicator dot
 * @var string $class Additional classes
 */

$tone = $tone ?? 'gold';
$text = $text ?? '';
$dot = $dot ?? false;
$class = $class ?? '';

$toneClasses = [
    'blue' => 'bg-blue-50 text-blue-700 ring-blue-200',
    'yellow' => 'bg-yellow-50 text-yellow-800 ring-yellow-200',
    'orange' => 'bg-orange-50 text-orange-700 ring-orange-200',
    'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'grey' => 'bg-gray-100 text-gray-600 ring-gray-200',
    'red' => 'bg-red-50 text-red-700 ring-red-200',
    'gold' => 'bg-gold/15 text-gold-deep ring-gold/35',
    'crimson' => 'bg-crimson/10 text-crimson ring-crimson/20',
];

$dotClasses = [
    'blue' => 'bg-blue-500',
    'yellow' => 'bg-yellow-500',
    'orange' => 'bg-orange-500',
    'green' => 'bg-emerald-500',
    'grey' => 'bg-gray-400',
    'red' => 'bg-red-500',
    'gold' => 'bg-gold-dark',
    'crimson' => 'bg-crimson',
];

$baseClass = 'inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset';
$finalClass = trim($baseClass . ' ' . ($toneClasses[$tone] ?? $toneClasses['gold']) . ' ' . $class);
?>
<span class="<?= esc($finalClass, 'attr') ?>">
    <?php if ($dot): ?>
        <span class="h-1.5 w-1.5 rounded-full <?= esc($dotClasses[$tone] ?? $dotClasses['gold'], 'attr') ?>" aria-hidden="true"></span>
    <?php endif; ?>
    <?= esc($text) ?>
</span>
