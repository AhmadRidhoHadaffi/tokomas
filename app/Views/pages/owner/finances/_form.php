<?php
$finance = $finance ?? [];
$isEdit = !empty($isEdit);
$errors = session()->getFlashdata('errors') ?? [];
$categories = $categories ?? [];
$action = $isEdit ? base_url('owner/pembukuan/' . $finance['id']) : base_url('owner/pembukuan');
$type = (string) old('type', $finance['type'] ?? 'Pemasukan');
?>
<form method="post" action="<?= esc($action, 'attr') ?>" class="space-y-5 rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6">
    <?= csrf_field() ?>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="finance-type" class="mb-1.5 block text-sm font-medium text-charcoal/80">Tipe <span class="text-crimson">*</span></label>
            <select id="finance-type" name="type" class="w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
                <option value="Pemasukan" <?= $type === 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="Pengeluaran" <?= $type === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label for="finance-category" class="mb-1.5 block text-sm font-medium text-charcoal/80">Kategori <span class="text-crimson">*</span></label>
            <select id="finance-category" name="category" class="w-full rounded-lg border border-line px-3.5 py-2.5 text-sm">
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $group => $options): ?>
                    <optgroup label="<?= esc($group) ?>">
                        <?php foreach ($options as $option): ?>
                            <option value="<?= esc($option, 'attr') ?>" <?= old('category', $finance['category'] ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category'])): ?>
                <p class="mt-1.5 text-xs text-crimson"><?= esc($errors['category']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?= form_field([
        'name' => 'description', 'label' => 'Deskripsi', 'required' => true,
        'value' => old('description', $finance['description'] ?? ''),
        'placeholder' => 'Contoh: Pembelian bahan baku perak',
        'error' => $errors['description'] ?? '',
    ]) ?>

    <div class="grid gap-4 sm:grid-cols-2">
        <?= form_field([
            'type' => 'number', 'name' => 'amount', 'label' => 'Jumlah (Rp)', 'required' => true,
            'value' => old('amount', $finance['amount'] ?? ''),
            'placeholder' => 'Contoh: 500000', 'error' => $errors['amount'] ?? '',
            'extraAttrs' => 'step="1" min="1"',
        ]) ?>
        <?= form_field([
            'type' => 'date', 'name' => 'entry_date', 'label' => 'Tanggal',
            'value' => old('entry_date', $finance['entry_date'] ?? date('Y-m-d')),
            'error' => $errors['entry_date'] ?? '',
        ]) ?>
    </div>

    <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
        <?= ui_button(['href' => base_url('owner/pembukuan'), 'content' => 'Batal', 'variant' => 'light', 'size' => 'md']) ?>
        <?= ui_button(['type' => 'submit', 'content' => $isEdit ? 'Simpan Perubahan' : 'Simpan Catatan', 'icon' => 'check', 'size' => 'md']) ?>
    </div>
</form>
