<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="space-y-6">
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h1 class="font-serif text-3xl font-semibold text-charcoal">Pengaturan</h1><p class="mt-2 text-sm text-charcoal/60">Konfigurasi toko dan harga emas.</p>
        <dl class="mt-6 grid gap-5 sm:grid-cols-2">
            <div><dt class="text-sm text-charcoal/50">Nama Toko</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($store['store_name'] ?? 'Toko Mas Gembira') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Telepon</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($store['phone'] ?? '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">Email</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($store['email'] ?? '-') ?></dd></div>
            <div><dt class="text-sm text-charcoal/50">WhatsApp</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($store['whatsapp'] ?? '-') ?></dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-charcoal/50">Alamat</dt><dd class="mt-1 font-medium text-charcoal"><?= esc($store['address'] ?? '-') ?></dd></div>
        </dl>
        <p class="mt-6 rounded-lg bg-ivory px-4 py-3 text-sm text-charcoal/60">Perubahan pengaturan belum dapat disimpan dari halaman ini.</p>
    </div>

    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <h2 class="font-serif text-xl font-semibold text-charcoal">Harga Emas per Gram</h2>
        <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b border-line text-charcoal/60"><th class="p-3">Kadar</th><th class="p-3">Harga</th><th class="p-3">Diperbarui</th></tr></thead><tbody><?php foreach ($goldPrices ?? [] as $price): ?><tr class="border-b border-line/70"><td class="p-3 font-medium"><?= esc($price['purity']) ?></td><td class="p-3">Rp <?= number_format((float) $price['price_per_gram'], 0, ',', '.') ?></td><td class="p-3"><?= format_datetime((string) ($price['updated_at'] ?? '')) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
</div>
<?= $this->endSection() ?>
