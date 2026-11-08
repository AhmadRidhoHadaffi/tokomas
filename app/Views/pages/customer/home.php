<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?php
$customer = $customer ?? null;
$activeOrders = $activeOrders ?? [];
$customerId = isset($customer['id']) ? (int) $customer['id'] : 0;
$displayName = $customer['name'] ?? session('user_name') ?? 'Pelanggan';
$firstName = explode(' ', trim((string) $displayName))[0] ?: 'Pelanggan';

$openOrders = array_values(array_filter($activeOrders, static function ($order) {
    return ($order['status'] ?? '') !== 'Diambil';
}));
$featured = $openOrders[0] ?? null;
$recentOrders = array_slice($activeOrders, 0, 3);

$transactions = [];
$promo = null;
$unreadCount = 0;

if ($customerId > 0) {
    try {
        $transactionService = new \App\Services\TransactionService();
        $transactionResult = $transactionService->getAll(['customer_id' => $customerId, 'per_page' => 3]);
        $transactions = $transactionResult['items'] ?? [];
        foreach ($transactions as &$transaction) {
            $detail = $transactionService->getById((int) $transaction['id']);
            $transaction['items'] = $detail['items'] ?? [];
        }
        unset($transaction);
    } catch (\Throwable $e) {
        $transactions = [];
    }

    try {
        $unreadCount = (new \App\Services\NotificationService())->getUnreadCount($customerId);
    } catch (\Throwable $e) {
        $unreadCount = 0;
    }

    try {
        $promoNotification = (new \App\Models\NotificationModel())
            ->where('customer_id', $customerId)
            ->where('type', 'Promosi')
            ->orderBy('created_at', 'DESC')
            ->first();
        if ($promoNotification && !empty($promoNotification['promotion_id'])) {
            $promo = (new \App\Models\PromotionModel())->find((int) $promoNotification['promotion_id']);
        }
    } catch (\Throwable $e) {
        $promo = null;
    }
}

$orderProduct = static function (array $order): string {
    $product = trim((string) ($order['product'] ?? ''));
    if ($product !== '') {
        return $product;
    }
    $fallback = trim(((string) ($order['jewelry_type'] ?? '')) . ' ' . ((string) ($order['model'] ?? '')));
    return $fallback !== '' ? $fallback : 'Pesanan custom';
};

$transactionProducts = static function (array $transaction): string {
    $names = array_map(static function ($item) {
        return (string) ($item['name'] ?? '');
    }, $transaction['items'] ?? []);
    $names = array_values(array_filter($names, static fn ($name) => $name !== ''));
    return $names ? implode(', ', $names) : (string) ($transaction['invoice_no'] ?? 'Transaksi');
};
?>
<div class="space-y-6">
    <section class="grid gap-4 lg:grid-cols-3">
        <div class="relative overflow-hidden rounded-2xl bg-charcoal p-6 text-white lg:col-span-2">
            <div class="bg-arabesque absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="relative">
                <h1 class="font-serif text-2xl font-semibold sm:text-3xl">Selamat datang, <?= esc($firstName) ?>!</h1>
                <p class="mt-1 text-sm text-white/65">Senang melihat Anda kembali di Toko Mas Gembira.</p>

                <?php if ($featured): ?>
                    <div class="mt-6 rounded-xl bg-white p-4 text-charcoal">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs text-charcoal/50">Pesanan terbaru &middot; <?= esc($featured['order_no'] ?? '-') ?></p>
                                <p class="truncate font-semibold"><?= esc($orderProduct($featured)) ?></p>
                            </div>
                            <a href="<?= base_url('pelanggan/pesanan') ?>" class="inline-flex shrink-0 items-center px-2 -mx-2 py-3 -my-3 text-xs font-medium text-gold-deep hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">
                                Lacak
                            </a>
                        </div>
                        <?= view('components/customer/order_progress', ['status' => $featured['status'] ?? 'Masuk']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-1">
            <a href="<?= base_url('pelanggan/pesanan') ?>" class="flex flex-col justify-between rounded-2xl border border-line bg-white p-5 transition-colors duration-150 hover:border-gold/60 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                <?= icon('gem', 'h-6 w-6 text-gold-dark', 24, 1.5) ?>
                <div class="mt-4">
                    <p class="font-serif text-3xl font-semibold text-charcoal"><?= count($openOrders) ?></p>
                    <p class="text-sm text-charcoal/55">Pesanan Custom Aktif</p>
                </div>
            </a>
            <a href="<?= base_url('pelanggan/notifikasi') ?>" class="flex flex-col justify-between rounded-2xl border border-line bg-white p-5 transition-colors duration-150 hover:border-gold/60 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                <?= icon('bell', 'h-6 w-6 text-gold-dark', 24, 1.5) ?>
                <div class="mt-4">
                    <p class="font-serif text-3xl font-semibold text-charcoal"><?= (int) $unreadCount ?></p>
                    <p class="text-sm text-charcoal/55">Notifikasi Belum Dibaca</p>
                </div>
            </a>
        </div>
    </section>

    <?php if ($promo): ?>
        <div x-data="{ promoVisible: true }" x-cloak x-show="promoVisible" aria-label="Promosi terbaru">
            <div class="relative flex items-start gap-4 rounded-2xl bg-gold-gradient p-5 pr-12 sm:items-center">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-charcoal text-gold">
                    <?= icon('gift', 'h-5 w-5', 20, 1.75) ?>
                </span>
                <div class="min-w-0">
                    <p class="font-serif text-lg font-semibold text-charcoal"><?= esc($promo['title'] ?? 'Promosi') ?></p>
                    <p class="text-sm text-charcoal/75"><?= esc($promo['message'] ?? '') ?></p>
                </div>
                <button
                    type="button"
                    @click="promoVisible = false"
                    aria-label="Tutup promosi"
                    class="absolute right-1.5 top-1.5 flex h-10 w-10 items-center justify-center rounded-full text-charcoal/70 transition-colors duration-150 hover:bg-charcoal/10 hover:text-charcoal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-charcoal/30"
                >
                    <?= icon('x', 'h-4 w-4', 16, 2) ?>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-line bg-white">
            <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
                <h2 class="font-serif text-lg font-semibold text-charcoal">Transaksi Terakhir</h2>
                <a href="<?= base_url('pelanggan/riwayat') ?>" class="inline-flex shrink-0 items-center py-3 -my-3 text-xs font-medium text-gold-deep hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">Lihat semua</a>
            </div>
            <?php if (empty($transactions)): ?>
                <?= view('components/customer/empty_state', [
                    'heading' => 'Belum ada transaksi',
                    'description' => 'Transaksi di toko akan tercatat otomatis di akun Anda.',
                    'compact' => true,
                ]) ?>
            <?php else: ?>
                <ul class="divide-y divide-line">
                    <?php foreach ($transactions as $transaction): ?>
                        <li class="flex items-center justify-between gap-3 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-charcoal"><?= esc($transactionProducts($transaction)) ?></p>
                                <p class="text-xs text-charcoal/45"><?= esc(format_date((string) ($transaction['transaction_date'] ?? ''))) ?></p>
                            </div>
                            <div class="flex shrink-0 flex-col items-end gap-1">
                                <span class="text-sm font-semibold"><?= esc(rupiah((float) ($transaction['total'] ?? 0))) ?></span>
                                <?= view('components/customer/payment_badge', ['method' => $transaction['payment_method'] ?? 'Tunai']) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="overflow-hidden rounded-2xl border border-line bg-white">
            <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
                <h2 class="font-serif text-lg font-semibold text-charcoal">Pesanan Custom Terakhir</h2>
                <a href="<?= base_url('pelanggan/pesanan') ?>" class="inline-flex shrink-0 items-center py-3 -my-3 text-xs font-medium text-gold-deep hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">Lihat semua</a>
            </div>
            <?php if (empty($recentOrders)): ?>
                <?= view('components/customer/empty_state', [
                    'heading' => 'Belum ada pesanan custom',
                    'description' => 'Wujudkan perhiasan impian Anda — ajukan pesanan pertama.',
                    'compact' => true,
                ]) ?>
            <?php else: ?>
                <ul class="divide-y divide-line">
                    <?php foreach ($recentOrders as $order): ?>
                        <li>
                            <a href="<?= base_url('pelanggan/pesanan/' . (int) ($order['id'] ?? 0)) ?>" class="flex items-center gap-3 px-5 py-3.5 transition-colors duration-150 hover:bg-ivory focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/40">
                                <?php if (!empty($order['reference_image_url'])): ?>
                                    <img src="<?= esc($order['reference_image_url'], 'attr') ?>" alt="" class="h-11 w-11 shrink-0 rounded-lg object-cover">
                                <?php else: ?>
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-ivory text-gold-dark">
                                        <?= icon('gem', 'h-5 w-5', 20, 1.5) ?>
                                    </span>
                                <?php endif; ?>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-charcoal"><?= esc($orderProduct($order)) ?></p>
                                    <p class="text-xs text-charcoal/45"><?= esc($order['order_no'] ?? '-') ?> &middot; <?= esc(format_date((string) ($order['order_date'] ?? ''))) ?></p>
                                </div>
                                <?= view('components/customer/status_badge', ['status' => $order['status'] ?? 'Masuk']) ?>
                                <?= icon('chevron-right', 'h-4 w-4 shrink-0 text-charcoal/30', 16, 2) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
