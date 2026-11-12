<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'per_page' => 20, 'total_pages' => 0];
$orders = $result['items'] ?? [];

$orderProduct = static function (array $order): string {
    $product = trim((string) ($order['product'] ?? ''));
    if ($product !== '') {
        return $product;
    }
    $fallback = trim(((string) ($order['jewelry_type'] ?? '')) . ' ' . ((string) ($order['model'] ?? '')));
    return $fallback !== '' ? $fallback : 'Pesanan custom';
};
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Pesanan Custom</h1>
            <p class="mt-1 text-sm text-charcoal/55">Pantau setiap tahap pembuatan perhiasan Anda.</p>
        </div>
        <?= ui_button([
            'href' => base_url('pelanggan/pesanan/baru'),
            'content' => 'Ajukan Pesanan Baru',
            'icon' => 'plus',
            'variant' => 'primary',
            'size' => 'md',
        ]) ?>
    </div>

    <?php if (empty($orders)): ?>
        <div class="rounded-2xl border border-line bg-white">
            <?= view('components/customer/empty_state', [
                'heading' => 'Belum ada pesanan custom',
                'description' => 'Wujudkan perhiasan impian Anda — ajukan pesanan pertama.',
            ]) ?>
        </div>
    <?php else: ?>
        <ul class="space-y-4">
            <?php foreach ($orders as $index => $order): ?>
                <?php
                $orderId = (int) ($order['id'] ?? 0);
                $modalVar = 'orderModal' . $orderId . 'x' . $index;
                $estimatedPrice = (float) ($order['estimated_price'] ?? 0);
                $dp = (float) ($order['dp'] ?? 0);
                $remaining = max(0, $estimatedPrice - $dp);

                $fullOrder = $order;
                try {
                    $fullOrder = (new \App\Services\CustomOrderService())->getById($orderId) ?? $order;
                } catch (\Throwable $e) {
                    $fullOrder = $order;
                }
                ?>
                <li class="rounded-2xl border border-line bg-white p-5 sm:p-6" x-data="{ <?= esc($modalVar, 'attr') ?>: false }">
                    <div class="flex items-start gap-4">
                        <?php if (!empty($order['reference_image_url'])): ?>
                            <img src="<?= esc($order['reference_image_url'], 'attr') ?>" alt="" class="h-16 w-16 shrink-0 rounded-xl object-cover">
                        <?php else: ?>
                            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-ivory text-gold-dark">
                                <?= icon('gem', 'h-7 w-7', 28, 1.5) ?>
                            </span>
                        <?php endif; ?>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-mono text-xs text-charcoal/50"><?= esc($order['order_no'] ?? '-') ?></p>
                                <?= view('components/customer/status_badge', ['status' => $order['status'] ?? 'Masuk']) ?>
                            </div>
                            <h2 class="mt-1 break-words font-serif text-lg font-semibold text-charcoal"><?= esc($orderProduct($order)) ?></h2>
                            <p class="text-xs text-charcoal/50">
                                Dipesan <?= esc(format_date((string) ($order['order_date'] ?? ''))) ?>
                                &middot;
                                Target <?= esc(format_date((string) ($order['due_date'] ?? ''))) ?>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <?= view('components/customer/order_progress', ['status' => $order['status'] ?? 'Masuk']) ?>
                    </div>

                    <div class="mt-6 flex flex-col gap-4 border-t border-line pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <dl class="flex gap-8 text-sm">
                            <div>
                                <dt class="text-xs text-charcoal/50">DP</dt>
                                <dd class="font-medium text-charcoal"><?= esc(rupiah($dp)) ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-charcoal/50">Sisa</dt>
                                <dd class="font-semibold text-crimson"><?= esc(rupiah($remaining)) ?></dd>
                            </div>
                        </dl>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <?php if (($order['status'] ?? '') === 'Masuk'): ?>
                                <?= ui_button([
                                    'href' => base_url('pelanggan/pesanan/' . $orderId . '/ubah'),
                                    'content' => 'Ubah',
                                    'icon' => 'pencil',
                                    'variant' => 'light',
                                    'size' => 'md',
                                ]) ?>
                            <?php endif; ?>
                            <?= ui_button([
                                'type' => 'button',
                                'content' => 'Lihat Detail',
                                'variant' => 'light',
                                'size' => 'md',
                                'attrs' => ['@click' => esc($modalVar, 'attr') . ' = true'],
                            ]) ?>
                        </div>
                    </div>

                    <?= view('components/ui/modal', [
                        'id' => $modalVar,
                        'modalTitle' => $orderProduct($fullOrder),
                        'description' => $fullOrder['order_no'] ?? '',
                        'size' => 'lg',
                        'content' => view('components/customer/order_detail_content', ['order' => $fullOrder]),
                    ]) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (($result['total_pages'] ?? 0) > 1): ?>
            <div class="pt-2">
                <?= view('components/ui/pagination', [
                    'currentPage' => (int) ($result['page'] ?? 1),
                    'totalPages' => (int) $result['total_pages'],
                    'total' => (int) ($result['total'] ?? 0),
                    'perPage' => (int) ($result['per_page'] ?? 20),
                    'baseUrl' => base_url('pelanggan/pesanan'),
                ]) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
