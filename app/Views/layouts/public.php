<?= $this->extend('layouts/app') ?>
<?= $this->section('body') ?>
<div class="min-h-screen bg-ivory">
    <?= $this->include('components/layout/header') ?>
    <main><?= $this->renderSection('content') ?></main>
    <?= $this->include('components/layout/footer') ?>
</div>
<?= $this->endSection() ?>
