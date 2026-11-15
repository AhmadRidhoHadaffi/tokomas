<?php
/**
 * Empty State Component
 *
 * Render through the empty_state() helper to keep options isolated.
 *
 * @var array $emptyState
 * @var string $icon Icon name
 * @var string $title Title text
 * @var string $description Description text
 * @var string $action Optional action button HTML
 */

$emptyState = is_array($emptyState ?? null) ? $emptyState : [];

$icon = $emptyState['icon'] ?? 'package';
$title = $emptyState['title'] ?? 'Belum ada data';
$description = $emptyState['description'] ?? '';
$action = $emptyState['action'] ?? '';
?>
<div class="flex flex-col items-center justify-center px-6 py-12 text-center">
    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-ivory text-gold-dark">
        <?= icon($icon, 'h-8 w-8', 32, 1.5) ?>
    </div>
    <h3 class="mt-4 font-serif text-lg font-semibold text-charcoal"><?= esc($title) ?></h3>
    <?php if ($description): ?>
        <p class="mt-2 max-w-sm text-sm text-charcoal/60"><?= esc($description) ?></p>
    <?php endif; ?>
    <?php if ($action): ?>
        <div class="mt-6"><?= $action ?></div>
    <?php endif; ?>
</div>
