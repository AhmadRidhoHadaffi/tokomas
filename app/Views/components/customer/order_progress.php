<?php
/**
 * Custom Order Progress Stepper
 *
 * @var string $status Current custom order status
 */

$status = $status ?? 'Masuk';
$steps = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];
$labels = [
    'Masuk' => 'Pesanan Masuk',
    'Diproses' => 'Diproses',
    'Dalam Pembuatan' => 'Dalam Pembuatan',
    'Selesai' => 'Selesai',
    'Diambil' => 'Diambil',
];
$current = array_search($status, $steps, true);
$current = $current === false ? 0 : $current;
$currentLabel = $labels[$steps[$current]] ?? $status;
?>
<div>
    <ol class="flex items-start" aria-label="Progres pesanan">
        <?php foreach ($steps as $index => $step): ?>
            <?php
            $done = $index < $current;
            $active = $index === $current;
            $dotClass = $done
                ? 'bg-gold text-charcoal'
                : ($active ? 'bg-charcoal text-gold ring-4 ring-gold/30' : 'border border-line bg-white text-charcoal/35');
            $labelClass = $active
                ? 'font-semibold text-charcoal'
                : ($done ? 'text-charcoal/70' : 'text-charcoal/40');
            ?>
            <li class="relative flex flex-1 flex-col items-center" <?= $active ? 'aria-current="step"' : '' ?>>
                <?php if ($index > 0): ?>
                    <span class="absolute right-1/2 top-3.5 h-0.5 w-full <?= $index <= $current ? 'bg-gold' : 'bg-line' ?>" aria-hidden="true"></span>
                <?php endif; ?>
                <span class="relative z-10 flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-semibold <?= esc($dotClass, 'attr') ?>">
                    <?php if ($done): ?>
                        <?= icon('check', 'h-3.5 w-3.5', 14, 2.5) ?>
                    <?php else: ?>
                        <?= $index + 1 ?>
                    <?php endif; ?>
                </span>
                <span class="mt-2 hidden px-1 text-center text-xs sm:block <?= esc($labelClass, 'attr') ?>">
                    <?= esc($labels[$step]) ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ol>
    <p class="mt-3 text-center text-xs text-charcoal/60 sm:hidden">
        Tahap <?= $current + 1 ?> dari <?= count($steps) ?> &middot;
        <span class="font-semibold text-charcoal"><?= esc($currentLabel) ?></span>
    </p>
</div>
