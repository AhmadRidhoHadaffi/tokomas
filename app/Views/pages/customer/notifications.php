<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/flash') ?>
<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'per_page' => 20, 'total_pages' => 0];
$notifications = $result['items'] ?? [];
$unreadCount = (int) ($unreadCount ?? 0);

$counts = [
    'Status Pesanan' => 0,
    'Pembayaran' => 0,
    'Promosi' => 0,
];
foreach ($notifications as $notification) {
    $type = $notification['type'] ?? '';
    if (isset($counts[$type])) {
        $counts[$type]++;
    }
}

$kindMeta = [
    'Status Pesanan' => [
        'tone' => 'blue',
        'icon' => 'gem',
        'iconClass' => 'bg-blue-50 text-blue-700',
    ],
    'Pembayaran' => [
        'tone' => 'orange',
        'icon' => 'wallet',
        'iconClass' => 'bg-orange-50 text-orange-700',
    ],
    'Promosi' => [
        'tone' => 'gold',
        'icon' => 'gift',
        'iconClass' => 'bg-gold/15 text-gold-deep',
    ],
];
?>
<div class="mx-auto max-w-3xl space-y-6" x-data="{ filter: 'Semua' }">
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Notifikasi</h1>
            <p class="mt-1 text-sm text-charcoal/55">
                <?php if ($unreadCount > 0): ?>
                    <span class="font-medium text-charcoal"><?= $unreadCount ?></span> belum dibaca
                <?php else: ?>
                    Semua notifikasi sudah dibaca
                <?php endif; ?>
            </p>
        </div>
        <?php if ($unreadCount > 0): ?>
            <form method="post" action="<?= base_url('pelanggan/notifikasi/dibaca-semua') ?>">
                <?= csrf_field() ?>
                <?= ui_button([
                    'type' => 'submit',
                    'content' => 'Tandai semua dibaca',
                    'icon' => 'check-check',
                    'variant' => 'light',
                    'size' => 'sm',
                ]) ?>
            </form>
        <?php else: ?>
            <?= ui_button([
                'type' => 'button',
                'content' => 'Tandai semua dibaca',
                'icon' => 'check-check',
                'variant' => 'light',
                'size' => 'sm',
                'disabled' => true,
            ]) ?>
        <?php endif; ?>
    </div>

    <?= view('components/ui/tabs', [
        'items' => [
            ['value' => 'Semua', 'label' => 'Semua'],
            ['value' => 'Status Pesanan', 'label' => 'Status Pesanan'],
            ['value' => 'Pembayaran', 'label' => 'Pembayaran'],
            ['value' => 'Promosi', 'label' => 'Promosi'],
        ],
        'model' => 'filter',
        'variant' => 'underline',
    ]) ?>

    <?php if (empty($notifications)): ?>
        <div class="rounded-2xl border border-line bg-white">
            <?= view('components/customer/empty_state', [
                'heading' => 'Tidak ada notifikasi',
                'description' => 'Kabar terbaru tentang pesanan dan promo akan muncul di sini.',
            ]) ?>
        </div>
    <?php else: ?>
        <ul
            x-show="filter === 'Semua'
                || (filter === 'Status Pesanan' && <?= (int) $counts['Status Pesanan'] ?> > 0)
                || (filter === 'Pembayaran' && <?= (int) $counts['Pembayaran'] ?> > 0)
                || (filter === 'Promosi' && <?= (int) $counts['Promosi'] ?> > 0)"
            class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white"
        >
            <?php foreach ($notifications as $notification): ?>
                <?php
                $notificationId = (int) ($notification['id'] ?? 0);
                $type = (string) ($notification['type'] ?? '');
                $meta = $kindMeta[$type] ?? $kindMeta['Status Pesanan'];
                $isRead = (int) ($notification['is_read'] ?? 0) === 1;
                ?>
                <li
                    x-show="filter === 'Semua' || filter === '<?= esc($type, 'js') ?>'"
                    class="flex gap-3 px-4 py-4 sm:gap-4 sm:px-5 <?= $isRead ? 'bg-white' : 'bg-gold/[0.06]' ?>"
                >
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full <?= esc($meta['iconClass'], 'attr') ?>">
                        <?= icon($meta['icon'], 'h-5 w-5', 20, 1.75) ?>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-sm <?= $isRead ? 'font-medium text-charcoal/80' : 'font-semibold text-charcoal' ?>"><?= esc($notification['title'] ?? '-') ?></p>
                            <?= view('components/ui/badge', ['tone' => $meta['tone'], 'text' => $type]) ?>
                            <?php if (!$isRead): ?>
                                <span class="h-2.5 w-2.5 rounded-full bg-crimson" aria-label="Belum dibaca"></span>
                            <?php endif; ?>
                        </div>
                        <p class="mt-1 break-words text-sm text-charcoal/65"><?= esc($notification['message'] ?? '') ?></p>
                        <p class="mt-1.5 text-xs text-charcoal/40"><?= esc(format_datetime((string) ($notification['created_at'] ?? ''))) ?></p>
                    </div>
                    <?php if (!$isRead): ?>
                        <form method="post" action="<?= base_url('pelanggan/notifikasi/' . $notificationId . '/dibaca') ?>" class="shrink-0 self-center">
                            <?= csrf_field() ?>
                            <?= ui_button([
                                'type' => 'submit',
                                'content' => 'Tandai',
                                'icon' => 'check',
                                'variant' => 'light',
                                'size' => 'sm',
                                'ariaLabel' => 'Tandai notifikasi sudah dibaca',
                            ]) ?>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php foreach ($counts as $type => $count): ?>
            <div x-show="filter === '<?= esc($type, 'js') ?>' && <?= (int) $count ?> === 0" x-cloak class="rounded-2xl border border-line bg-white">
                <?= view('components/customer/empty_state', [
                    'heading' => 'Tidak ada notifikasi',
                    'description' => 'Kabar terbaru tentang pesanan dan promo akan muncul di sini.',
                ]) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (($result['total_pages'] ?? 0) > 1): ?>
        <?= view('components/ui/pagination', [
            'currentPage' => (int) ($result['page'] ?? 1),
            'totalPages' => (int) $result['total_pages'],
            'total' => (int) ($result['total'] ?? 0),
            'perPage' => (int) ($result['per_page'] ?? 20),
            'baseUrl' => base_url('pelanggan/notifikasi'),
        ]) ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
