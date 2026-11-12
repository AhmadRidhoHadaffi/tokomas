<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?php
$order = $order ?? [];
$product = trim((string) ($order['product'] ?? ''));
if ($product === '') {
    $product = trim(((string) ($order['jewelry_type'] ?? '')) . ' ' . ((string) ($order['model'] ?? '')));
}
$product = $product !== '' ? $product : 'Detail Pesanan';
?>
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <a
            href="<?= base_url('pelanggan/pesanan') ?>"
            class="inline-flex min-h-10 items-center gap-2 rounded-lg px-2 text-sm font-medium text-charcoal/60 transition-colors duration-150 hover:bg-white hover:text-charcoal focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
        >
            <?= icon('arrow-left', 'h-4 w-4', 16, 2) ?>
            Kembali ke Pesanan
        </a>
    </div>

    <section class="overflow-hidden rounded-2xl border border-line bg-white">
        <div class="border-b border-line px-5 py-5 sm:px-6">
            <div class="flex flex-wrap items-center gap-2">
                <p class="font-mono text-xs text-charcoal/50"><?= esc($order['order_no'] ?? '-') ?></p>
                <?= view('components/customer/status_badge', ['status' => $order['status'] ?? 'Masuk']) ?>
            </div>
            <h1 class="mt-1 break-words font-serif text-2xl font-semibold text-charcoal sm:text-3xl"><?= esc($product) ?></h1>
            <p class="mt-1 text-sm text-charcoal/50">
                Dipesan <?= esc(format_date((string) ($order['order_date'] ?? ''))) ?>
                &middot;
                Target <?= esc(format_date((string) ($order['due_date'] ?? ''))) ?>
            </p>
            <?php if (($order['status'] ?? '') === 'Masuk'): ?>
                <div class="mt-4">
                    <?= ui_button([
                        'href' => base_url('pelanggan/pesanan/' . $order['id'] . '/ubah'),
                        'content' => 'Ubah Pesanan',
                        'icon' => 'pencil',
                        'variant' => 'light',
                        'size' => 'md',
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="border-b border-line px-5 py-6 sm:px-6">
            <?= view('components/customer/order_progress', ['status' => $order['status'] ?? 'Masuk']) ?>
        </div>

        <div class="px-5 py-6 sm:px-6">
            <?= view('components/customer/order_detail_content', ['order' => $order]) ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
