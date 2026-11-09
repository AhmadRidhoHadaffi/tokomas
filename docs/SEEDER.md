# SEEDER — Data Demo Toko Mas Gembira

> Branch: `hadaffi` · Anchor tanggal: **2026-11-09** · Database dev: `db_tokogembira`
> Seeder bersifat **idempoten**: dijalankan berapa kali pun, jumlah baris demo tidak bertambah.

---

## 1. Perintah

```bash
# Isi / segarkan data demo (aman dijalankan berulang)
php spark db:seed DemoSeeder

# Verifikasi jumlah baris + invarian
php spark demo:verify

# Hapus HANYA baris demo
php spark demo:clean

# Uji siklus seed → clean di database test (menolak berjalan di DB non-test)
php spark demo:test-cycle
```

`DemoSeeder` memanggil seeder modular berikut secara berurutan:

| Urutan | Seeder | Tabel |
|---:|---|---|
| 1 | `SettingSeeder` | `store_settings` |
| 2 | `UserSeeder` | `users` |
| 3 | `GoldPriceSeeder` | `gold_prices` |
| 4 | `CustomerSeeder` | `customers` |
| 5 | `ProductSeeder` | `products` |
| 6 | `TransactionSeeder` | `transactions`, `transaction_details` |
| 7 | `FinanceSeeder` | `finances` (pemasukan penjualan + entri manual) |
| 8 | `CustomOrderSeeder` | `custom_orders`, `custom_order_histories`, `finances`, `notifications` |
| 9 | `PromotionSeeder` | `promotions`, `promotion_customers`, `notifications` |
| 10 | `NotificationSeeder` | `notifications` (pembayaran) |

Perencanaan deterministik ada di `app/Database/Seeds/Support/DemoPlan.php`.
Helper idempotensi ada di `app/Database/Seeds/Support/DemoSeedTrait.php`.

---

## 2. Jumlah Baris Demo

Angka di bawah adalah **baris demo saja** (bukan total tabel), diambil dari `php spark demo:verify`.

| Tabel | Baris demo | Penanda |
|---|---:|---|
| `users` | 16 | email berakhiran `@demo.test` |
| `customers` | 15 | email `@demo.test` / telepon `08137788*` |
| `products` | 36 | kode `DM-*` |
| `transactions` | 80 | `invoice_no` `DM-YYYYMMDD-NNN` |
| `transaction_details` | 204 | terhubung ke transaksi demo |
| `custom_orders` | 18 | `order_no` `PD-2026-NNN` |
| `custom_order_histories` | 50 | satu baris per transisi status |
| `finances` | 38 entri manual `[DEMO]` + 65 pemasukan penjualan + 15 DP + 3 pelunasan | deskripsi `[DEMO]` / linked |
| `promotions` | 7 | judul `[DEMO] ...` |
| `promotion_customers` | 9 | promosi `terpilih` |
| `notifications` | 117 untuk pelanggan demo (+2 untuk pelanggan lama pada promosi "semua") | customer demo / promotion demo |
| `store_settings` | 1 baris (diperbarui, bukan diduplikasi) | — |
| `gold_prices` | 4 baris (satu per kadar) | PK `purity` |

> Catatan: `gold_prices` memakai `purity` sebagai primary key sehingga **tidak bisa menyimpan riwayat harga**. Hanya harga terakhir yang di-seed. Ini keterbatasan skema, bukan bug seeder.

---

## 3. Invarian yang Diperiksa `demo:verify`

### Jumlah
- 16 user, 15 customer, 36 produk, 80 transaksi, 204 detail, 18 pesanan custom, 50 histori, 7 promosi, 9 penerima promosi, 1 setting, 4 harga emas.

### Integritas
- Tidak ada stok produk demo negatif.
- `transactions.total = SUM(detail.price × quantity) − discount` untuk semua transaksi demo.
- Semua `transaction_details.quantity > 0`.
- Setiap pesanan custom memiliki histori **lengkap dan berurutan**: `Masuk → Diproses → Dalam Pembuatan → Selesai → Diambil` sesuai status akhir.
- Status terakhir pada histori sama dengan `custom_orders.status`.
- DP pada `finances` = `custom_orders.dp` (bila > 0).
- Pelunasan pada `finances` = `estimated_price − dp` untuk pesanan `Diambil`.
- Tepat satu pemasukan `Penjualan` untuk setiap transaksi `Lunas`.
- Semua `notifications.created_at` valid (bukan `0000-00-00`).
- Tidak ada notifikasi promosi duplikat per (`customer_id`, `promotion_id`, `title`).
- Tidak ada FK yatim pada 16 relasi utama (transaksi, detail, pesanan, histori, keuangan, promosi, penerima, notifikasi, harga emas).
- `customers.user_id` pelanggan demo selalu menunjuk user ber-role `pelanggan`.
- Semua user demo lolos `password_verify` dengan password demo.
- File gambar produk (`public/assets/img/products/`) dan referensi pesanan (`public/uploads/custom-orders/`) benar-benar ada.
- Notifikasi demo memiliki campuran baris `is_read = 0` dan `is_read = 1`.

---

## 4. Idempotensi

Kunci natural yang dipakai:

| Tabel | Kunci |
|---|---|
| `users` | `email` |
| `customers` | `email` (fallback `phone`) |
| `products` | `code` |
| `transactions` | `invoice_no` |
| `custom_orders` | `order_no` |
| `custom_order_histories` | (`custom_order_id`, `status`) |
| `finances` manual | `description` |
| `finances` penjualan | `transaction_id` (unik) |
| `finances` DP/pelunasan | (`custom_order_id`, `category`) |
| `promotions` | `title` |
| `promotion_customers` | (`promotion_id`, `customer_id`) |
| `notifications` | (`customer_id`, `type`, `title`, `message`) |
| `gold_prices` | `purity` |
| `store_settings` | baris pertama (`id`) |

Hasil uji dua kali berturut-turut di `db_tokogembira`:

```
users 19 / customers 16 / products 41 / transactions 80 / transaction_details 204
custom_orders 18 / custom_order_histories 50 / finances 121 / promotions 7
promotion_customers 9 / notifications 119 / store_settings 1 / gold_prices 4
```

Jumlah identik sebelum dan sesudah run kedua (`Compare-Object` kosong).

---

## 5. Uji Cleanup (`DemoCleanSeeder`)

`php spark demo:test-cycle`:

1. Menolak berjalan bila koneksi `tests` tidak berakhiran `_test`.
2. Menyimpan baris `store_settings` & `gold_prices` sebagai referensi.
3. Mencatat jumlah baris non-demo.
4. Menjalankan `DemoSeeder` (16/15/36/80/18/7 sesuai ekspektasi).
5. Menjalankan `DemoCleanSeeder`.
6. Memulihkan `store_settings` & `gold_prices`.
7. Membandingkan jumlah baris non-demo.

Hasil: **lulus** — seluruh baris demo kembali 0 dan jumlah baris non-demo tidak berubah.

Urutan hapus di `DemoCleanSeeder` mengikuti FK:
`notifications → finances → transaction_details → transactions → custom_order_histories → custom_orders → promotion_customers → promotions → customers → users → products`.

Yang **tidak** dihapus: data lama/non-demo, `store_settings`, `gold_prices` (data referensi tunggal).

---

## 6. Keputusan Data

- **Stok produk** pada `DemoPlan` adalah stok **saat ini** (setelah seluruh penjualan demo). Seeder tidak mengurangi stok lagi agar hasil deterministik dan tidak pernah negatif.
- **Tanggal** semua data diturunkan dari anchor `2026-11-09`, sehingga nomor invoice/order dan urutan histori stabil.
- **Transaksi hari ini** sengaja dibuat 6 baris pada tanggal anchor agar dashboard "hari ini" terisi bila diakses pada 2026-11-09.
- **Pesanan custom** memiliki histori lengkap sampai status akhir, plus notifikasi tiap transisi.
- **Promosi "semua"** mengirim notifikasi ke seluruh pelanggan, termasuk pelanggan lama yang sudah ada sebelum dataset demo.
- **Password** semua akun demo: lihat `docs/DEMO-AKUN.md`.
- **Gambar** produk memakai 4 JPG dari `design/public/` yang disalin ke `public/assets/img/products/`; referensi pesanan custom memakai 3 JPG di `public/uploads/custom-orders/`.

---

## 7. Keterbatasan / Gap

1. `gold_prices` tidak punya kolom riwayat (PK = `purity`); perlu migration baru bila riwayat harga diinginkan.
2. `products` tidak punya kolom `metal`; validasi `ProductRules` tidak dipakai route mana pun.
3. `users` tidak punya `username`/`phone`; halaman karyawan yang membacanya tetap perlu diperbaiki di view.
4. `promotions` tidak punya `start_date`/`end_date`; hanya `send_at`.
5. `DemoCleanSeeder` mengandalkan integritas referensial. Baris notifikasi yatim (hasil truncate manual saat pengujian) dapat ikut terhapus bila `customer_id`-nya kebetulan sama dengan ID pelanggan demo baru.
