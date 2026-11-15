<?php
/**
 * Stat Card Component
 *
 * Render through the stat_card() helper so the featured flag never leaks into
 * the following cards.
 *
 * @var array $statCard
 * @var string $icon Icon name (lucide)
 * @var string $label Stat label
 * @var string $value Stat value (formatted)
 * @var array $trend ['value' => '+12%', 'up' => true]
 * @var string $hint Optional hint text
 * @var bool $featured Dark/featured variant
 */

$statCard = is_array($statCard ?? null) ? $statCard : [];

$icon = $statCard['icon'] ?? 'package';
$label = $statCard['label'] ?? '';
$value = $statCard['value'] ?? '0';
$trend = $statCard['trend'] ?? null;
$hint = $statCard['hint'] ?? '';
$featured = (bool) ($statCard['featured'] ?? false);

$cardClass = $featured
    ? 'bg-charcoal text-white shadow-card'
    : 'border border-line bg-white shadow-card';
$iconBg = $featured
    ? 'bg-gold/15 text-gold'
    : 'bg-ivory text-gold-dark';
$labelColor = $featured
    ? 'text-white/65'
    : 'text-charcoal/55';
$valueColor = $featured
    ? 'text-3xl text-gold'
    : 'text-2xl text-charcoal';
$hintColor = $featured
    ? 'text-white/50'
    : 'text-charcoal/45';
?>
<div class="flex h-full flex-col rounded-2xl p-5 <?= esc($cardClass, 'attr') ?>">
    <div class="flex items-center justify-between">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl <?= esc($iconBg, 'attr') ?>">
            <?= icon($icon, 'h-5 w-5', 24, 1.75) ?>
        </span>
        <?php if (is_array($trend) && ! empty($trend['value'])): ?>
            <?php
            $trendUp = (bool) ($trend['up'] ?? true);
            $trendClass = $trendUp
                ? ($featured ? 'bg-emerald-400/15 text-emerald-300' : 'bg-emerald-50 text-emerald-700')
                : ($featured ? 'bg-red-400/15 text-red-300' : 'bg-red-50 text-red-700');
            $trendIcon = $trendUp ? 'arrow-up' : 'arrow-down';
            ?>
            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium <?= esc($trendClass, 'attr') ?>">
                <?= icon($trendIcon, 'h-3.5 w-3.5') ?>
                <?= esc($trend['value']) ?>
            </span>
        <?php endif; ?>
    </div>
    <p class="mt-4 text-sm <?= esc($labelColor, 'attr') ?>"><?= esc($label) ?></p>
    <p class="mt-1 font-serif font-semibold <?= esc($valueColor, 'attr') ?>"><?= $value ?></p>
    <?php if ($hint): ?>
        <p class="mt-auto pt-2 text-xs <?= esc($hintColor, 'attr') ?>"><?= esc($hint) ?></p>
    <?php endif; ?>
</div>
