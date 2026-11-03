# UI Implementation Guide - Toko Mas Gembira

Panduan lengkap untuk melanjutkan implementasi UI yang pixel-perfect dengan design/

## Status Saat Ini

### ✅ Selesai & Teruji

1. **Foundation (100%)**
   - Helpers: `icon()` (46 Lucide), `format_helper.php` (rupiah, date, number, weight, percent, time_ago)
   - Tailwind CSS: Font, base styles, utilities (arabesque, scrollbar-thin)
   - Alpine.js loaded via CDN

2. **UI Components (100%)**
   - Button: 8 variants (primary, outline, outlineDark, outlineLight, ghost, danger, dark, light), 3 sizes (sm, md, lg)
   - Badge: 8 tones (blue, yellow, orange, green, grey, red, gold, crimson) + dot indicator
   - Modal: Alpine.js dengan transisi matching design
   - FormField: text, email, password, number, date, select, textarea, file
   - SearchInput: dengan ikon search
   - StatCard: featured variant, trend indicator
   - EmptyState: icon, title, description, action
   - Skeleton: line, circle, rect
   - Tabs: underline & pill variants (Alpine.js)
   - Table: sortable headers
   - Pagination: range dengan ellipsis
   - Logo: SVG mark + text, light/dark tone

3. **Layout Infrastructure (100%)**
   - Config/Navigation.php: menu per role
   - Sidebar: active state, compact tablet, mobile drawer
   - Header: title, notifications dropdown, user profile
   - Dashboard layout: desktop + mobile dengan Alpine drawer
   - Flash messages: success, error, warning, info (auto-dismiss)

4. **Pages (3%)**
   - Owner Dashboard: stats cards, chart placeholders, tables dengan empty state

### ⏳ Sisa Pekerjaan (97% halaman)

- 2 halaman publik (Login, Register, Landing)
- 10 halaman owner lainnya
- 8 halaman karyawan
- 5 halaman pelanggan
- 3 error pages
- Chart.js integration
- Upload component
- Notifikasi real data

---

## Pattern Implementasi Per Jenis Halaman

### 1. List Page (Produk, Transaksi, Pelanggan, dll)

**File**: `app/Views/pages/{role}/{module}/index.php`

```php
<?php
$this->extend('layouts/dashboard');
$this->section('title'); echo 'Produk & Stok'; $this->endSection();
$this->section('content');
?>

<!-- Header dengan Search & Action -->
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="font-serif text-2xl font-semibold text-charcoal">Produk & Stok</h1>
    <?= view('components/ui/search_input', ['name' => 'search', 'value' => $search]) ?>
    <?= view('components/ui/button', [
        'variant' => 'primary',
        'content' => icon('plus', 'h-4 w-4') . ' Tambah Produk',
        'onclick' => 'showModal = true',
    ]) ?>
</div>

<!-- Tabs (optional) -->
<div x-data="{ tab: 'all' }">
    <?= view('components/ui/tabs', [
        'items' => [
            ['value' => 'all', 'label' => 'Semua', 'count' => $total],
            ['value' => 'low', 'label' => 'Stok Menipis', 'count' => $lowStock],
        ],
        'model' => 'tab',
        'variant' => 'underline',
    ]) ?>
</div>

<!-- Table -->
<div class="mt-6 rounded-2xl border border-line bg-white shadow-card">
    <?php if (empty($items)): ?>
        <?= view('components/ui/empty_state', [
            'icon' => 'package',
            'title' => 'Belum ada produk',
            'description' => 'Tambahkan produk pertama Anda',
            'action' => view('components/ui/button', ['variant' => 'primary', 'content' => 'Tambah Produk']),
        ]) ?>
    <?php else: ?>
        <?= view('components/ui/table', [
            'headers' => [
                ['label' => 'Kode', 'sortable' => true, 'key' => 'code'],
                ['label' => 'Nama', 'sortable' => true, 'key' => 'name'],
                ['label' => 'Harga', 'sortable' => true, 'key' => 'price'],
                ['label' => 'Stok', 'sortable' => true, 'key' => 'stock'],
                ['label' => 'Aksi'],
            ],
            'rows' => $tableRows, // Render di controller atau view
        ]) ?>
        
        <div class="border-t border-line p-4">
            <?= view('components/ui/pagination', [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'total' => $total,
                'perPage' => $perPage,
                'baseUrl' => base_url('owner/produk'),
            ]) ?>
        </div>
    <?php endif; ?>
</div>

<?php $this->endSection(); ?>
```

**Controller Pattern**:
```php
public function index(): string
{
    $service = new ProductService();
    $page = (int) ($this->request->getGet('page') ?? 1);
    $search = $this->request->getGet('search') ?? '';
    
    $result = $service->getAll([
        'page' => $page,
        'per_page' => 20,
        'search' => $search,
    ]);

    // Render table rows
    $tableRows = '';
    foreach ($result['items'] as $item) {
        $tableRows .= '<tr class="border-b border-line/70">';
        $tableRows .= '<td class="p-3">' . esc($item['code']) . '</td>';
        $tableRows .= '<td class="p-3">' . esc($item['name']) . '</td>';
        $tableRows .= '<td class="p-3">' . rupiah($item['price']) . '</td>';
        $tableRows .= '<td class="p-3">' . view_cell('App\\Cells\\StockBadgeCell::show', ['stock' => $item['stock']]) . '</td>';
        $tableRows .= '<td class="p-3">
            <a href="' . base_url('owner/produk/' . $item['id']) . '" class="text-gold-deep hover:underline">Detail</a>
        </td>';
        $tableRows .= '</tr>';
    }

    return view('pages/owner/products/index', [
        'title' => 'Produk & Stok',
        'items' => $result['items'],
        'tableRows' => $tableRows,
        'page' => $page,
        'total' => $result['total'],
        'totalPages' => $result['total_pages'],
        'perPage' => $result['per_page'],
        'search' => $search,
    ]);
}
```

---

### 2. Form Page (Create/Edit)

**File**: `app/Views/pages/{role}/{module}/create.php`

```php
<?php
$this->extend('layouts/dashboard');
$this->section('title'); echo 'Tambah Produk'; $this->endSection();
$this->section('content');
?>

<div class="mx-auto max-w-2xl">
    <h1 class="font-serif text-2xl font-semibold text-charcoal mb-6">Tambah Produk Baru</h1>
    
    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
        <form method="POST" action="<?= base_url('owner/produk') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="space-y-4">
                <?= view('components/ui/form_field', [
                    'label' => 'Nama Produk',
                    'name' => 'name',
                    'id' => 'name',
                    'value' => old('name'),
                    'error' => session('errors.name'),
                    'required' => true,
                ]) ?>
                
                <?= view('components/ui/form_field', [
                    'type' => 'select',
                    'label' => 'Kategori',
                    'name' => 'category',
                    'id' => 'category',
                    'value' => old('category'),
                    'options' => [
                        'Cincin' => 'Cincin',
                        'Kalung' => 'Kalung',
                        'Gelang' => 'Gelang',
                        'Anting' => 'Anting',
                    ],
                    'error' => session('errors.category'),
                    'required' => true,
                ]) ?>
                
                <?= view('components/ui/form_field', [
                    'type' => 'number',
                    'label' => 'Harga (Rp)',
                    'name' => 'price',
                    'id' => 'price',
                    'value' => old('price'),
                    'error' => session('errors.price'),
                    'required' => true,
                ]) ?>
                
                <?= view('components/ui/form_field', [
                    'type' => 'file',
                    'label' => 'Gambar Produk',
                    'name' => 'image',
                    'id' => 'image',
                    'accept' => 'image/*',
                    'error' => session('errors.image'),
                ]) ?>
            </div>
            
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <?= view('components/ui/button', [
                    'variant' => 'outline',
                    'content' => 'Batal',
                    'href' => base_url('owner/produk'),
                ]) ?>
                <?= view('components/ui/button', [
                    'variant' => 'primary',
                    'type' => 'submit',
                    'content' => 'Simpan Produk',
                ]) ?>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>
```

---

### 3. Detail Page

Sama seperti form, tapi read-only dengan tombol Edit/Hapus.

---

### 4. Modal Form (untuk quick action)

```php
<!-- Di halaman utama -->
<div x-data="{ showModal: false }">
    <?= view('components/ui/modal', [
        'id' => 'showModal',
        'title' => 'Tambah Produk Cepat',
        'description' => 'Isi form di bawah',
        'size' => 'lg',
        'content' => '<form>...</form>',
        'footer' => view('components/ui/button', [...]),
    ]) ?>
</div>
```

---

## Checklist Class dari Desain (Copy Exact)

Saat membuat halaman baru, **copy class Tailwind dari TSX** dan pastikan:

1. **Spacing**: `gap-4`, `space-y-6`, `px-4 py-6`, `mt-4`
2. **Grid**: `grid grid-cols-2 xl:grid-cols-4`, `lg:col-span-3`
3. **Border**: `border border-line`, `rounded-2xl`
4. **Shadow**: `shadow-card`
5. **Typography**: `font-serif text-2xl font-semibold`, `text-sm text-charcoal/60`
6. **Transitions**: `transition-colors duration-150`, `hover:bg-ivory`

---

## Chart.js Integration (Belum Implementasi)

Download Chart.js ke `public/assets/js/chart.min.js`, lalu di layout:

```html
<script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
```

Di halaman:
```php
$this->section('scripts');
?>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: <?= json_encode($chartData) ?>,
    options: { /* config matching design */ }
});
</script>
<?php
$this->endSection();
```

---

## Testing Setiap Halaman

1. Buka di browser dengan role yang sesuai
2. Cek responsive (390px, 1024px, 1440px)
3. Test interaksi (modal, tab, search, pagination)
4. Bandingkan dengan design/src/pages/... (screenshot)
5. Jalankan `composer test` - pastikan tetap hijau

---

## Commit Pattern

```bash
git add app/Views/pages/owner/products/
git commit -m "feat(ui): owner products page - list, create, edit, delete

- List with search, filter, pagination
- Modal for quick add
- Form validation with old() and errors
- Empty state for no products
- Stock badge integration
- Responsive table -> card on mobile
"
```

---

## Prioritas Halaman Berikutnya

1. **Login & Register** (entry point, critical)
2. **Owner Transaksi** (catat transaksi - form kompleks)
3. **Owner Produk** (CRUD complete)
4. **Landing Page** (marketing, public)
5. **Customer Home & Orders** (user experience)
6. Sisanya ikuti checklist

Setiap halaman ~2-3 jam kerja untuk pixel-perfect matching.
