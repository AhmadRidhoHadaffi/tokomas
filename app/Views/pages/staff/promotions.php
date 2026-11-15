<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php
$items = $result['items'] ?? [];
$total = (int) ($result['total'] ?? 0);
?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Promosi & Notifikasi',
        'description' => 'Daftar promosi yang dibuat dan dikirim ke pelanggan.',
        'actions' => '<span class="inline-flex items-center rounded-full bg-gold-light px-3 py-1 text-sm font-medium text-charcoal">'
            . esc((string) $total) . ' promosi</span>',
    ]) ?>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'gift',
                'title' => 'Belum ada promosi',
                'description' => 'Promosi yang dibuat oleh pemilik toko akan tampil di sini.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Dikirim</th>
                            <th class="px-4 py-3">Target</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $promo): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gold-light text-gold-deep">
                                            <?= icon('gift', 'h-4 w-4') ?>
                                        </span>
                                        <span class="font-medium text-charcoal"><?= esc($promo['title']) ?></span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/70"><?= format_datetime((string) ($promo['send_at'] ?? '')) ?></td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-ivory px-2.5 py-1 text-xs font-medium text-charcoal/70">
                                        <?= esc(($promo['target_type'] ?? 'semua') === 'semua' ? 'Semua pelanggan' : 'Pelanggan terpilih') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $promo['status']]) ?></td>
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
            'baseUrl' => base_url('karyawan/promosi'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
