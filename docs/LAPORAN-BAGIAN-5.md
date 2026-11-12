# Laporan Bagian 5 — Backend, Grafik, dan Pembersihan Data Lama

> Proyek: **Toko Mas Gembira** (CodeIgniter 4.7.4, view PHP, Tailwind CLI, Alpine.js, Chart.js)
> Branch: `hadaffi`
> Tanggal: **12 November 2026**
> Database: `db_tokogembira` (dev) dan `db_tokogembira_test` (PHPUnit)
> Status: siap di-push setelah seluruh pemeriksaan lulus.

---

## 1. Ringkasan

Bagian ini menindaklanjuti tujuh keputusan pengguna: menghapus dead code, menambahkan backend yang sebelumnya tidak ada, memperbaiki grafik dashboard dengan data asli, membersihkan data demo lama, lalu memverifikasi dan menyerahkan perubahan.

| Pekerjaan | Hasil |
|---|---|
| Dead code | 5 view flat `pages/staff/*` dan `Staff\PageController` dihapus; tidak ada referensi tersisa |
| Backend baru | CRUD produk, CRUD karyawan, pengaturan + harga emas, buat/kirim promosi, edit pembukuan, edit pelanggan, custom order pelanggan, mark-read notifikasi, ubah profil + kata sandi |
| Grafik dashboard | Line chart penjualan 7 hari dan bar chart pemasukan/pengeluaran 6 bulan memakai data asli dari `DashboardService`; Chart.js dimuat lewat CDN |
| Data demo lama | 3 user, 1 customer, dan 2 notifikasi lama dibersihkan lewat `php spark demo:clean-legacy`; data `@demo.test` tidak tersentuh |
| Verifikasi | PHPUnit **41 tes / 244 assertion OK**; smoke **30 halaman kunci FAILURES=0**; `php spark demo:verify` semua PASS; `npm run build` sukses |

Tidak ada perubahan pada `design/`, tidak ada migrasi lama yang diubah, dan tidak ada migrasi baru.

---

## 2. Dead Code yang Dihapus

| Berkas | Alasan |
|---|---|
| `app/Controllers/Staff/PageController.php` | Tidak dirujuk route mana pun dan hanya merender view flat lama |
| `app/Views/pages/staff/customers.php` | Duplikat halaman karyawan yang sudah punya view khusus |
| `app/Views/pages/staff/custom_orders.php` | Sama |
| `app/Views/pages/staff/transactions.php` | Sama |
| `app/Views/pages/staff/finances.php` | Sama |
| `app/Views/pages/staff/products.php` | Sama |

`pages/staff/dashboard.php` dan `pages/staff/promotions.php` tetap dipertahankan karena masih dipakai controller karyawan.

---

## 3. Backend yang Ditambahkan

### 3.1 Validasi baru

| Kelas | Dipakai oleh |
|---|---|
| `EmployeeRules` | CRUD karyawan, reset kata sandi |
| `StoreSettingRules` | Pengaturan toko dan notifikasi |
| `PromotionRules` | Buat promosi |
| `CustomerRules` | Edit pelanggan dari sisi owner |
| `ProfileRules` | Ubah profil dan ganti kata sandi pelanggan |

### 3.2 Controller dan route

| Fitur | Controller | Route utama |
|---|---|---|
| CRUD produk | `Owner\ProductController` | `GET/POST owner/produk`, `owner/produk/baru`, `owner/produk/{id}/ubah`, `owner/produk/{id}/hapus` |
| CRUD karyawan | `Owner\EmployeeController` | `owner/karyawan`, `owner/karyawan/baru`, `owner/karyawan/{id}/ubah`, `owner/karyawan/{id}/toggle`, `owner/karyawan/{id}/kata-sandi` |
| Pengaturan + harga emas | `Owner\SettingController` | `GET/POST owner/pengaturan` |
| Buat/kirim promosi | `Owner\PromotionController` | `owner/promosi`, `owner/promosi/baru`, `POST owner/promosi`, `POST owner/promosi/{id}/kirim` |
| Edit pembukuan | `Owner\FinanceController` | `owner/pembukuan/{id}/ubah`, `POST owner/pembukuan/{id}` |
| Edit pelanggan | `Owner\CustomerController` | `owner/pelanggan/{id}/ubah`, `POST owner/pelanggan/{id}` |
| Custom order pelanggan | `Customer\OrderController` | `pelanggan/pesanan`, `pelanggan/pesanan/baru`, `pelanggan/pesanan/{id}/ubah`, `POST pelanggan/pesanan/{id}` |
| Mark-read notifikasi | `Customer\NotificationController` | `POST pelanggan/notifikasi/{id}/dibaca`, `POST pelanggan/notifikasi/dibaca-semua` |
| Profil pelanggan | `Customer\ProfileController` | `POST pelanggan/profil`, `POST pelanggan/profil/kata-sandi` |

Catatan keamanan dan perilaku:

- Semua aksi mutasi memakai `POST` + `csrf_field()` dan berada di balik filter `auth` + `role`.
- Aksi hapus produk dan pembukuan memakai varian tombol `danger` + dialog konfirmasi.
- Pembukuan otomatis dari transaksi/custom order tetap terkunci: `FinanceService` menolak update/hapus, dan UI menampilkan label “Terkunci”.
- Pelanggan hanya dapat mengubah custom order saat status masih `Masuk`; DP selalu diisi `0` dari sisi pelanggan dan pembayaran dilakukan di toko.
- Pelanggan hanya dapat menandai notifikasi miliknya sendiri (`NotificationService::markAsRead` memeriksa `customer_id`).
- Route `POST pelanggan/profil` sengaja tanpa ID; controller memakai customer milik sesi.

### 3.3 Upload gambar

`App\Services\UploadService` memvalidasi ekstensi, MIME type, dan ukuran maksimal 2 MB sebelum menyimpan ke `public/uploads/{products,custom-orders}`. Nama berkas diacak, dan penggantian gambar lama menghapus berkas lama dengan pengecekan `basename()`.

### 3.4 View baru / diperbarui

- Owner: form produk, form karyawan, reset kata sandi, form promosi, form pelanggan, form pembukuan (`_form`, `create`, `edit`), serta index yang diselaraskan.
- Pelanggan: form pengajuan/ubah custom order, detail pesanan dengan tombol ubah, notifikasi dengan filter dan mark-read, profil dengan form data + ganti kata sandi.
- `components/ui/form_field.php` kini mendukung `extraAttrs` untuk atribut seperti `step`, `min`, dan `max`.

---

## 4. Grafik Dashboard Owner

`DashboardService::getOwnerStats()` sekarang mengembalikan:

- `sales_last_7_days` — label tanggal dan total penjualan `Lunas` per hari;
- `cashflow_last_6_months` — label bulan, pemasukan, dan pengeluaran;
- `sales_yesterday`, `transactions_yesterday`, dan `sales_change_percent` untuk tren stat card.

Dashboard merender dua canvas:

| Grafik | Tipe | Data |
|---|---|---|
| Penjualan 7 hari | Line + area gradient | `sales_last_7_days` |
| Pemasukan vs pengeluaran | Bar | `cashflow_last_6_months` |

Chart.js 4.4.1 dimuat dari CDN di section `scripts` layout dashboard. Warna memakai token proyek: gold `#D4AF37`, crimson `#8B0000`, charcoal `#1C1C1C`. Tooltip menampilkan rupiah, sumbu Y memakai format ringkas (`rb`/`jt`), dan container memakai tinggi tetap agar responsif tanpa scroll horizontal.

---

## 5. Pembersihan Data Demo Lama

Perintah baru:

```powershell
php spark demo:clean-legacy
```

`LegacyDemoCleanSeeder` hanya menargetkan sidik jari demo lama:

- domain `@tokomasgembira.id`;
- akun login lama `siti@example.com`;
- customer yang terhubung ke user lama tersebut;
- transaksi, custom order, histori, pembukuan, promosi, dan notifikasi turunannya.

Data `@demo.test`, kode `DM-*`, dan order `PD-2026-*` tidak pernah disentuh. Seeder idempoten; run kedua menghasilkan `0 user(s), 0 customer(s)`.

Hasil pada DB dev:

| Objek | Sebelum | Sesudah |
|---|---:|---:|
| User lama | 3 | 0 |
| Customer lama | 1 | 0 |
| Notifikasi lama | 2 | 0 |
| User demo | 16 | 16 |
| Customer demo | 15 | 15 |

`php spark demo:verify` tetap PASS setelah pembersihan.

---

## 6. Verifikasi

| Pemeriksaan | Hasil |
|---|---|
| `php -l` pada 38 berkas berubah/baru | 0 error |
| PHPUnit | **OK (41 tes, 244 assertion)** |
| Smoke HTTP 30 halaman kunci | `FAILURES=0` |
| Mutasi HTTP yang diuji | Produk create/update/delete, pembukuan create/update/delete, karyawan create/toggle/reset, edit pelanggan, buat promosi, simpan pengaturan, customer order create/update, mark-read notifikasi, ubah profil, ganti kata sandi |
| `php spark demo:verify` | Semua PASS |
| `php spark demo:clean-legacy` (run kedua) | 0 baris terhapus (idempoten) |
| `npm run build` | Sukses (hanya peringatan Browserslist usang) |

Data uji mutasi (produk `SMK-001`, karyawan smoke, customer/order smoke, pembukuan smoke, promosi smoke) sudah dihapus kembali dari DB dev. Pengaturan toko dan harga emas dikembalikan ke nilai seed.

---

## 7. Berkas Utama

| Kelompok | Berkas |
|---|---|
| Controller | `Owner/{Product,Employee,Setting,Promotion,Finance,Customer}Controller.php`, `Customer/{Order,Notification,Profile}Controller.php` |
| Service | `UploadService.php`, `DashboardService.php` |
| Validasi | `EmployeeRules`, `StoreSettingRules`, `PromotionRules`, `CustomerRules`, `ProfileRules` |
| Route | `app/Config/Routes/owner.php`, `app/Config/Routes/customer.php` |
| View owner | `pages/owner/{products,employees,settings,promotions,customers,finances,dashboard}/*` |
| View pelanggan | `pages/customer/{orders,order_detail,notifications,profile}.php`, `pages/customer/orders/form.php` |
| Pembersihan lama | `app/Database/Seeds/LegacyDemoCleanSeeder.php`, `app/Commands/DemoCleanLegacy.php` |
| Tes | `tests/feature/Business/AdminCrudBusinessTest.php`, `DashboardBusinessTest.php`, `tests/feature/Web/RoutePageTest.php` |

---

## 8. Keterbatasan

- Riwayat harga emas tetap tidak tersedia karena `gold_prices` memakai `purity` sebagai primary key; keputusan pengguna adalah tidak menambah migrasi.
- Badge notifikasi di header belum memakai state bersama; mark-read memperbarui data di halaman notifikasi, bukan badge global.
- Export laporan belum termasuk dalam lingkup Bagian 5.
