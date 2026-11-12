<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$items = $result['items'] ?? [];
$search = (string) ($filters['search'] ?? '');
$category = (string) ($filters['category'] ?? '');
$purity = (string) ($filters['purity'] ?? '');
$stockLevel = (string) ($filters['stock_level'] ?? '');
$baseQuery = array_filter([
    'search' => $search,
    'category' => $category,
    'purity' => $purity,
    'stock_level' => $stockLevel,
], static fn ($value) => $value !== '');
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Produk &amp; Stok</h1>
            <p class="mt-2 text-sm text-charcoal/60">Kelola katalog perhiasan dan ketersediaan stok.</p>
        </div>
        <?= ui_button([
            'href' => base_url('owner/produk/baru'),
            'content' => 'Tambah Produk',
            'icon' => 'plus',
            'size' => 'md',
        ]) ?>
    </div>

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
                    <?= ui_button(['href' => base_url('owner/produk'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4 sm:px-6">
            <p class="text-sm text-charcoal/60"><?= esc((string) ($result['total'] ?? 0)) ?> produk</p>
        </div>

        <?php if ($items === []): ?>
            <?= view('components/ui/empty_state', [
                'icon' => 'package',
                'title' => 'Belum ada produk',
                'description' => 'Tambahkan produk pertama untuk mulai mengelola katalog.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-charcoal/60">
                            <th class="p-3">Kode</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Kadar</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $product): ?>
                            <tr class="border-b border-line/70 last:border-0">
                                <td class="p-3 font-mono text-xs text-charcoal/70"><?= esc($product['code']) ?></td>
                                <td class="p-3">
                                    <p class="font-medium text-charcoal"><?= esc($product['name']) ?></p>
                                    <?php if (!empty($product['model'])): ?>
                                        <p class="text-xs text-charcoal/50"><?= esc($product['model']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3"><?= esc($product['category']) ?></td>
                                <td class="p-3"><?= esc($product['purity']) ?></td>
                                <td class="p-3 whitespace-nowrap">Rp <?= esc(number_format((float) $product['price'], 0, ',', '.')) ?></td>
                                <td class="p-3"><?= view_cell('App\\Cells\\StockBadgeCell::show', ['stock' => $product['stock']]) ?></td>
                                <td class="p-3">
                                    <div class="flex justify-end gap-2">
                                        <?= ui_button([
                                            'href' => base_url('owner/produk/' . $product['id'] . '/ubah'),
                                            'content' => 'Ubah',
                                            'icon' => 'pencil',
                                            'variant' => 'light',
                                            'size' => 'sm',
                                        ]) ?>
                                        <form method="post" action="<?= base_url('owner/produk/' . $product['id'] . '/hapus') ?>" onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.');">
                                            <?= csrf_field() ?>
                                            <?= ui_button(['type' => 'submit', 'content' => 'Hapus', 'icon' => 'trash-2', 'variant' => 'danger', 'size' => 'sm']) ?>
                                        </form>
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
            'total' => (int) ($result['total'] ?? 0),
            'perPage' => (int) ($result['per_page'] ?? 20),
            'baseUrl' => base_url('owner/produk'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
