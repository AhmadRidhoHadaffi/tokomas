<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Promosi & Notifikasi</h1><p class="mt-2 text-sm text-charcoal/60">Daftar promo aktif.</p></div>
        <span class="rounded-full bg-gold-light px-3 py-1 text-sm text-charcoal"><?= esc((string) ($result['total'] ?? 0)) ?> promosi</span>
    </div>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Judul</th><th class="p-3">Dikirim</th><th class="p-3">Target</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($result['items'] ?? [] as $promo): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($promo['title']) ?></td><td class="p-3"><?= format_datetime((string) ($promo['send_at'] ?? '')) ?></td><td class="p-3"><?= esc(($promo['target_type'] ?? 'semua') === 'semua' ? 'Semua pelanggan' : 'Pelanggan terpilih') ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $promo['status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php if (empty($result['items'])): ?>
        <p class="mt-6 rounded-lg bg-ivory px-4 py-3 text-sm text-charcoal/60">Belum ada promosi.</p>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
