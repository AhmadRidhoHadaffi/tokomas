<?php
/**
 * Transaction Detail Content
 *
 * @var array $transaction
 */

$transaction = $transaction ?? [];
$items = $transaction['items'] ?? [];
$subtotal = 0.0;
foreach ($items as $item) {
    $subtotal += (float) ($item['price'] ?? 0) * (float) ($item['quantity'] ?? 0);
}
$discount = (float) ($transaction['discount'] ?? 0);
$total = (float) ($transaction['total'] ?? max(0, $subtotal - $discount));
$status = $transaction['payment_status'] ?? 'Lunas';
$statusTone = $status === 'Lunas' ? 'green' : 'yellow';
?>
<div class="space-y-6">
    <dl class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
        <div>
            <dt class="text-charcoal/50">Tanggal</dt>
            <dd class="mt-0.5 font-medium text-charcoal"><?= esc(format_datetime((string) ($transaction['transaction_date'] ?? ''))) ?></dd>
        </div>
        <div>
            <dt class="text-charcoal/50">Pelanggan</dt>
            <dd class="mt-0.5 font-medium text-charcoal"><?= esc($transaction['customer_name'] ?? '-') ?></dd>
        </div>
        <div>
            <dt class="text-charcoal/50">Kasir</dt>
            <dd class="mt-0.5 font-medium text-charcoal"><?= esc($transaction['cashier_name'] ?? '-') ?></dd>
        </div>
        <div>
            <dt class="text-charcoal/50">Status</dt>
            <dd class="mt-1">
                <?= badge(['tone' => $statusTone, 'text' => $status, 'dot' => true]) ?>
            </dd>
        </div>
    </dl>

    <div class="scrollbar-thin overflow-x-auto rounded-xl border border-line">
        <table class="w-full min-w-[420px] text-sm">
            <thead class="bg-ivory text-xs text-charcoal/55">
                <tr>
                    <th scope="col" class="px-4 py-2.5 text-left font-semibold">Produk</th>
                    <th scope="col" class="px-4 py-2.5 text-center font-semibold">Qty</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-semibold">Harga</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-semibold">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-charcoal/50">Tidak ada rincian item.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-charcoal"><?= esc($item['name'] ?? '-') ?></p>
                                <p class="text-xs text-charcoal/45"><?= esc($item['code'] ?? '-') ?></p>
                            </td>
                            <td class="px-4 py-3 text-center"><?= (int) ($item['quantity'] ?? 0) ?></td>
                            <td class="px-4 py-3 text-right"><?= esc(rupiah((float) ($item['price'] ?? 0))) ?></td>
                            <td class="px-4 py-3 text-right font-medium">
                                <?= esc(rupiah((float) ($item['price'] ?? 0) * (float) ($item['quantity'] ?? 0))) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="ml-auto max-w-xs space-y-2 text-sm">
        <div class="flex justify-between gap-4">
            <span class="text-charcoal/55">Subtotal</span>
            <span><?= esc(rupiah($subtotal)) ?></span>
        </div>
        <div class="flex justify-between gap-4">
            <span class="text-charcoal/55">Diskon</span>
            <span class="text-crimson">&minus; <?= esc(rupiah($discount)) ?></span>
        </div>
        <div class="flex items-center justify-between gap-4">
            <span class="text-charcoal/55">Metode Bayar</span>
            <?= view('components/customer/payment_badge', ['method' => $transaction['payment_method'] ?? 'Tunai']) ?>
        </div>
        <div class="flex justify-between gap-4 border-t border-line pt-3">
            <span class="font-semibold text-charcoal">Total</span>
            <span class="font-serif text-xl font-semibold text-charcoal"><?= esc(rupiah($total)) ?></span>
        </div>
    </div>
</div>
