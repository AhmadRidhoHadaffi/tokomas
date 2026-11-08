<?php
/**
 * Public Landing Page
 *
 * The visual reference is the desktop landing screenshot in the brief.
 * Content that is not available from the database yet falls back to the
 * curated showcase data used in design/src/data so the page stays complete.
 */

$title = 'Toko Mas Gembira — Perhiasan Emas & Perak Terbaik di Padang';
$this->extend('layouts/public');

$storeData = $store ?? [];
$storeName = $storeData['store_name'] ?? 'Toko Mas Gembira';
$storeAddress = $storeData['address'] ?? 'Pasar Raya Padang, Blok A Lt. 1 No. 12, Kp. Jao, Padang Barat, Kota Padang, Sumatera Barat 25112';
$storePhone = $storeData['phone'] ?? '0812-6677-8899';
$storeEmail = $storeData['email'] ?? 'halo@tokomasgembira.id';

$waNumber = preg_replace('/\D+/', '', (string) ($storeData['whatsapp'] ?? ''));
$waHref = $waNumber !== '' ? 'https://wa.me/' . $waNumber : 'https://wa.me/6281266778899';

$hours = [
    ['day' => 'Senin – Sabtu', 'time' => '09.00 – 17.00 WIB'],
    ['day' => 'Minggu', 'time' => '10.00 – 15.00 WIB'],
];

// Gold price card: use the 22K row when available, otherwise the screenshot value.
$goldPrice = 1650000.0;
$goldUpdated = '25 Sep 2026, 09.00 WIB';
if (!empty($goldPrices)) {
    $selectedPrice = null;
    foreach ($goldPrices as $row) {
        if (strtoupper((string) ($row['purity'] ?? '')) === '22K') {
            $selectedPrice = $row;
            break;
        }
    }
    $selectedPrice ??= $goldPrices[0];
    $goldPrice = (float) ($selectedPrice['price_per_gram'] ?? $goldPrice);
    if (!empty($selectedPrice['updated_at'])) {
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

$showcaseProducts = [];
if (!empty($products)) {
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
        } elseif (!preg_match('/^https?:\/\//i', $image)) {
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
?>

<?= $this->section('content') ?>

<!-- ============ HERO ============ -->
<section id="beranda" class="relative overflow-hidden bg-charcoal pb-14 pt-32 lg:pt-40">
    <div class="bg-arabesque absolute inset-0" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12">
            <div class="reveal lg:col-span-7">
                <h1 class="font-serif text-4xl font-semibold leading-[1.1] text-white sm:text-5xl lg:text-[64px]">
                    Perhiasan <span class="text-gold">Emas &amp; Perak</span> Terbaik di Padang
                </h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/70 sm:text-lg">
                    Belanja online, pesan custom, dan pantau pesanan Anda — semua dalam satu platform.
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="#produk" class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-gold-gradient px-6 text-[15px] font-medium text-charcoal shadow-sm transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:brightness-110 hover:shadow-gold-glow active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                        Lihat Produk Kami
                    </a>
                    <a href="<?= site_url('daftar') ?>" class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-white/70 bg-transparent px-6 text-[15px] font-medium text-white transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:bg-white/10 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                        Buat Akun Gratis
                    </a>
                </div>
            </div>

            <div class="reveal relative mx-auto w-full max-w-sm lg:col-span-5 lg:max-w-none" style="transition-delay: 80ms">
                <div class="rounded-t-[999px] border border-gold/40 p-3">
                    <img
                        src="<?= $imageBase ?>cincin-emas.jpg"
                        alt="Cincin emas filigree khas Minang di atas beludru gelap"
                        class="aspect-[4/5] w-full rounded-t-[999px] object-cover"
                        width="800"
                        height="1000"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>
                <div class="absolute -bottom-5 left-4 right-4 rounded-xl border border-gold/30 bg-charcoal/95 px-5 py-4 backdrop-blur sm:left-auto sm:right-[-12px] sm:w-64">
                    <p class="text-xs text-white/55">Harga emas 22K hari ini</p>
                    <p class="mt-0.5 font-serif text-xl font-semibold text-gold"><?= rupiah($goldPrice) ?> / gr</p>
                    <p class="mt-1 text-[11px] text-white/40">Diperbarui <?= esc($goldUpdated) ?></p>
                </div>
            </div>
        </div>

        <ul class="reveal mt-20 grid grid-cols-2 gap-x-6 gap-y-5 border-t border-white/10 pt-8 sm:grid-cols-3 lg:grid-cols-5">
            <?php foreach ($trustBadges as $badge): ?>
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gold/40 text-gold">
                        <?= icon($badge['icon'], 'h-4 w-4', 16, 1.75) ?>
                    </span>
                    <span class="text-sm text-white/80"><?= esc($badge['label']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- ============ ABOUT ============ -->
<section id="tentang" class="scroll-mt-24 bg-ivory py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal">
            <div class="relative">
                <img
                    src="<?= $imageBase ?>toko.jpg"
                    alt="Etalase perhiasan emas di Toko Mas Gembira, Pasar Raya Padang"
                    class="aspect-[4/3] w-full rounded-2xl object-cover shadow-card"
                    width="900"
                    height="675"
                    loading="lazy"
                >
                <div class="absolute -bottom-6 right-6 max-w-[220px] rounded-xl bg-crimson px-5 py-4 text-white shadow-lg">
                    <p class="font-serif text-3xl font-semibold">27 tahun</p>
                    <p class="text-xs text-white/80">melayani warga Padang sejak 1998</p>
                </div>
            </div>
        </div>

        <div class="reveal" style="transition-delay: 60ms">
            <h2 class="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
                Toko Perhiasan Terpercaya di Padang Barat
            </h2>
            <p class="mt-5 leading-relaxed text-charcoal/70">
                Berawal dari kios kecil di Pasar Raya Padang, <?= esc($storeName) ?> telah menjadi pilihan keluarga Minang untuk
                perhiasan pernikahan, hadiah, hingga simpanan masa depan. Kami menjual emas dan perak dengan kadar yang jelas,
                menerima pesanan custom, serta membeli kembali emas Anda dengan harga yang adil.
            </p>
            <ul class="mt-8 space-y-6">
                <?php foreach ($aboutHighlights as $highlight): ?>
                    <li class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-gold-dark shadow-card">
                            <?= icon($highlight['icon'], 'h-5 w-5', 20, 1.75) ?>
                        </span>
                        <div class="min-w-0">
                            <p class="font-semibold text-charcoal"><?= esc($highlight['title']) ?></p>
                            <p class="mt-0.5 text-sm text-charcoal/60"><?= esc($highlight['text']) ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<!-- ============ PRODUCT SHOWCASE ============ -->
<section
    id="produk"
    class="scroll-mt-24 bg-charcoal py-20 lg:py-28"
    x-data="<?= esc('{ selected: null, products: ' . $showcaseJson . ' }', 'attr') ?>"
    @keydown.escape.window="selected = null"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', !!selected)"
>
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl">Koleksi Pilihan Kami</h2>
                <p class="mt-3 max-w-lg text-white/60">
                    Berat, kadar, dan ukuran tertera jelas. Semua koleksi tersedia di toko dan bisa dipesan lewat akun Anda.
                </p>
            </div>
            <a href="<?= site_url('daftar') ?>" class="inline-flex min-h-[40px] items-center text-sm font-medium text-gold hover:underline">
                Lihat semua koleksi →
            </a>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($showcaseProducts as $index => $product): ?>
                <div class="reveal h-full" style="transition-delay: <?= $index * 40 ?>ms">
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-charcoal-soft transition-[box-shadow,border-color,transform] duration-200 ease-out hover:-translate-y-1 hover:border-gold/50 hover:shadow-gold-glow">
                        <div class="relative overflow-hidden">
                            <img
                                src="<?= esc($product['image'], 'attr') ?>"
                                alt="<?= esc($product['name'], 'attr') ?>"
                                class="aspect-[4/3] w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                width="800"
                                height="600"
                                loading="lazy"
                            >
                            <span class="absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-semibold <?= $product['metal'] === 'Emas' ? 'bg-gold text-charcoal' : 'bg-gray-200 text-charcoal' ?>">
                                <?= esc($product['metal']) ?>
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="font-serif text-xl font-semibold text-white"><?= esc($product['name']) ?></h3>
                            <p class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-white/55">
                                <?php foreach ($product['detail'] as $detailIndex => $detail): ?>
                                    <?php if ($detailIndex > 0): ?>
                                        <span aria-hidden="true">·</span>
                                    <?php endif; ?>
                                    <span><?= esc($detail) ?></span>
                                <?php endforeach; ?>
                            </p>
                            <p class="mt-4 text-sm text-white/50">
                                Mulai <span class="font-semibold text-gold"><?= rupiah($product['price']) ?></span>
                            </p>
                            <button
                                type="button"
                                @click="selected = products[<?= (int) $index ?>]"
                                class="mt-5 inline-flex h-11 w-full items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-gold/80 bg-transparent px-5 text-sm font-medium text-gold transition-[background-color,color,box-shadow,transform,filter,border-color] duration-150 ease-out hover:border-gold hover:bg-gold/10 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
                            >
                                Lihat Detail
                            </button>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Product detail modal -->
    <div
        x-show="selected"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] flex items-end justify-center p-0 sm:items-center sm:p-4"
        role="dialog"
        aria-modal="true"
        :aria-label="selected ? selected.name : 'Detail produk'"
    >
        <div class="absolute inset-0 bg-charcoal/70 backdrop-blur-sm" @click="selected = null" aria-hidden="true"></div>

        <div
            x-show="selected"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative max-h-[90dvh] w-full max-w-2xl overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl sm:rounded-2xl sm:p-6"
        >
            <button
                type="button"
                @click="selected = null"
                aria-label="Tutup detail produk"
                class="absolute right-4 top-4 z-10 rounded-lg bg-white/90 p-2 text-charcoal/70 shadow-sm transition-colors duration-150 hover:bg-ivory hover:text-charcoal focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
            >
                <?= icon('x', 'h-5 w-5') ?>
            </button>

            <div class="grid gap-6 sm:grid-cols-2">
                <img
                    :src="selected ? selected.image : ''"
                    :alt="selected ? selected.name : ''"
                    class="aspect-square w-full rounded-xl object-cover"
                    width="600"
                    height="600"
                >
                <div class="flex min-w-0 flex-col">
                    <span class="w-fit rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-deep" x-text="selected ? selected.metal : ''"></span>
                    <h3 class="mt-3 font-serif text-2xl font-semibold text-charcoal" x-text="selected ? selected.name : ''"></h3>
                    <p class="mt-3 text-sm leading-relaxed text-charcoal/70" x-text="selected ? selected.description : ''"></p>

                    <dl class="mt-5 divide-y divide-line rounded-xl border border-line">
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Berat</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[0] : ''"></dd>
                        </div>
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Kadar</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[1] : ''"></dd>
                        </div>
                        <div class="flex justify-between gap-4 px-4 py-2.5 text-sm">
                            <dt class="text-charcoal/55">Ukuran</dt>
                            <dd class="text-right font-medium text-charcoal" x-text="selected ? selected.detail[2] : ''"></dd>
                        </div>
                    </dl>

                    <p class="mt-5 text-sm text-charcoal/55">Harga mulai</p>
                    <p class="font-serif text-2xl font-semibold text-charcoal" x-text="selected ? selected.price_label : ''"></p>

                    <div class="mt-auto flex flex-col gap-2 pt-6">
                        <a
                            href="<?= esc($waHref, 'attr') ?>"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-gold-gradient px-5 text-sm font-medium text-charcoal shadow-sm transition-[filter,box-shadow] duration-150 hover:brightness-110 hover:shadow-gold-glow focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
                        >
                            <?= icon('message-circle', 'h-4 w-4') ?>
                            Tanya via WhatsApp
                        </a>
                        <a
                            href="<?= site_url('masuk') ?>"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-line bg-white px-5 text-sm font-medium text-charcoal transition-colors duration-150 hover:border-gold/60 hover:bg-ivory focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30"
                        >
                            Masuk untuk memesan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ CUSTOM ORDER ============ -->
<section id="custom" class="scroll-mt-24 bg-ivory py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal lg:sticky lg:top-28 lg:self-start">
            <h2 class="font-serif text-3xl font-semibold leading-tight text-charcoal sm:text-4xl">
                Wujudkan Perhiasan Impian Anda
            </h2>
            <p class="mt-5 max-w-lg leading-relaxed text-charcoal/70">
                Cincin nikah dengan ukiran nama, liontin inisial, atau gelang bermotif Rumah Gadang — pengrajin kami siap
                membuatnya sesuai keinginan. Ajukan pesanan dari akun Anda, bayar DP, dan pantau prosesnya hingga selesai.
            </p>
            <a href="<?= site_url('daftar') ?>" class="mt-8 inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-gold-gradient px-6 text-[15px] font-medium text-charcoal shadow-sm transition-[filter,box-shadow] duration-150 hover:brightness-110 hover:shadow-gold-glow focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Pesan Custom Sekarang
            </a>
        </div>

        <ol class="relative">
            <?php foreach ($customSteps as $index => $step): ?>
                <li class="reveal relative flex gap-5 pb-10 last:pb-0" style="transition-delay: <?= $index * 50 ?>ms">
                    <?php if ($index < count($customSteps) - 1): ?>
                        <span class="absolute left-[23px] top-12 h-[calc(100%-48px)] w-px bg-gold/50" aria-hidden="true"></span>
                    <?php endif; ?>
                    <span class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-2 border-gold bg-white text-gold-dark">
                        <?= icon($step['icon'], 'h-5 w-5', 20, 1.75) ?>
                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-crimson text-[10px] font-semibold text-white">
                            <?= $index + 1 ?>
                        </span>
                    </span>
                    <div class="min-w-0 pt-1.5">
                        <p class="font-semibold text-charcoal"><?= esc($step['title']) ?></p>
                        <p class="mt-1 text-sm leading-relaxed text-charcoal/60"><?= esc($step['text']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<!-- ============ PROMO ============ -->
<section class="relative overflow-hidden bg-crimson py-20 lg:py-24">
    <div class="bg-arabesque absolute inset-0 opacity-40" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-2xl">
            <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl">Selalu Tahu Kabar Pesanan &amp; Promo Terbaru</h2>
            <p class="mt-4 text-white/75">
                Dengan akun pelanggan, semua informasi penting sampai langsung ke Anda — tanpa perlu bolak-balik ke toko.
            </p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
            <?php foreach ($promoFeatures as $index => $feature): ?>
                <div class="reveal h-full" style="transition-delay: <?= $index * 50 ?>ms">
                    <div class="h-full rounded-2xl border border-white/15 bg-white/[0.06] p-6">
                        <?= icon($feature['icon'], 'h-7 w-7 text-gold', 28, 1.5) ?>
                        <h3 class="mt-5 font-serif text-xl font-semibold text-white"><?= esc($feature['title']) ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70"><?= esc($feature['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="reveal mt-10">
            <a href="<?= site_url('daftar') ?>" class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-gold-gradient px-6 text-[15px] font-medium text-charcoal shadow-sm transition-[filter,box-shadow] duration-150 hover:brightness-110 hover:shadow-gold-glow focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30">
                Daftar Sekarang
            </a>
        </div>
    </div>
</section>

<!-- ============ WHY US ============ -->
<section class="bg-ivory py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <h2 class="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Mengapa Memilih Toko Mas Gembira</h2>
            <p class="mt-4 text-charcoal/65">Empat alasan pelanggan kembali kepada kami, dari generasi ke generasi.</p>
        </div>
        <div class="reveal mt-12">
            <div class="grid overflow-hidden rounded-2xl border border-line bg-white shadow-card sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($whyUs as $index => $item): ?>
                    <?php
                    $borderClass = '';
                    if ($index > 0) {
                        $borderClass .= ' border-t border-line sm:border-t-0';
                    }
                    if ($index % 2 === 1) {
                        $borderClass .= ' sm:border-l';
                    }
                    if ($index >= 2) {
                        $borderClass .= ' sm:border-t lg:border-t-0';
                    }
                    if ($index === 2) {
                        $borderClass .= ' lg:border-l';
                    }
                    ?>
                    <div class="border-line p-7 <?= trim($borderClass) ?>">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-ivory text-gold-dark">
                            <?= icon($item['icon'], 'h-6 w-6', 24, 1.5) ?>
                        </span>
                        <h3 class="mt-5 font-serif text-lg font-semibold text-charcoal"><?= esc($item['title']) ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal/60"><?= esc($item['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============ REGISTER BANNER ============ -->
<section class="bg-ivory pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal">
            <div class="relative overflow-hidden rounded-2xl bg-gold-gradient px-6 py-12 sm:px-12 lg:flex lg:items-center lg:justify-between lg:py-14">
                <div class="bg-arabesque absolute inset-0 opacity-50 mix-blend-multiply" aria-hidden="true"></div>
                <div class="relative max-w-xl">
                    <h2 class="font-serif text-3xl font-semibold text-charcoal sm:text-4xl">Bergabung Sekarang dan Mulai Belanja</h2>
                    <p class="mt-3 text-charcoal/75">Gratis selamanya. Simpan riwayat belanja dan dapatkan promo khusus pelanggan.</p>
                </div>
                <div class="relative mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0">
                    <a href="<?= site_url('daftar') ?>" class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-charcoal px-6 text-[15px] font-medium text-white transition-colors duration-150 hover:bg-charcoal-soft focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-charcoal/20">
                        Buat Akun Gratis
                    </a>
                    <a href="<?= site_url('masuk') ?>" class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-charcoal/70 px-6 text-[15px] font-medium text-charcoal transition-colors duration-150 hover:bg-charcoal/10 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-charcoal/20">
                        Masuk ke Akun
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ CONTACT ============ -->
<section id="kontak" class="scroll-mt-24 bg-charcoal py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-5 lg:px-8">
        <div class="reveal lg:col-span-2">
            <h2 class="font-serif text-3xl font-semibold text-white sm:text-4xl">Kunjungi Toko Kami</h2>
            <p class="mt-4 text-white/60">Kami tunggu kedatangan Anda di Pasar Raya Padang.</p>

            <ul class="mt-8 space-y-5 text-sm">
                <li class="flex gap-4">
                    <?= icon('map-pin', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storeAddress) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('phone', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storePhone) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('mail', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <span class="min-w-0 break-words text-white/80"><?= esc($storeEmail) ?></span>
                </li>
                <li class="flex gap-4">
                    <?= icon('clock', 'mt-0.5 h-5 w-5 shrink-0 text-gold', 20, 1.75) ?>
                    <div class="min-w-0 space-y-1">
                        <?php foreach ($hours as $hour): ?>
                            <p class="text-white/80">
                                <span class="inline-block w-28 text-white/50 sm:w-32"><?= esc($hour['day']) ?></span>
                                <?= esc($hour['time']) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </li>
            </ul>

            <a
                href="<?= esc($waHref, 'attr') ?>"
                target="_blank"
                rel="noreferrer"
                class="mt-8 inline-flex h-11 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-wa px-5 text-sm font-medium text-white shadow-sm transition-[filter,box-shadow,transform] duration-150 hover:brightness-110 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-wa/40"
            >
                <?= icon('message-circle', 'h-4 w-4') ?>
                Chat WhatsApp
            </a>
        </div>

        <div class="reveal min-w-0 lg:col-span-3" style="transition-delay: 60ms">
            <div class="overflow-hidden rounded-2xl border border-gold/30">
                <iframe
                    title="Peta lokasi Toko Mas Gembira di Pasar Raya Padang"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=100.3560%2C-0.9560%2C100.3680%2C-0.9440&amp;layer=mapnik&amp;marker=-0.9500%2C100.3620"
                    class="h-[320px] w-full sm:h-[380px] lg:h-[440px]"
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
