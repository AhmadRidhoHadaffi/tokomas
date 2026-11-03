<?php
/**
 * Pagination Component
 * 
 * @var int $currentPage
 * @var int $totalPages
 * @var int $total Total items
 * @var int $perPage Items per page
 * @var string $baseUrl Base URL for pagination links
 */

$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$total = $total ?? 0;
$perPage = $perPage ?? 20;
$baseUrl = $baseUrl ?? '';

if ($totalPages <= 1) {
    return;
}

$start = ($currentPage - 1) * $perPage + 1;
$end = min($currentPage * $perPage, $total);
?>
<div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
    <p class="text-sm text-charcoal/60">
        Menampilkan <span class="font-medium text-charcoal"><?= $start ?>-<?= $end ?></span> dari <span class="font-medium text-charcoal"><?= $total ?></span> data
    </p>
    
    <nav class="flex items-center gap-1" aria-label="Pagination">
        <?php if ($currentPage > 1): ?>
            <a href="<?= esc($baseUrl . '?page=' . ($currentPage - 1), 'attr') ?>" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-white text-charcoal/60 transition-colors hover:border-gold hover:text-charcoal">
                <?= icon('chevron-right', 'h-4 w-4 rotate-180') ?>
            </a>
        <?php endif; ?>

        <?php
        $range = 2;
        $start = max(1, $currentPage - $range);
        $end = min($totalPages, $currentPage + $range);

        if ($start > 1): ?>
            <a href="<?= esc($baseUrl . '?page=1', 'attr') ?>" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-white text-sm text-charcoal/60 transition-colors hover:border-gold hover:text-charcoal">1</a>
            <?php if ($start > 2): ?>
                <span class="px-2 text-charcoal/40">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $currentPage): ?>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gold-gradient text-sm font-medium text-charcoal shadow-sm"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= esc($baseUrl . '?page=' . $i, 'attr') ?>" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-white text-sm text-charcoal/60 transition-colors hover:border-gold hover:text-charcoal"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?>
                <span class="px-2 text-charcoal/40">...</span>
            <?php endif; ?>
            <a href="<?= esc($baseUrl . '?page=' . $totalPages, 'attr') ?>" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-white text-sm text-charcoal/60 transition-colors hover:border-gold hover:text-charcoal"><?= $totalPages ?></a>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="<?= esc($baseUrl . '?page=' . ($currentPage + 1), 'attr') ?>" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-white text-charcoal/60 transition-colors hover:border-gold hover:text-charcoal">
                <?= icon('chevron-right', 'h-4 w-4') ?>
            </a>
        <?php endif; ?>
    </nav>
</div>
