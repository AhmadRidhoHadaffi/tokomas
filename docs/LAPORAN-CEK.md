# LAPORAN CEK — Tombol, Kesiapan Data, dan Rencana Perbaikan

> Proyek: **Toko Mas Gembira** (CodeIgniter 4.7.4, view PHP, Tailwind CLI, Alpine.js, Chart.js)
> Branch: `hadaffi` · Commit terakhir: `ca5d1e3 bikin frontend owner karyawan dan pelanggan`
> Tanggal cek: 9 November 2026 · Lingkungan: `development`
> Dokumen ini ditulis **sebelum** ada perubahan kode pada Bagian 2–4.

---

## 0. Ringkasan Eksekutif

| Area | Kondisi awal | Risiko |
|---|---|---|
| Tombol | 62 kandidat elemen, **59 tombol nyata** di **37 file**. Hanya 8 pemakaian `components/ui/button`; sisanya class ad-hoc. | Tinggi — tampilan & state tidak konsisten, beberapa tombol berujung 404/405. |
| Kesiapan data | 13 tabel; hanya `users` (3), `customers` (1), `products` (5), `gold_prices` (4), `store_settings` (1) berisi. 8 tabel transaksi kosong. | Tinggi — dashboard, grafik, laporan, riwayat, notifikasi tidak bisa diuji. |
| Kontrak Controller ↔ View | **7 halaman owner/staff** memakai variabel yang tidak dikirim controller. | Kritis — halaman tampil kosong/undefined key. |
| Semantik & keamanan | 4 route `/test/*` terbuka di semua environment; beberapa `<button>` tanpa `type`; tombol mati. | Tinggi — akses tidak sah & submit tak sengaja. |
| Baseline tes | `OK (24 tests, 136 assertions)`; `npm run build` sukses. | Acuan agar tidak turun. |

**Kesimpulan:** antarmuka pelanggan sudah matang, tetapi area owner/karyawan masih berupa kerangka. Seeder lengkap + perbaikan kontrak view diperlukan sebelum uji fitur bisa bermakna.

---

## 1. Metodologi Audit Tombol

1. Pemindaian statis seluruh `app/Views` dan `app/Cells` untuk `<button>`, `<a>` bergaya tombol, `<input type=submit>`, tombol ikon, tab, paginasi, dan tombol di modal.
2. Setiap temuan dibandingkan dengan spesifikasi `design/src/components/ui/Button.tsx`:
   - **Varian:** `primary`, `outline`, `outlineDark`, `outlineLight`, `ghost`, `danger`, `dark`, `light`.
   - **Ukuran:** `sm` (`h-9 px-3.5 text-xs gap-1.5`), `md` (`h-11 px-5 text-sm gap-2`), `lg` (`h-12 px-6 text-[15px] gap-2`).
   - **Base:** `inline-flex items-center justify-center whitespace-nowrap rounded-lg font-medium`, transisi 150ms, `active:scale-[0.98]`, `disabled:pointer-events-none disabled:opacity-50`, `focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-gold/30`.
3. Pemeriksaan manual konteks form (method, CSRF, `type`), target sentuh (≥40 px), dan tujuan `href`.
4. Hasil mentah: `C:\Users\ahmad\AppData\Local\Temp\opencode\buttons.json` (62 entri).

### Kode masalah yang dipakai

| Kode | Arti |
|---|---|
| **T1** | Class tombol ad-hoc, tidak memakai `components/ui/button`. |
| **T2** | Tidak ada state hover/active/focus-visible yang lengkap. |
| **T3** | `<button>` tanpa `type` (default `submit`, rawan submit tak sengaja). |
| **T4** | Target sentuh < 40 px. |
| **T5** | Tombol ikon tanpa `aria-label`. |
| **T6** | Mengarah ke route yang tidak terdaftar (404) / method salah (405). |
| **T7** | Aksi berbahaya tanpa varian `danger` + konfirmasi. |
| **T8** | Tidak ada state `disabled`/`loading`. |
| **T9** | Varian/ukuran tidak konsisten untuk fungsi yang sama. |
| **T10** | Kredensial demo hard-coded di UI. |
| **T11** | Tombol mati (tidak melakukan apa pun). |
| **T12** | Form tanpa CSRF / method tidak sesuai. |
| **T13** | `<a>` dipakai untuk aksi non-navigasi. |
| **T14** | Teks/ikon berpotensi meluap atau tidak sejajar di layar kecil. |

---

## 2. Tabel Temuan Tombol

### 2.1 Temuan kritis (wajib diperbaiki)

| # | File:baris | Jenis | Masalah | Kode | Severitas |
|---|---|---|---|---|---|
| 1 | `pages/public/login.php:78` | `<button type=button>` "Lupa kata sandi?" | Tidak punya handler/route; tombol mati dan menyesatkan. | T11 | **Kritis** |
| 2 | `pages/owner/employees/index.php:5` | `<a>` "Tambah Karyawan" | `owner/karyawan/buat` tidak ada → 404. | T6 | **Kritis** |
| 3 | `pages/owner/employees/index.php:12` | `<a>` "Edit" | `owner/karyawan/{id}` tidak ada → 404. | T6 | **Kritis** |
| 4 | `pages/owner/promotions/index.php:5` | `<a>` "Buat Promosi" | `owner/promosi/buat` tidak ada → 404. | T6 | **Kritis** |
| 5 | `pages/owner/settings/index.php:5` | `<form method=post>` + submit | Route `POST owner/pengaturan` tidak ada (hanya GET) → 405; data tidak tersimpan. | T6/T12 | **Kritis** |
| 6 | `pages/staff/transactions.php:5` | `<a>` "Transaksi Baru" | Prefix salah `staff/transaksi/buat` → 404. | T6 | **Kritis** |
| 7 | `pages/staff/finances.php:5` | `<a>` "Tambah Catatan" | `staff/keuangan/buat` → 404. | T6 | **Kritis** |
| 8 | `pages/staff/custom_orders.php:5` | `<a>` "Order Baru" | `staff/custom-order/buat` → 404. | T6 | **Kritis** |
| 9 | `pages/staff/custom_orders.php:11` | `<a>` kode pesanan | `staff/custom-order/{id}` → 404. | T6 | **Kritis** |
| 10 | `pages/staff/transactions.php:11` | `<a>` invoice | `staff/transaksi/{id}` → 404. | T6 | **Kritis** |
| 11 | `app/Config/Routes.php:17-20` | Route `/test/*` | Terbuka di produksi; mem-bypass auth & menampilkan data dummy. | — | **Kritis (keamanan)** |
| 12 | `components/layout/sidebar.php:84` + `customer_topbar.php:77` + `profile.php:169` | Logout | Sudah POST + CSRF (baik), tetapi bentuk belum memakai varian `danger`/`ghost` komponen. | T1/T7 | Sedang |

### 2.2 Tombol ad-hoc yang harus dimigrasikan ke komponen

| # | File:baris | Label/fungsi | Class saat ini | Masalah | Severitas |
|---|---|---|---|---|---|
| 13 | `pages/owner/custom_orders/create.php:3` | Simpan | `rounded-lg bg-gold px-4 py-2` | Tanpa `type`, hover/focus/active, tinggi ±40. | T1,T2,T3,T8 |
| 14 | `pages/owner/finances/create.php:4` | Simpan | idem | Tanpa `type`, state tidak lengkap. | T1,T2,T3,T8 |
| 15 | `pages/owner/finances/index.php:4` | Filter | idem | Tanpa `type`, target < 44. | T1,T2,T3 |
| 16 | `pages/owner/products/index.php:12` | Cari | `bg-gold px-4 py-2 font-medium` | Bukan gradient/token komponen. | T1,T2 |
| 17 | `pages/owner/customers/index.php:11` | Cari | idem | Bukan komponen. | T1,T2 |
| 18 | `pages/owner/transactions/index.php:6` | Cari | idem | Bukan komponen. | T1,T2 |
| 19 | `pages/owner/transactions/create.php:4` | Simpan | idem | Bukan komponen. | T1,T2 |
| 20 | `pages/owner/reports/index.php:9` | Tampilkan | idem | Bukan komponen; form memakai `start`/`end` yang tidak dibaca controller. | T1,T6 |
| 21 | `pages/owner/settings/index.php:13` | Simpan Perubahan | `px-5 py-2.5` | Tinggi 42, bukan `h-11`. | T1,T9 |
| 22 | `pages/staff/custom_orders/create.php:3` | Simpan | `bg-gold px-4 py-2` | Tanpa `type`. | T1,T2,T3 |
| 23 | `pages/staff/finances/create.php:4` | Simpan | idem | Tanpa `type`. | T1,T2,T3 |
| 24 | `pages/staff/finances/index.php:4` | Filter | idem | Tanpa `type`. | T1,T2,T3 |
| 25 | `pages/staff/products/index.php:12` | Cari | idem | Bukan komponen. | T1 |
| 26 | `pages/staff/customers/index.php:10` | Cari | idem | Bukan komponen. | T1 |
| 27 | `pages/staff/transactions/index.php:6` | Cari | idem | Bukan komponen. | T1 |
| 28 | `pages/staff/transactions/create.php:4` | Simpan | idem | Bukan komponen. | T1 |
| 29 | `pages/staff/custom_orders.php:11` | Filter | idem | Opsi status memakai nilai Inggris (`pending` dsb.) yang tidak ada di enum. | T1,T9 |
| 30 | `pages/staff/products.php:11`, `staff/customers.php:10`, `staff/finances.php:12`, `staff/transactions.php:11` | Cari/Filter | idem | View legacy duplikat; route aktif memakai `.../index.php`. | T1,T11 |
| 31 | `pages/customer/history.php:247` | Tutup | `h-11 ... light` manual | Duplikasi persis varian `light`; rawan drift. | T1 |
| 32 | `pages/customer/history.php:254` | Cetak Nota | `h-11 ... primary` manual | Duplikasi varian `primary`; tanpa ikon printer. | T1 |
| 33 | `pages/customer/profile.php:95` | Edit Profil | `h-11 ... outline` manual | Duplikasi varian `outline`. | T1 |
| 34 | `pages/customer/profile.php:150` | Batal | `h-11 ... light` manual | Duplikasi varian `light`; `gap-2` hilang. | T1,T14 |
| 35 | `pages/customer/profile.php:157` | Simpan Perubahan | `h-11 ... primary` manual | Duplikasi varian `primary`. | T1 |
| 36 | `pages/customer/profile.php:169` | Keluar | teks `text-crimson` manual | Bukan `ghost/danger` sesuai desain. | T1,T7 |
| 37 | `pages/customer/notifications.php:66` | Tandai semua dibaca | `h-10 ... text-xs` manual | Tinggi 40 ≠ token `sm` (36) atau `md` (44); inkonsisten. | T1,T9 |
| 38 | `pages/customer/orders.php:105` | Lihat detail (dinamis) | primary `h-11` manual | Duplikasi komponen. | T1 |
| 39 | `pages/customer/notifications.php:112` | Buka notifikasi (dinamis) | manual | Duplikasi komponen. | T1 |
| 40 | `pages/customer/home.php:124` | Tutup promosi | `h-10 w-10 rounded-full` | Tombol ikon ad-hoc; belum ada varian ikon di komponen. | T1 |
| 41 | `pages/public/landing.php:209` | Lihat Produk Kami | primary `lg` manual | Duplikasi komponen; `focus-visible` di urutan akhir. | T1 |
| 42 | `pages/public/landing.php:348` | Pilih produk (dinamis) | manual | Duplikasi komponen. | T1 |
| 43 | `pages/public/landing.php:389` | Tutup detail produk | `rounded-lg p-2` | Target ±36 px. | T4 |
| 44 | `components/layout/public_navbar.php:60-66` | Masuk / Daftar (desktop) | `h-9 px-5 text-xs` | Padding `px-5` ≠ token `sm` (`px-3.5`); duplikasi komponen. | T1,T9 |
| 45 | `components/layout/public_navbar.php:110-116` | Masuk / Daftar (mobile) | `h-11` manual | Duplikasi komponen; transisi `transition-colors` ≠ base. | T1 |
| 46 | `components/layout/public_navbar.php:54` | Menu mobile | `p-2` | Target ±40 px; aria dinamis sudah baik. | T4 |
| 47 | `components/layout/header.php:61,79` | Menu & notifikasi | `p-2`, ring-2 | Target 40; ring 2 ≠ 4 sesuai desain. | T4,T2 |
| 48 | `components/layout/customer_topbar.php:77` | Keluar (ikon) | `h-10 w-10` | Sudah 40; ad-hoc. | T1 |
| 49 | `components/layout/sidebar.php:84` | Keluar | `min-h-10` | 40 px, ad-hoc, tidak `danger`. | T1,T7 |
| 50 | `partials/flash.php:30,48,66,84` | Tutup notifikasi | tanpa padding, ikon `h-4` | Target ±16–20 px, **tanpa `aria-label`**. | T4,T5 |
| 51 | `components/ui/modal.php:87` | Tutup modal | `p-2` + ikon 20 | Target ±36 px. | T4 |
| 52 | `components/ui/table.php:22` | Sort kolom | `inline-flex gap-1.5` | Tanpa `aria-sort`, target < 40, tidak ada indikator arah. | T4,T5 |
| 53 | `components/ui/tabs.php:32,58` | Tab | `px-4 py-2/py-3` | Tinggi ±36–40; `role=tab` tanpa `aria-controls`/navigasi panah. | T4 |
| 54 | `components/ui/pagination.php:44-83` | Paginasi | `h-9 w-9` | Target 36 px; halaman aktif `<span>` tidak bisa difokus. | T4 |
| 55 | `pages/customer/history.php:75,82` | Sort tanggal/total | `py-3 -my-3` | Target cukup, tetapi tanpa `aria-sort`. | T5 |
| 56 | `pages/customer/history.php:224` | Tutup modal | `h-10 w-10` | 40 px; ad-hoc. | T1 |
| 57 | `pages/public/login.php:114-134` | Owner/Karyawan/Pelanggan quick-fill | `min-h-[40px]` | Kredensial hard-coded (`gembira123`), akan usang setelah seeder baru. | T10 |
| 58 | `pages/public/login.php:61`, `register.php:88,119` | Toggle password | `h-10 w-10` | 40 px, aria baik; ad-hoc. | T1 |

### 2.3 Temuan komponen `components/ui/button.php`

| # | Masalah | Dampak | Severitas |
|---|---|---|---|
| C1 | Belum mendukung **ikon kiri/kanan**, **ikon saja** (`iconOnly`), **full width**, **loading**, `name`/`value`, `target`, `rel`, `aria-label`, `aria-disabled`. | Banyak kebutuhan tombol desain tidak bisa dipenuhi komponen. | Tinggi |
| C2 | `href` + `disabled` tidak menghasilkan `aria-disabled`/`tabindex=-1`; `<a>` tetap bisa diklik. | State disabled palsu. | Sedang |
| C3 | `onclick` di-escape ke atribut; tidak ada jalur aman untuk atribut Alpine (`@click`, `:class`). | Komponen sulit dipakai untuk tombol modal/dropdown. | Sedang |
| C4 | Base class sudah sama dengan desain, tetapi `focus-visible:outline-none` ada di desain dan di komponen (OK). | — | Info |
| C5 | Tidak ada `title`/`aria-label` otomatis untuk tombol ikon. | Aksesibilitas. | Sedang |

### 2.4 Yang sudah baik (dipertahankan)

- Logout customer topbar, sidebar, dan profil sudah `<form method=post>` + `csrf_field()`.
- Tombol ikon header/customer topbar sudah punya `aria-label`.
- Tab memakai `type="button"` dan `role="tab"`.
- Tombol modal memakai `type="button"`.
- Tidak ditemukan `<a href="#">` sebagai aksi (kecuali anchor navigasi sah).
- Tombol WhatsApp FAB: `aria-label`, 56 px, `rel="noreferrer"` — aman.

---

## 3. Audit Kesiapan Data

### 3.1 Tabel dan jumlah baris (database dev `db_tokogembira`, sebelum seeding)

| Tabel | Baris | Relasi utama | Status |
|---|---:|---|---|
| `users` | 3 | — | Ada owner, 2 karyawan? (1 pelanggan) |
| `customers` | 1 | `user_id → users.id` (unik) | **Bug lama:** `user_id=1` (owner), bukan user pelanggan. |
| `products` | 5 | — | Semua kategori belum terwakili. |
| `transactions` | 0 | `customer_id`, `user_id` | Kosong. |
| `transaction_details` | 0 | `transaction_id`, `product_id` (unik per transaksi) | Kosong. |
| `custom_orders` | 0 | `customer_id` | Kosong. |
| `custom_order_histories` | 0 | `custom_order_id`, `changed_by` | Kosong. |
| `finances` | 0 | `user_id`, `transaction_id` (unik), `custom_order_id` | Kosong. |
| `promotions` | 0 | `user_id` | Kosong. |
| `promotion_customers` | 0 | PK (`promotion_id`,`customer_id`) | Kosong. |
| `notifications` | 0 | `customer_id`, `promotion_id` | Kosong. |
| `store_settings` | 1 | — | Ada 1 baris. |
| `gold_prices` | 4 | PK `purity`, `updated_by → users.id` | 4 kadar, tanpa riwayat. |

**Total tabel aplikasi: 13.** Tidak ada perubahan migration yang dilakukan.

### 3.2 Keterbatasan skema yang ditemukan

1. **`gold_prices` tidak menyimpan riwayat.** Primary key adalah `purity`, sehingga hanya satu harga per kadar. Permintaan "riwayat beberapa tanggal" **tidak bisa** dipenuhi tanpa migration baru → akan dilaporkan sebagai gap dan **tidak** diubah tanpa persetujuan.
2. **`users` tidak punya `username`, `phone`, `deleted_at`.** View `owner/employees/index.php` membaca `username` dan `phone` yang tidak ada.
3. **`products` tidak punya `metal`.** `ProductRules` mewajibkan `metal` & `karat`, sedangkan tabel memakai `purity` → aturan tidak pernah dipakai (tidak ada route produk CRUD).
4. **`promotions` tidak punya `start_date`/`end_date`.** View owner/staff membaca kolom tersebut → undefined index. Kolom yang ada: `send_at`.
5. **`transactions.payment_status` hanya `Lunas`/`Menunggu`** (tidak ada `Batal`/`DP`).
6. **`store_settings` hanya punya `updated_at`** (tanpa `created_at`).
7. **`custom_order_histories.created_at` dan `notifications.created_at` wajib (NOT NULL)**, tetapi tidak ada di `allowedFields` dan `useTimestamps=false`. MySQL di mesin ini tidak `STRICT_TRANS_TABLES`, jadi insert berhasil dengan **`0000-00-00 00:00:00`** → timeline & urutan notifikasi rusak. **Bug terbukti.**
8. **`store_settings.updated_at` dan `gold_prices.updated_at` tidak diperbarui** oleh model (`useTimestamps=false`) → "dicatat oleh/diperbarui" tidak akurat.

### 3.3 Seeder yang sudah ada

| File | Isi | Masalah |
|---|---|---|
| `DemoSeeder.php` | 3 user, 1 customer, 5 produk, 1 setting, 4 harga emas. | Tidak idempoten; password `owner123`/`karyawan123`/`pelanggan123`; `$userId` berisi **bool** dari `insert()` sehingga `customers.user_id = 1`; tidak ada transaksi/order/pembukuan/promosi/notifikasi. |
| `TestSeeder.php` | 4 user, 2 customer, 2 produk, 1 setting. | Layak untuk PHPUnit; **tidak boleh diubah perilakunya**. |

### 3.4 Halaman yang kosong/tidak bisa diuji karena data

| Halaman | Dampak tanpa data | Catatan |
|---|---|---|
| Owner dashboard | Angka 0, grafik placeholder, tabel kosong. | Grafik memang belum diintegrasikan (gap fitur). |
| Owner transaksi & pembukuan | Tabel kosong; filter tidak bermakna. | — |
| Owner pesanan custom | Tabel kosong; timeline tidak ada. | `show.php` hanya 4 baris. |
| Owner pelanggan/karyawan/promosi | Tabel kosong **dan** variabel tidak cocok (lihat §4). | — |
| Owner laporan | `$report` tidak pernah dikirim → halaman error/kosong. | — |
| Owner pengaturan | `$settings` tidak pernah dikirim → form kosong. | POST 405. |
| Karyawan dashboard | `$tasks` tidak dikirim; key statistik tidak cocok. | — |
| Karyawan pelanggan/promosi | Variabel tidak cocok. | — |
| Pelanggan beranda/pesanan/riwayat/notifikasi | Semua kosong (tidak ada transaksi/order/notifikasi). | — |

---

## 4. Bug Kontrak Controller ↔ View (terbukti dari kode)

| # | Halaman | Controller mengirim | View membaca | Akibat | Severitas |
|---|---|---|---|---|---|
| V1 | `owner/customers/index` | `result`, `filters` | `$customers`, `$total`, `$search` | Tabel kosong, "0 pelanggan". | Kritis |
| V2 | `owner/employees/index` | `result`, `filters` | `$employees`, `username`, `phone` | Tabel kosong; kolom tidak ada. | Kritis |
| V3 | `owner/promotions/index` | `result` | `$promotions`, `start_date`, `end_date` | Kosong/undefined index. | Kritis |
| V4 | `owner/reports/index` | `sales`, `finances`, `stock`, `customOrders`, `startDate`, `endDate` | `$report`, `$start`, `$end`, `$report['top_products']` | Halaman error/kosong. | Kritis |
| V5 | `owner/settings/index` | `store`, `goldPrices` | `$settings` | Form kosong; POST 405. | Kritis |
| V6 | `staff/customers/index` | `result`, `filters` | `$customers`, `$search` | Tabel kosong. | Kritis |
| V7 | `staff/promotions` | `result` | `$promotions`, `start_date`, `end_date` | Kosong/undefined index. | Kritis |
| V8 | `staff/dashboard` | `stats` (key: `sales_today`, `transactions_today`, `active_custom_orders`, `low_stock_count`) | `active_orders`, `low_stock`, `new_customers`, `$tasks` | Semua 0; daftar tugas kosong. | Tinggi |
| V9 | `owner/dashboard/index` | `stats` | `sales_today` dll. (cocok) | **Cocok**; tetapi stok hard-code "0 item" & grafik placeholder. | Sedang |
| V10 | `pages/owner/*/show.php` | `transaction`/`order` | Cocok sebagian | Tidak menampilkan item/timeline lengkap. | Sedang |
| V11 | `layouts/dashboard.php` / `header.php` | — | Label role `'Karyawan A� Kasir'` | Mojibake (encoding rusak). | Sedang |

---

## 5. Cakupan Route (kondisi awal)

| Role | Route GET | Route POST | Fitur yang **belum** punya route |
|---|---|---|---|
| Public | `/`, `/masuk`, `/daftar` | login, register, logout | reset password |
| Owner | dashboard, produk, transaksi, transaksi/baru, transaksi/{id}, pembukuan, pembukuan/baru, pesanan-custom (+baru, +detail), pelanggan, promosi, karyawan, laporan, pengaturan | transaksi, pembukuan, pembukuan/{id}/hapus, pesanan-custom, ubah-status, promosi/{id}/kirim | produk CRUD, karyawan CRUD/reset, pengaturan POST, laporan ekspor, promosi CRUD, pelanggan detail/edit, pembukuan edit |
| Karyawan | dashboard, produk, transaksi, transaksi/baru, transaksi/{id}, pembukuan, pembukuan/baru, pesanan-custom (+baru, +detail), pelanggan, promosi | transaksi, pembukuan, pesanan-custom, ubah-status | update stok, pelanggan detail/edit, promosi buat/kirim |
| Pelanggan | beranda, pesanan, pesanan/{id}, riwayat, notifikasi, profil | — | **buat pesanan custom, tandai notifikasi, ubah profil/password** |
| AJAX | `notifikasi/belum-dibaca` | — | mark-read |

> Sesuai aturan "jangan menambah fitur", fitur tanpa route akan **dilaporkan sebagai gap**, bukan diimplementasikan. Pengecualian: route `/dev/tombol` (khusus development) dan perbaikan bug/route yang sudah jelas dimaksudkan.

---

## 6. Rencana Perbaikan (urutan kerja)

### Bagian 2 — Seeder
1. Backup database dev ke `database/backup/` (gitignored).
2. Buat seeder modular: `UserSeeder`, `CustomerSeeder`, `ProductSeeder`, `GoldPriceSeeder`, `SettingSeeder`, `TransactionSeeder`, `FinanceSeeder`, `CustomOrderSeeder`, `PromotionSeeder`, `NotificationSeeder`; pemanggil `DemoSeeder`.
3. Idempoten lewat kode unik (`email`, `code`, `invoice_no`, `order_no`, judul promosi).
4. Data bertanda demo: email `@demo.test`, kode `DEMO-*`.
5. `DemoCleanSeeder` menghapus hanya data bertanda demo dengan urutan FK benar.
6. Verifikasi invarian + jalankan dua kali + uji cleanup di `db_tokogembira_test`.
7. Dokumentasi `docs/SEEDER.md` dan `docs/DEMO-AKUN.md`.

### Bagian 3 — Uji & perbaikan bug
1. Perbaiki bug timestamp `custom_order_histories` & `notifications` (model), dan `store_settings`/`gold_prices` `updated_at`.
2. Perbaiki kontrak view V1–V8 dan mojibake V11.
3. Perbaiki form/route yang terbukti salah (pengaturan POST, filter laporan, link 404) **tanpa** menambah fitur baru.
4. Tambah tes PHPUnit: otorisasi antar-role, isolasi data pelanggan, rollback stok, riwayat status, toggle notifikasi, ringkasan laporan.
5. Tulis `docs/LAPORAN-UJI.md` + screenshot.

### Bagian 4 — Tombol
1. Finalisasi `components/ui/button.php`: varian lengkap, ukuran, ikon kiri/kanan, ikon saja, full width, `disabled`, `loading`, `href`, atribut Alpine/aria.
2. Ganti seluruh 59 tombol nyata dengan komponen (kecuali kontrol native seperti tab/paginasi yang akan memakai helper class yang sama).
3. Konfirmasi aksi berbahaya dengan modal Alpine + POST/DELETE + CSRF.
4. Halaman `/dev/tombol` hanya di `development`; guard route `/test/*`.
5. Screenshot sebelum/sesudah 390/768/1440; `npm run build`; uji ulang PHPUnit.

### Rencana commit
1. `docs: audit tombol, data, dan rencana perbaikan`
2. `feat(seed): data demo lengkap dan idempoten`
3. `fix: kontrak view owner/karyawan dan timestamp histori`
4. `test: feature tests otorisasi, isolasi, dan rollback`
5. `feat(ui): komponen tombol tunggal + halaman dev`

---

## 7. Hal yang Perlu Keputusan Pemilik Produk

1. **Riwayat harga emas** tidak didukung skema (`gold_prices` PK = `purity`). Perlu migration baru bila diinginkan.
2. **Fitur tanpa route** (produk CRUD, karyawan CRUD, pengaturan simpan, promosi buat, pelanggan edit, pembuatan pesanan oleh pelanggan, tandai notifikasi, ubah profil) akan dilaporkan sebagai gap. Apakah boleh diimplementasikan di iterasi terpisah?
3. **Grafik dashboard** masih placeholder; Chart.js belum diintegrasikan.
4. **Ekspor CSV laporan** belum ada route/implementasi.
5. **Password demo** akan diseragamkan menjadi satu password terdokumentasi (rencana: `Gembira#2026`) dan email `@demo.test`.
