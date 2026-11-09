<?php

namespace App\Database\Seeds\Support;

/**
 * Deterministic demo data plan.
 *
 * All seeders read from this class so products, transactions and orders stay
 * consistent between runs (and when calculating reserved stock).
 *
 * The anchor date keeps the generated invoice/order numbers stable, which is
 * what makes DemoSeeder idempotent across runs.
 */
class DemoPlan
{
    public const ANCHOR = '2026-11-09';
    public const PASSWORD = 'Gembira#2026';

    public static function date(string $modifier = ''): string
    {
        $ts = strtotime(self::ANCHOR . ' 10:00:00' . ($modifier !== '' ? ' ' . $modifier : ''));

        return date('Y-m-d', $ts);
    }

    public static function datetime(string $modifier = '', string $time = '10:00:00'): string
    {
        return self::date($modifier) . ' ' . $time;
    }

    /** @return array<int, array<string, mixed>> */
    public static function users(): array
    {
        return [
            ['name' => 'Hj. Rosmaniar', 'email' => 'owner@demo.test', 'role' => 'owner', 'is_active' => 1, 'offset' => 400],
            ['name' => 'Rina Marlina', 'email' => 'rina@demo.test', 'role' => 'karyawan', 'is_active' => 1, 'offset' => 380],
            ['name' => 'Fajar Ramadhan', 'email' => 'fajar@demo.test', 'role' => 'karyawan', 'is_active' => 1, 'offset' => 300],
            ['name' => 'Yuni Astuti', 'email' => 'yuni@demo.test', 'role' => 'karyawan', 'is_active' => 0, 'offset' => 240],

            ['name' => 'Siti Rahmawati', 'email' => 'siti@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 210],
            ['name' => 'Andi Pratama', 'email' => 'andi@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 200],
            ['name' => 'Dewi Anggraini', 'email' => 'dewi@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 5],
            ['name' => 'Rizky Saputra', 'email' => 'rizky@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 190],
            ['name' => 'Putri Ayu Lestari', 'email' => 'putri@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 170],
            ['name' => 'Bayu Kurniawan', 'email' => 'bayu@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 150],
            ['name' => 'Nisa Fadillah', 'email' => 'nisa@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 120],
            ['name' => 'Arif Hidayat', 'email' => 'arif@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 95],
            ['name' => 'Melati Sari', 'email' => 'melati@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 70],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 45],
            ['name' => 'Sari Indah', 'email' => 'sari@demo.test', 'role' => 'pelanggan', 'is_active' => 1, 'offset' => 20],
            ['name' => 'Doni Firmansyah', 'email' => 'doni@demo.test', 'role' => 'pelanggan', 'is_active' => 0, 'offset' => 10],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function customers(): array
    {
        // 0813-prefixed numbers keep demo customers clear of the legacy
        // Siti Rahmawati row (081277881020) that predates the demo dataset.
        $phones = [
            '081377881020', '081377881021', '081377881022', '081377881023',
            '081377881024', '081377881025', '081377881026', '081377881027',
            '081377881028', '081377881029', '081377881030', '081377881031',
            '081377881032', '081377881033', '081377881034',
        ];
        $addresses = [
            'Jl. Sudirman No. 10, Padang',
            'Jl. Khatib Sulaiman No. 21, Padang',
            'Jl. By Pass Km 8, Padang',
            'Perumahan Pondok Indah Blok C/5, Padang',
            'Jl. Veteran No. 44, Padang',
            'Jl. Pemuda No. 7, Padang',
            'Jl. H. Agus Salim No. 12, Padang',
            'Jl. Belanti Indah No. 3, Padang',
            'Jl. Gajah Mada No. 88, Padang',
            'Jl. Purus IV No. 9, Padang',
            'Jl. Sawahan No. 15, Padang',
            'Jl. Marapalam No. 6, Padang',
            null, null, null,
        ];

        $users = self::users();
        $customers = [];

        foreach (array_slice($users, 4) as $i => $user) {
            $customers[] = [
                'user_email' => $user['email'],
                'name' => $user['name'],
                'phone' => $phones[$i],
                'email' => $user['email'],
                'address' => $addresses[$i],
                'offset' => $user['offset'],
            ];
        }

        for ($i = 12; $i < 15; $i++) {
            $customers[] = [
                'user_email' => null,
                'name' => 'Pelanggan Walk-in ' . ($i - 11),
                'phone' => $phones[$i],
                'email' => 'walkin' . ($i - 11) . '@demo.test',
                'address' => null,
                'offset' => 150 - ($i * 3),
            ];
        }

        return $customers;
    }

    /** @return array<int, array<string, mixed>> */
    public static function products(): array
    {
        return [
            ['code' => 'DM-CI-01', 'name' => 'Cincin Filigree Minang', 'category' => 'Cincin', 'purity' => '22K', 'weight' => 3.20, 'size' => '14', 'model' => 'Filigree', 'price' => 5280000, 'stock' => 6, 'image' => 'product-1.jpg'],
            ['code' => 'DM-CI-02', 'name' => 'Cincin Batang Pinang', 'category' => 'Cincin', 'purity' => '24K', 'weight' => 4.10, 'size' => '16', 'model' => 'Batang Pinang', 'price' => 7150000, 'stock' => 2, 'image' => null],
            ['code' => 'DM-CI-03', 'name' => 'Cincin Bunga Mawar', 'category' => 'Cincin', 'purity' => '18K', 'weight' => 2.80, 'size' => '13', 'model' => 'Mawar', 'price' => 3720000, 'stock' => 0, 'image' => null],
            ['code' => 'DM-CI-04', 'name' => 'Cincin Akik Perak', 'category' => 'Cincin', 'purity' => 'Perak 925', 'weight' => 3.50, 'size' => '17', 'model' => 'Akik', 'price' => 520000, 'stock' => 12, 'image' => null],
            ['code' => 'DM-CI-05', 'name' => 'Cincin Tunangan Kembar', 'category' => 'Cincin', 'purity' => '17K', 'weight' => 5.20, 'size' => '15', 'model' => 'Kembar', 'price' => 5850000, 'stock' => 3, 'image' => null],
            ['code' => 'DM-CI-06', 'name' => 'Cincin Ukir Rumah Gadang', 'category' => 'Cincin', 'purity' => '22K', 'weight' => 4.60, 'size' => '18', 'model' => 'Ukir', 'price' => 8050000, 'stock' => 8, 'image' => null],
            ['code' => 'DM-CI-07', 'name' => 'Cincin Perak Minimalis', 'category' => 'Cincin', 'purity' => 'Perak 925', 'weight' => 2.10, 'size' => '12', 'model' => 'Minimalis', 'price' => 310000, 'stock' => 20, 'image' => null, 'deleted' => true],
            ['code' => 'DM-CI-08', 'name' => 'Cincin Berlian Tiruan', 'category' => 'Cincin', 'purity' => '18K', 'weight' => 3.30, 'size' => '14', 'model' => 'Berlian', 'price' => 4950000, 'stock' => 1, 'image' => null],

            ['code' => 'DM-KL-01', 'name' => 'Kalung Rumah Gadang', 'category' => 'Kalung', 'purity' => '24K', 'weight' => 8.50, 'size' => null, 'model' => 'Rumah Gadang', 'price' => 14450000, 'stock' => 4, 'image' => 'product-2.jpg'],
            ['code' => 'DM-KL-02', 'name' => 'Kalung Rantai Singgalang', 'category' => 'Kalung', 'purity' => '22K', 'weight' => 6.20, 'size' => null, 'model' => 'Rantai', 'price' => 9850000, 'stock' => 3, 'image' => null],
            ['code' => 'DM-KL-03', 'name' => 'Kalung Bunga Melati', 'category' => 'Kalung', 'purity' => '18K', 'weight' => 5.40, 'size' => null, 'model' => 'Melati', 'price' => 6480000, 'stock' => 0, 'image' => null],
            ['code' => 'DM-KL-04', 'name' => 'Kalung Perak Sulawesi', 'category' => 'Kalung', 'purity' => 'Perak 925', 'weight' => 7.00, 'size' => null, 'model' => 'Sulawesi', 'price' => 850000, 'stock' => 7, 'image' => null],
            ['code' => 'DM-KL-05', 'name' => 'Kalung Liontin Hati', 'category' => 'Kalung', 'purity' => '17K', 'weight' => 3.80, 'size' => null, 'model' => 'Hati', 'price' => 4180000, 'stock' => 5, 'image' => null],
            ['code' => 'DM-KL-06', 'name' => 'Kalung Adat Minang', 'category' => 'Kalung', 'purity' => '24K', 'weight' => 10.10, 'size' => null, 'model' => 'Adat', 'price' => 17200000, 'stock' => 2, 'image' => null],
            ['code' => 'DM-KL-07', 'name' => 'Kalung Choker Modern', 'category' => 'Kalung', 'purity' => '18K', 'weight' => 4.70, 'size' => null, 'model' => 'Choker', 'price' => 5640000, 'stock' => 9, 'image' => null],
            ['code' => 'DM-KL-08', 'name' => 'Kalung Perak Klasik', 'category' => 'Kalung', 'purity' => 'Perak 925', 'weight' => 5.60, 'size' => null, 'model' => 'Klasik', 'price' => 690000, 'stock' => 15, 'image' => null, 'deleted' => true],

            ['code' => 'DM-GL-01', 'name' => 'Gelang Bunga Cengkeh', 'category' => 'Gelang', 'purity' => '18K', 'weight' => 12.00, 'size' => null, 'model' => 'Bunga', 'price' => 12600000, 'stock' => 3, 'image' => 'product-3.jpg'],
            ['code' => 'DM-GL-02', 'name' => 'Gelang Rantai Padang', 'category' => 'Gelang', 'purity' => '22K', 'weight' => 9.50, 'size' => null, 'model' => 'Rantai', 'price' => 12350000, 'stock' => 4, 'image' => null],
            ['code' => 'DM-GL-03', 'name' => 'Gelang Perak Ukir', 'category' => 'Gelang', 'purity' => 'Perak 925', 'weight' => 8.20, 'size' => null, 'model' => 'Ukir', 'price' => 980000, 'stock' => 10, 'image' => null],
            ['code' => 'DM-GL-04', 'name' => 'Gelang Kaki Bayi', 'category' => 'Gelang', 'purity' => '17K', 'weight' => 2.40, 'size' => null, 'model' => 'Bayi', 'price' => 2650000, 'stock' => 6, 'image' => null],
            ['code' => 'DM-GL-05', 'name' => 'Gelang Tali Emas', 'category' => 'Gelang', 'purity' => '24K', 'weight' => 5.00, 'size' => null, 'model' => 'Tali', 'price' => 8750000, 'stock' => 0, 'image' => null],
            ['code' => 'DM-GL-06', 'name' => 'Gelang Kerawang', 'category' => 'Gelang', 'purity' => '18K', 'weight' => 10.80, 'size' => null, 'model' => 'Kerawang', 'price' => 11450000, 'stock' => 2, 'image' => null],
            ['code' => 'DM-GL-07', 'name' => 'Gelang Perak Minimalis', 'category' => 'Gelang', 'purity' => 'Perak 925', 'weight' => 6.40, 'size' => null, 'model' => 'Minimalis', 'price' => 720000, 'stock' => 18, 'image' => null],

            ['code' => 'DM-AN-01', 'name' => 'Anting Perak Pauh Kacang', 'category' => 'Anting', 'purity' => 'Perak 925', 'weight' => 2.50, 'size' => null, 'model' => 'Pauh Kacang', 'price' => 450000, 'stock' => 15, 'image' => null],
            ['code' => 'DM-AN-02', 'name' => 'Anting Emas Bulan', 'category' => 'Anting', 'purity' => '22K', 'weight' => 1.80, 'size' => null, 'model' => 'Bulan', 'price' => 2650000, 'stock' => 5, 'image' => 'product-4.jpg'],
            ['code' => 'DM-AN-03', 'name' => 'Anting Juntai Melati', 'category' => 'Anting', 'purity' => '18K', 'weight' => 2.20, 'size' => null, 'model' => 'Juntai', 'price' => 2980000, 'stock' => 3, 'image' => null],
            ['code' => 'DM-AN-04', 'name' => 'Anting Tusuk Emas', 'category' => 'Anting', 'purity' => '24K', 'weight' => 1.50, 'size' => null, 'model' => 'Tusuk', 'price' => 2850000, 'stock' => 0, 'image' => null],
            ['code' => 'DM-AN-05', 'name' => 'Anting Perak Bintang', 'category' => 'Anting', 'purity' => 'Perak 925', 'weight' => 1.90, 'size' => null, 'model' => 'Bintang', 'price' => 390000, 'stock' => 22, 'image' => null],
            ['code' => 'DM-AN-06', 'name' => 'Anting Gantung Adat', 'category' => 'Anting', 'purity' => '17K', 'weight' => 2.80, 'size' => null, 'model' => 'Gantung', 'price' => 3250000, 'stock' => 1, 'image' => null],
            ['code' => 'DM-AN-07', 'name' => 'Anting Kancing Emas', 'category' => 'Anting', 'purity' => '22K', 'weight' => 1.60, 'size' => null, 'model' => 'Kancing', 'price' => 2380000, 'stock' => 7, 'image' => null],

            ['code' => 'DM-LI-01', 'name' => 'Liontin Tanduk Kerbau', 'category' => 'Liontin', 'purity' => '17K', 'weight' => 5.50, 'size' => null, 'model' => 'Tanduk', 'price' => 4125000, 'stock' => 4, 'image' => null],
            ['code' => 'DM-LI-02', 'name' => 'Liontin Huruf G', 'category' => 'Liontin', 'purity' => '18K', 'weight' => 2.90, 'size' => null, 'model' => 'Huruf', 'price' => 3420000, 'stock' => 9, 'image' => null],
            ['code' => 'DM-LI-03', 'name' => 'Liontin Nyiur Melambai', 'category' => 'Liontin', 'purity' => '22K', 'weight' => 3.60, 'size' => null, 'model' => 'Nyiur', 'price' => 5260000, 'stock' => 2, 'image' => null],
            ['code' => 'DM-LI-04', 'name' => 'Liontin Perak Sirih', 'category' => 'Liontin', 'purity' => 'Perak 925', 'weight' => 4.20, 'size' => null, 'model' => 'Sirih', 'price' => 560000, 'stock' => 11, 'image' => null],
            ['code' => 'DM-LI-05', 'name' => 'Liontin Emas Bulan Sabit', 'category' => 'Liontin', 'purity' => '24K', 'weight' => 4.80, 'size' => null, 'model' => 'Bulan Sabit', 'price' => 7980000, 'stock' => 0, 'image' => null],
            ['code' => 'DM-LI-06', 'name' => 'Liontin Perak Minimalis', 'category' => 'Liontin', 'purity' => 'Perak 925', 'weight' => 2.60, 'size' => null, 'model' => 'Minimalis', 'price' => 340000, 'stock' => 16, 'image' => null],
        ];
    }

    /** @return array<string, int> sold quantity per product code */
    public static function soldQuantities(): array
    {
        $sold = [];
        foreach (self::transactions() as $transaction) {
            foreach ($transaction['items'] as $item) {
                $sold[$item['code']] = ($sold[$item['code']] ?? 0) + $item['quantity'];
            }
        }

        return $sold;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function transactions(): array
    {
        static $plan = null;
        if ($plan !== null) {
            return $plan;
        }

        $products = array_values(array_filter(self::products(), fn ($p) => empty($p['deleted'])));
        $customers = self::customers();
        $allowedCustomers = [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $cashiers = ['owner@demo.test', 'rina@demo.test', 'fajar@demo.test'];

        $seed = 20261109;
        $plan = [];
        $perDate = [];

        for ($i = 0; $i < 80; $i++) {
            // Six transactions on the anchor date so the dashboard has content.
            if ($i < 6) {
                $offset = 0;
            } else {
                $roll = self::rand($seed, 0, 10000) / 10000;
                $offset = (int) floor(180 * ($roll ** 1.7));
                $offset = max(1, $offset);
            }

            $date = self::date('-' . $offset . ' days');
            $perDate[$date] = ($perDate[$date] ?? 0) + 1;
            $invoice = 'DM-' . date('Ymd', strtotime($date)) . '-' . str_pad((string) $perDate[$date], 3, '0', STR_PAD_LEFT);

            $itemCount = self::rand($seed, 1, 4);
            $chosen = [];
            while (count($chosen) < $itemCount) {
                $index = self::rand($seed, 0, count($products) - 1);
                if (! isset($chosen[$index])) {
                    $chosen[$index] = true;
                }
            }

            $items = [];
            $subtotal = 0;
            foreach (array_keys($chosen) as $index) {
                $product = $products[$index];
                $quantity = self::rand($seed, 1, 2);
                $items[] = ['code' => $product['code'], 'quantity' => $quantity, 'price' => (int) $product['price']];
                $subtotal += (int) $product['price'] * $quantity;
            }

            $discount = 0;
            if (self::rand($seed, 1, 100) <= 28) {
                $rate = self::rand($seed, 2, 10) / 100;
                $discount = (int) (round(($subtotal * $rate) / 10000) * 10000);
                $discount = min($discount, $subtotal);
            }

            $methodRoll = self::rand($seed, 1, 100);
            $method = $methodRoll <= 45 ? 'Tunai' : ($methodRoll <= 75 ? 'Transfer' : 'QRIS');

            $status = self::rand($seed, 1, 100) <= 82 ? 'Lunas' : 'Menunggu';

            $customerIndex = $i % 6 === 0 ? 0 : $allowedCustomers[self::rand($seed, 0, count($allowedCustomers) - 1)];

            $plan[] = [
                'invoice_no' => $invoice,
                'date' => $date,
                'time' => sprintf('%02d:%02d:00', self::rand($seed, 9, 19), self::rand($seed, 0, 59)),
                'customer_index' => $customerIndex,
                'cashier_email' => $cashiers[self::rand($seed, 0, count($cashiers) - 1)],
                'items' => $items,
                'discount' => $discount,
                'subtotal' => $subtotal,
                'total' => $subtotal - $discount,
                'payment_method' => $method,
                'payment_status' => $status,
            ];
        }

        usort($plan, fn ($a, $b) => [$a['date'], $a['invoice_no']] <=> [$b['date'], $b['invoice_no']]);

        return $plan;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function customOrders(): array
    {
        return [
            ['order_no' => 'PD-2026-001', 'customer_index' => 0, 'jewelry_type' => 'Cincin', 'model' => 'Ukir Nama', 'engraving_name' => 'Siti & Andi', 'purity' => '22K', 'weight' => 4.20, 'estimated_price' => 7200000, 'dp' => 2000000, 'status' => 'Diambil', 'offset' => 140, 'due_offset' => -110, 'image' => 'demo-1.jpg', 'notes' => 'Ukiran nama pasangan, finishing matte.'],
            ['order_no' => 'PD-2026-002', 'customer_index' => 1, 'jewelry_type' => 'Kalung', 'model' => 'Rantai Adat', 'engraving_name' => null, 'purity' => '24K', 'weight' => 8.00, 'estimated_price' => 13500000, 'dp' => 5000000, 'status' => 'Diambil', 'offset' => 125, 'due_offset' => -95, 'image' => null, 'notes' => 'Panjang rantai 45 cm.'],
            ['order_no' => 'PD-2026-003', 'customer_index' => 3, 'jewelry_type' => 'Gelang', 'model' => 'Kerawang', 'engraving_name' => null, 'purity' => '18K', 'weight' => 11.50, 'estimated_price' => 12500000, 'dp' => 12500000, 'status' => 'Diambil', 'offset' => 110, 'due_offset' => -80, 'image' => 'demo-2.jpg', 'notes' => 'Lunas saat pemesanan.'],
            ['order_no' => 'PD-2026-004', 'customer_index' => 4, 'jewelry_type' => 'Anting', 'model' => 'Juntai Panjang', 'engraving_name' => null, 'purity' => '17K', 'weight' => 3.10, 'estimated_price' => 3600000, 'dp' => 0, 'status' => 'Selesai', 'offset' => 60, 'due_offset' => -8, 'image' => null, 'notes' => 'Belum diambil, sudah dihubungi.'],
            ['order_no' => 'PD-2026-005', 'customer_index' => 0, 'jewelry_type' => 'Liontin', 'model' => 'Huruf S', 'engraving_name' => 'Siti', 'purity' => '18K', 'weight' => 2.80, 'estimated_price' => 3300000, 'dp' => 1000000, 'status' => 'Selesai', 'offset' => 48, 'due_offset' => -5, 'image' => 'demo-3.jpg', 'notes' => 'Huruf timbul, poles mengkilap.'],
            ['order_no' => 'PD-2026-006', 'customer_index' => 5, 'jewelry_type' => 'Cincin', 'model' => 'Kembar', 'engraving_name' => 'B & N', 'purity' => '24K', 'weight' => 5.00, 'estimated_price' => 9200000, 'dp' => 3000000, 'status' => 'Selesai', 'offset' => 40, 'due_offset' => 3, 'image' => null, 'notes' => 'Jatuh tempo dekat.'],
            ['order_no' => 'PD-2026-007', 'customer_index' => 6, 'jewelry_type' => 'Kalung', 'model' => 'Choker Modern', 'engraving_name' => null, 'purity' => '18K', 'weight' => 5.20, 'estimated_price' => 5900000, 'dp' => 1500000, 'status' => 'Dalam Pembuatan', 'offset' => 32, 'due_offset' => 6, 'image' => null, 'notes' => null],
            ['order_no' => 'PD-2026-008', 'customer_index' => 7, 'jewelry_type' => 'Gelang', 'model' => 'Rantai Padang', 'engraving_name' => null, 'purity' => '22K', 'weight' => 9.00, 'estimated_price' => 11800000, 'dp' => 4000000, 'status' => 'Dalam Pembuatan', 'offset' => 27, 'due_offset' => -2, 'image' => 'demo-1.jpg', 'notes' => 'Lewat jatuh tempo, prioritas pengrajin.'],
            ['order_no' => 'PD-2026-009', 'customer_index' => 8, 'jewelry_type' => 'Anting', 'model' => 'Bulan', 'engraving_name' => null, 'purity' => 'Perak 925', 'weight' => 2.20, 'estimated_price' => 480000, 'dp' => 100000, 'status' => 'Dalam Pembuatan', 'offset' => 21, 'due_offset' => 9, 'image' => null, 'notes' => null],
            ['order_no' => 'PD-2026-010', 'customer_index' => 9, 'jewelry_type' => 'Liontin', 'model' => 'Nyiur', 'engraving_name' => null, 'purity' => '22K', 'weight' => 3.70, 'estimated_price' => 5400000, 'dp' => 2000000, 'status' => 'Dalam Pembuatan', 'offset' => 15, 'due_offset' => 12, 'image' => 'demo-2.jpg', 'notes' => 'Referensi foto dari pelanggan.'],
            ['order_no' => 'PD-2026-011', 'customer_index' => 10, 'jewelry_type' => 'Cincin', 'model' => 'Filigree', 'engraving_name' => null, 'purity' => '18K', 'weight' => 2.90, 'estimated_price' => 3850000, 'dp' => 0, 'status' => 'Diproses', 'offset' => 9, 'due_offset' => 18, 'image' => null, 'notes' => 'Menunggu konfirmasi desain.'],
            ['order_no' => 'PD-2026-012', 'customer_index' => 0, 'jewelry_type' => 'Gelang', 'model' => 'Bunga Cengkeh', 'engraving_name' => null, 'purity' => '18K', 'weight' => 12.50, 'estimated_price' => 13200000, 'dp' => 5000000, 'status' => 'Diproses', 'offset' => 6, 'due_offset' => 21, 'image' => null, 'notes' => null],
            ['order_no' => 'PD-2026-013', 'customer_index' => 11, 'jewelry_type' => 'Kalung', 'model' => 'Rumah Gadang', 'engraving_name' => null, 'purity' => '24K', 'weight' => 9.20, 'estimated_price' => 15800000, 'dp' => 2500000, 'status' => 'Diproses', 'offset' => 4, 'due_offset' => -1, 'image' => 'demo-3.jpg', 'notes' => 'Jatuh tempo besok.'],
            ['order_no' => 'PD-2026-014', 'customer_index' => 3, 'jewelry_type' => 'Anting', 'model' => 'Gantung Adat', 'engraving_name' => null, 'purity' => '17K', 'weight' => 2.60, 'estimated_price' => 3100000, 'dp' => 500000, 'status' => 'Masuk', 'offset' => 2, 'due_offset' => 25, 'image' => null, 'notes' => null],
            ['order_no' => 'PD-2026-015', 'customer_index' => 4, 'jewelry_type' => 'Cincin', 'model' => 'Batang Pinang', 'engraving_name' => 'R', 'purity' => '22K', 'weight' => 4.30, 'estimated_price' => 7600000, 'dp' => 0, 'status' => 'Masuk', 'offset' => 1, 'due_offset' => 30, 'image' => null, 'notes' => 'Ukiran huruf R di dalam.'],
            ['order_no' => 'PD-2026-016', 'customer_index' => 1, 'jewelry_type' => 'Liontin', 'model' => 'Bulan Sabit', 'engraving_name' => null, 'purity' => '24K', 'weight' => 4.90, 'estimated_price' => 8100000, 'dp' => 2000000, 'status' => 'Masuk', 'offset' => 0, 'due_offset' => 28, 'image' => null, 'notes' => null],
            ['order_no' => 'PD-2026-017', 'customer_index' => 5, 'jewelry_type' => 'Gelang', 'model' => 'Kaki Bayi', 'engraving_name' => 'Aisyah', 'purity' => '17K', 'weight' => 2.30, 'estimated_price' => 2550000, 'dp' => 750000, 'status' => 'Masuk', 'offset' => 0, 'due_offset' => 14, 'image' => 'demo-1.jpg', 'notes' => 'Hadiah ulang tahun anak.'],
            ['order_no' => 'PD-2026-018', 'customer_index' => 6, 'jewelry_type' => 'Kalung', 'model' => 'Perak Klasik', 'engraving_name' => null, 'purity' => 'Perak 925', 'weight' => 6.00, 'estimated_price' => 760000, 'dp' => 200000, 'status' => 'Masuk', 'offset' => 0, 'due_offset' => 10, 'image' => null, 'notes' => 'Permintaan khusus pelanggan baru.'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function promotions(): array
    {
        return [
            ['title' => '[DEMO] Diskon Akhir Tahun', 'message' => 'Nikmati potongan spesial untuk koleksi cincin dan kalung pilihan. Berlaku hingga akhir bulan.', 'target_type' => 'semua', 'status' => 'Terkirim', 'send_offset' => -18, 'targets' => []],
            ['title' => '[DEMO] Koleksi Lebaran', 'message' => 'Koleksi emas terbaru sudah tersedia. Reservasi sekarang untuk memastikan ukuran Anda.', 'target_type' => 'semua', 'status' => 'Terkirim', 'send_offset' => -60, 'targets' => []],
            ['title' => '[DEMO] Undangan Pelanggan Setia', 'message' => 'Terima kasih telah menjadi pelanggan setia. Kami menyiapkan prioritas layanan custom untuk Anda.', 'target_type' => 'terpilih', 'status' => 'Terkirim', 'send_offset' => -9, 'targets' => [0, 1, 3]],
            ['title' => '[DEMO] Promo Cincin Tunangan', 'message' => 'Gratis ukir nama untuk pemesanan cincin tunangan minggu ini.', 'target_type' => 'semua', 'status' => 'Terjadwal', 'send_offset' => 5, 'targets' => []],
            ['title' => '[DEMO] Prioritas Pengrajin', 'message' => 'Pesan sekarang dan dapatkan prioritas pengerjaan untuk gelang custom.', 'target_type' => 'terpilih', 'status' => 'Terjadwal', 'send_offset' => 12, 'targets' => [0, 4, 5]],
            ['title' => '[DEMO] Draf Koleksi Perak', 'message' => 'Draf kampanye untuk koleksi perak 925. Menunggu persetujuan pemilik.', 'target_type' => 'semua', 'status' => 'Draf', 'send_offset' => null, 'targets' => []],
            ['title' => '[DEMO] Draf Hadiah Pernikahan', 'message' => 'Draf penawaran paket perhiasan pernikahan untuk pelanggan terpilih.', 'target_type' => 'terpilih', 'status' => 'Draf', 'send_offset' => null, 'targets' => [1, 3, 5]],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function manualFinances(): array
    {
        $rows = [];
        $seed = 777001;

        for ($m = 0; $m < 6; $m++) {
            $monthStart = strtotime(self::ANCHOR . ' first day of this month');
            $monthStart = strtotime('-' . $m . ' months', $monthStart);
            $monthLabel = date('F Y', $monthStart);

            $rows[] = ['type' => 'Pengeluaran', 'category' => 'Sewa', 'description' => '[DEMO] Sewa kios ' . $monthLabel, 'amount' => 2500000, 'date' => date('Y-m-03', $monthStart)];
            $rows[] = ['type' => 'Pengeluaran', 'category' => 'Gaji Karyawan', 'description' => '[DEMO] Gaji karyawan ' . $monthLabel, 'amount' => 4500000, 'date' => date('Y-m-28', $monthStart)];
            $rows[] = ['type' => 'Pengeluaran', 'category' => 'Bahan Baku', 'description' => '[DEMO] Pembelian bahan baku emas ' . $monthLabel, 'amount' => self::rand($seed, 900, 2500) * 1000, 'date' => date('Y-m-07', $monthStart)];
            $rows[] = ['type' => 'Pengeluaran', 'category' => 'Upah Pengrajin', 'description' => '[DEMO] Upah pengrajin ' . $monthLabel, 'amount' => self::rand($seed, 1500, 3000) * 1000, 'date' => date('Y-m-15', $monthStart)];
            $rows[] = ['type' => 'Pengeluaran', 'category' => 'Operasional', 'description' => '[DEMO] Operasional toko ' . $monthLabel, 'amount' => self::rand($seed, 300, 900) * 1000, 'date' => date('Y-m-12', $monthStart)];

            if ($m % 2 === 0) {
                $rows[] = ['type' => 'Pengeluaran', 'category' => 'Lainnya', 'description' => '[DEMO] Perawatan etalase ' . $monthLabel, 'amount' => self::rand($seed, 150, 500) * 1000, 'date' => date('Y-m-20', $monthStart)];
                $rows[] = ['type' => 'Pemasukan', 'category' => 'Beli Emas', 'description' => '[DEMO] Penjualan emas batangan ' . $monthLabel, 'amount' => self::rand($seed, 3000, 8000) * 1000, 'date' => date('Y-m-22', $monthStart)];
            }

            if ($m % 3 === 0) {
                $rows[] = ['type' => 'Pemasukan', 'category' => 'Lainnya', 'description' => '[DEMO] Jasa reparasi ' . $monthLabel, 'amount' => self::rand($seed, 250, 1200) * 1000, 'date' => date('Y-m-25', $monthStart)];
            }
        }

        return $rows;
    }

    public static function rand(int &$seed, int $min, int $max): int
    {
        $seed = (int) (($seed * 1103515245 + 12345) & 0x7FFFFFFF);

        if ($max <= $min) {
            return $min;
        }

        return $min + ($seed % ($max - $min + 1));
    }
}
