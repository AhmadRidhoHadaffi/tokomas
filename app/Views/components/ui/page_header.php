<?php
/**
 * Page Header Component
 *
 * Shared heading block used by dashboard pages: title, optional eyebrow &
 * description on the left, optional action buttons on the right.
 *
 * @var string $title       Page title
 * @var string $description Optional supporting text
 * @var string $eyebrow     Optional small label above the title
 * @var string $actions     Pre-rendered action HTML (buttons, badges, ...)
 */

$title       = $title ?? '';
$description = $description ?? '';
$eyebrow     = $eyebrow ?? '';
$actions     = $actions ?? '';
?>
<div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div class="min-w-0">
        <?php if ($eyebrow !== ''): ?>
            <p class="mb-1 text-xs font-semibold uppercase tracking-[0.16em] text-gold-dark"><?= esc($eyebrow) ?></p>
        <?php endif; ?>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl"><?= esc($title) ?></h1>
        <?php if ($description !== ''): ?>
            <p class="mt-2 max-w-2xl text-sm text-charcoal/60"><?= esc($description) ?></p>
        <?php endif; ?>
    </div>
    <?php if ($actions !== ''): ?>
        <div class="flex shrink-0 flex-wrap items-center gap-2"><?= $actions ?></div>
    <?php endif; ?>
</div>
