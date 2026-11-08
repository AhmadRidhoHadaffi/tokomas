<?php
/**
 * Customer Empty State
 *
 * @var string $title
 * @var string $description
 * @var string $action Optional action HTML
 * @var bool $compact Reduce vertical padding
 */

$title = $title ?? 'Belum ada data';
$heading = $heading ?? $title;
$description = $description ?? '';
$action = $action ?? '';
$compact = $compact ?? false;
?>
<div class="flex flex-col items-center justify-center px-6 text-center <?= $compact ? 'py-10' : 'py-14' ?>">
    <svg viewBox="0 0 120 96" class="h-24 w-32" aria-hidden="true">
        <ellipse cx="60" cy="86" rx="38" ry="5" fill="#F6EDDD"></ellipse>
        <circle cx="60" cy="52" r="26" fill="none" stroke="#D4AF37" stroke-width="3"></circle>
        <circle cx="60" cy="52" r="20" fill="none" stroke="#D4AF37" stroke-width="1" opacity="0.5"></circle>
        <path d="M48 26 L54 16 H66 L72 26 L60 36 Z" fill="#FDF8F0" stroke="#B8860B" stroke-width="1.6" stroke-linejoin="round"></path>
        <path d="M48 26 H72 M54 16 L60 36 L66 16" fill="none" stroke="#B8860B" stroke-width="1" opacity="0.7"></path>
        <circle cx="96" cy="22" r="2" fill="#D4AF37"></circle>
        <circle cx="22" cy="34" r="1.5" fill="#D4AF37"></circle>
    </svg>
    <p class="mt-4 font-serif text-lg font-semibold text-charcoal"><?= esc($heading) ?></p>
    <?php if ($description): ?>
        <p class="mt-1 max-w-sm text-sm text-charcoal/55"><?= esc($description) ?></p>
    <?php endif; ?>
    <?php if ($action): ?>
        <div class="mt-5"><?= $action ?></div>
    <?php endif; ?>
</div>
