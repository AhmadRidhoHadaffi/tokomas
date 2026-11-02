# Pemetaan Desain ke Web Server-Rendered

| Halaman | URL | View | Tabel utama |
|---|---|---|---|
| Landing | / | pages/public/landing | products, promotions, gold_prices, store_settings |
| Login/Register | /masuk, /daftar | pages/public/login, register | users, customers |
| Dashboard owner | /owner | pages/owner/dashboard/index | transactions, custom_orders, products, finances |
| Produk & stok | /owner/produk, /karyawan/produk | pages/*/products/index | products |
| Transaksi | /owner/transaksi, /karyawan/transaksi | pages/*/transactions | transactions, transaction_details, products, finances |
| Pesanan custom | /owner/pesanan-custom, /karyawan/pesanan-custom, /pelanggan/pesanan | pages/*/custom_orders | custom_orders, custom_order_histories, notifications |
| Pembukuan | /owner/pembukuan, /karyawan/pembukuan | pages/*/finances | finances |
| Pelanggan | /owner/pelanggan, /karyawan/pelanggan, /pelanggan/profil | area customer views | customers, users, transactions |
| Promosi | /owner/promosi, /karyawan/promosi | area promotion views | promotions, promotion_customers, notifications |
| Laporan | /owner/laporan | pages/owner/reports | transactions, finances, products, custom_orders |
| Pengaturan | /owner/pengaturan | pages/owner/settings | store_settings, gold_prices |
| Notifikasi | /pelanggan/notifikasi | pages/customer/notifications | notifications |
