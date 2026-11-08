<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Manajemen Karyawan</h1><p class="mt-2 text-sm text-charcoal/60">Daftar karyawan aktif.</p></div>
        <a href="<?= site_url('owner/karyawan/buat') ?>" class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal">Tambah Karyawan</a>
    </div>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Nama</th><th class="p-3">Username</th><th class="p-3">Telepon</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead><tbody><?php foreach ($employees ?? [] as $emp): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($emp['name']) ?></td><td class="p-3"><?= esc($emp['username']) ?></td><td class="p-3"><?= esc($emp['phone'] ?? '-') ?></td><td class="p-3"><?= view_cell('App\\Cells\\StatusBadgeCell::show', ['status' => $emp['is_active'] ? 'aktif' : 'nonaktif']) ?></td><td class="p-3"><a href="<?= site_url('owner/karyawan/' . $emp['id']) ?>" class="text-gold-deep hover:underline">Edit</a></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
