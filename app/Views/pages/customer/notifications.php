<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
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
<div
    class="mx-auto max-w-3xl space-y-6"
    x-data="{
        filter: 'Semua',
        readAll: false,
        readIds: [],
        unread: <?= $unreadCount ?>,
        markRead(id, wasUnread) {
            if (this.readAll || !wasUnread) return;
            if (!this.readIds.includes(id)) {
                this.readIds.push(id);
                this.unread = Math.max(0, this.unread - 1);
            }
        },
        markAll() {
            this.readAll = true;
            this.unread = 0;
        }
    }"
>
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Notifikasi</h1>
            <p class="mt-1 text-sm text-charcoal/55">
                <span x-show="unread > 0"><span x-text="unread"><?= $unreadCount ?></span> belum dibaca</span>
                <span x-show="unread === 0">Semua notifikasi sudah dibaca</span>
            </p>
        </div>
        <button
            type="button"
            @click="markAll()"
            :disabled="unread === 0"
            class="inline-flex h-10 shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-lg border border-line bg-white px-3.5 text-xs font-medium text-charcoal transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:border-gold/60 hover:bg-ivory focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30 active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50"
        >
            <?= icon('check-check', 'h-4 w-4', 16, 2) ?>
            Tandai semua dibaca
        </button>
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
            class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white"
            x-show="(filter === 'Semua' && <?= count($notifications) ?> > 0)
                || (filter === 'Status Pesanan' && <?= $counts['Status Pesanan'] ?> > 0)
                || (filter === 'Pembayaran' && <?= $counts['Pembayaran'] ?> > 0)
                || (filter === 'Promosi' && <?= $counts['Promosi'] ?> > 0)"
        >
            <?php foreach ($notifications as $notification): ?>
                <?php
                $notificationId = (int) ($notification['id'] ?? 0);
                $type = (string) ($notification['type'] ?? '');
                $meta = $kindMeta[$type] ?? $kindMeta['Status Pesanan'];
                $serverRead = (int) ($notification['is_read'] ?? 0) === 1;
                $readExpression = '(' . ($serverRead ? 'true' : 'false') . ' || readAll || readIds.includes(' . $notificationId . '))';
                ?>
                <li x-show="filter === 'Semua' || filter === '<?= esc($type, 'js') ?>'">
                    <button
                        type="button"
                        @click="markRead(<?= $notificationId ?>, <?= $serverRead ? 0 : 1 ?>)"
                        :class="<?= $readExpression ?> ? 'bg-transparent' : 'bg-gold/[0.06]'"
                        class="flex w-full gap-4 px-5 py-4 text-left transition-colors duration-150 hover:bg-ivory focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/40"
                    >
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full <?= esc($meta['iconClass'], 'attr') ?>">
                            <?= icon($meta['icon'], 'h-5 w-5', 20, 1.75) ?>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex flex-wrap items-center gap-2">
                                <span
                                    :class="<?= $readExpression ?> ? 'font-medium text-charcoal/80' : 'font-semibold text-charcoal'"
                                    class="text-sm <?= $serverRead ? 'font-medium text-charcoal/80' : 'font-semibold text-charcoal' ?>"
                                ><?= esc($notification['title'] ?? '-') ?></span>
                                <?= view('components/ui/badge', ['tone' => $meta['tone'], 'text' => $type]) ?>
                            </span>
                            <span class="mt-1 block break-words text-sm text-charcoal/65"><?= esc($notification['message'] ?? '') ?></span>
                            <span class="mt-1.5 block text-xs text-charcoal/40"><?= esc(format_datetime((string) ($notification['created_at'] ?? ''))) ?></span>
                        </span>
                        <span
                            x-show="!<?= $readExpression ?>"
                            class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-crimson"
                            aria-label="Belum dibaca"
                        ></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php foreach ($counts as $type => $count): ?>
            <div x-show="filter === '<?= esc($type, 'js') ?>' && <?= (int) $count ?> === 0" class="rounded-2xl border border-line bg-white">
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
