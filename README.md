# Logistic Vendor

MVP web pengiriman paket antar pelabuhan untuk rute Kendari, Raha, dan Baubau.

## Stack

- Laravel 12
- Filament 4
- MySQL 8
- Tailwind/Vite bawaan Laravel

## Local Setup

Pastikan PHP, Composer, Node.js, npm, Git, dan MySQL sudah tersedia di terminal.

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

Untuk lokal development, ubah `.env` menjadi:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logistic_vendor
DB_USERNAME=superadmin
DB_PASSWORD=password_database_lokal
```

## Run Locally

```powershell
php artisan serve
```

Admin panel:

```text
http://localhost:8000/admin
```

Default development admin:

```text
Email: superadmin@example.com
Password: password
```

Ganti password sebelum production.

Command production readiness:

```powershell
php artisan app:production-check
php artisan app:set-user-password superadmin@example.com --generate
```

## Documentation

- `docs/USER_GUIDE.md`: panduan operasional admin/operator.
- `docs/QA.md`: catatan test dan smoke test terakhir.
- `docs/DEPLOYMENT.md`: deployment, backup, dan smoke test production.
- `docs/PRODUCTION_READINESS.md`: status kesiapan production dan kriteria go-live.
- `docs/DATABASE.md`: entity dan index penting.
- `docs/MODULES.md`: ringkasan modul per mission.
