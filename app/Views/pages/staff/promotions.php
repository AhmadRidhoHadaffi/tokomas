<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <h1 class="font-serif text-3xl font-semibold text-charcoal">Promosi & Notifikasi</h1><p class="mt-2 text-sm text-charcoal/60">Daftar promo aktif.</p>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Judul</th><th class="p-3">Periode</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($promotions ?? [] as $promo): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($promo['title']) ?></td><td class="p-3"><?= format_date($promo['start_date']) ?> - <?= format_date($promo['end_date']) ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $promo['status']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
