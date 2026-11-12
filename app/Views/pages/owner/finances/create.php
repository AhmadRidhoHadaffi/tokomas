<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Tambah Catatan Pembukuan</h1>
        <p class="mt-2 text-sm text-charcoal/60">Catat pemasukan atau pengeluaran toko.</p>
    </div>
    <?= $this->include('pages/owner/finances/_form', ['finance' => [], 'isEdit' => false]) ?>
</div>
<?= $this->endSection() ?>
