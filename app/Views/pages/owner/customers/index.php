<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Data Pelanggan</h1><p class="mt-2 text-sm text-charcoal/60">Daftar pelanggan terdaftar.</p></div>
        <span class="rounded-full bg-gold-light px-3 py-1 text-sm text-charcoal"><?= esc((string) ($result['total'] ?? 0)) ?> pelanggan</span>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <input name="search" value="<?= esc((string) ($filters['search'] ?? '')) ?>" placeholder="Cari nama atau email" class="rounded-lg border border-line px-3 py-2">
        <?= ui_button(['type' => 'submit', 'content' => 'Cari', 'icon' => 'search', 'size' => 'md']) ?>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Nama</th><th class="p-3">Email</th><th class="p-3">Telepon</th><th class="p-3">Bergabung</th><th class="p-3">Total Transaksi</th></tr></thead><tbody><?php foreach ($result['items'] ?? [] as $c): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($c['name']) ?></td><td class="p-3 text-charcoal/60"><?= esc($c['email'] ?? '-') ?></td><td class="p-3"><?= esc($c['phone'] ?? '-') ?></td><td class="p-3"><?= format_date((string) ($c['created_at'] ?? '')) ?></td><td class="p-3"><?= esc((string) ($c['total_transactions'] ?? 0)) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
