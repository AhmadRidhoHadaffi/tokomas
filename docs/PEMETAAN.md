# Pemetaan Desain -> Endpoint -> Tabel Database

## 1. Halaman Public

| Halaman | Endpoint | Tabel | Keterangan |
|---|---|---|---|
| Landing - Produk Unggulan | GET /api/v1/public/products | products | 6 produk terbaru |
| Landing - Promosi Aktif | GET /api/v1/public/promotions | promotions | status=Terkirim |
| Landing - Harga Emas | GET /api/v1/public/gold-prices | gold_prices | 24K, 22K, 18K, 17K |
| Landing - Info Kontak | GET /api/v1/public/store-info | store_settings | nama, email, phone, wa, address |
| Login | POST /api/v1/auth/login | users | email + password, rate limited |
| Register | POST /api/v1/auth/register | users, customers | buat user role pelanggan + record customer |

## 2. Halaman Owner

| Halaman | Endpoint | Tabel | Keterangan |
|---|---|---|---|
| Dashboard | GET /api/v1/dashboard | transactions, custom_orders, products, finances | statistik lengkap owner |
| Produk & Stok | GET /api/v1/products | products | search, filter category/purity/stock, pagination |
| Produk Detail | GET /api/v1/products/:id | products | format camelCase + metal/karat |
| Tambah Produk | POST /api/v1/products | products | upload gambar multipart/json |
| Edit Produk | PUT /api/v1/products/:id | products | update atribut produk |
| Hapus Produk | DELETE /api/v1/products/:id | products | soft delete via deleted_at |
| Transaksi List | GET /api/v1/transactions | transactions, transaction_details, customers, users | invoice, kasir, pelanggan, total |
| Transaksi Detail | GET /api/v1/transactions/:id | transactions, transaction_details, products | rincian item, harga riil transaksi |
| Catat Transaksi | POST /api/v1/transactions | transactions, transaction_details, products, finances | validasi stok, kurangi stok, catat finance Lunas |
| Pembukuan List | GET /api/v1/finances | finances, users | daftar arus kas masuk/keluar |
| Pembukuan Summary | GET /api/v1/finances/summary | finances | total income, expense, balance |
| Pembukuan Kategori | GET /api/v1/finances/categories | - | referensi kategori masuk & keluar |
| Tambah Pembukuan | POST /api/v1/finances | finances | entri manual |
| Edit Pembukuan | PUT /api/v1/finances/:id | finances | entri manual saja (tolak auto-record) |
| Hapus Pembukuan | DELETE /api/v1/finances/:id | finances | entri manual saja (tolak auto-record) |
| Pesanan Custom List | GET /api/v1/custom-orders | custom_orders, customers | ringkasan pesanan & status |
| Pesanan Custom Detail | GET /api/v1/custom-orders/:id | custom_orders, custom_order_histories, customers, users | histori status dan pembuat ubahan |
| Tambah Pesanan | POST /api/v1/custom-orders | custom_orders, custom_order_histories, finances, notifications | generate PC-YYYY-NNN, DP auto-finance, notif |
| Edit Pesanan | PUT /api/v1/custom-orders/:id | custom_orders | ubah catatan, spek, estimasi harga |
| Ubah Status Pesanan | PUT /api/v1/custom-orders/:id/status | custom_orders, custom_order_histories, finances, notifications | urutan maju ketat, pelunasan saat Diambil |
| Pelanggan List | GET /api/v1/customers | customers, transactions | agregasi total transaksi dan total belanja |
| Pelanggan Detail | GET /api/v1/customers/:id | customers, transactions | profil lengkap pelanggan |
| Edit Pelanggan | PUT /api/v1/customers/:id | customers | update kontak/alamat |
| Promosi List | GET /api/v1/promotions | promotions, promotion_customers | daftar promo, hitung target |
| Promosi Detail | GET /api/v1/promotions/:id | promotions, promotion_customers | detail promo & target |
| Tambah Promosi | POST /api/v1/promotions | promotions, promotion_customers | target semua atau terpilih |
| Edit Promosi | PUT /api/v1/promotions/:id | promotions | update sebelum terkirim |
| Hapus Promosi | DELETE /api/v1/promotions/:id | promotions, promotion_customers | hapus promo |
| Kirim Promosi | POST /api/v1/promotions/:id/send | promotions, notifications, customers, promotion_customers | generate baris notifikasi ke pelanggan target |
| Karyawan List | GET /api/v1/employees | users | role karyawan |
| Karyawan Detail | GET /api/v1/employees/:id | users | profil akun staf |
| Tambah Karyawan | POST /api/v1/employees | users | registrasi staf baru |
| Edit Karyawan | PUT /api/v1/employees/:id | users | ubah nama/email |
| Toggle Aktif | PUT /api/v1/employees/:id/toggle-active | users | aktifkan / nonaktifkan staf |
| Reset Password | PUT /api/v1/employees/:id/reset-password | users | ubah password staf |
| Laporan Penjualan | GET /api/v1/reports/sales | transactions, customers, users | agregat & detail, filter tgl, CSV |
| Laporan Pembukuan | GET /api/v1/reports/finances | finances, users | agregat & detail, filter tgl, CSV |
| Laporan Stok | GET /api/v1/reports/stock | products | ringkasan status stok, CSV |
| Laporan Pesanan Custom | GET /api/v1/reports/custom-orders | custom_orders, customers | agregat nilai estimasi & DP, CSV |
| Pengaturan Toko | GET, PUT /api/v1/settings/store | store_settings | info toko & toggle notifikasi |
| Pengaturan Harga Emas | GET, PUT /api/v1/settings/gold-prices | gold_prices | update harga per gram 24K, 22K, 18K, 17K |

## 3. Halaman Karyawan

Akses sama seperti Owner kecuali modul:
- Karyawan (ditutup)
- Laporan (ditutup)
- Pengaturan (ditutup)
- Dashboard menampilkan statistik operasional (tanpa saldo pembukuan)

## 4. Halaman Pelanggan

| Halaman | Endpoint | Tabel | Keterangan |
|---|---|---|---|
| Pesanan Saya | GET /api/v1/custom-orders?customer_id=X | custom_orders, custom_order_histories | hanya milik user aktif |
| Detail Pesanan | GET /api/v1/custom-orders/:id | custom_orders, custom_order_histories | cek hak milik customer |
| Notifikasi | GET /api/v1/notifications | notifications | hanya milik customer aktif |
| Jumlah Belum Dibaca | GET /api/v1/notifications/unread-count | notifications | counter badge |
| Tandai Dibaca | PUT /api/v1/notifications/:id/read | notifications | update is_read = 1 |
| Tandai Semua Dibaca | PUT /api/v1/notifications/mark-all-read | notifications | update seluruh notifikasi customer |
| Profil Saya | GET, PUT /api/v1/customers/:id | customers | update kontak & alamat sendiri |

## 5. Pemetaan Kolom Khusus

- Produk: DB `purity` (24K, 22K, 18K, 17K, Perak 925) dipetakan bolak-balik ke `{ metal: 'Emas'|'Perak', karat: string }`.
- Transaksi: `user_id` -> nama kasir, `customer_id` -> nama pelanggan.
- Pembukuan: `user_id` -> recordedBy name.
- Pesanan Custom: `jewelry_type` + `model` dirangkum jadi `product` untuk UI, histori status join ke users untuk nama staf.
- Promosi: DB `target_type` (semua/terpilih) + relasi `promotion_customers` dihitung jadi jumlah penerima (`recipientCount`).
