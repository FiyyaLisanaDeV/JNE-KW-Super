# Deployment Production

Catatan deployment production untuk Logistic Vendor.

## Server Minimal

- PHP 8.3+ dengan extension `intl`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, dan `fileinfo`.
- Composer 2.
- Node.js 20+ dan npm untuk build asset.
- MySQL 8.
- Web server Nginx atau Apache diarahkan ke folder `public`.

## Environment Production

Salin `.env.example` menjadi `.env`, lalu isi nilai production:

```env
APP_NAME="Logistic Vendor"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-produksi-anda

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logistic_vendor
DB_USERNAME=nama_user_db
DB_PASSWORD=password_kuat

SESSION_DRIVER=database
SESSION_ENCRYPT=true
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Wajib:

- Jangan memakai password development di production.
- Jangan commit file `.env`.
- Pastikan `APP_KEY` sudah terisi dari `php artisan key:generate --force`.
- Pastikan `APP_URL` memakai HTTPS.

## Build dan Deploy

Jalankan di server:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:assets
php artisan app:production-check
```

Jika production check menemukan user dengan password default, ganti password user:

```bash
php artisan app:set-user-password superadmin@example.com --generate
php artisan app:set-user-password admin.kendari@example.com --generate
php artisan app:set-user-password agen.raha@example.com --generate
php artisan app:set-user-password agen.baubau@example.com --generate
php artisan app:set-user-password owner@example.com --generate
```

Simpan hasil password di password manager. Setelah itu jalankan ulang:

```bash
php artisan app:production-check
```

Folder yang perlu writable oleh user web server:

```text
storage
bootstrap/cache
```

## Web Server

Root dokumen web server harus mengarah ke folder `public`, bukan root project.

Contoh Nginx ringkas:

```nginx
server {
    listen 80;
    server_name domain-produksi-anda;
    root /var/www/logistic-vendor/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan SSL dengan Certbot atau SSL panel hosting sebelum go-live.

## Queue Worker

MVP ini memakai `QUEUE_CONNECTION=database`. Untuk production, jalankan worker:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Jika memakai Supervisor, pastikan worker restart otomatis setelah deploy:

```bash
php artisan queue:restart
```

## Backup

Backup database harian direkomendasikan:

```bash
mysqldump -u nama_user_db -p logistic_vendor > backup-logistic-vendor-$(date +%F).sql
```

Simpan backup di lokasi berbeda dari server aplikasi. Uji restore minimal sebelum go-live:

```bash
mysql -u nama_user_db -p logistic_vendor_restore < backup-logistic-vendor-YYYY-MM-DD.sql
```

File yang juga perlu masuk strategi backup:

- `.env` production.
- Folder `storage/app` jika nanti menyimpan file upload.
- Dump database harian, minimal disimpan di lokasi berbeda dari server aplikasi.

## Rollback Singkat

Jika deploy bermasalah:

1. Aktifkan maintenance mode:

```bash
php artisan down --render="errors::503"
```

2. Kembalikan kode ke release sebelumnya.
3. Jalankan:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan up
```

Jika masalah berasal dari migration destructive, restore database dari backup terakhir.

## Go-Live Checklist

- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL` memakai domain HTTPS.
- [ ] `php artisan app:production-check` tidak memiliki item FAIL.
- [ ] Semua password default user sudah diganti.
- [ ] Admin bisa login dari jaringan kantor.
- [ ] Printer thermal fisik berhasil print resi.
- [ ] QR resi berhasil discan dari kamera HP.
- [ ] Backup database pertama berhasil dibuat dan restore test berhasil.
- [ ] Domain dan SSL aktif.

## Smoke Test Setelah Deploy

- `/admin/login` terbuka.
- Login super admin berhasil.
- Data Master Cabang, Rute, dan Tarif bisa dibuka.
- Check-in paket menghasilkan nomor resi.
- Cetak resi terbuka dan QR mengarah ke pelacakan publik.
- `/tracking` bisa mencari resi.
- Manifest bisa dibuat, dicetak, ditandai berangkat, dan ditandai tiba.
