# PROGRESS.md

Progress proyek MVP Web Pengiriman Paket Antar Pelabuhan.

## Current Status

Status MVP: **Mission 0-10 selesai**.

Status UI: **Redesign admin hijau-putih diterapkan**.

## Completed

### Mission 0 - Setup Project

Status: selesai.

Hasil:

- Laravel project dibuat di root repository.
- Filament 4 terpasang dengan admin panel `/admin`.
- Database MySQL lokal terkonfigurasi.
- Admin development dibuat.
- Dokumentasi dasar dibuat.
- App berjalan lokal di `http://127.0.0.1:8000`.

Validasi:

```text
php artisan test
2 passed

/admin/login
HTTP 200
```

Catatan:

- Laravel menggunakan versi 12.60.2 karena Filament 4 kompatibel dengan Laravel 12.
- PHP lengkap 8.4.21 dipakai karena PHP Herd Lite tidak membawa extension `intl`.

### Mission 1 - Core Database & Models

Status: selesai.

Hasil:

- Migration core dibuat untuk branches, routes, pricing_rules, shipments, shipment_status_logs, manifests, dan manifest_items.
- `users` ditambah `role` dan `branch_id`.
- Model relationship dan konstanta status/category/role dibuat.
- Seeder awal dibuat.

Seed data:

```text
branches: 3
routes: 6
pricing_rules: 18
users: 5
```

User development:

```text
superadmin@example.com / password
admin.kendari@example.com / password
agen.raha@example.com / password
agen.baubau@example.com / password
owner@example.com / password
```

Validasi:

```text
php artisan migrate:fresh --seed
php artisan test
2 passed
```

### Mission 2 - Admin Resource: Routes, Branches, Pricing

Status: selesai.

Hasil:

- Filament resource dibuat untuk Branches, Routes, dan Pricing Rules.
- Form CRUD punya validasi dasar, toggle active/inactive, dan dropdown relasi route.
- Tabel master data punya search, filter aktif/nonaktif, dan filter relevan.
- Aksi edit/delete tersedia pada list.
- User model dibuat kompatibel dengan akses Filament panel.

Admin pages:

```text
/admin/branches
/admin/routes
/admin/pricing-rules
```

Validasi:

```text
php artisan route:list --path=admin
php artisan test
5 passed
```

### Mission 3 - Shipping Cost Estimator

Status: selesai.

Hasil:

- Service `App\Services\ShippingCostCalculator` dibuat.
- Kalkulator membaca tarif aktif dari `pricing_rules`.
- Output breakdown mencakup actual weight, volume weight, chargeable weight, tarif dasar, biaya per kg, extra fees, diskon, subtotal, dan total.
- Missing dimensions menghasilkan `volume_weight = 0`.
- Discount kosong dianggap `0`.
- Total tidak bisa negatif.
- Route/category tanpa active pricing rule ditolak.

Validasi:

```text
php artisan test
10 passed
```

### Mission 4 - Check-in Paket

Status: selesai.

Hasil:

- Filament resource Shipment dibuat di `/admin/shipments`.
- Form check-in mencakup rute, data pengirim, data penerima, data paket, estimasi ongkir, pembayaran, dan catatan.
- Preview ongkir memakai `ShippingCostCalculator` dan update saat input utama berubah.
- Shipment disimpan dalam database transaction.
- Nomor resi dibuat otomatis dengan format `{ROUTE}-{YYMMDD}-{SEQUENCE}`.
- Sequence resi reset per tanggal dan per rute.
- Public tracking token dibuat saat check-in.
- Status awal shipment adalah `checked_in`.
- Status log awal dibuat bersama shipment.

Validasi:

```text
php artisan test
12 passed
```

### Mission 5 - Manajemen Paket

Status: selesai.

Hasil:

- Shipment list dirapikan untuk operasi harian.
- Search tersedia untuk nomor resi, pengirim, penerima, dan data utama lain.
- Filter tersedia untuk route, status, dan payment status.
- Detail/view page shipment dibuat.
- Detail menampilkan ringkasan, pengirim/penerima, paket/tarif, catatan, dan riwayat status.
- Action update status tersedia dari list dan detail.
- Action tandai bermasalah dan batalkan tersedia.
- Setiap perubahan status membuat `shipment_status_logs`.
- Shipment completed/cancelled dikunci dari update status service.

Validasi:

```text
php artisan test
16 passed
```

### Mission 6 - Print Resi

Status: selesai.

Hasil:

- Route print resi dibuat di `/admin/shipments/{shipment}/receipt`.
- Layout thermal 80mm dibuat.
- QR code dibuat dari URL tracking `/t/{public_tracking_token}`.
- Tombol `Print Resi` tersedia dari detail shipment.
- Layout bisa diprint ulang dari detail shipment.
- CSS `@media print` menyembunyikan toolbar/action saat cetak.
- Route print memakai auth Filament agar akses tanpa login diarahkan ke `/admin/login`.

Validasi:

```text
php artisan test
18 passed
```

### Mission 7 - Public Tracking

Status: selesai.

Hasil:

- Halaman publik `/tracking` dibuat.
- Pencarian resi tersedia lewat form dan URL `/tracking/{receipt_number}`.
- QR token resi membuka `/t/{public_tracking_token}`.
- Timeline status paket tampil untuk publik.
- Nomor HP dan alamat dimasking.
- Internal note tidak tampil.
- State resi tidak ditemukan dibuat jelas.

Validasi:

```text
php artisan test
21 passed
```

### Mission 8 - Manifest Pengiriman

Status: selesai.

Hasil:

- Filament resource Manifest dibuat di `/admin/manifests`.
- Manifest number otomatis dibuat per rute dan tanggal.
- Form manifest memilih paket eligible dengan status `waiting_departure`.
- Print manifest tersedia di `/admin/manifests/{manifest}/print`.
- Mark departed mengubah paket manifest menjadi `in_transit`.
- Mark arrived mengubah paket manifest menjadi `arrived_destination`.
- Setiap update status paket dari manifest membuat status log.

Validasi:

```text
php artisan test
24 passed
```

### Mission 9 - Dashboard & Reports

Status: selesai.

Hasil:

- Dashboard cards dibuat untuk ringkasan paket hari ini, status operasional, paket bermasalah, dan total ongkir hari ini.
- Daily report tersedia di `/admin/daily-report`.
- Filter report tersedia untuk tanggal, rute, status, admin, dan agen.
- Report menampilkan ringkasan total paket, total ongkir, paid, unpaid, dan tabel shipment.

Validasi:

```text
php artisan test
26 passed
```

### Mission 10 - Hardening & Cleanup

Status: selesai.

Hasil:

- Policy dasar ditambahkan untuk Branch, Route, Pricing Rule, Shipment, dan Manifest.
- Form validation dan flow kritikal diuji lewat feature/unit tests.
- Index database utama sudah tersedia di migration dan terdokumentasi.
- Backup notes dan deployment docs dilengkapi.
- Dokumentasi modul diperbarui sampai Mission 10.

Validasi akhir:

```text
php artisan test
26 passed

npm run build
success

Smoke test lokal
/admin/login HTTP 200
/tracking HTTP 200
/admin redirects to /admin/login when unauthenticated
/tracking?receipt_number=RESI-TIDAK-ADA shows not found state
```

### UI Polish

Status: selesai.

Hasil:

- Admin panel memakai brand `Logistik Nusantara`, font Inter, dan sidebar 260px.
- Theme Filament dibuat di `resources/css/filament/admin/theme.css`.
- Palet hijau-putih dari referensi UI Kit diterapkan ke background, sidebar, topbar, table, button, input, badge, modal, empty state, dan stats card.
- Halaman Tarif Pengiriman memakai header/subheading Bahasa Indonesia, tombol `Tambah Tarif`, filter di atas tabel, badge status `Aktif`/`Tidak Aktif`, dan kartu ringkasan tarif.
- Widget dashboard dibuat non-lazy agar ringkasan langsung tampil saat membuka admin.

Validasi:

```text
npm run build
php artisan test
26 passed, 117 assertions

Playwright smoke test
/admin desktop + mobile console clean
/admin/pricing-rules desktop + mobile console clean
```

### UI Polish Lanjutan - Konsistensi Admin

Status: selesai.

Hasil:

- Halaman Cabang, Rute, Paket, Manifest, dan Tarif punya heading/subheading Bahasa Indonesia yang konsisten.
- Table section diberi heading dan deskripsi sesuai konteks halaman.
- Filter utama dipindah ke area atas tabel agar alur kerja lebih cepat untuk admin.
- Status aktif/nonaktif, pembayaran, status paket, dan status manifest memakai badge teks agar lebih mudah dibaca.
- Tampilan Paket desktop dan mobile dicek setelah polish.

Validasi:

```text
php artisan test
26 passed, 117 assertions

npm run build
success

Playwright smoke test
/admin/branches ok
/admin/routes ok
/admin/shipments ok
/admin/manifests ok
/admin/pricing-rules ok
console clean
```

### Production Readiness

Status: siap deploy server setelah konfigurasi domain/server diisi.

Hasil:

- `.env.example` disanitasi untuk template production-safe.
- Dokumentasi deploy production diperluas di `docs/DEPLOYMENT.md`.
- Dokumen readiness baru dibuat di `docs/PRODUCTION_READINESS.md`.
- Command `php artisan app:production-check` dibuat untuk mengecek env, debug, key, HTTPS, DB, migration, writable folder, password default, asset build, dan session encryption.
- Command `php artisan app:set-user-password {email} --generate` dibuat untuk mengganti password user production dengan password kuat.
- Validasi Bahasa Indonesia tetap aktif untuk form admin.

Validasi:

```text
php artisan test
26 passed, 117 assertions

npm run build
success

Smoke test lokal
/admin/login HTTP 200
/tracking HTTP 200

php artisan app:production-check
command works; local environment correctly reports FAIL for APP_DEBUG=true and default development passwords
```

Catatan:

- Lokal development sengaja tetap memakai `APP_ENV=local`, `APP_DEBUG=true`, dan password default agar proses UAT lokal tidak terputus.
- Saat deploy server, isi `.env` production, generate password user dengan `app:set-user-password`, lalu jalankan ulang `app:production-check` sampai tidak ada FAIL.
