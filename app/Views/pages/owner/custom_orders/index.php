<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$items     = $result['items'] ?? [];
$search    = (string) ($filters['search'] ?? '');
$status    = (string) ($filters['status'] ?? '');
$startDate = (string) ($filters['start_date'] ?? '');
$endDate   = (string) ($filters['end_date'] ?? '');
$total     = (int) ($result['total'] ?? 0);
$hasFilter = $search !== '' || $status !== '' || $startDate !== '' || $endDate !== '';
$statusOptions = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Pesanan Custom',
        'description' => 'Kelola pesanan perhiasan custom, pantau DP, sisa pembayaran, dan status produksi.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc((string) $total) . ' pesanan</span>'
            . ui_button([
                'href' => base_url('owner/pesanan-custom/baru'),
                'content' => 'Tambah Pesanan',
                'icon' => 'plus',
                'size' => 'md',
            ]),
    ]) ?>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="No. pesanan, nama, atau telepon" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <label class="text-sm text-charcoal/70">Status
                <select name="status" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-44">
                    <option value="">Semua status</option>
                    <?php foreach ($statusOptions as $option): ?>
                        <option value="<?= esc($option, 'attr') ?>" <?= $status === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Dari
                <input type="date" name="start_date" value="<?= esc($startDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <label class="text-sm text-charcoal/70">Sampai
                <input type="date" name="end_date" value="<?= esc($endDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($hasFilter): ?>
                    <?= ui_button(['href' => base_url('owner/pesanan-custom'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'gem',
                'title' => 'Belum ada pesanan custom',
                'description' => 'Pesanan custom yang dibuat akan tampil di sini beserta status produksinya.',
                'action' => ui_button([
                    'href' => base_url('owner/pesanan-custom/baru'),
                    'content' => 'Tambah Pesanan',
                    'icon' => 'plus',
                    'size' => 'sm',
                ]),
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[960px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Pesanan</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">DP</th>
                            <th class="px-4 py-3">Sisa Bayar</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <?php
                            $estimated = (float) ($item['estimated_price'] ?? 0);
                            $dp = (float) ($item['dp'] ?? 0);
                            $remaining = max(0, $estimated - $dp);
                            ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <a href="<?= base_url('owner/pesanan-custom/' . $item['id']) ?>" class="font-medium text-gold-deep hover:underline">
                                        <?= esc($item['order_no']) ?>
                                    </a>
                                    <p class="mt-0.5 text-xs text-charcoal/50"><?= format_date((string) ($item['order_date'] ?? '')) ?></p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-medium text-charcoal"><?= esc($item['customer_name'] ?? '-') ?></p>
                                    <p class="mt-0.5 text-xs text-charcoal/50"><?= esc($item['customer_phone'] ?? '-') ?></p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($item['reference_image_url'])): ?>
                                            <img src="<?= esc($item['reference_image_url'], 'attr') ?>" alt="" class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-line" loading="lazy">
                                        <?php else: ?>
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ivory text-gold-dark">
                                                <?= icon('gem', 'h-5 w-5', 20, 1.5) ?>
                                            </span>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-charcoal"><?= esc($item['product'] ?? $item['jewelry_type']) ?></p>
                                            <?php if (!empty($item['purity'])): ?>
                                                <p class="mt-0.5 text-xs text-charcoal/50"><?= esc($item['purity']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/80"><?= rupiah($dp) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-charcoal"><?= rupiah($remaining) ?></td>
                                <td class="px-4 py-3.5"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $item['status']]) ?></td>
                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end">
                                        <?= ui_button([
                                            'href' => base_url('owner/pesanan-custom/' . $item['id']),
                                            'content' => 'Detail',
                                            'icon' => 'eye',
                                            'variant' => 'light',
                                            'size' => 'sm',
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if (($result['total_pages'] ?? 0) > 1): ?>
        <?= view('components/ui/pagination', [
            'currentPage' => (int) ($result['page'] ?? 1),
            'totalPages' => (int) $result['total_pages'],
            'total' => $total,
            'perPage' => (int) ($result['per_page'] ?? 20),
            'baseUrl' => base_url('owner/pesanan-custom'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
