# Catatan QA

Catatan validasi terakhir MVP.

## Automated Tests

```text
php artisan test
26 passed, 117 assertions
```

Coverage utama:

- Master data admin resources.
- Kalkulasi ongkir.
- Check-in paket dan nomor resi.
- Manajemen status dan log status.
- Cetak resi thermal.
- Pelacakan publik dan masking data.
- Nomor manifest, cetak, berangkat, tiba.
- Widget dasbor dan filter laporan harian.

## Build

```text
npm run build
success
```

## Smoke Test Lokal

```text
/admin/login -> HTTP 200
/tracking -> HTTP 200
/admin -> redirect ke /admin/login saat belum login
/tracking?receipt_number=RESI-TIDAK-ADA -> not found state tampil
```

## Visual Smoke

Target:

```text
http://127.0.0.1:8000/tracking
```

Viewport yang dicek:

- Desktop 1366x768.
- Mobile 390x844.

Hasil:

- Halaman tidak blank.
- Form tracking terlihat.
- Layout mobile berubah menjadi satu kolom.
- State resi tidak ditemukan tampil jelas.

## Remaining Production Checks

Sebelum production:

- Ganti password default semua user seed.
- Set `APP_DEBUG=false`.
- Set `APP_URL` ke domain production.
- Jalankan `php artisan app:production-check`.
- Jalankan backup database manual pertama.
- Coba print resi dari printer thermal fisik.
- Coba QR dari kamera HP.
