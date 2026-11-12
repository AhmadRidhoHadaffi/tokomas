<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php $items = $result['items'] ?? []; ?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Promosi &amp; Notifikasi</h1>
            <p class="mt-2 text-sm text-charcoal/60">Buat promo, pilih penerima, lalu kirim ke pelanggan.</p>
        </div>
        <?= ui_button([
            'href' => base_url('owner/promosi/baru'),
            'content' => 'Buat Promosi',
            'icon' => 'plus',
            'size' => 'md',
        ]) ?>
    </div>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= view('components/ui/empty_state', [
                'icon' => 'gift',
                'title' => 'Belum ada promosi',
                'description' => 'Buat promosi pertama untuk dikirim ke pelanggan.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-charcoal/60">
                            <th class="p-3">Judul</th>
                            <th class="p-3">Dibuat</th>
                            <th class="p-3">Dikirim</th>
                            <th class="p-3">Target</th>
                            <th class="p-3">Penerima</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $promo): ?>
                            <tr class="border-b border-line/70 last:border-0">
                                <td class="p-3">
                                    <p class="font-medium text-charcoal"><?= esc($promo['title']) ?></p>
                                    <p class="max-w-md text-xs text-charcoal/50"><?= esc($promo['message']) ?></p>
                                </td>
                                <td class="p-3"><?= format_date((string) ($promo['created_at'] ?? '')) ?></td>
                                <td class="p-3"><?= format_datetime((string) ($promo['send_at'] ?? '')) ?></td>
                                <td class="p-3"><?= esc(($promo['target_type'] ?? 'semua') === 'semua' ? 'Semua pelanggan' : 'Pelanggan terpilih') ?></td>
                                <td class="p-3"><?= esc((string) ($promo['recipient_count'] ?? 0)) ?></td>
                                <td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $promo['status']]) ?></td>
                                <td class="p-3 text-right">
                                    <?php if (($promo['status'] ?? '') !== 'Terkirim'): ?>
                                        <form method="post" action="<?= base_url('owner/promosi/' . $promo['id'] . '/kirim') ?>" class="inline" onsubmit="return confirm('Kirim promosi ini ke penerima sekarang?');">
                                            <?= csrf_field() ?>
                                            <?= ui_button(['type' => 'submit', 'content' => 'Kirim', 'icon' => 'send', 'variant' => 'outline', 'size' => 'sm']) ?>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-charcoal/40">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
