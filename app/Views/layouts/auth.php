<?= $this->extend('layouts/app') ?>
<?= $this->section('body') ?>
<div class="min-h-screen bg-ivory flex items-center justify-center p-6 bg-arabesque">
    <main class="w-full max-w-md"><?= $this->renderSection('content') ?></main>
</div>
<?= $this->endSection() ?>
