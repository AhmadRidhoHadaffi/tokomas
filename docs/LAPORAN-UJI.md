# LAPORAN UJI — Bagian 3

> Proyek: **Toko Mas Gembira** (CodeIgniter 4.7.4, view PHP, Tailwind CLI, Alpine.js, Chart.js)
> Branch: `hadaffi` · Commit acuan: `c474407 feat(seed): data demo lengkap dan idempoten`
> Tanggal uji: **9 November 2026** · Lingkungan: `development`
> Database uji: `db_tokogembira` (dev) dan `db_tokogembira_test` (PHPUnit)

---

## 0. Ringkasan Eksekutif

| Area | Hasil |
|---|---|
| PHPUnit | **32 tes, 190 assertion — OK** (sebelum Bagian 3: 24 tes, 136 assertion) |
| Halaman per peran (HTTP GET) | **33 halaman, semua HTTP 200**, tanpa error PHP/undefined key |
| Aksi mutasi HTTP | Login ditolak (akun nonaktif & password salah), buat/hapus pembukuan — **lulus** |
| Service mutasi (PHPUnit) | Transaksi, status custom order, pembukuan, promosi, auth, otorisasi silang peran — **lulus** |
| Data demo | `php spark demo:verify` — **semua PASS** |
| Bug fungsional ditemukan & diperbaiki | **14 kelompok** (timestamp model, kontrak view/controller, tautan mati, data dashboard) |
| Fitur tanpa backend | 12 fitur tidak dapat diuji/diperbaiki tanpa menambah fitur — dicatat di §7 |

Tidak ada perubahan skema database pada Bagian 3. Semua perbaikan adalah pembetulan bug yang terbukti (model metadata, kontrak data view/controller, dan tautan yang tidak punya rute).

---

## 1. Lingkungan dan Data Uji

### 1.1 Perangkat lunak

| Komponen | Versi |
|---|---|
| PHP | 8.2.12 |
| CodeIgniter | 4.7.4 |
| Database | MariaDB 10.4.32 (non-strict) |
| PHPUnit | 10.5.65 |
| Node/Tailwind | sesuai `package.json` proyek |

### 1.2 Isi database dev saat pengujian

| Tabel | Jumlah |
|---|---:|
| `users` | 19 (16 demo + 3 lama) |
| `customers` | 16 (15 demo + 1 lama) |
| `products` | 41 |
| `transactions` | 80 |
| `transaction_details` | 204 |
| `custom_orders` | 18 |
| `custom_order_histories` | 50 |
| `promotions` | 7 |
| `promotion_customers` | 9 |
| `notifications` | 119 |
| `finances` | 121 |
| `store_settings` | 1 |
| `gold_prices` | 4 |

Akun demo yang dipakai: `owner@demo.test`, `rina@demo.test` (karyawan), `siti@demo.test` (pelanggan), password `Gembira#2026`. Akun nonaktif: `yuni@demo.test`, `doni@demo.test`.

Sampel ID: transaksi **84** (`DM-20261109-006`), custom order **28** (`PD-2026-018`), pesanan milik Siti **22** (`PD-2026-012`).

---

## 2. Metode Pengujian

1. **PHPUnit** — unit/feature test terhadap service dan model pada database uji terpisah.
2. **HTTP smoke test** — login tiap peran lewat form asli (CSRF), lalu GET seluruh halaman; memeriksa status 200, teks kunci, dan tidak adanya `Undefined variable`/`Undefined array key`/`Fatal error`/`Warning`.
3. **HTTP mutation test** — login, buat dan hapus catatan pembukuan lewat form asli, lalu verifikasi baris di database; uji penolakan login akun nonaktif dan password salah.
4. **Seeder verification** — `php spark demo:verify` untuk invarian data demo.

Skrip bantu (di luar repo, folder temp): `smoke.ps1`, `mutation.ps1`, `db-helper.php`.

---

## 3. Hasil PHPUnit

```
PHPUnit 10.5.65
........................ 32 / 32 (100%)
OK (32 tests, 190 assertions)
```

Tes baru/ tambahan pada Bagian 3:

| Berkas | Tes | Cakupan |
|---|---|---|
| `tests/feature/Business/ModelTimestampTest.php` | 4 | `created_at` terisi untuk `notifications` & `custom_order_histories`; `updated_at` terisi untuk `store_settings` & `gold_prices`; update tidak menyentuh kolom yang tidak ada |
| `tests/feature/Business/DashboardBusinessTest.php` | 2 | `total_products`, `recent_transactions`, `active_order_list`, ringkasan pembukuan, statistik karyawan |
| `tests/feature/Business/FinanceBusinessTest.php` | +1 | update & hapus catatan manual |
| `tests/feature/Web/AuthPageTest.php` | +1 | 6 kombinasi akses lintas peran → 403 |

Tes lama yang tetap lulus: auth (register/login/nonaktif/ganti password), transaksi (total & rollback stok), custom order (histori, DP, progres status, larangan skip), promosi (kirim ke semua, larangan kirim ganda), laporan, pengaturan, kepemilikan data pelanggan, dan seluruh halaman peran tidak 500.

---

## 4. Hasil HTTP GET per Peran

Semua halaman diakses setelah login memakai sesi asli. `FAILURES=0`.

### 4.1 Owner (15 halaman)

| Halaman | Status | Teks kunci |
|---|---|---|
| `/owner` | 200 | Dashboard Owner |
| `/owner/produk` | 200 | Produk & Stok |
| `/owner/transaksi` | 200 | Transaksi Penjualan |
| `/owner/transaksi/baru` | 200 | Catat Transaksi Baru |
| `/owner/transaksi/84` | 200 | Detail Transaksi, `DM-20261109-006` |
| `/owner/pembukuan` | 200 | Pembukuan |
| `/owner/pembukuan/baru` | 200 | Tambah Catatan Pembukuan |
| `/owner/pesanan-custom` | 200 | Pesanan Custom |
| `/owner/pesanan-custom/baru` | 200 | Tambah Pesanan Custom |
| `/owner/pesanan-custom/28` | 200 | Detail Pesanan Custom, `PD-2026-018` |
| `/owner/pelanggan` | 200 | Data Pelanggan |
| `/owner/promosi` | 200 | Promosi & Notifikasi |
| `/owner/karyawan` | 200 | Manajemen Karyawan |
| `/owner/laporan` | 200 | Laporan |
| `/owner/pengaturan` | 200 | Pengaturan, Harga Emas per Gram |

### 4.2 Karyawan (12 halaman)

| Halaman | Status |
|---|---|
| `/karyawan` | 200 |
| `/karyawan/produk` | 200 |
| `/karyawan/transaksi` | 200 |
| `/karyawan/transaksi/baru` | 200 |
| `/karyawan/transaksi/84` | 200 |
| `/karyawan/pembukuan` | 200 |
| `/karyawan/pembukuan/baru` | 200 |
| `/karyawan/pesanan-custom` | 200 |
| `/karyawan/pesanan-custom/baru` | 200 |
| `/karyawan/pesanan-custom/28` | 200 |
| `/karyawan/pelanggan` | 200 |
| `/karyawan/promosi` | 200 |

### 4.3 Pelanggan (6 halaman)

| Halaman | Status | Teks kunci |
|---|---|---|
| `/pelanggan` | 200 | Siti |
| `/pelanggan/pesanan` | 200 | Pesanan |
| `/pelanggan/pesanan/22` | 200 | `PD-2026-012` |
| `/pelanggan/riwayat` | 200 | Riwayat |
| `/pelanggan/notifikasi` | 200 | Notifikasi |
| `/pelanggan/profil` | 200 | Profil |

---

## 5. Hasil Aksi Mutasi

### 5.1 HTTP (data dev, dibersihkan kembali)

| Aksi | Hasil |
|---|---|
| Login `yuni@demo.test` (nonaktif) | 303 balik ke `/masuk`, pesan “akun tidak aktif” tampil |
| Login `owner@demo.test` + password salah | 303 balik ke `/masuk`, pesan “Email atau password salah” tampil |
| Login owner valid | 303 ke `/owner` |
| Buat pembukuan `Pengeluaran / Operasional / 12.345` | 303, baris id 122 tercipta |
| Hapus pembukuan id 122 | 303, baris terhapus (count 0) |

### 5.2 Service (PHPUnit, database uji)

| Aksi | Hasil |
|---|---|
| Buat transaksi & kurangi stok | total dan stok benar |
| Stok kurang → rollback | transaksi tidak tersimpan, stok utuh |
| Custom order: histori + DP + notifikasi | 1 histori, 1 pemasukan, 1 notifikasi |
| Custom order: lompat status | ditolak |
| Custom order: progres sampai `Diambil` | 5 histori, 2 pemasukan (DP + pelunasan) |
| Promosi kirim ke semua | notifikasi dibuat untuk setiap pelanggan, status `Terkirim` |
| Promosi kirim dua kali | ditolak |
| Pembukuan: kategori salah | ditolak |
| Pembukuan: update & hapus manual | berhasil |
| Login akun nonaktif | ditolak |
| Akses lintas peran (6 kombinasi) | 403 |

---

## 6. Bug yang Ditemukan dan Diperbaiki

| # | Gejala | Perbaikan | Berkas |
|---|---|---|---|
| 1 | `notifications.created_at` dan `custom_order_histories.created_at` tersimpan `0000-00-00 00:00:00` pada MariaDB non-strict karena `useTimestamps=false` | Aktifkan `useTimestamps`, set `createdField`, kosongkan `updatedField` | `app/Models/NotificationModel.php`, `app/Models/CustomOrderHistoryModel.php` |
| 2 | `store_settings.updated_at` dan `gold_prices.updated_at` tidak pernah diperbarui | Aktifkan `useTimestamps`, kosongkan `createdField`, set `updatedField` | `app/Models/StoreSettingModel.php`, `app/Models/GoldPriceModel.php` |
| 3 | Halaman pelanggan owner membaca `$customers`, `$total`, `$search`, `order_count` yang tidak dikirim controller | Pakai `$result['items']`, `$result['total']`, `$filters['search']`, `total_transactions` | `app/Views/pages/owner/customers/index.php` |
| 4 | Halaman pelanggan karyawan memakai variabel yang sama salahnya | Sama seperti #3 | `app/Views/pages/staff/customers/index.php` |
| 5 | Halaman karyawan owner membaca `$employees` dan kolom `username`/`phone` yang tidak ada di tabel `users`, plus tautan “Tambah Karyawan” tanpa rute | Pakai `$result`, kolom `name`/`email`/`created_at`/`is_active`, hapus tautan mati | `app/Views/pages/owner/employees/index.php` |
| 6 | Halaman promosi owner membaca `$promotions`, kolom tanggal yang tidak ada, dan tautan “Buat Promosi” tanpa rute | Pakai `$result`, kolom `created_at`/`send_at`/`target_type`/`recipient_count`, hapus tautan mati, sambungkan tombol Kirim ke rute POST yang ada | `app/Views/pages/owner/promotions/index.php` |
| 7 | Halaman promosi karyawan membaca `$promotions` dan `start_date`/`end_date` | Pakai `$result` dan kolom yang benar | `app/Views/pages/staff/promotions.php` |
| 8 | Halaman laporan owner membaca `$report['total_sales']` dan `$start/$end` | Pakai `$sales`, `$finances`, `$stock`, `$customOrders`, `$startDate`, `$endDate` | `app/Views/pages/owner/reports/index.php` |
| 9 | Halaman pengaturan owner memakai form POST tanpa rute (berisiko 405) | Jadikan read-only dari `$store` dan `$goldPrices` | `app/Views/pages/owner/settings/index.php` |
| 10 | Dashboard owner menampilkan stok produk `0`, transaksi kosong, dan pesanan aktif kosong padahal data ada | `DashboardService` mengirim `total_products`, `recent_transactions`, `active_order_list`; view merender datanya | `app/Services/DashboardService.php`, `app/Views/pages/owner/dashboard/index.php` |
| 11 | Dashboard karyawan membaca `today_transactions`/`active_orders` yang tidak dikirim | Pakai `transactions_today`/`active_custom_orders` | `app/Views/pages/staff/dashboard.php` |
| 12 | Detail transaksi owner/karyawan hanya menampilkan 3 field, item tidak tampil | Tampilkan pelanggan, kasir, tanggal, metode, tabel item, diskon, total | `app/Views/pages/{owner,staff}/transactions/show.php` |
| 13 | Detail pesanan custom owner/karyawan hanya menampilkan 3 field, riwayat dan aksi status tidak ada | Tampilkan detail lengkap, riwayat status, dan form POST status berikutnya | `app/Views/pages/{owner,staff}/custom_orders/show.php` |
| 14 | Login memakai akun demo lama (`@tokomasgembira.id`, password `gembira123`), tombol “Lupa kata sandi?” tanpa rute, label “Nomor HP / Email” padahal autentikasi hanya email | Perbarui quick-fill ke `@demo.test` / `Gembira#2026`, hapus tombol mati, ubah label jadi “Email” | `app/Views/pages/public/login.php` |

Catatan: perbaikan #9 dan #14 sengaja memilih menghapus/menonaktifkan kontrol yang tidak punya backend, bukan menambah fitur, sesuai batasan tugas.

---

## 7. Fitur yang Belum Dapat Diuji / Diperbaiki (Gap)

Fitur berikut tidak memiliki rute/backend sehingga tidak diuji sebagai alur penuh. Tidak ada fitur baru yang ditambahkan.

| Fitur | Kondisi |
|---|---|
| CRUD produk | Hanya daftar/filter; tidak ada create/update/delete |
| CRUD karyawan | Hanya daftar/filter; tautan tambah sudah dihapus |
| Simpan pengaturan toko & harga emas | Hanya baca; tidak ada rute POST |
| Export laporan | Tidak ada rute/implementasi |
| Buat promosi | Tidak ada form/rute; hanya daftar dan kirim |
| Edit pembukuan | Service ada, tidak ada rute/form edit |
| Edit pelanggan | Hanya daftar |
| Pelanggan membuat/mengubah custom order | Hanya lihat daftar & detail |
| Tandai notifikasi dibaca (POST) | Hanya daftar; `NotificationService` sudah siap tetapi tidak ada rute |
| Ubah profil pelanggan | Halaman hanya baca |
| Badge notifikasi header | Tidak bisa berkurang setelah dibaca tanpa state/route bersama |
| Riwayat harga emas | Tidak mungkin tanpa migrasi baru; PK `gold_prices` adalah `purity` |
| Grafik dashboard | Masih placeholder (bukan bug fungsional) |
| View flat lama `pages/staff/{customers,custom_orders,transactions,finances,products}.php` | Tidak terpakai (dead code) dan masih memuat tautan lama; akan diputuskan pada Bagian 4 |

---

## 8. Perintah Reproduksi

```powershell
# Tes otomatis
vendor\bin\phpunit --no-coverage

# Verifikasi data demo
php spark demo:verify

# Siklus seed/clean pada DB uji
php spark demo:test-cycle

# Server pengembangan
php spark serve --port 8080
```

---

## 9. Kesimpulan

- Seluruh halaman Owner, Karyawan, dan Pelanggan yang memiliki rute berhasil dirender dengan data demo tanpa error.
- Bug yang terbukti (timestamp model, kontrak view/controller, data dashboard, tautan mati) sudah diperbaiki dan dilindungi tes regresi.
- Aksi inti (transaksi, custom order, pembukuan, promosi, auth, otorisasi) lulus baik di level service maupun HTTP.
- Gap yang tersisa adalah fitur yang memang belum memiliki backend; daftarnya diserahkan ke Bagian 4 untuk keputusan produk, bukan ditambal diam-diam.
