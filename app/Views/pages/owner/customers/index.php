<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$items = $result['items'] ?? [];
$search = (string) ($filters['search'] ?? '');
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Data Pelanggan</h1>
            <p class="mt-2 text-sm text-charcoal/60">Daftar pelanggan dan riwayat transaksi mereka.</p>
        </div>
        <span class="rounded-full bg-gold-light px-3 py-1 text-sm text-charcoal"><?= esc((string) ($result['total'] ?? 0)) ?> pelanggan</span>
    </div>

    <div class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
        <form method="get" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <label class="flex-1 text-sm text-charcoal/70">Cari
                <input name="search" value="<?= esc($search, 'attr') ?>" placeholder="Nama, email, atau nomor HP" class="mt-1 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
            </label>
            <div class="flex gap-2">
                <?= ui_button(['type' => 'submit', 'content' => 'Cari', 'icon' => 'search', 'size' => 'md']) ?>
                <?php if ($search !== ''): ?>
                    <?= ui_button(['href' => base_url('owner/pelanggan'), 'content' => 'Reset', 'variant' => 'light', 'size' => 'md']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= view('components/ui/empty_state', [
                'icon' => 'user',
                'title' => 'Belum ada pelanggan',
                'description' => 'Pelanggan yang mendaftar akan muncul di sini.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-charcoal/60">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Telepon</th>
                            <th class="p-3">Bergabung</th>
                            <th class="p-3">Transaksi</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $customer): ?>
                            <tr class="border-b border-line/70 last:border-0">
                                <td class="p-3 font-medium text-charcoal"><?= esc($customer['name']) ?></td>
                                <td class="p-3 text-charcoal/60"><?= esc($customer['email'] ?? '-') ?></td>
                                <td class="p-3"><?= esc($customer['phone'] ?? '-') ?></td>
                                <td class="p-3"><?= format_date((string) ($customer['created_at'] ?? '')) ?></td>
                                <td class="p-3"><?= esc((string) ($customer['total_transactions'] ?? 0)) ?></td>
                                <td class="p-3 text-right">
                                    <?= ui_button([
                                        'href' => base_url('owner/pelanggan/' . $customer['id'] . '/ubah'),
                                        'content' => 'Ubah',
                                        'icon' => 'pencil',
                                        'variant' => 'light',
                                        'size' => 'sm',
                                    ]) ?>
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
            'baseUrl' => base_url('owner/pelanggan'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
