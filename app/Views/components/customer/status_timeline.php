<?php
/**
 * Custom Order Status Timeline
 *
 * @var array $history Rows with status, created_at, changed_by_name
 */

$history = $history ?? [];
$ordered = array_reverse(array_values($history));
?>
<?php if (empty($ordered)): ?>
    <p class="text-sm text-charcoal/50">Belum ada riwayat status.</p>
<?php else: ?>
    <ol class="relative space-y-4">
        <?php foreach ($ordered as $index => $entry): ?>
            <?php $isLatest = $index === 0; ?>
            <li class="relative flex gap-3 pl-1">
                <?php if ($index < count($ordered) - 1): ?>
                    <span class="absolute left-[9px] top-5 h-[calc(100%)] w-px bg-line" aria-hidden="true"></span>
                <?php endif; ?>
                <span class="relative mt-1 h-3 w-3 shrink-0 rounded-full ring-4 <?= $isLatest ? 'bg-gold ring-gold/20' : 'bg-charcoal/20 ring-white' ?>" aria-hidden="true"></span>
                <div class="min-w-0">
                    <p class="text-sm font-medium <?= $isLatest ? 'text-charcoal' : 'text-charcoal/70' ?>">
                        <?= esc($entry['status'] ?? '-') ?>
                    </p>
                    <p class="text-xs text-charcoal/45">
                        <?= esc(format_datetime((string) ($entry['created_at'] ?? ''))) ?>
                        &middot;
                        <?= esc($entry['changed_by_name'] ?? 'Sistem') ?>
                    </p>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>
