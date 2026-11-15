<?php

namespace App\Services;

use App\Models\GoldPriceModel;
use App\Models\ProductModel;
use App\Models\StoreSettingModel;

/**
 * Menyiapkan data bersama untuk halaman publik (beranda, produk, custom,
 * tentang, dan kontak) agar isi tiap halaman tetap konsisten.
 */
class LandingService
{
    public function data(?int $productLimit = null): array
    {
        $store = (new StoreSettingModel())->first() ?? [];
        $goldPrices = (new GoldPriceModel())->findAll();

        $storeName = $store['store_name'] ?? 'Toko Mas Gembira';
        $storeAddress = $store['address'] ?? 'Pasar Raya Padang, Blok A Lt. 1 No. 12, Kp. Jao, Padang Barat, Kota Padang, Sumatera Barat 25112';
        $storePhone = $store['phone'] ?? '0812-6677-8899';
        $storeEmail = $store['email'] ?? 'halo@tokomasgembira.id';

        $waNumber = preg_replace('/\D+/', '', (string) ($store['whatsapp'] ?? ''));
        $waHref = $waNumber !== '' ? 'https://wa.me/' . $waNumber : 'https://wa.me/6281266778899';

        $hours = [
            ['day' => 'Senin – Sabtu', 'time' => '09.00 – 17.00 WIB'],
            ['day' => 'Minggu', 'time' => '10.00 – 15.00 WIB'],
        ];

        // Harga emas 22K bila tersedia, selain itu pakai nilai acuan desain.
        $goldPrice = 1650000.0;
        $goldUpdated = '25 Sep 2026, 09.00 WIB';
        if (! empty($goldPrices)) {
            $selectedPrice = null;
            foreach ($goldPrices as $row) {
                if (strtoupper((string) ($row['purity'] ?? '')) === '22K') {
                    $selectedPrice = $row;
                    break;
                }
            }
            $selectedPrice ??= $goldPrices[0];
            $goldPrice = (float) ($selectedPrice['price_per_gram'] ?? $goldPrice);
            if (! empty($selectedPrice['updated_at'])) {
                $goldUpdated = format_datetime($selectedPrice['updated_at']) . ' WIB';
            }
        }

        $imageBase = base_url('assets/images/landing/');

        $fallbackProducts = [
            [
                'name' => 'Cincin Emas',
                'detail' => ['3,2 gr', '22K · 91,6%', 'Ukuran 14'],
                'metal' => 'Emas',
                'price' => 5280000,
                'price_label' => 'Rp 5.280.000',
                'image' => $imageBase . 'cincin-emas.jpg',
                'description' => 'Cincin filigree khas Minang dengan detail anyaman halus buatan pengrajin lokal Padang.',
            ],
            [
                'name' => 'Cincin Perak',
                'detail' => ['4,5 gr', 'Perak 925', 'Ukuran 16'],
                'metal' => 'Perak',
                'price' => 385000,
                'price_label' => 'Rp 385.000',
                'image' => $imageBase . 'cincin-perak.jpg',
                'description' => 'Cincin perak sterling dengan batu solitaire, cocok untuk dipakai sehari-hari.',
            ],
            [
                'name' => 'Kalung Emas',
                'detail' => ['8,0 gr', '22K · 91,6%', '45 cm'],
                'metal' => 'Emas',
                'price' => 13200000,
                'price_label' => 'Rp 13.200.000',
                'image' => $imageBase . 'kalung-emas.jpg',
                'description' => 'Kalung rantai Italy dengan liontin ukir, kilau emas kuning yang hangat.',
            ],
            [
                'name' => 'Gelang Emas',
                'detail' => ['12,5 gr', '22K · 91,6%', 'Ø 6 cm'],
                'metal' => 'Emas',
                'price' => 20625000,
                'price_label' => 'Rp 20.625.000',
                'image' => $imageBase . 'gelang-emas.jpg',
                'description' => 'Gelang keroncong dengan ukiran bunga, investasi sekaligus perhiasan warisan.',
            ],
            [
                'name' => 'Anting Emas',
                'detail' => ['2,4 gr', '18K · 75%', 'Tusuk'],
                'metal' => 'Emas',
                'price' => 3240000,
                'price_label' => 'Rp 3.240.000',
                'image' => $imageBase . 'anting-emas.jpg',
                'description' => 'Anting tetes filigree yang ringan dan anggun untuk acara resmi maupun harian.',
            ],
            [
                'name' => 'Cincin Pasangan Custom',
                'detail' => ['Mulai 6 gr', '18K / 22K', 'Sesuai pesanan'],
                'metal' => 'Emas',
                'price' => 8775000,
                'price_label' => 'Rp 8.775.000',
                'image' => $imageBase . 'cincin-pasangan.jpg',
                'description' => 'Cincin nikah dan tunangan dengan ukiran nama pasangan, dibuat sesuai ukuran Anda.',
            ],
        ];

        $fallbackByCategory = [
            'Cincin' => 'cincin-emas.jpg',
            'Kalung' => 'kalung-emas.jpg',
            'Gelang' => 'gelang-emas.jpg',
            'Anting' => 'anting-emas.jpg',
            'Liontin' => 'cincin-perak.jpg',
        ];

        $products = (new ProductModel())->orderBy('created_at', 'DESC');
        if ($productLimit !== null) {
            $products = $products->findAll($productLimit);
        } else {
            $products = $products->findAll();
        }

        $showcaseProducts = [];
        if (! empty($products)) {
            foreach ($products as $product) {
                $purity = trim((string) ($product['purity'] ?? ''));
                $purityPercent = match (true) {
                    str_contains($purity, '24') => '99,9%',
                    str_contains($purity, '22') => '91,6%',
                    str_contains($purity, '18') => '75%',
                    str_contains($purity, '17') => '70,8%',
                    default => '',
                };
                $purityText = $purity !== '' && $purityPercent !== '' ? $purity . ' · ' . $purityPercent : $purity;

                $weight = isset($product['weight']) && $product['weight'] !== null
                    ? format_number((float) $product['weight'], 1)
                    : '';
                $size = trim((string) ($product['size'] ?? ''));
                $category = trim((string) ($product['category'] ?? ''));
                $metal = (stripos($purity, 'perak') !== false || stripos((string) ($product['name'] ?? ''), 'perak') !== false) ? 'Perak' : 'Emas';

                $image = trim((string) ($product['image'] ?? ''));
                if ($image === '') {
                    $image = $imageBase . ($fallbackByCategory[$category] ?? 'cincin-emas.jpg');
                } elseif (! preg_match('/^https?:\/\//i', $image)) {
                    $image = base_url(ltrim($image, '/'));
                }

                $showcaseProducts[] = [
                    'name' => (string) ($product['name'] ?? 'Perhiasan'),
                    'detail' => [
                        $weight !== '' ? $weight . ' gr' : 'Berat tersedia',
                        $purityText !== '' ? $purityText : $metal,
                        $size !== '' ? 'Ukuran ' . $size : 'Sesuai pesanan',
                    ],
                    'metal' => $metal,
                    'price' => (float) ($product['price'] ?? 0),
                    'price_label' => rupiah((float) ($product['price'] ?? 0)),
                    'image' => $image,
                    'description' => 'Perhiasan ' . strtolower($category !== '' ? $category : 'emas') . ' pilihan Toko Mas Gembira, dibuat dengan kadar yang jelas dan pengerjaan rapi.',
                ];
            }
        } else {
            $showcaseProducts = $fallbackProducts;
        }

        $showcaseJson = json_encode($showcaseProducts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);

        $trustBadges = [
            ['icon' => 'badge-check', 'label' => 'Emas & Perak Asli'],
            ['icon' => 'gem', 'label' => 'Terima Pesanan Custom'],
            ['icon' => 'bell-ring', 'label' => 'Notifikasi Status Pesanan'],
            ['icon' => 'refresh-cw', 'label' => 'Terima Beli Emas'],
            ['icon' => 'map-pin', 'label' => 'Pasar Raya Padang'],
        ];

        $aboutHighlights = [
            ['icon' => 'gem', 'title' => 'Kualitas Terjamin', 'text' => 'Setiap perhiasan diperiksa ketat sebelum sampai ke tangan Anda.'],
            ['icon' => 'crown', 'title' => 'Kadar Emas Asli', 'text' => 'Kadar tertera jelas di nota — 17K, 18K, hingga 22K.'],
            ['icon' => 'handshake', 'title' => 'Pelayanan Transparan', 'text' => 'Harga, berat, dan ongkos bikin dijelaskan tanpa biaya tersembunyi.'],
        ];

        $customSteps = [
            ['icon' => 'pencil-line', 'title' => 'Buat akun & ajukan pesanan', 'text' => 'Daftar gratis, lalu isi jenis perhiasan, kadar, dan ukuran.'],
            ['icon' => 'image-plus', 'title' => 'Upload gambar referensi', 'text' => 'Kirim foto model impian Anda sebagai acuan pengrajin.'],
            ['icon' => 'hammer', 'title' => 'Pesanan dikerjakan ahli', 'text' => 'Pengrajin berpengalaman menempa dan mengukir sesuai pesanan.'],
            ['icon' => 'bell-ring', 'title' => 'Notifikasi saat pesanan selesai', 'text' => 'Anda dikabari di setiap tahap hingga siap diambil.'],
        ];

        $promoFeatures = [
            ['icon' => 'bell-ring', 'title' => 'Notifikasi Real-Time', 'text' => 'Pantau pesanan custom dari masuk hingga siap diambil, langsung di akun Anda.'],
            ['icon' => 'gift', 'title' => 'Promosi Eksklusif', 'text' => 'Diskon ongkos bikin dan penawaran khusus hanya untuk pelanggan terdaftar.'],
            ['icon' => 'history', 'title' => 'Riwayat Transaksi', 'text' => 'Semua nota pembelian tersimpan rapi — tak perlu takut nota hilang.'],
        ];

        $whyUs = [
            ['icon' => 'gem', 'title' => 'Kualitas', 'text' => 'Emas dan perak asli dengan kadar yang terverifikasi.'],
            ['icon' => 'receipt-text', 'title' => 'Transaksi Digital', 'text' => 'Nota digital, pembayaran Tunai, Transfer, maupun QRIS.'],
            ['icon' => 'refresh-cw', 'title' => 'Beli Emas', 'text' => 'Kami menerima pembelian kembali emas Anda dengan harga wajar.'],
            ['icon' => 'map-pin', 'title' => 'Lokasi', 'text' => 'Mudah dijangkau di jantung Pasar Raya Padang.'],
        ];

        return [
            'store' => $store,
            'storeName' => $storeName,
            'storeAddress' => $storeAddress,
            'storePhone' => $storePhone,
            'storeEmail' => $storeEmail,
            'waHref' => $waHref,
            'hours' => $hours,
            'goldPrice' => $goldPrice,
            'goldUpdated' => $goldUpdated,
            'imageBase' => $imageBase,
            'showcaseProducts' => $showcaseProducts,
            'showcaseJson' => $showcaseJson,
            'trustBadges' => $trustBadges,
            'aboutHighlights' => $aboutHighlights,
            'customSteps' => $customSteps,
            'promoFeatures' => $promoFeatures,
            'whyUs' => $whyUs,
        ];
    }
}
