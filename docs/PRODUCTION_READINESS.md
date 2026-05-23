# Production Readiness

Status kesiapan production untuk Logistic Vendor.

## Status Saat Ini

MVP sudah siap masuk tahap deploy server setelah konfigurasi production diisi.

Sudah selesai:

- Fitur inti admin, paket, tarif, resi, tracking, manifest, dashboard, dan laporan.
- UI admin Bahasa Indonesia dengan tema hijau-putih.
- Pesan validasi form Bahasa Indonesia.
- Automated test untuk flow utama.
- Template `.env.example` production-safe.
- Command `php artisan app:production-check`.
- Command `php artisan app:set-user-password`.
- Dokumentasi deployment, backup, smoke test, dan rollback.

## Yang Harus Diisi di Server

- Domain production.
- SSL/HTTPS.
- Database production kosong atau database hasil migrasi.
- User database dengan password kuat.
- `.env` production.
- Password user aplikasi yang bukan default.
- Worker queue jika server memakai background job.

## Command Verifikasi

Jalankan setelah deploy:

```bash
php artisan app:production-check
php artisan test
npm run build
```

Untuk server production yang tidak menyimpan dev dependency, `php artisan test` boleh dijalankan di staging/local sebelum deploy final.

## Kriteria Siap Go-Live

- `php artisan app:production-check` tidak memiliki FAIL.
- Semua user default sudah diberi password kuat.
- Login admin berhasil.
- Input paket berhasil membuat resi.
- Print resi berhasil di printer thermal.
- QR tracking membuka halaman publik.
- Manifest bisa dibuat dan dicetak.
- Backup database berhasil dibuat dan diuji restore.

## Catatan Keamanan

- Jangan gunakan password `password` untuk user production.
- Gunakan `php artisan app:set-user-password email@domain.com --generate` untuk membuat password kuat.
- Jangan gunakan kredensial database development.
- Jangan tampilkan `APP_DEBUG=true` ke publik.
- Jangan arahkan web server ke root project; arahkan ke folder `public`.
- Simpan backup di lokasi berbeda dari server aplikasi.
