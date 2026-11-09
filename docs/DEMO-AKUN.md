# AKUN DEMO — Toko Mas Gembira

> Dataset demo: `php spark db:seed DemoSeeder` · Anchor: **2026-11-09**
> Semua akun demo memakai password yang sama.

## Password

```
Gembira#2026
```

Password disimpan sebagai hash bcrypt (`password_hash`) dan diverifikasi oleh `php spark demo:verify`.

---

## Akun Staf

| Nama | Email | Role | Status |
|---|---|---|---|
| Hj. Rosmaniar | `owner@demo.test` | owner | aktif |
| Rina Marlina | `rina@demo.test` | karyawan | aktif |
| Fajar Ramadhan | `fajar@demo.test` | karyawan | aktif |
| Yuni Astuti | `yuni@demo.test` | karyawan | **nonaktif** (uji login ditolak) |

## Akun Pelanggan

| Nama | Email | Telepon | Status |
|---|---|---|---|
| Siti Rahmawati | `siti@demo.test` | 081377881020 | aktif |
| Andi Pratama | `andi@demo.test` | 081377881021 | aktif |
| Dewi Anggraini | `dewi@demo.test` | 081377881022 | aktif |
| Rizky Saputra | `rizky@demo.test` | 081377881023 | aktif |
| Putri Ayu Lestari | `putri@demo.test` | 081377881024 | aktif |
| Bayu Kurniawan | `bayu@demo.test` | 081377881025 | aktif |
| Nisa Fadillah | `nisa@demo.test` | 081377881026 | aktif |
| Arif Hidayat | `arif@demo.test` | 081377881027 | aktif |
| Melati Sari | `melati@demo.test` | 081377881028 | aktif |
| Hendra Gunawan | `hendra@demo.test` | 081377881029 | aktif |
| Sari Indah | `sari@demo.test` | 081377881030 | aktif |
| Doni Firmansyah | `doni@demo.test` | 081377881031 | **nonaktif** |

### Pelanggan walk-in (tanpa akun login)

| Nama | Telepon |
|---|---|
| Pelanggan Walk-in 1 | 081377881032 |
| Pelanggan Walk-in 2 | 081377881033 |
| Pelanggan Walk-in 3 | 081377881034 |

---

## Akun yang Disarankan untuk Uji Manual

| Skenario | Akun |
|---|---|
| Dashboard owner | `owner@demo.test` |
| Kasir / transaksi / pesanan custom | `rina@demo.test` atau `fajar@demo.test` |
| Login ditolak (nonaktif) | `yuni@demo.test` / `doni@demo.test` |
| Pelanggan dengan data terlengkap | `siti@demo.test` |
| Pelanggan tanpa transaksi | `hendra@demo.test`, `sari@demo.test` |

`siti@demo.test` memiliki:
- 3 pesanan custom pada berbagai status (`Diambil`, `Selesai`, `Diproses`),
- transaksi penjualan (termasuk hari anchor),
- notifikasi status pesanan, pembayaran, dan promosi,
- DP dan pelunasan pada pembukuan.

---

## Akun Lama (non-demo)

Database dev masih menyimpan data dari seeder lama:

| Email | Role | Password lama |
|---|---|---|
| `owner@tokomasgembira.id` | owner | `owner123` |
| `rina@tokomasgembira.id` | karyawan | `karyawan123` |
| `siti@example.com` | pelanggan | `pelanggan123` |

Akun ini **tidak** termasuk dataset demo dan tidak dihapus oleh `php spark demo:clean`.
Gunakan akun `@demo.test` untuk pengujian agar data konsisten.

> **Catatan:** tombol quick-fill di halaman login saat ini masih menuliskan password lama `gembira123`. Perbaikan ada di Bagian 3/4.
