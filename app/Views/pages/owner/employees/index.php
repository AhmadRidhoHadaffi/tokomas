<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$items = $result['items'] ?? [];
$search = (string) ($filters['search'] ?? '');
$activeFilter = $filters['is_active'] ?? null;
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Manajemen Karyawan</h1>
            <p class="mt-2 text-sm text-charcoal/60">Kelola akun karyawan, status aktif, dan kata sandi.</p>
        </div>
        <?= ui_button([
            'href' => base_url('owner/karyawan/baru'),
            'content' => 'Tambah Karyawan',
            'icon' => 'user-plus',
            'size' => 'md',
        ]) ?>
    </div>

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
            <?= view('components/ui/empty_state', [
                'icon' => 'users',
                'title' => 'Belum ada karyawan',
                'description' => 'Tambahkan akun karyawan agar mereka dapat mencatat transaksi.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-charcoal/60">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Bergabung</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $employee): ?>
                            <tr class="border-b border-line/70 last:border-0">
                                <td class="p-3 font-medium text-charcoal"><?= esc($employee['name']) ?></td>
                                <td class="p-3 text-charcoal/60"><?= esc($employee['email']) ?></td>
                                <td class="p-3"><?= format_date((string) ($employee['created_at'] ?? '')) ?></td>
                                <td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $employee['is_active'] ? 'aktif' : 'nonaktif']) ?></td>
                                <td class="p-3">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <?= ui_button([
                                            'href' => base_url('owner/karyawan/' . $employee['id'] . '/ubah'),
                                            'content' => 'Ubah',
                                            'icon' => 'pencil',
                                            'variant' => 'light',
                                            'size' => 'sm',
                                        ]) ?>
                                        <?= ui_button([
                                            'href' => base_url('owner/karyawan/' . $employee['id'] . '/kata-sandi'),
                                            'content' => 'Reset sandi',
                                            'icon' => 'key-round',
                                            'variant' => 'outline',
                                            'size' => 'sm',
                                        ]) ?>
                                        <form method="post" action="<?= base_url('owner/karyawan/' . $employee['id'] . '/toggle') ?>" onsubmit="return confirm('<?= $employee['is_active'] ? 'Nonaktifkan karyawan ini? Ia tidak akan bisa masuk.' : 'Aktifkan kembali karyawan ini?' ?>');">
                                            <?= csrf_field() ?>
                                            <?= ui_button([
                                                'type' => 'submit',
                                                'content' => $employee['is_active'] ? 'Nonaktifkan' : 'Aktifkan',
                                                'icon' => $employee['is_active'] ? 'user-x' : 'user-check',
                                                'variant' => $employee['is_active'] ? 'danger' : 'light',
                                                'size' => 'sm',
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
