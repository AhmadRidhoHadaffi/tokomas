<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Keuangan</h1><p class="mt-2 text-sm text-charcoal/60">Pencatatan pemasukan dan pengeluaran.</p></div>
        <a href="<?= site_url('staff/keuangan/buat') ?>" class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal">Tambah Catatan</a>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <select name="type" class="rounded-lg border border-line px-3 py-2"><option value="">Semua Tipe</option><option value="income" <?= ($type ?? '') === 'income' ? 'selected' : '' ?>>Pemasukan</option><option value="expense" <?= ($type ?? '') === 'expense' ? 'selected' : '' ?>>Pengeluaran</option></select>
        <input type="date" name="date" value="<?= esc((string) ($date ?? '')) ?>" class="rounded-lg border border-line px-3 py-2">
        <?= ui_button(['type' => 'submit', 'content' => 'Filter', 'size' => 'md']) ?>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Tanggal</th><th class="p-3">Tipe</th><th class="p-3">Deskripsi</th><th class="p-3">Jumlah</th></tr></thead><tbody><?php foreach ($records ?? [] as $rec): ?><tr class="border-b border-line/70"><td class="p-3"><?= format_date($rec['date']) ?></td><td class="p-3"><span class="rounded-full px-2 py-1 text-xs <?= $rec['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>"><?= $rec['type'] === 'income' ? 'Masuk' : 'Keluar' ?></span></td><td class="p-3"><?= esc($rec['description']) ?></td><td class="p-3 font-medium"><?= $rec['type'] === 'income' ? '+' : '-' ?> Rp <?= number_format($rec['amount'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
