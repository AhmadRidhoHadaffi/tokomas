<?= $this->extend('layouts/app') ?>
<?= $this->section('body') ?>
<div class="min-h-screen bg-ivory">
    <?= $this->include('components/layout/customer_topbar') ?>
    <main class="mx-auto max-w-6xl p-6 lg:p-8"><?= $this->renderSection('content') ?></main>
</div>
<?= $this->endSection() ?>
