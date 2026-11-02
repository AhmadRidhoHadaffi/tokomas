# API Documentation - Toko Mas Gembira

Base URL: http://localhost:8080/api/v1

## Authentication Endpoints

### Register
POST /auth/register
Body:
{
  "name": "Pelanggan Baru",
  "email": "pelanggan@example.com",
  "phone": "081234567890",
  "password": "password123",
  "address": "Jl. Test No. 1"
}

### Login
POST /auth/login
Body:
{
  "email": "pelanggan@example.com",
  "password": "password123"
}

### Get Current User
GET /auth/me
Headers: Authorization: Bearer {token}

### Logout
POST /auth/logout
Headers: Authorization: Bearer {token}

### Change Password
PUT /auth/password
Headers: Authorization: Bearer {token}
Body:
{
  "current_password": "password123",
  "new_password": "newpassword456"
}

## Public Endpoints (No Auth)

GET /public/products - Featured products
GET /public/promotions - Active promotions
GET /public/gold-prices - Gold prices per gram
GET /public/store-info - Store information

## Products (Owner & Karyawan)

GET /products?search=&category=&purity=&stock_level=&page=1&per_page=20
GET /products/{id}
POST /products - Create with multipart/form-data for image
PUT /products/{id}
DELETE /products/{id} - Soft delete

## Transactions (Owner & Karyawan)

GET /transactions?search=&payment_status=&start_date=&end_date=&page=1
GET /transactions/{id}
POST /transactions
Body:
{
  "customer_id": 1,
  "items": [
    {"product_id": 1, "quantity": 2},
    {"product_id": 2, "quantity": 1}
  ],
  "discount": 50000,
  "payment_method": "Tunai",
  "payment_status": "Lunas"
}

## Finances (Owner & Karyawan)

GET /finances?type=&category=&start_date=&end_date=&page=1
GET /finances/summary?start_date=2026-10-01&end_date=2026-10-31
GET /finances/categories
GET /finances/{id}
POST /finances
PUT /finances/{id}
DELETE /finances/{id}

## Custom Orders (Owner & Karyawan)

GET /custom-orders?search=&status=&customer_id=&page=1
GET /custom-orders/{id}
POST /custom-orders
PUT /custom-orders/{id}
PUT /custom-orders/{id}/status
Body: {"status": "Diproses"}

## Customers (Owner & Karyawan)

GET /customers?search=&page=1
GET /customers/{id}
PUT /customers/{id}

## Promotions (Owner & Karyawan)

GET /promotions?status=&page=1
GET /promotions/{id}
POST /promotions
Body:
{
  "title": "Promo Emas",
  "message": "Diskon 10%",
  "target_type": "semua",
  "status": "Draf"
}
PUT /promotions/{id}
DELETE /promotions/{id}
POST /promotions/{id}/send - Send to customers

## Notifications (Pelanggan)

GET /notifications?is_read=0&page=1
GET /notifications/unread-count
PUT /notifications/{id}/read
PUT /notifications/mark-all-read

## Employees (Owner Only)

GET /employees?search=&is_active=&page=1
GET /employees/{id}
POST /employees
PUT /employees/{id}
PUT /employees/{id}/toggle-active
PUT /employees/{id}/reset-password
Body: {"new_password": "newpass123"}

## Dashboard (Owner & Karyawan)

GET /dashboard - Stats berbeda per role

## Settings (Owner Only)

GET /settings/store
PUT /settings/store
GET /settings/gold-prices
PUT /settings/gold-prices
Body:
{
  "prices": [
    {"purity": "24K", "price_per_gram": 1200000},
    {"purity": "22K", "price_per_gram": 1100000}
  ]
}

## Reports (Owner Only)

GET /reports/sales?start_date=2026-10-01&end_date=2026-10-31&export=csv
GET /reports/finances?start_date=2026-10-01&end_date=2026-10-31&export=csv
GET /reports/stock?export=csv
GET /reports/custom-orders?start_date=2026-10-01&end_date=2026-10-31&export=csv

## Response Format

Success:
{
  "success": true,
  "message": "...",
  "data": {...},
  "meta": {"page": 1, "per_page": 20, "total": 100, "total_pages": 5}
}

Error:
{
  "success": false,
  "message": "Error message",
  "errors": {"field": "validation error"}
}

## Testing Steps

1. Start server: php spark serve
2. Register pelanggan via POST /auth/register
3. Login and save token
4. Test public endpoints (no auth)
5. Create owner/karyawan via database seeder
6. Test protected endpoints with token
