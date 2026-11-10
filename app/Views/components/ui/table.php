<?php
/**
 * Table Component
 * 
 * @var array $headers [['label' => 'Name', 'class' => '', 'sortable' => true, 'key' => 'name'], ...]
 * @var string $rows Table body HTML (pre-rendered)
 * @var string $class Additional table classes
 */

$headers = $headers ?? [];
$rows = $rows ?? '';
$class = $class ?? '';
?>
<div class="overflow-x-auto">
    <table class="w-full text-left text-sm <?= esc($class, 'attr') ?>">
        <?php if (!empty($headers)): ?>
            <thead>
                <tr class="border-b border-line text-charcoal/60">
                    <?php foreach ($headers as $header): ?>
                        <th class="p-3 font-medium <?= esc($header['class'] ?? '', 'attr') ?>">
                            <?php if ($header['sortable'] ?? false): ?>
                                <button type="button" class="inline-flex min-h-10 -my-2 items-center gap-1.5 py-2 hover:text-charcoal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">
                                    <?= esc($header['label']) ?>
                                    <?= icon('chevrons-up-down', 'h-3.5 w-3.5') ?>
                                </button>
                            <?php else: ?>
                                <?= esc($header['label']) ?>
                            <?php endif; ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
        <?php endif; ?>
        <tbody>
            <?= $rows ?>
        </tbody>
    </table>
</div>
