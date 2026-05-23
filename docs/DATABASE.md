# Database

Mission 1 membuat entity utama MVP:

- `users`: akun admin dengan `role` dan relasi opsional ke `branches`.
- `branches`: cabang atau titik agen.
- `routes`: rute antar kota/pelabuhan awal.
- `pricing_rules`: tarif per rute dan kategori paket.
- `shipments`: data paket, snapshot tarif, nomor resi, status, dan token tracking publik.
- `shipment_status_logs`: riwayat status paket.
- `manifests`: manifest keberangkatan.
- `manifest_items`: daftar paket dalam manifest.

Seed awal mencakup cabang Kendari/Raha/Baubau, 6 rute awal, 18 pricing rule, dan 5 user development.

## Index Penting

- `shipments.receipt_number` unique untuk pencarian resi.
- `shipments.public_tracking_token` unique untuk QR tracking publik.
- `shipments.status`, `shipments.payment_status`, dan `shipments.checked_in_at` untuk filter operasional.
- `shipments(route_id, status)` untuk daftar paket per rute/status.
- `shipment_status_logs(shipment_id, created_at)` untuk timeline paket.
- `manifests.manifest_number` unique untuk dokumen manifest.
- `manifests(departure_date)`, `manifests(status)`, dan `manifests(route_id, departure_date)` untuk pencarian manifest.
- `manifest_items.shipment_id` unique agar satu paket hanya masuk satu manifest aktif.

## Backup

Gunakan `mysqldump` harian untuk database production dan simpan hasil backup di lokasi terpisah dari server aplikasi. Restore test perlu dilakukan berkala agar file backup tidak hanya tersimpan, tapi benar-benar bisa dipakai.
