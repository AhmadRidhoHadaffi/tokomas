<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$statuses = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];
$currentIndex = array_search($order['status'], $statuses, true);
$nextStatus = $currentIndex !== false && isset($statuses[$currentIndex + 1]) ? $statuses[$currentIndex + 1] : null;
?>
<div class="space-y-6">
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="font-serif text-3xl font-semibold text-charcoal">Detail Pesanan Custom</h1>
                <p class="mt-1 text-sm text-charcoal/60"><?= esc($order['order_no']) ?></p>
            </div>
            <?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $order['status']]) ?>
        </div>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-charcoal/50">Pelanggan</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['customer_name'] ?: '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Telepon</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['customer_phone'] ?: '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Jenis / Model</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['product'] ?? trim($order['jewelry_type'] . ' ' . $order['model'])) ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Kadar</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['purity']) ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Berat</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['weight'] !== null ? format_weight($order['weight']) : '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Ukiran</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['engraving_name'] ?: '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Estimasi Harga</dt><dd class="mt-1 font-medium text-charcoal">Rp <?= number_format((float) $order['estimated_price'], 0, ',', '.') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">DP</dt><dd class="mt-1 font-medium text-charcoal">Rp <?= number_format((float) $order['dp'], 0, ',', '.') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Tanggal Pesan</dt><dd class="mt-1 font-medium text-charcoal"><?= format_date((string) $order['order_date']) ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Estimasi Selesai</dt><dd class="mt-1 font-medium text-charcoal"><?= format_date((string) ($order['due_date'] ?? '')) ?></dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-charcoal/50">Catatan</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($order['notes'] ?: '-') ?></dd></div>
        </dl>
        <?php if (! empty($order['reference_image_url'])): ?>
            <img src="<?= esc($order['reference_image_url'], 'attr') ?>" alt="Referensi pesanan <?= esc($order['order_no'], 'attr') ?>" class="mt-6 h-40 w-40 rounded-xl border border-line object-cover">
        <?php endif; ?>

        <?php if ($nextStatus !== null): ?>
            <form method="post" action="<?= base_url('karyawan/pesanan-custom/' . $order['id'] . '/ubah-status') ?>" class="mt-6 flex flex-wrap items-end gap-3 border-t border-line pt-6">
                <?= csrf_field() ?>
                <label class="text-sm text-charcoal/70">Ubah status menjadi
                    <select name="status" class="mt-1 block rounded-lg border border-line px-3 py-2">
                        <option value="<?= esc($nextStatus) ?>" selected><?= esc($nextStatus) ?></option>
                    </select>
                </label>
                <button type="submit" class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal">Simpan Status</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h2 class="font-serif text-xl font-semibold text-charcoal">Riwayat Status</h2>
        <ol class="mt-4 space-y-3">
            <?php foreach ($order['history'] ?? [] as $history): ?>
                <li class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-line px-4 py-3 text-sm">
                    <span class="font-medium text-charcoal"><?= esc($history['status']) ?></span>
                    <span class="text-charcoal/55"><?= esc($history['changed_by_name'] ?? '-') ?> · <?= format_datetime((string) $history['created_at']) ?></span>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>

    <a href="<?= base_url('karyawan/pesanan-custom') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gold-deep hover:underline"><?= icon('arrow-left', 'h-4 w-4') ?> Kembali ke daftar pesanan</a>
</div>
<?= $this->endSection() ?>
