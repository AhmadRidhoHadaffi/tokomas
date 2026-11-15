<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$store = $store ?? [];
$errors = session()->getFlashdata('errors') ?? [];
$oldPrices = old('prices');
$oldPrices = is_array($oldPrices) ? $oldPrices : [];

$priceByPurity = [];
foreach (($goldPrices ?? []) as $priceRow) {
    $priceByPurity[$priceRow['purity']] = $priceRow;
}
$purities = $purities ?? ['24K', '22K', '18K', '17K'];
?>
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Pengaturan</h1>
        <p class="mt-2 text-sm text-charcoal/60">Konfigurasi identitas toko, notifikasi, dan harga emas per gram.</p>
    </div>

    <form method="post" action="<?= base_url('owner/pengaturan') ?>" class="space-y-6">
        <?= csrf_field() ?>

        <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Identitas Toko</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <?= form_field([
                    'name' => 'store_name', 'label' => 'Nama Toko', 'required' => true,
                    'value' => old('store_name', $store['store_name'] ?? ''),
                    'error' => $errors['store_name'] ?? '',
                ]) ?>
                <?= form_field([
                    'type' => 'email', 'name' => 'email', 'label' => 'Email Toko',
                    'value' => old('email', $store['email'] ?? ''),
                    'error' => $errors['email'] ?? '',
                ]) ?>
                <?= form_field([
                    'type' => 'tel', 'name' => 'phone', 'label' => 'Telepon',
                    'value' => old('phone', $store['phone'] ?? ''),
                    'error' => $errors['phone'] ?? '',
                ]) ?>
                <?= form_field([
                    'type' => 'tel', 'name' => 'whatsapp', 'label' => 'WhatsApp',
                    'value' => old('whatsapp', $store['whatsapp'] ?? ''),
                    'hint' => 'Gunakan format 62… agar tautan WhatsApp berfungsi.',
                    'error' => $errors['whatsapp'] ?? '',
                ]) ?>
                <?= form_field([
                    'type' => 'textarea', 'name' => 'address', 'label' => 'Alamat', 'class' => 'sm:col-span-2',
                    'value' => old('address', $store['address'] ?? ''),
                    'error' => $errors['address'] ?? '', 'rows' => '3',
                ]) ?>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Notifikasi Otomatis</h2>
            <p class="mt-1 text-sm text-charcoal/55">Tentukan notifikasi yang dikirim ke pelanggan.</p>
            <div class="mt-4 space-y-3">
                <?php
                $notifyOptions = [
                    'notify_status_change' => ['Perubahan status pesanan custom', 'Kirim notifikasi saat status pesanan berubah.'],
                    'notify_payment' => ['Pembayaran', 'Kirim notifikasi terkait pembayaran dan pelunasan.'],
                    'notify_low_stock' => ['Stok menipis', 'Tampilkan peringatan saat stok produk hampir habis.'],
                ];
                foreach ($notifyOptions as $name => [$label, $hint]):
                    $checked = (string) old($name, $store[$name] ?? 1) === '1';
                ?>
                    <label class="flex items-start gap-3 rounded-lg border border-line px-4 py-3 text-sm">
                        <input type="checkbox" name="<?= esc($name, 'attr') ?>" value="1" class="mt-0.5 h-4 w-4 rounded border-line text-gold focus:ring-gold" <?= $checked ? 'checked' : '' ?>>
                        <span>
                            <span class="font-medium text-charcoal"><?= esc($label) ?></span>
                            <span class="mt-0.5 block text-xs text-charcoal/55"><?= esc($hint) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
            <h2 class="font-serif text-lg font-semibold text-charcoal">Harga Emas per Gram</h2>
            <p class="mt-1 text-sm text-charcoal/55">Kosongkan kolom jika harga kadar tersebut tidak ingin diubah.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <?php foreach ($purities as $purity): ?>
                    <?php
                    $current = $oldPrices[$purity] ?? ($priceByPurity[$purity]['price_per_gram'] ?? '');
                    $updatedAt = $priceByPurity[$purity]['updated_at'] ?? null;
                    ?>
                    <?= form_field([
                        'type' => 'number', 'name' => 'prices[' . $purity . ']', 'id' => 'price-' . $purity,
                        'label' => 'Harga ' . $purity, 'value' => $current,
                        'placeholder' => 'Contoh: 1500000',
                        'hint' => $updatedAt ? 'Diperbarui ' . format_datetime((string) $updatedAt) : 'Belum ada harga',
                        'extraAttrs' => 'step="1" min="1"',
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="flex justify-end">
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Pengaturan', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
