<?php
/**
 * Public Products Page
 */

$this->extend('layouts/public');

$this->section('title');
echo 'Koleksi Perhiasan';
$this->endSection();
?>

<?= $this->section('content') ?>

<?= view('pages/public/partials/page_hero', [
    'pageTitle' => 'Koleksi Perhiasan',
    'pageSubtitle' => 'Emas dan perak pilihan dengan kadar jelas, harga transparan, dan pengerjaan rapi oleh pengrajin Padang.',
]) ?>
<?= $this->include('pages/public/partials/products') ?>
<?= $this->include('pages/public/partials/register_banner') ?>

<?= $this->endSection() ?>
