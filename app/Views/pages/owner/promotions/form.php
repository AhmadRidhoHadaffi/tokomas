<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$selected = old('customer_ids');
$selected = is_array($selected) ? array_map('intval', $selected) : [];
$targetType = (string) old('target_type', 'semua');
?>
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Buat Promosi</h1>
        <p class="mt-2 text-sm text-charcoal/60">Promosi disimpan sebagai draf. Kirim dari halaman daftar promosi.</p>
    </div>

    <form method="post" action="<?= base_url('owner/promosi') ?>" class="space-y-6 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6" x-data="{ target: '<?= esc($targetType, 'attr') ?>' }">
        <?= csrf_field() ?>

        <?= form_field([
            'name' => 'title', 'label' => 'Judul Promosi', 'required' => true,
            'value' => old('title'), 'placeholder' => 'Contoh: Diskon Perhiasan Akhir Tahun',
            'error' => $errors['title'] ?? '',
        ]) ?>

        <?= form_field([
            'type' => 'textarea', 'name' => 'message', 'label' => 'Isi Pesan', 'required' => true,
            'value' => old('message'), 'rows' => '4',
            'placeholder' => 'Tulis pesan singkat yang akan tampil di notifikasi pelanggan.',
            'error' => $errors['message'] ?? '',
        ]) ?>

        <div>
            <p class="mb-1.5 block text-sm font-medium text-charcoal/80">Target Penerima <span class="text-crimson">*</span></p>
            <div class="grid gap-3 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-line px-4 py-3 text-sm" :class="target === 'semua' ? 'border-gold bg-gold/5' : ''">
                    <input type="radio" name="target_type" value="semua" class="mt-0.5 h-4 w-4 text-gold focus:ring-gold" x-model="target" <?= $targetType === 'semua' ? 'checked' : '' ?>>
                    <span>
                        <span class="font-medium text-charcoal">Semua pelanggan</span>
                        <span class="mt-0.5 block text-xs text-charcoal/55">Kirim ke seluruh pelanggan terdaftar.</span>
                    </span>
                </label>
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-line px-4 py-3 text-sm" :class="target === 'terpilih' ? 'border-gold bg-gold/5' : ''">
                    <input type="radio" name="target_type" value="terpilih" class="mt-0.5 h-4 w-4 text-gold focus:ring-gold" x-model="target" <?= $targetType === 'terpilih' ? 'checked' : '' ?>>
                    <span>
                        <span class="font-medium text-charcoal">Pelanggan terpilih</span>
                        <span class="mt-0.5 block text-xs text-charcoal/55">Pilih penerima tertentu di bawah.</span>
                    </span>
                </label>
            </div>
        </div>

        <div x-show="target === 'terpilih'" x-cloak class="rounded-xl border border-line">
            <div class="border-b border-line px-4 py-3">
                <p class="text-sm font-medium text-charcoal">Pilih Pelanggan</p>
                <p class="mt-0.5 text-xs text-charcoal/55">Centang satu atau lebih penerima.</p>
            </div>
            <div class="max-h-72 space-y-1 overflow-y-auto p-3">
                <?php if (empty($customers)): ?>
                    <p class="px-2 py-3 text-sm text-charcoal/55">Belum ada pelanggan terdaftar.</p>
                <?php else: ?>
                    <?php foreach ($customers as $customer): ?>
                        <label class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm hover:bg-ivory">
                            <input type="checkbox" name="customer_ids[]" value="<?= (int) $customer['id'] ?>" class="h-4 w-4 rounded border-line text-gold focus:ring-gold" <?= in_array((int) $customer['id'], $selected, true) ? 'checked' : '' ?>>
                            <span class="min-w-0">
                                <span class="block font-medium text-charcoal"><?= esc($customer['name']) ?></span>
                                <span class="block truncate text-xs text-charcoal/55"><?= esc($customer['phone'] ?? '') ?><?= !empty($customer['email']) ? ' · ' . esc($customer['email']) : '' ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <?= ui_button(['href' => base_url('owner/promosi'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
            <?= ui_button(['type' => 'submit', 'content' => 'Simpan Draf', 'icon' => 'check', 'size' => 'md']) ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
