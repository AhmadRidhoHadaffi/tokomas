<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$items     = $result['items'] ?? [];
$search    = (string) ($filters['search'] ?? '');
$category  = (string) ($filters['category'] ?? '');
$purity    = (string) ($filters['purity'] ?? '');
$stockLevel = (string) ($filters['stock_level'] ?? '');
$total     = (int) ($result['total'] ?? 0);
$baseQuery = array_filter([
    'search' => $search,
    'category' => $category,
    'purity' => $purity,
    'stock_level' => $stockLevel,
], static fn ($value) => $value !== '');
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Produk & Stok',
        'description' => 'Lihat katalog perhiasan dan ketersediaan stok toko.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc((string) $total) . ' produk</span>',
    ]) ?>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="Kode atau nama produk" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <label class="text-sm text-charcoal/70">Kategori
                <select name="category" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
                    <option value="">Semua</option>
                    <?php foreach (($categories ?? []) as $option): ?>
                        <option value="<?= esc($option, 'attr') ?>" <?= $category === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Kadar
                <select name="purity" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-32">
                    <option value="">Semua</option>
                    <?php foreach (($purities ?? []) as $option): ?>
                        <option value="<?= esc($option, 'attr') ?>" <?= $purity === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Stok
                <select name="stock_level" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
                    <option value="">Semua stok</option>
                    <option value="tersedia" <?= $stockLevel === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="hampir" <?= $stockLevel === 'hampir' ? 'selected' : '' ?>>Hampir habis</option>
                    <option value="habis" <?= $stockLevel === 'habis' ? 'selected' : '' ?>>Habis</option>
                </select>
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($baseQuery !== []): ?>
                    <?= ui_button(['href' => base_url('karyawan/produk'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'package',
                'title' => 'Belum ada produk',
                'description' => 'Belum ada produk yang sesuai dengan filter yang dipilih.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Kadar</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $product): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="whitespace-nowrap px-4 py-3.5 font-mono text-xs text-charcoal/60"><?= esc($product['code']) ?></td>
                                <td class="px-4 py-3.5">
                                    <p class="font-medium text-charcoal"><?= esc($product['name']) ?></p>
                                    <?php if (!empty($product['model'])): ?>
                                        <p class="mt-0.5 text-xs text-charcoal/50"><?= esc($product['model']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= esc($product['category']) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= esc($product['purity']) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-charcoal"><?= rupiah((float) $product['price']) ?></td>
                                <td class="px-4 py-3.5"><?= view_cell('App\\Cells\\StockBadgeCell::show', ['stock' => $product['stock']]) ?></td>
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
            'baseUrl' => base_url('karyawan/produk'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
