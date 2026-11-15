<?php
/**
 * Public Custom Order Page
 */

$this->extend('layouts/public');

$this->section('title');
echo 'Pesanan Custom';
$this->endSection();
?>

<?= $this->section('content') ?>

<?= view('pages/public/partials/page_hero', [
    'pageTitle' => 'Pesanan Custom',
    'pageSubtitle' => 'Cincin nikah, liontin inisial, atau gelang bermotif Rumah Gadang — dibuat khusus sesuai keinginan Anda.',
]) ?>
<?= $this->include('pages/public/partials/custom') ?>
<?= $this->include('pages/public/partials/promo') ?>
<?= $this->include('pages/public/partials/register_banner') ?>

<?= $this->endSection() ?>
