<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="font-serif text-3xl font-semibold text-charcoal">Produk & Stok</h1><p class="mt-2 text-sm text-charcoal/60">Data produk dari database.</p></div>
    </div>
    <form method="get" class="mt-6 flex flex-wrap gap-3">
        <input name="search" value="<?= esc((string) ($search ?? '')) ?>" placeholder="Cari kode atau nama" class="rounded-lg border border-line px-3 py-2">
        <select name="stock_level" class="rounded-lg border border-line px-3 py-2"><option value="">Semua stok</option><option value="tersedia" <?= ($stock_level ?? '') === 'tersedia' ? 'selected' : '' ?>>Tersedia</option><option value="hampir" <?= ($stock_level ?? '') === 'hampir' ? 'selected' : '' ?>>Hampir habis</option><option value="habis" <?= ($stock_level ?? '') === 'habis' ? 'selected' : '' ?>>Habis</option></select>
        <button class="rounded-lg bg-gold px-4 py-2 font-medium text-charcoal" type="submit">Cari</button>
    </form>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Kode</th><th class="p-3">Nama</th><th class="p-3">Kadar</th><th class="p-3">Harga</th><th class="p-3">Stok</th></tr></thead><tbody><?php foreach ($products ?? [] as $product): ?><tr class="border-b border-line/70"><td class="p-3"><?= esc($product['code']) ?></td><td class="p-3"><?= esc($product['name']) ?></td><td class="p-3"><?= esc($product['purity']) ?></td><td class="p-3">Rp <?= esc(number_format((float) $product['price'], 0, ',', '.')) ?></td><td class="p-3"><?= view_cell('App\\Cells\\StockBadgeCell::show', ['stock' => $product['stock']]) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?= $this->endSection() ?>
