# Rute Web Toko Mas Gembira

Semua halaman memakai session authentication dan CSRF CI4.

| URL | Method | Role | Controller / View |
|---|---|---|---|
| / | GET | publik | Public\LandingController, pages/public/landing |
| /masuk | GET/POST | tamu | Public\AuthController, pages/public/login |
| /daftar | GET/POST | tamu | Public\AuthController, pages/public/register |
| /keluar | POST | login | Public\AuthController |
| /owner/* | GET | owner | Owner controllers, pages/owner/* |
| /karyawan/* | GET | karyawan | Staff controllers, pages/staff/* |
| /pelanggan/* | GET | pelanggan | Customer controllers, pages/customer/* |
| /ajax/* | GET/POST | sesuai modul | Ajax controllers, JSON same-origin |

POST form memakai csrf_field(), validasi memakai redirect()->back()->withInput().
