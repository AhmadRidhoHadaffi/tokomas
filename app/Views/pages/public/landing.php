<?php
/**
 * Public Landing Page (Beranda)
 */

$this->extend('layouts/public');

$this->section('title');
echo 'Perhiasan Emas & Perak Terbaik di Padang';
$this->endSection();
?>

<?= $this->section('content') ?>

<?= $this->include('pages/public/partials/hero') ?>
<?= $this->include('pages/public/partials/about') ?>
<?= $this->include('pages/public/partials/products') ?>
<?= $this->include('pages/public/partials/why_us') ?>
<?= $this->include('pages/public/partials/register_banner') ?>
<?= $this->include('pages/public/partials/contact') ?>

<?= $this->endSection() ?>
