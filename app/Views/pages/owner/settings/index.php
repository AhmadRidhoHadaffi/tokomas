<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="rounded-2xl border border-line bg-white p-6 shadow-card">
    <h1 class="font-serif text-3xl font-semibold text-charcoal">Pengaturan</h1><p class="mt-2 text-sm text-charcoal/60">Konfigurasi toko.</p>
    <form method="post" class="mt-8 max-w-2xl space-y-6">
        <?= csrf_field() ?>
        <div><label class="block text-sm font-medium text-charcoal">Nama Toko</label><input type="text" name="store_name" value="<?= esc($settings['store_name'] ?? 'Toko Mas Gembira') ?>" class="mt-2 w-full rounded-lg border border-line px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-charcoal">Alamat</label><textarea name="address" rows="3" class="mt-2 w-full rounded-lg border border-line px-3 py-2"><?= esc($settings['address'] ?? '') ?></textarea></div>
        <div><label class="block text-sm font-medium text-charcoal">Telepon</label><input type="tel" name="phone" value="<?= esc($settings['phone'] ?? '') ?>" class="mt-2 w-full rounded-lg border border-line px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-charcoal">Email</label><input type="email" name="email" value="<?= esc($settings['email'] ?? '') ?>" class="mt-2 w-full rounded-lg border border-line px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-charcoal">Harga Emas per Gram (Rp)</label><input type="number" name="gold_price_per_gram" value="<?= esc((string) ($settings['gold_price_per_gram'] ?? 1000000)) ?>" class="mt-2 w-full rounded-lg border border-line px-3 py-2"></div>
        <button type="submit" class="rounded-lg bg-gold px-5 py-2.5 font-medium text-charcoal">Simpan Perubahan</button>
    </form>
</div>
<?= $this->endSection() ?>
