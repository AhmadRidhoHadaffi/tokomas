<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$items     = $result['items'] ?? [];
$search    = (string) ($filters['search'] ?? '');
$type      = (string) ($filters['type'] ?? '');
$category  = (string) ($filters['category'] ?? '');
$startDate = (string) ($filters['start_date'] ?? '');
$endDate   = (string) ($filters['end_date'] ?? '');
$total     = (int) ($result['total'] ?? 0);
$hasFilter = $search !== '' || $type !== '' || $category !== '' || $startDate !== '' || $endDate !== '';
$allCategories = [];
foreach (($categories ?? []) as $group) {
    foreach ($group as $option) {
        $allCategories[$option] = $option;
    }
}
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Pembukuan',
        'description' => 'Pemasukan, pengeluaran, dan saldo toko pada periode berjalan.',
        'actions' => ui_button([
            'href' => base_url('karyawan/pembukuan/baru'),
            'content' => 'Tambah Catatan',
            'icon' => 'plus',
            'size' => 'md',
        ]),
    ]) ?>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-emerald-700">Pemasukan</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/70 text-emerald-700"><?= icon('arrow-down', 'h-4 w-4') ?></span>
            </div>
            <p class="mt-3 font-serif text-xl font-semibold text-emerald-800"><?= rupiah((float) ($summary['income'] ?? 0)) ?></p>
        </div>
        <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-red-700">Pengeluaran</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/70 text-red-700"><?= icon('arrow-up', 'h-4 w-4') ?></span>
            </div>
            <p class="mt-3 font-serif text-xl font-semibold text-red-800"><?= rupiah((float) ($summary['expense'] ?? 0)) ?></p>
        </div>
        <div class="rounded-2xl border border-gold/30 bg-gold-light p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-charcoal/70">Saldo</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/70 text-gold-dark"><?= icon('wallet', 'h-4 w-4') ?></span>
            </div>
            <p class="mt-3 font-serif text-xl font-semibold text-charcoal"><?= rupiah((float) ($summary['balance'] ?? 0)) ?></p>
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
            <label class="text-sm text-charcoal/70">Dari
                <input type="date" name="start_date" value="<?= esc($startDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <label class="text-sm text-charcoal/70">Sampai
                <input type="date" name="end_date" value="<?= esc($endDate, 'attr') ?>" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-40">
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($hasFilter): ?>
                    <?= ui_button(['href' => base_url('karyawan/pembukuan'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'book-open',
                'title' => 'Belum ada catatan',
                'description' => 'Tambahkan catatan pemasukan atau pengeluaran pertama.',
                'action' => ui_button([
                    'href' => base_url('karyawan/pembukuan/baru'),
                    'content' => 'Tambah Catatan',
                    'icon' => 'plus',
                    'size' => 'sm',
                ]),
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <?php $isIncome = ($item['type'] ?? '') === 'Pemasukan'; ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= format_date((string) ($item['entry_date'] ?? '')) ?></td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium <?= $isIncome ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                                        <?= esc($item['type']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-charcoal/70"><?= esc($item['category']) ?></td>
                                <td class="px-4 py-3.5">
                                    <p class="text-charcoal"><?= esc($item['description']) ?></p>
                                    <?php if (!empty($item['transaction_id']) || !empty($item['custom_order_id'])): ?>
                                        <p class="mt-0.5 text-xs text-charcoal/45">Otomatis dari transaksi</p>
                                    <?php endif; ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 font-medium <?= $isIncome ? 'text-emerald-700' : 'text-red-700' ?>">
                                    <?= $isIncome ? '+' : '-' ?> <?= rupiah((float) $item['amount']) ?>
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
            'baseUrl' => base_url('karyawan/pembukuan'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
