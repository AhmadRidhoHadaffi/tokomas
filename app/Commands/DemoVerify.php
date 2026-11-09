<?php

namespace App\Commands;

use App\Database\Seeds\Support\DemoPlan;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DemoVerify extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'demo:verify';
    protected $description = 'Verify demo row counts and data invariants.';
    protected $usage = 'demo:verify';
    protected $arguments = [];
    protected $options = [];

    private array $failures = [];

    public function run(array $params)
    {
        $db = db_connect();

        $customers = $db->table('customers')->like('email', '@demo.test', 'before')->countAllResults();
        $transactions = $db->table('transactions')->like('invoice_no', 'DM-', 'after')->countAllResults();
        $orders = $db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->countAllResults();
        $promotions = $db->table('promotions')->like('title', '[DEMO]', 'after')->countAllResults();

        $expectedDetails = 0;
        foreach (DemoPlan::transactions() as $transaction) {
            $expectedDetails += count($transaction['items']);
        }

        $expectedHistories = 0;
        foreach (DemoPlan::customOrders() as $order) {
            $expectedHistories += array_search($order['status'], ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'], true) + 1;
        }

        $counts = [
            'users @demo.test' => [$db->table('users')->like('email', '@demo.test', 'before')->countAllResults(), 16],
            'customers @demo.test' => [$customers, 15],
            'products DM-*' => [$db->table('products')->like('code', 'DM-', 'after')->countAllResults(), 36],
            'transactions DM-*' => [$transactions, 80],
            'transaction_details demo' => [
                $db->table('transaction_details as td')->join('transactions as t', 't.id = td.transaction_id')->like('t.invoice_no', 'DM-', 'after')->countAllResults(),
                $expectedDetails,
            ],
            'custom_orders PD-2026-*' => [$orders, 18],
            'custom_order_histories demo' => [
                $db->table('custom_order_histories as h')->join('custom_orders as o', 'o.id = h.custom_order_id')->like('o.order_no', 'PD-2026-', 'after')->countAllResults(),
                $expectedHistories,
            ],
            'promotions [DEMO]' => [$promotions, 7],
            'promotion_customers demo' => [
                $db->table('promotion_customers as pc')->join('promotions as p', 'p.id = pc.promotion_id')->like('p.title', '[DEMO]', 'after')->countAllResults(),
                9,
            ],
            'store_settings' => [$db->table('store_settings')->countAllResults(), 1],
            'gold_prices' => [$db->table('gold_prices')->countAllResults(), 4],
        ];

        $this->section('Row counts');
        foreach ($counts as $label => [$actual, $expected]) {
            $this->check($actual === $expected, $label, "expected {$expected}, got {$actual}");
        }

        $this->section('Invariants');
        $this->checkNegativeStock($db);
        $this->checkTransactionTotals($db);
        $this->checkOrderHistories($db);
        $this->checkOrderFinances($db);
        $this->checkNotificationTimestamps($db);
        $this->checkOrphans($db);
        $this->checkCustomerLinks($db);
        $this->checkPasswords($db);
        $this->checkFiles($db);
        $this->checkNotificationMix($db);

        CLI::newLine();

        if ($this->failures !== []) {
            CLI::error('FAILED: ' . count($this->failures) . ' check(s) failed.');
            foreach ($this->failures as $failure) {
                CLI::write('  - ' . $failure, 'red');
            }

            return EXIT_ERROR;
        }

        CLI::write('All demo checks passed.', 'green');

        return EXIT_SUCCESS;
    }

    private function section(string $title): void
    {
        CLI::newLine();
        CLI::write('== ' . $title . ' ==', 'yellow');
    }

    private function check(bool $condition, string $label, string $detail = ''): void
    {
        if ($condition) {
            CLI::write('  [PASS] ' . $label, 'green');

            return;
        }

        $message = $label . ($detail !== '' ? ' (' . $detail . ')' : '');
        $this->failures[] = $message;
        CLI::write('  [FAIL] ' . $message, 'red');
    }

    private function checkNegativeStock(object $db): void
    {
        $count = $db->table('products')->like('code', 'DM-', 'after')->where('stock <', 0)->countAllResults();
        $this->check($count === 0, 'No negative demo stock', "{$count} row(s)");
    }

    private function checkTransactionTotals(object $db): void
    {
        $rows = $db->query(
            "SELECT t.invoice_no, t.discount, t.total, COALESCE(SUM(d.price * d.quantity), 0) AS subtotal
             FROM transactions t
             LEFT JOIN transaction_details d ON d.transaction_id = t.id
             WHERE t.invoice_no LIKE 'DM-%'
             GROUP BY t.id, t.invoice_no, t.discount, t.total"
        )->getResultArray();

        $bad = [];
        foreach ($rows as $row) {
            $expected = (float) $row['subtotal'] - (float) $row['discount'];
            if (abs($expected - (float) $row['total']) > 0.01) {
                $bad[] = $row['invoice_no'];
            }
        }

        $this->check($bad === [], 'Transaction total = subtotal - discount', implode(', ', array_slice($bad, 0, 5)));

        $zeroQty = $db->table('transaction_details as td')
            ->join('transactions as t', 't.id = td.transaction_id')
            ->like('t.invoice_no', 'DM-', 'after')
            ->where('td.quantity <=', 0)
            ->countAllResults();
        $this->check($zeroQty === 0, 'Transaction detail quantity > 0', "{$zeroQty} row(s)");
    }

    private function checkOrderHistories(object $db): void
    {
        $orders = $db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->get()->getResultArray();
        $sequence = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];
        $bad = [];

        foreach ($orders as $order) {
            $histories = $db->table('custom_order_histories')
                ->where('custom_order_id', $order['id'])
                ->orderBy('created_at', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            $statuses = array_column($histories, 'status');
            $expected = array_slice($sequence, 0, array_search($order['status'], $sequence, true) + 1);

            if ($statuses !== $expected) {
                $bad[] = $order['order_no'];
                continue;
            }

            if (end($statuses) !== $order['status']) {
                $bad[] = $order['order_no'] . ' (last status mismatch)';
            }

            foreach ($histories as $history) {
                if (strtotime((string) $history['created_at']) < strtotime('2020-01-01')) {
                    $bad[] = $order['order_no'] . ' (invalid created_at)';
                    break;
                }
            }
        }

        $this->check($bad === [], 'Every order has full status history', implode(', ', array_slice($bad, 0, 5)));
    }

    private function checkOrderFinances(object $db): void
    {
        $orders = $db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->get()->getResultArray();
        $bad = [];

        foreach ($orders as $order) {
            if ((float) $order['dp'] > 0) {
                $dp = $db->table('finances')
                    ->where('custom_order_id', $order['id'])
                    ->where('category', 'DP Pesanan Custom')
                    ->get(1)
                    ->getRowArray();

                if (! $dp || abs((float) $dp['amount'] - (float) $order['dp']) > 0.01) {
                    $bad[] = $order['order_no'] . ' (DP)';
                }
            }

            if ($order['status'] === 'Diambil') {
                $remaining = (float) $order['estimated_price'] - (float) $order['dp'];
                $settlement = $db->table('finances')
                    ->where('custom_order_id', $order['id'])
                    ->where('category', 'Pelunasan Custom')
                    ->get(1)
                    ->getRowArray();

                if ($remaining > 0 && (! $settlement || abs((float) $settlement['amount'] - $remaining) > 0.01)) {
                    $bad[] = $order['order_no'] . ' (pelunasan)';
                }
            }
        }

        $this->check($bad === [], 'DP/pelunasan finance rows match orders', implode(', ', array_slice($bad, 0, 5)));

        $lunas = $db->table('transactions')->like('invoice_no', 'DM-', 'after')->where('payment_status', 'Lunas')->countAllResults();
        $income = $db->table('finances')->where('category', 'Penjualan')->like('description', 'Penjualan DM-', 'after')->countAllResults();
        $this->check($income === $lunas, 'One sales income row per paid demo transaction', "expected {$lunas}, got {$income}");
    }

    private function checkNotificationTimestamps(object $db): void
    {
        $zero = $db->table('notifications')
            ->groupStart()
            ->where('created_at', null)
            ->orWhere('created_at', '0000-00-00 00:00:00')
            ->groupEnd()
            ->countAllResults();
        $this->check($zero === 0, 'Notifications have valid created_at', "{$zero} row(s)");

        $dupes = $db->query(
            "SELECT COUNT(*) AS total FROM (
                SELECT customer_id, promotion_id, title, COUNT(*) AS n
                FROM notifications
                WHERE promotion_id IS NOT NULL
                GROUP BY customer_id, promotion_id, title
                HAVING n > 1
            ) dupes"
        )->getRowArray();
        $this->check((int) ($dupes['total'] ?? 0) === 0, 'No duplicate promotion notifications');
    }

    private function checkOrphans(object $db): void
    {
        $checks = [
            'transactions → customers' => 'SELECT COUNT(*) c FROM transactions t LEFT JOIN customers x ON x.id = t.customer_id WHERE x.id IS NULL',
            'transactions → users' => 'SELECT COUNT(*) c FROM transactions t LEFT JOIN users x ON x.id = t.user_id WHERE x.id IS NULL',
            'transaction_details → transactions' => 'SELECT COUNT(*) c FROM transaction_details d LEFT JOIN transactions x ON x.id = d.transaction_id WHERE x.id IS NULL',
            'transaction_details → products' => 'SELECT COUNT(*) c FROM transaction_details d LEFT JOIN products x ON x.id = d.product_id WHERE x.id IS NULL',
            'custom_orders → customers' => 'SELECT COUNT(*) c FROM custom_orders o LEFT JOIN customers x ON x.id = o.customer_id WHERE x.id IS NULL',
            'custom_order_histories → orders' => 'SELECT COUNT(*) c FROM custom_order_histories h LEFT JOIN custom_orders x ON x.id = h.custom_order_id WHERE x.id IS NULL',
            'custom_order_histories → users' => 'SELECT COUNT(*) c FROM custom_order_histories h LEFT JOIN users x ON x.id = h.changed_by WHERE x.id IS NULL',
            'finances → users' => 'SELECT COUNT(*) c FROM finances f LEFT JOIN users x ON x.id = f.user_id WHERE x.id IS NULL',
            'finances → transactions' => 'SELECT COUNT(*) c FROM finances f LEFT JOIN transactions x ON x.id = f.transaction_id WHERE f.transaction_id IS NOT NULL AND x.id IS NULL',
            'finances → custom_orders' => 'SELECT COUNT(*) c FROM finances f LEFT JOIN custom_orders x ON x.id = f.custom_order_id WHERE f.custom_order_id IS NOT NULL AND x.id IS NULL',
            'promotions → users' => 'SELECT COUNT(*) c FROM promotions p LEFT JOIN users x ON x.id = p.user_id WHERE x.id IS NULL',
            'promotion_customers → promotions' => 'SELECT COUNT(*) c FROM promotion_customers pc LEFT JOIN promotions x ON x.id = pc.promotion_id WHERE x.id IS NULL',
            'promotion_customers → customers' => 'SELECT COUNT(*) c FROM promotion_customers pc LEFT JOIN customers x ON x.id = pc.customer_id WHERE x.id IS NULL',
            'notifications → customers' => 'SELECT COUNT(*) c FROM notifications n LEFT JOIN customers x ON x.id = n.customer_id WHERE x.id IS NULL',
            'notifications → promotions' => 'SELECT COUNT(*) c FROM notifications n LEFT JOIN promotions x ON x.id = n.promotion_id WHERE n.promotion_id IS NOT NULL AND x.id IS NULL',
            'gold_prices → users' => 'SELECT COUNT(*) c FROM gold_prices g LEFT JOIN users x ON x.id = g.updated_by WHERE x.id IS NULL',
        ];

        foreach ($checks as $label => $sql) {
            $row = $db->query($sql)->getRowArray();
            $this->check((int) ($row['c'] ?? 0) === 0, 'No orphan: ' . $label, (string) ($row['c'] ?? 0));
        }
    }

    private function checkCustomerLinks(object $db): void
    {
        $bad = $db->query(
            "SELECT COUNT(*) c FROM customers c LEFT JOIN users u ON u.id = c.user_id
             WHERE c.email LIKE '%@demo.test' AND c.user_id IS NOT NULL AND (u.id IS NULL OR u.role <> 'pelanggan')"
        )->getRowArray();
        $this->check((int) ($bad['c'] ?? 0) === 0, 'Demo customer user_id points to a pelanggan');
    }

    private function checkPasswords(object $db): void
    {
        $users = $db->table('users')->like('email', '@demo.test', 'before')->get()->getResultArray();
        $bad = [];

        foreach ($users as $user) {
            if (! password_verify(DemoPlan::PASSWORD, $user['password'])) {
                $bad[] = $user['email'];
            }
        }

        $this->check($bad === [], 'All demo users share the demo password', implode(', ', $bad));
    }

    private function checkFiles(object $db): void
    {
        $products = $db->table('products')->like('code', 'DM-', 'after')->where('image IS NOT NULL')->get()->getResultArray();
        $missing = [];

        foreach ($products as $product) {
            if (! is_file(FCPATH . 'assets/img/products/' . $product['image'])) {
                $missing[] = $product['image'];
            }
        }

        $this->check($missing === [], 'Product images exist', implode(', ', $missing));

        $orders = $db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->where('reference_image IS NOT NULL')->get()->getResultArray();
        $missingOrders = [];

        foreach ($orders as $order) {
            if (! is_file(FCPATH . 'uploads/custom-orders/' . $order['reference_image'])) {
                $missingOrders[] = $order['reference_image'];
            }
        }

        $this->check($missingOrders === [], 'Custom order reference images exist', implode(', ', array_unique($missingOrders)));
    }

    private function checkNotificationMix(object $db): void
    {
        $unread = $db->table('notifications as n')
            ->join('customers as c', 'c.id = n.customer_id')
            ->like('c.email', '@demo.test', 'before')
            ->where('n.is_read', 0)
            ->countAllResults();
        $read = $db->table('notifications as n')
            ->join('customers as c', 'c.id = n.customer_id')
            ->like('c.email', '@demo.test', 'before')
            ->where('n.is_read', 1)
            ->countAllResults();

        $this->check($unread > 0, 'Demo notifications include unread rows', "{$unread} unread");
        $this->check($read > 0, 'Demo notifications include read rows', "{$read} read");
    }
}
