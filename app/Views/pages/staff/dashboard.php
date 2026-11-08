<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="space-y-6">
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h1 class="font-serif text-3xl font-semibold text-charcoal">Dashboard Karyawan</h1><p class="mt-2 text-sm text-charcoal/60">Ringkasan aktivitas hari ini.</p>
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Transaksi Hari Ini</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($stats['today_transactions'] ?? 0)) ?></p></div>
            <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Custom Order Aktif</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($stats['active_orders'] ?? 0)) ?></p></div>
            <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Stok Menipis</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($stats['low_stock'] ?? 0)) ?></p></div>
            <div class="rounded-xl border border-line bg-gold-light/30 p-5"><p class="text-sm text-charcoal/60">Pelanggan Baru</p><p class="mt-2 font-serif text-2xl font-semibold text-charcoal"><?= esc((string) ($stats['new_customers'] ?? 0)) ?></p></div>
        </div>
    </div>
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h2 class="font-serif text-xl font-semibold text-charcoal">Tugas Hari Ini</h2>
        <div class="mt-4 space-y-3"><?php foreach ($tasks ?? [] as $task): ?><div class="flex items-center gap-3 rounded-lg border border-line p-3"><span class="text-2xl"><?= esc($task['icon'] ?? '📋') ?></span><div class="flex-1"><p class="font-medium text-charcoal"><?= esc($task['title']) ?></p><p class="text-sm text-charcoal/60"><?= esc($task['description']) ?></p></div></div><?php endforeach; ?></div>
    </div>
</div>
<?= $this->endSection() ?>
