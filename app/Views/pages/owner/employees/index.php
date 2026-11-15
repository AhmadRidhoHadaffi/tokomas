<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$items = $result['items'] ?? [];
$search = (string) ($filters['search'] ?? '');
$activeFilter = $filters['is_active'] ?? null;
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Manajemen Karyawan',
        'description' => 'Kelola akun karyawan, status aktif, dan kata sandi.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc((string) ($result['total'] ?? 0)) . ' karyawan</span>'
            . ui_button([
                'href' => base_url('owner/karyawan/baru'),
                'content' => 'Tambah Karyawan',
                'icon' => 'user-plus',
                'size' => 'md',
            ]),
    ]) ?>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="Nama atau email" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <label class="text-sm text-charcoal/70">Status
                <select name="is_active" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm sm:w-44">
                    <option value="">Semua</option>
                    <option value="1" <?= $activeFilter === '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $activeFilter === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($search !== '' || $activeFilter !== null): ?>
                    <?= ui_button(['href' => base_url('owner/karyawan'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'users',
                'title' => 'Belum ada karyawan',
                'description' => 'Tambahkan akun karyawan agar mereka dapat mencatat transaksi.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="p-3">Karyawan</th>
                            <th class="p-3">Bergabung</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $employee): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="p-3">
                                    <p class="whitespace-nowrap font-medium text-charcoal"><?= esc($employee['name']) ?></p>
                                    <p class="mt-0.5 whitespace-nowrap text-xs text-charcoal/50"><?= esc($employee['email']) ?></p>
                                </td>
                                <td class="whitespace-nowrap p-3 text-charcoal/70"><?= format_date((string) ($employee['created_at'] ?? '')) ?></td>
                                <td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $employee['is_active'] ? 'aktif' : 'nonaktif']) ?></td>
                                <td class="p-3">
                                    <div class="flex flex-nowrap justify-end gap-2">
                                        <?= ui_button([
                                            'href' => base_url('owner/karyawan/' . $employee['id'] . '/ubah'),
                                            'icon' => 'pencil',
                                            'iconOnly' => true,
                                            'variant' => 'light',
                                            'size' => 'sm',
                                            'title' => 'Ubah data karyawan',
                                            'ariaLabel' => 'Ubah data ' . $employee['name'],
                                        ]) ?>
                                        <?= ui_button([
                                            'href' => base_url('owner/karyawan/' . $employee['id'] . '/kata-sandi'),
                                            'icon' => 'key-round',
                                            'iconOnly' => true,
                                            'variant' => 'outline',
                                            'size' => 'sm',
                                            'title' => 'Reset kata sandi',
                                            'ariaLabel' => 'Reset kata sandi ' . $employee['name'],
                                        ]) ?>
                                        <form method="post" action="<?= base_url('owner/karyawan/' . $employee['id'] . '/toggle') ?>" onsubmit="return confirm('<?= $employee['is_active'] ? 'Nonaktifkan karyawan ini? Ia tidak akan bisa masuk.' : 'Aktifkan kembali karyawan ini?' ?>');">
                                            <?= csrf_field() ?>
                                            <?= ui_button([
                                                'type' => 'submit',
                                                'icon' => $employee['is_active'] ? 'user-x' : 'user-check',
                                                'iconOnly' => true,
                                                'variant' => $employee['is_active'] ? 'danger' : 'light',
                                                'size' => 'sm',
                                                'title' => $employee['is_active'] ? 'Nonaktifkan karyawan' : 'Aktifkan karyawan',
                                                'ariaLabel' => ($employee['is_active'] ? 'Nonaktifkan ' : 'Aktifkan ') . $employee['name'],
                                            ]) ?>
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
            'baseUrl' => base_url('owner/karyawan'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
