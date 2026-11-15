<?php
/**
 * Public About Page
 */

$this->extend('layouts/public');

$this->section('title');
echo 'Tentang Kami';
$this->endSection();
?>

<?= $this->section('content') ?>

<?= view('pages/public/partials/page_hero', [
    'pageTitle' => 'Tentang Toko Mas Gembira',
    'pageSubtitle' => 'Melayani keluarga Padang sejak 1998 dengan perhiasan berkadar jelas dan pelayanan transparan.',
]) ?>
<?= $this->include('pages/public/partials/about') ?>
<?= $this->include('pages/public/partials/why_us') ?>
<?= $this->include('pages/public/partials/register_banner') ?>

<?= $this->endSection() ?>
