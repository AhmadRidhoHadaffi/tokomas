<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Manajemen Karyawan</h1><p class="mt-2 text-sm text-charcoal/60">Daftar karyawan terdaftar.</p></div>
        <span class="rounded-full bg-gold-light px-3 py-1 text-sm text-charcoal"><?= esc((string) ($result['total'] ?? 0)) ?> karyawan</span>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <input name="search" value="<?= esc((string) ($filters['search'] ?? '')) ?>" placeholder="Cari nama atau email" class="rounded-lg border border-line px-3 py-2">
        <?= ui_button(['type' => 'submit', 'content' => 'Cari', 'icon' => 'search', 'size' => 'md']) ?>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Nama</th><th class="p-3">Email</th><th class="p-3">Bergabung</th><th class="p-3">Status</th></tr></thead><tbody><?php foreach ($result['items'] ?? [] as $emp): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($emp['name']) ?></td><td class="p-3 text-charcoal/60"><?= esc($emp['email']) ?></td><td class="p-3"><?= format_date((string) ($emp['created_at'] ?? '')) ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $emp['is_active'] ? 'aktif' : 'nonaktif']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php if (empty($result['items'])): ?>
        <p class="mt-6 rounded-lg bg-ivory px-4 py-3 text-sm text-charcoal/60">Belum ada karyawan yang cocok dengan pencarian.</p>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
