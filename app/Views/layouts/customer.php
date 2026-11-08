<?php
/**
 * Customer Layout
 *
 * Authenticated shell for the pelanggan area: sticky gold topbar,
 * centred content column and a mobile-only bottom navigation.
 */

$customerUserId = (int) (session('user_id') ?? 0);
$customerUnreadCount = 0;

if ($customerUserId > 0) {
    try {
        $layoutCustomer = (new \App\Services\CustomerService())->getByUserId($customerUserId);
        if ($layoutCustomer) {
            $customerUnreadCount = (new \App\Services\NotificationService())->getUnreadCount((int) $layoutCustomer['id']);
        }
    } catch (\Throwable $e) {
        $customerUnreadCount = 0;
    }
}
?>
<?= $this->extend('layouts/app') ?>
<?= $this->section('body') ?>
<div class="min-h-screen w-full bg-ivory pb-24 lg:pb-0">
    <?= $this->include('components/layout/customer_topbar', ['unreadCount' => $customerUnreadCount]) ?>

    <main class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:py-10">
        <?= $this->include('partials/flash') ?>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('components/layout/customer_bottom_nav', ['unreadCount' => $customerUnreadCount]) ?>
</div>
<?= $this->endSection() ?>
