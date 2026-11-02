# Struktur Backend - Toko Mas Gembira

## Arsitektur Request Flow

`
Request → Route → Filter (CORS, JWT, Role) → Controller → Service → Model → Database
                                                    ↓
Response ← Controller ← Service (Business Logic + DB Transaction)
`

## Folder Structure

`
app/
├── Config/
│   ├── Routes/Api/V1/          13 route files (modular)
│   ├── Jwt.php                 JWT config (secret, ttl, issuer)
│   ├── Cors.php                CORS config (origins from env)
│   └── Filters.php             jwt, role, throttle alias
├── Controllers/Api/V1/
│   ├── BaseApiController.php   Response helpers seragam
│   ├── AuthController.php
│   ├── ProductController.php
│   ├── TransactionController.php
│   ├── FinanceController.php
│   ├── CustomOrderController.php
│   ├── CustomerController.php
│   ├── PromotionController.php
│   ├── NotificationController.php
│   ├── EmployeeController.php
│   ├── DashboardController.php
│   ├── SettingController.php
│   ├── ReportController.php
│   └── PublicController.php
├── Services/                   LOGIKA BISNIS di sini
│   ├── AuthService.php         Register (user+customer), login
│   ├── ProductService.php      CRUD, transform purity↔metal+karat
│   ├── TransactionService.php  DB transaction, validasi stok, auto finance
│   ├── FinanceService.php      CRUD, validate category, summary
│   ├── CustomOrderService.php  Status change + history + notification + auto finance
│   ├── CustomerService.php     List with stats (aggregate transactions)
│   ├── PromotionService.php    Send → create notifications
│   ├── NotificationService.php Mark read, unread count
│   ├── EmployeeService.php     CRUD karyawan, toggle active, reset password
│   ├── DashboardService.php    Stats owner vs karyawan
│   ├── SettingService.php      Store settings, gold prices
│   └── ReportService.php       Sales, finance, stock, custom order reports
├── Models/                     13 models (1 per tabel)
│   ├── UserModel.php           Hash password callback
│   ├── CustomerModel.php       getWithStats (aggregate)
│   ├── ProductModel.php        Soft delete, stock methods
│   ├── TransactionModel.php    Generate invoice TRX-YYYYMMDD-NNN, getWithDetails
│   ├── TransactionDetailModel.php
│   ├── FinanceModel.php        getSummary (income/expense/balance)
│   ├── CustomOrderModel.php    Generate order PC-YYYY-NNN, getWithHistory
│   ├── CustomOrderHistoryModel.php
│   ├── NotificationModel.php   Mark read methods, unread count
│   ├── PromotionModel.php
│   ├── PromotionCustomerModel.php
│   ├── StoreSettingModel.php
│   └── GoldPriceModel.php
├── Libraries/
│   ├── JwtLibrary.php          Encode/decode JWT (firebase/php-jwt)
│   ├── FileUploader.php        Upload gambar (max 2MB, jpeg/png/webp)
│   └── CsvExporter.php         Export laporan ke CSV
├── Filters/
│   ├── JwtAuthFilter.php       Validate token, inject user ke request
│   └── RoleFilter.php          Check role dari JWT payload
├── Validation/                 Validation rules per modul
│   ├── AuthRules.php
│   ├── ProductRules.php
│   ├── TransactionRules.php
│   ├── FinanceRules.php
│   └── CustomOrderRules.php
└── Database/
    ├── Migrations/             13 migration (JANGAN diubah)
    └── Seeds/
        └── DemoSeeder.php      1 owner, 1 karyawan, 1 pelanggan, 5 produk

tests/                          (PHPUnit, belum dibuat - next step)
docs/
├── API.md                      Dokumentasi endpoint
├── STRUKTUR.md                 (file ini)
└── PEMETAAN.md                 (next)

public/uploads/
├── products/                   Upload gambar produk
└── custom-orders/              Upload reference image pesanan
`

## Business Logic Penting

### 1. Transaction (app/Services/TransactionService.php)
- DB Transaction atomik
- Validasi stok cukup untuk semua item
- Total dihitung di server (subtotal - discount)
- Kurangi stok per item
- Insert transaction + transaction_details
- Auto-record ke finances jika status Lunas
- Rollback semua jika ada error

### 2. Custom Order Status Change (app/Services/CustomOrderService.php)
- Validasi status progression (hanya maju, tidak skip)
- Insert ke custom_order_histories
- Buat notification ke customer (jika store_settings.notify_status_change = 1)
- Auto-record pelunasan ke finances jika status = Diambil
- DB Transaction

### 3. Promotion Send (app/Services/PromotionService.php)
- Get target customers (semua atau terpilih dari promotion_customers)
- Create notifications untuk setiap customer
- Update promotion status → Terkirim
- DB Transaction

### 4. Product Transform (app/Services/ProductService.php)
- DB: purity (24K, 22K, 18K, 17K, Perak 925)
- Frontend: metal (Emas/Perak) + karat (string)
- Transform saat response dan saat save

### 5. Auth Register (app/Services/AuthService.php)
- Buat user (role pelanggan)
- Buat customer (linked via user_id)
- Return JWT token
- DB Transaction

## Security Features

- JWT: expire 7 hari, secret dari env
- Password: bcrypt (password_hash default)
- CORS: whitelist origins dari env
- Rate limit: login/register 5 req/min per IP (Throttle filter)
- Role-based access: jwt + role filter di routes
- Upload: validate MIME type, max 2MB
- SQL injection: query builder + prepared statements
- User password: tidak pernah di response (unset di model/service)
- Customer ownership: pelanggan hanya akses data miliknya (filter by customer_id dari JWT)

## Response Format

Seragam lewat BaseApiController:

Success:
`json
{
  "success": true,
  "message": "...",
  "data": {...},
  "meta": {"page": 1, "per_page": 20, "total": 100, "total_pages": 5}
}
`

Error:
`json
{
  "success": false,
  "message": "Error message",
  "errors": {"field": "validation error"}
}
`

## Environment Variables Required

`
CI_ENVIRONMENT = development
app.baseURL = http://localhost:8080
app.appTimezone = Asia/Jakarta
app.defaultLocale = id

database.default.* = db_tokogembira
database.tests.* = db_tokogembira_test

jwt.secret = your-secret-key
jwt.ttl = 604800
jwt.issuer = toko-mas-gembira

cors.allowedOrigins = http://localhost:5173,http://localhost:3000
`

## Demo Credentials

Owner: owner@tokomasgembira.id / owner123
Karyawan: rina@tokomasgembira.id / karyawan123
Pelanggan: siti@example.com / pelanggan123
