<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$items = $result['items'] ?? [];
$search = (string) ($filters['search'] ?? '');
$type = (string) ($filters['type'] ?? '');
$category = (string) ($filters['category'] ?? '');
$allCategories = [];
foreach (($categories ?? []) as $group) {
    foreach ($group as $option) {
        $allCategories[$option] = $option;
    }
}
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Pembukuan</h1>
            <p class="mt-2 text-sm text-charcoal/60">Catat pemasukan dan pengeluaran toko.</p>
        </div>
        <?= ui_button([
            'href' => base_url('owner/pembukuan/baru'),
            'content' => 'Tambah Catatan',
            'icon' => 'plus',
            'size' => 'md',
        ]) ?>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
            <p class="text-sm text-emerald-700">Pemasukan</p>
            <p class="mt-1 font-serif text-xl font-semibold text-emerald-800">Rp <?= esc(number_format((float) ($summary['income'] ?? 0), 0, ',', '.')) ?></p>
        </div>
        <div class="rounded-xl border border-red-100 bg-red-50 p-4">
            <p class="text-sm text-red-700">Pengeluaran</p>
            <p class="mt-1 font-serif text-xl font-semibold text-red-800">Rp <?= esc(number_format((float) ($summary['expense'] ?? 0), 0, ',', '.')) ?></p>
        </div>
        <div class="rounded-xl border border-gold/30 bg-gold-light p-4">
            <p class="text-sm text-charcoal/70">Saldo</p>
            <p class="mt-1 font-serif text-xl font-semibold text-charcoal">Rp <?= esc(number_format((float) ($summary['balance'] ?? 0), 0, ',', '.')) ?></p>
        </div>
    </div>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="Deskripsi catatan" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <label class="text-sm text-charcoal/70">Tipe
                <select name="type" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
                    <option value="">Semua tipe</option>
                    <option value="Pemasukan" <?= $type === 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="Pengeluaran" <?= $type === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
            </label>
            <label class="text-sm text-charcoal/70">Kategori
                <select name="category" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-44">
                    <option value="">Semua kategori</option>
                    <?php foreach ($allCategories as $option): ?>
                        <option value="<?= esc($option, 'attr') ?>" <?= $category === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($search !== '' || $type !== '' || $category !== ''): ?>
                    <?= ui_button(['href' => base_url('owner/pembukuan'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= view('components/ui/empty_state', [
                'icon' => 'book-open',
                'title' => 'Belum ada catatan',
                'description' => 'Tambahkan catatan pemasukan atau pengeluaran pertama.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-charcoal/60">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php $isAuto = !empty($item['transaction_id']) || !empty($item['custom_order_id']); ?>
                            <tr class="border-b border-line/70 last:border-0">
                                <td class="p-3 whitespace-nowrap"><?= format_date((string) ($item['entry_date'] ?? '')) ?></td>
                                <td class="p-3">
                                    <span class="<?= ($item['type'] ?? '') === 'Pemasukan' ? 'text-emerald-700' : 'text-red-700' ?>"><?= esc($item['type']) ?></span>
                                </td>
                                <td class="p-3"><?= esc($item['category']) ?></td>
                                <td class="p-3">
                                    <p class="text-charcoal"><?= esc($item['description']) ?></p>
                                    <?php if ($isAuto): ?>
                                        <p class="text-xs text-charcoal/45">Otomatis dari transaksi</p>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 whitespace-nowrap font-medium">Rp <?= esc(number_format((float) $item['amount'], 0, ',', '.')) ?></td>
                                <td class="p-3">
                                    <?php if ($isAuto): ?>
                                        <p class="text-right text-xs text-charcoal/40">Terkunci</p>
                                    <?php else: ?>
                                        <div class="flex justify-end gap-2">
                                            <?= ui_button([
                                                'href' => base_url('owner/pembukuan/' . $item['id'] . '/ubah'),
                                                'content' => 'Ubah',
                                                'icon' => 'pencil',
                                                'variant' => 'light',
                                                'size' => 'sm',
                                            ]) ?>
                                            <form method="post" action="<?= base_url('owner/pembukuan/' . $item['id'] . '/hapus') ?>" onsubmit="return confirm('Hapus catatan pembukuan ini?');">
                                                <?= csrf_field() ?>
                                                <?= ui_button(['type' => 'submit', 'content' => 'Hapus', 'icon' => 'trash-2', 'variant' => 'danger', 'size' => 'sm']) ?>
                                            </form>
                                        </div>
                                    <?php endif; ?>
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
            'baseUrl' => base_url('owner/pembukuan'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
