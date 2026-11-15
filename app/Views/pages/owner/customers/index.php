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
            <?= empty_state([
                'icon' => 'user',
                'title' => 'Belum ada pelanggan',
                'description' => 'Pelanggan yang mendaftar akan muncul di sini.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3">Bergabung</th>
                            <th class="px-4 py-3">Transaksi</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $customer): ?>
                            <?php
                            $nameParts = preg_split('/\s+/', trim((string) ($customer['name'] ?? ''))) ?: [];
                            $initials = '';
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= mb_substr($part, 0, 1);
                            }
                            ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold-light text-xs font-semibold text-gold-deep" aria-hidden="true">
                                            <?= esc(mb_strtoupper($initials)) ?>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="whitespace-nowrap font-medium text-charcoal"><?= esc($customer['name']) ?></p>
                                            <p class="mt-0.5 max-w-[220px] truncate text-xs text-charcoal/50"><?= esc($customer['email'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= esc($customer['phone'] ?? '-') ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= format_date((string) ($customer['created_at'] ?? '')) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-ivory px-2.5 py-1 text-xs font-medium text-charcoal/70">
                                        <?= esc((string) ($customer['total_transactions'] ?? 0)) ?> transaksi
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
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
