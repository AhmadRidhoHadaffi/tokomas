<?= $this->extend('layouts/app') ?>
<?= $this->section('body') ?>
<div class="min-h-screen bg-ivory flex">
    <?= view_cell('App\\Cells\\SidebarCell::show', ['role' => session('role')]) ?>
    <div class="flex-1 min-w-0">
        <?= $this->include('components/layout/header') ?>
        <main class="p-6 lg:p-8"><?= $this->renderSection('content') ?></main>
    </div>
</div>
<?= $this->endSection() ?>
