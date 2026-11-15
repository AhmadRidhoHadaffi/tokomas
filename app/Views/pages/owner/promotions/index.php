<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php $items = $result['items'] ?? []; ?>
<div class="space-y-6">
    <?= view('components/ui/page_header', [
        'title' => 'Promosi & Notifikasi',
        'description' => 'Buat promo, pilih penerima, lalu kirim ke pelanggan.',
        'actions' => ui_button([
            'href' => base_url('owner/promosi/baru'),
            'content' => 'Buat Promosi',
            'icon' => 'plus',
            'size' => 'md',
        ]),
    ]) ?>

    <div class="rounded-2xl border border-line bg-white shadow-card">
        <?php if ($items === []): ?>
            <?= empty_state([
                'icon' => 'gift',
                'title' => 'Belum ada promosi',
                'description' => 'Buat promosi pertama untuk dikirim ke pelanggan.',
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-charcoal/50">
                            <th class="p-3">Promosi</th>
                            <th class="p-3">Dikirim</th>
                            <th class="p-3">Target</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $promo): ?>
                            <tr class="border-b border-line/70 transition-colors hover:bg-gold/10 last:border-0 <?= $index % 2 === 1 ? 'bg-ivory/60' : '' ?>">
                                <td class="p-3">
                                    <p class="font-medium text-charcoal"><?= esc($promo['title']) ?></p>
                                    <p class="mt-0.5 max-w-[280px] text-xs text-charcoal/50"><?= esc($promo['message']) ?></p>
                                </td>
                                <td class="whitespace-nowrap p-3 text-charcoal/70">
                                    <p><?= format_datetime((string) ($promo['send_at'] ?? '')) ?></p>
                                    <p class="mt-0.5 text-xs text-charcoal/45">Dibuat <?= format_date((string) ($promo['created_at'] ?? '')) ?></p>
                                </td>
                                <td class="p-3">
                                    <p class="whitespace-nowrap text-charcoal/70"><?= esc(($promo['target_type'] ?? 'semua') === 'semua' ? 'Semua pelanggan' : 'Pelanggan terpilih') ?></p>
                                    <p class="mt-0.5 text-xs text-charcoal/45"><?= esc((string) ($promo['recipient_count'] ?? 0)) ?> penerima</p>
                                </td>
                                <td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $promo['status']]) ?></td>
                                <td class="whitespace-nowrap p-3 text-right">
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
