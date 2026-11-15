<?php
/**
 * Public Contact Page
 */

$this->extend('layouts/public');

$this->section('title');
echo 'Kontak & Lokasi';
$this->endSection();
?>

<?= $this->section('content') ?>

<?= view('pages/public/partials/page_hero', [
    'pageTitle' => 'Hubungi Kami',
    'pageSubtitle' => 'Kunjungi toko kami di Pasar Raya Padang atau hubungi kami melalui telepon dan WhatsApp.',
]) ?>
<?= $this->include('pages/public/partials/contact') ?>

<?= $this->endSection() ?>
