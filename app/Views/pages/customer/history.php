<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'per_page' => 20, 'total_pages' => 0];
$transactions = $result['items'] ?? [];

$customer = null;
try {
    $customer = (new \App\Services\CustomerService())->getByUserId((int) (session('user_id') ?? 0));
} catch (\Throwable $e) {
    $customer = null;
}
$customerId = isset($customer['id']) ? (int) $customer['id'] : 0;

$totalSpent = 0.0;
if ($customerId > 0) {
    try {
        $row = \Config\Database::connect()
            ->table('transactions')
            ->selectSum('total', 'grand_total')
            ->where('customer_id', $customerId)
            ->get()
            ->getRowArray();
        $totalSpent = (float) ($row['grand_total'] ?? 0);
    } catch (\Throwable $e) {
        $totalSpent = 0.0;
    }
}

$transactionService = null;
try {
    $transactionService = new \App\Services\TransactionService();
} catch (\Throwable $e) {
    $transactionService = null;
}
foreach ($transactions as &$transaction) {
    $transaction['items'] = $transaction['items'] ?? [];
    if ($transactionService && empty($transaction['items'])) {
        try {
            $detail = $transactionService->getById((int) ($transaction['id'] ?? 0));
            $transaction['items'] = $detail['items'] ?? [];
        } catch (\Throwable $e) {
            $transaction['items'] = [];
        }
    }
}
unset($transaction);

$transactionProducts = static function (array $transaction): string {
    $names = array_map(static function ($item) {
        return (string) ($item['name'] ?? '');
    }, $transaction['items'] ?? []);
    $names = array_values(array_filter($names, static fn ($name) => $name !== ''));
    return $names ? implode(', ', $names) : (string) ($transaction['invoice_no'] ?? 'Transaksi');
};
?>
<div class="space-y-6" x-data="{ txOpen: false, tx: 0, txInvoice: '' }">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-charcoal sm:text-3xl">Riwayat Transaksi</h1>
            <p class="mt-1 text-sm text-charcoal/55">Semua nota pembelian Anda tersimpan di sini.</p>
        </div>
        <div class="text-left sm:text-right">
            <p class="text-xs text-charcoal/50">Total belanja</p>
            <p class="font-serif text-2xl font-semibold text-charcoal"><?= esc(rupiah($totalSpent)) ?></p>
        </div>
    </div>

    <div class="hidden md:block">
        <div class="scrollbar-thin overflow-x-auto rounded-2xl border border-line bg-white">
            <table id="customer-history-table" class="w-full min-w-[720px] text-sm">
                <thead>
                    <tr class="border-b border-line bg-white">
                        <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-xs font-semibold text-charcoal/55">
                            <button type="button" data-sort="date" class="inline-flex min-h-10 items-center gap-1 rounded py-3 -my-3 transition-colors duration-150 hover:text-charcoal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">
                                Tanggal
                                <?= icon('chevrons-up-down', 'h-3.5 w-3.5 opacity-50', 14, 2) ?>
                            </button>
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-xs font-semibold text-charcoal/55">Produk</th>
                        <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right text-xs font-semibold text-charcoal/55">
                            <button type="button" data-sort="total" class="inline-flex min-h-10 items-center gap-1 rounded py-3 -my-3 transition-colors duration-150 hover:text-charcoal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold/50">
                                Total
                                <?= icon('chevrons-up-down', 'h-3.5 w-3.5 opacity-50', 14, 2) ?>
                            </button>
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-xs font-semibold text-charcoal/55">Metode Bayar</th>
                        <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right text-xs font-semibold text-charcoal/55">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="5" class="p-0">
                                <?= view('components/customer/empty_state', [
                                    'heading' => 'Belum ada transaksi',
                                    'description' => 'Transaksi di toko akan tercatat otomatis di akun Anda.',
                                    'compact' => true,
                                ]) ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $index => $transaction): ?>
                            <?php
                            $transactionId = (int) ($transaction['id'] ?? 0);
                            $invoiceNo = (string) ($transaction['invoice_no'] ?? '-');
                            $dateValue = (string) ($transaction['transaction_date'] ?? '');
                            ?>
                            <tr
                                data-date="<?= esc((string) strtotime($dateValue), 'attr') ?>"
                                data-total="<?= esc((string) (float) ($transaction['total'] ?? 0), 'attr') ?>"
                                class="border-b border-line/70 transition-colors duration-150 last:border-0 hover:bg-gold/10 <?= $index % 2 === 1 ? 'bg-ivory' : 'bg-white' ?>"
                            >
                                <td class="whitespace-nowrap px-4 py-3.5 text-charcoal/85"><?= esc(format_date($dateValue)) ?></td>
                                <td class="px-4 py-3.5 text-charcoal/85">
                                    <span class="font-medium text-charcoal"><?= esc($transactionProducts($transaction)) ?></span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-right font-semibold text-charcoal"><?= esc(rupiah((float) ($transaction['total'] ?? 0))) ?></td>
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <?= view('components/customer/payment_badge', ['method' => $transaction['payment_method'] ?? 'Tunai']) ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                    <?= ui_button([
                                        'type' => 'button',
                                        'content' => 'Detail',
                                        'icon' => 'eye',
                                        'variant' => 'light',
                                        'size' => 'sm',
                                        'attrs' => [
                                            '@click' => 'tx = ' . $transactionId . "; txInvoice = '" . esc($invoiceNo, 'js') . "'; txOpen = true",
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="md:hidden">
        <?php if (empty($transactions)): ?>
            <div class="overflow-hidden rounded-2xl border border-line bg-white">
                <?= view('components/customer/empty_state', ['heading' => 'Belum ada transaksi', 'compact' => true]) ?>
            </div>
        <?php else: ?>
            <ul class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                <?php foreach ($transactions as $transaction): ?>
                    <?php
                    $transactionId = (int) ($transaction['id'] ?? 0);
                    $invoiceNo = (string) ($transaction['invoice_no'] ?? '-');
                    ?>
                    <li>
                        <button
                            type="button"
                            @click="tx = <?= $transactionId ?>; txInvoice = '<?= esc($invoiceNo, 'js') ?>'; txOpen = true"
                            class="flex w-full items-center gap-3 px-4 py-3.5 text-left transition-colors duration-150 hover:bg-ivory focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold/40"
                        >
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-charcoal"><?= esc($transactionProducts($transaction)) ?></p>
                                <p class="mt-0.5 text-xs text-charcoal/45">
                                    <?= esc(format_date((string) ($transaction['transaction_date'] ?? ''))) ?>
                                    &middot;
                                    <?= esc($transaction['payment_method'] ?? 'Tunai') ?>
                                </p>
                            </div>
                            <span class="shrink-0 text-sm font-semibold"><?= esc(rupiah((float) ($transaction['total'] ?? 0))) ?></span>
                            <?= icon('chevron-right', 'h-4 w-4 shrink-0 text-charcoal/30', 16, 2) ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if (($result['total_pages'] ?? 0) > 1): ?>
        <?= view('components/ui/pagination', [
            'currentPage' => (int) ($result['page'] ?? 1),
            'totalPages' => (int) $result['total_pages'],
            'total' => (int) ($result['total'] ?? 0),
            'perPage' => (int) ($result['per_page'] ?? 20),
            'baseUrl' => base_url('pelanggan/riwayat'),
        ]) ?>
    <?php endif; ?>

    <!-- Transaction detail modal -->
    <div
        x-show="txOpen"
        x-cloak
        @keydown.escape.window="txOpen = false"
        class="fixed inset-0 z-[60] flex items-end justify-center p-0 sm:items-center sm:p-6"
        style="display: none;"
    >
        <div
            x-show="txOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="txOpen = false"
            class="absolute inset-0 bg-charcoal/60 backdrop-blur-[2px]"
        ></div>

        <div
            x-show="txOpen"
            x-transition:enter="transition ease-out duration-220"
            x-transition:enter-start="opacity-0 scale-96 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-96 translate-y-3"
            role="dialog"
            aria-modal="true"
            aria-labelledby="tx-modal-title"
            class="relative flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl"
            @click.stop
        >
            <div class="flex items-start justify-between gap-4 border-b border-line px-6 py-5">
                <div>
                    <h2 id="tx-modal-title" class="font-serif text-xl font-semibold text-charcoal">Detail Transaksi</h2>
                    <p class="mt-1 text-sm text-charcoal/60" x-text="txInvoice"></p>
                </div>
                <?= ui_button([
                    'type' => 'button',
                    'icon' => 'x',
                    'iconOnly' => true,
                    'variant' => 'ghost',
                    'size' => 'md',
                    'ariaLabel' => 'Tutup',
                    'attrs' => ['@click' => 'txOpen = false'],
                ]) ?>
            </div>

            <div class="scrollbar-thin flex-1 overflow-y-auto px-6 py-5">
                <?php if (empty($transactions)): ?>
                    <p class="text-sm text-charcoal/55">Belum ada transaksi.</p>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <div x-show="tx === <?= (int) ($transaction['id'] ?? 0) ?>">
                            <?= view('components/customer/transaction_detail_content', ['transaction' => $transaction]) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-line bg-ivory/60 px-6 py-4 sm:flex-row sm:justify-end">
                <?= ui_button([
                    'type' => 'button',
                    'content' => 'Tutup',
                    'variant' => 'light',
                    'size' => 'md',
                    'attrs' => ['@click' => 'txOpen = false'],
                ]) ?>
                <?= ui_button([
                    'type' => 'button',
                    'content' => 'Cetak Nota',
                    'icon' => 'printer',
                    'variant' => 'primary',
                    'size' => 'md',
                    'attrs' => ['@click' => 'window.print()'],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    (function () {
        var table = document.getElementById('customer-history-table');
        if (!table || !table.tBodies.length) return;

        var tbody = table.tBodies[0];
        var buttons = table.querySelectorAll('th [data-sort]');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var key = button.dataset.sort;
                var dir = button.dataset.dir === 'asc' ? 'desc' : 'asc';

                buttons.forEach(function (other) {
                    delete other.dataset.dir;
                    var header = other.closest('th');
                    if (header) header.removeAttribute('aria-sort');
                });

                button.dataset.dir = dir;
                var activeHeader = button.closest('th');
                if (activeHeader) activeHeader.setAttribute('aria-sort', dir === 'asc' ? 'ascending' : 'descending');

                var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr[data-' + key + ']'));
                rows.sort(function (a, b) {
                    var left = parseFloat(a.dataset[key]) || 0;
                    var right = parseFloat(b.dataset[key]) || 0;
                    return (left - right) * (dir === 'asc' ? 1 : -1);
                });
                rows.forEach(function (row) { tbody.appendChild(row); });
            });
        });
    })();
</script>
<?= $this->endSection() ?>
