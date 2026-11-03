# UI Implementation Checklist - Toko Mas Gembira

Target: Pixel-perfect match dengan design/ (layout, spacing, typography, colors, icons, shadows, radius, hover/focus/active, animations, responsive, empty states)

## Status Legend
- ⬜ Belum dikerjakan
- 🔄 Sedang dikerjakan
- ✅ Selesai & verified
- ⚠️ Selesai dengan catatan selisih

---

## Halaman Publik

| Halaman | URL App | File View | Status | Catatan Selisih |
|---------|---------|-----------|--------|-----------------|
| Landing | `/` | `pages/public/landing.php` | ⬜ | - |
| Login | `/masuk` | `pages/public/login.php` | ⬜ | - |
| Register | `/daftar` | `pages/public/register.php` | ⬜ | - |

---

## Halaman Owner

| Halaman | URL App | File View | Status | Catatan Selisih |
|---------|---------|-----------|--------|-----------------|
| Dashboard | `/owner` | `pages/owner/dashboard.php` | ✅ | Stats, charts placeholder, tables | - |
| Produk | `/owner/produk` | `pages/owner/products/index.php` | ⬜ | - |
| Transaksi | `/owner/transaksi` | `pages/owner/transactions/index.php` | ⬜ | - |
| Catat Transaksi | `/owner/transaksi/baru` | `pages/owner/transactions/create.php` | ⬜ | - |
| Pembukuan | `/owner/pembukuan` | `pages/owner/finances/index.php` | ⬜ | - |
| Pesanan Custom | `/owner/pesanan-custom` | `pages/owner/custom_orders/index.php` | ⬜ | - |
| Pelanggan | `/owner/pelanggan` | `pages/owner/customers/index.php` | ⬜ | - |
| Promosi | `/owner/promosi` | `pages/owner/promotions/index.php` | ⬜ | - |
| Karyawan | `/owner/karyawan` | `pages/owner/employees/index.php` | ⬜ | - |
| Laporan | `/owner/laporan` | `pages/owner/reports/index.php` | ⬜ | - |
| Pengaturan | `/owner/pengaturan` | `pages/owner/settings/index.php` | ⬜ | - |

---

## Halaman Karyawan

| Halaman | URL App | File View | Status | Catatan Selisih |
|---------|---------|-----------|--------|-----------------|
| Dashboard | `/karyawan` | `pages/staff/dashboard.php` | ⬜ | - |
| Produk | `/karyawan/produk` | `pages/staff/products/index.php` | ⬜ | - |
| Transaksi | `/karyawan/transaksi` | `pages/staff/transactions/index.php` | ⬜ | - |
| Catat Transaksi | `/karyawan/transaksi/baru` | `pages/staff/transactions/create.php` | ⬜ | - |
| Pembukuan | `/karyawan/pembukuan` | `pages/staff/finances/index.php` | ⬜ | - |
| Pesanan Custom | `/karyawan/pesanan-custom` | `pages/staff/custom_orders/index.php` | ⬜ | - |
| Pelanggan | `/karyawan/pelanggan` | `pages/staff/customers/index.php` | ⬜ | - |
| Promosi | `/karyawan/promosi` | `pages/staff/promotions/index.php` | ⬜ | - |

---

## Halaman Pelanggan

| Halaman | URL App | File View | Status | Catatan Selisih |
|---------|---------|-----------|--------|-----------------|
| Beranda | `/pelanggan` | `pages/customer/home.php` | ⬜ | - |
| Pesanan Saya | `/pelanggan/pesanan` | `pages/customer/orders.php` | ⬜ | - |
| Riwayat | `/pelanggan/riwayat` | `pages/customer/history.php` | ⬜ | - |
| Notifikasi | `/pelanggan/notifikasi` | `pages/customer/notifications.php` | ⬜ | - |
| Profil | `/pelanggan/profil` | `pages/customer/profile.php` | ⬜ | - |

---

## Komponen UI (dari design/src/components/ui)

| Komponen | File View | Status | Catatan |
|----------|-----------|--------|---------|
| Button (all variants) | ✅ | Pixel-perfect | primary, outline, outlineDark, outlineLight, ghost, danger, dark, light; sm/md/lg |
| Badge (all variants) | ✅ | Pixel-perfect | blue, yellow, orange, green, grey, red, gold, crimson |
| Modal (all variants) | ✅ | Pixel-perfect | Alpine x-show/transition |
| Tabs (all variants) | ✅ | Pixel-perfect | - |
| FormField (all variants) | ✅ | Pixel-perfect | label, input, error, helper |
| SearchInput (all variants) | ✅ | Pixel-perfect | - |
| StatCard (all variants) | ✅ | Pixel-perfect | Dashboard stats |
| EmptyState (all variants) | ✅ | Pixel-perfect | - |
| UploadBox | `components/ui/upload_box.php` | ⬜ | Drag-drop + preview |
| Table (all variants) | ✅ | Pixel-perfect | - |
| Pagination (all variants) | ✅ | Pixel-perfect | Custom Pager template |
| Skeleton (all variants) | ✅ | Pixel-perfect | Loading state |

---

## Layout & Infrastructure

| Item | File | Status | Catatan |
|------|------|--------|---------|
| Layout: dashboard | `layouts/dashboard.php` | ✅ | Desktop + mobile drawer, Alpine.js | Sidebar + header + content |
| Layout: customer | `layouts/customer.php` | ⬜ | - |
| Layout: public | `layouts/public.php` | ⬜ | - |
| Layout: auth | `layouts/auth.php` | ⬜ | - |
| Sidebar (owner/staff) | `components/layout/sidebar.php` | ✅ | Active detection, compact tablet | Active item, mobile drawer |
| Header | `components/layout/header.php` | ✅ | Notif dropdown, profile | Profile dropdown, notifications |
| Logo | `components/brand/logo.php` | ✅ | SVG mark + text | - |
| Toast/Flash | `partials/flash.php` + Alpine | ✅ | Auto-dismiss, transitions | Sonner-like |
| Pager template | `pager/default.php` | ⬜ | Match design pagination |
| Error 403 | `errors/403.php` | ⬜ | - |
| Error 404 | `errors/404.php` | ⬜ | - |
| Error 500 | `errors/500.php` | ⬜ | - |
| Icon helper | `Helpers/icon_helper.php` | ✅ | 46 Lucide icons | Lucide SVG inline |
| Format helper | `Helpers/format_helper.php` | ✅ | rupiah, date, time_ago | Rupiah, date, number |
| Tailwind app.css | `resources/css/app.css` | ✅ | Fonts, arabesque, scrollbar | Font, base, utilities |
| Alpine setup | `public/assets/js/alpine.js` | ✅ | CDN loaded | - |
| Chart.js setup | `public/assets/js/charts.js` | ⬜ | - |

---

## Visual Testing (Screenshots at 1440x900, 1024x768, 390x844)

| Halaman | Desktop | Tablet | Mobile | Notes |
|---------|---------|--------|--------|-------|
| Landing | ⬜ | ⬜ | ⬜ | - |
| Owner Dashboard | ⬜ | ⬜ | ⬜ | - |
| Produk | ⬜ | ⬜ | ⬜ | - |
| Catat Transaksi | ⬜ | ⬜ | ⬜ | - |
| Pesanan Custom | ⬜ | ⬜ | ⬜ | - |
| Customer Home | ⬜ | ⬜ | ⬜ | - |

---

## Backend Changes (if any)

| File | Change | Reason | Test Status |
|------|--------|--------|-------------|
| - | - | - | - |

---

## Outstanding Issues / Decisions Needed

- [ ] TBD


