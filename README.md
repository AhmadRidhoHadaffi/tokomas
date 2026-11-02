# Toko Mas Gembira

Sistem informasi manajemen dan promosi toko emas berbasis CodeIgniter 4 dengan tampilan server-rendered (PHP Views + Tailwind CSS).

## Stack

- CodeIgniter 4.7.4 (PHP 8.2+)
- MySQL/MySQLi
- Tailwind CSS 3.4.17 (CLI build)
- Alpine.js 3.14 (interaktivitas ringan)
- Session authentication (bukan JWT)

## Struktur

- `app/Controllers/{Public,Owner,Staff,Customer,Ajax}`: controller per area role.
- `app/Services`: logika bisnis (dipanggil controller, tidak punya query).
- `app/Models`, `app/Entities`: data layer.
- `app/Views`: layout + komponen + halaman, server-rendered CI4 views.
- `app/Cells`: komponen presentasi dengan logika kecil.
- `app/Database/Migrations`: skema database (tidak diubah tanpa migration baru).
- `resources/css/app.css`: input Tailwind → `public/assets/css/app.css`.
- `tests/feature`, `tests/unit`: PHPUnit suite.
- `docs/`: dokumentasi rute, struktur, dan pemetaan.

## Setup

1. Clone repo, `composer install`, `npm install`.
2. Duplikat `env` ke `.env`, atur database:
   ```
   database.default.database = db_tokogembira
   database.default.username = root
   database.default.password =
   
   database.tests.database = db_tokogembira_test
   ```
3. Buat database MySQL:
   ```sql
   CREATE DATABASE db_tokogembira CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   CREATE DATABASE db_tokogembira_test CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
4. Migrasi dan seed data demo:
   ```bash
   php spark migrate --all
   php spark db:seed DemoSeeder
   php spark migrate --all --dbgroup tests
   ```
5. Build CSS:
   ```bash
   npm run build
   # atau npm run dev untuk watch mode
   ```
6. Jalankan server:
   ```bash
   php spark serve
   ```
   Buka http://localhost:8080

## Testing

```bash
composer test
# atau
vendor/bin/phpunit
```

Database tes (`db_tokogembira_test`) terpisah, tidak pernah menyentuh data produksi.

## Role & Akses

- **Owner**: akses penuh (produk, transaksi, pembukuan, pesanan custom, pelanggan, promosi, karyawan, laporan, pengaturan).
- **Karyawan**: produk, transaksi, pembukuan, pesanan custom, pelanggan, promosi.
- **Pelanggan**: lihat pesanan custom sendiri, riwayat transaksi, notifikasi, profil.
- **Publik**: landing page, login, register.

## Migration & Data

- Migration ada di `app/Database/Migrations/`. JANGAN diubah atau dihapus.
- Perubahan skema hanya lewat migration BARU.
- Tes memakai database terpisah `db_tokogembira_test`.
- JANGAN menjalankan tes atau migration ke `db_tokogembira` produksi.

## Keamanan

- CSRF aktif global untuk semua form.
- Session-based auth, regenerasi session ID saat login.
- Filter `auth`, `role`, `guest`, `throttle` di rute.
- Password hash `password_hash()`, tidak pernah dikirim ke client.
- Query memakai Query Builder dan prepared statement.
- Output view memakai `esc()`.

## Deployment

1. `APP_ENV = production` di `.env`.
2. `composer install --no-dev --optimize-autoloader`.
3. `npm run build`.
4. Arahkan web server ke folder `public/`.
5. Atur `app.baseURL` sesuai domain produksi.
6. Aktifkan HTTPS dan `app.forceGlobalSecureRequests = true`.

## Dokumentasi

- `docs/RUTE.md`: daftar URL dan role.
- `docs/STRUKTUR.md`: alur request dan organisasi kode.
- `docs/PEMETAAN.md`: pemetaan halaman desain ke controller/view/tabel.

## Status

Refactor dari REST API + React ke server-rendered CI4 views sudah selesai:

- ✅ Session auth, filter, routing, layout, placeholder semua halaman
- ✅ Modul Produk owner/karyawan (list dengan filter/sort/pagination)
- ✅ Tes auth dan routing (5/5 lulus tanpa database)
- ⏳ Tes database menunggu MySQL lokal aktif (migration ke `db_tokogembira_test`)
- ⏳ Modul transaksi, pembukuan, pesanan custom, pelanggan, promosi, laporan, pengaturan
- ⏳ Form POST dengan validasi dan CSRF
- ⏳ Tampilan isi halaman (sekarang placeholder)

JWT, CORS lintas origin, React, dan Blade tidak dipakai.
