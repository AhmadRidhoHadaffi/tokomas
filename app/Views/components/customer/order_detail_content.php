<?php
/**
 * Custom Order Detail Content
 *
 * Used by the customer orders modal and the standalone order detail page.
 *
 * @var array $order
 */

$order = $order ?? [];
$reference = $order['reference_image_url'] ?? null;
$estimatedPrice = (float) ($order['estimated_price'] ?? 0);
$dp = (float) ($order['dp'] ?? 0);
$remaining = max(0, $estimatedPrice - $dp);

$weight = $order['weight'] ?? null;
$weightText = ($weight === null || $weight === '')
    ? '-'
    : rtrim(rtrim(number_format((float) $weight, 2, ',', '.'), '0'), ',') . ' gr';

$fields = [
    'Model' => $order['model'] ?? '-',
    'Nama Ukiran' => ($order['engraving_name'] ?? '') !== '' ? $order['engraving_name'] : '-',
    'Kadar' => $order['purity'] ?? '-',
    'Berat Estimasi' => $weightText,
];
?>
<div class="grid gap-6 sm:grid-cols-2">
    <div class="min-w-0">
        <p class="mb-2 flex items-center gap-1.5 text-xs font-medium text-charcoal/55">
            <?= icon('image', 'h-3.5 w-3.5', 14, 1.75) ?>
            Foto referensi
        </p>
        <?php if (!empty($reference)): ?>
            <img
                src="<?= esc($reference, 'attr') ?>"
                alt="Foto referensi pesanan"
                class="aspect-square w-full rounded-xl object-cover"
            >
        <?php else: ?>
            <div class="flex aspect-square w-full flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-line bg-ivory px-4 text-center text-charcoal/35">
                <?= icon('image', 'h-10 w-10', 40, 1.5) ?>
                <span class="text-xs">Belum ada foto referensi</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="min-w-0 space-y-5">
        <dl class="space-y-3 text-sm">
            <?php foreach ($fields as $label => $value): ?>
                <div class="flex justify-between gap-4">
                    <dt class="shrink-0 text-charcoal/55"><?= esc($label) ?></dt>
                    <dd class="min-w-0 break-words text-right font-medium text-charcoal"><?= esc($value) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>

        <div class="rounded-xl bg-ivory p-4 text-sm">
            <div class="flex justify-between gap-3">
                <span class="text-charcoal/55">DP dibayar</span>
                <span class="font-medium"><?= esc(rupiah($dp)) ?></span>
            </div>
            <div class="mt-2 flex justify-between gap-3 border-t border-gold/20 pt-2">
                <span class="font-medium">Sisa pembayaran</span>
                <span class="font-serif text-lg font-semibold text-crimson"><?= esc(rupiah($remaining)) ?></span>
            </div>
        </div>

        <div>
            <p class="text-xs text-charcoal/50">Catatan</p>
            <p class="mt-1 break-words text-sm text-charcoal/80">
                <?= esc(($order['notes'] ?? '') !== '' ? $order['notes'] : 'Tidak ada catatan.') ?>
            </p>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-charcoal">Riwayat status</p>
            <?= view('components/customer/status_timeline', ['history' => $order['history'] ?? []]) ?>
        </div>
    </div>
</div>
