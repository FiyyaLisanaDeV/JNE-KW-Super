# Modules

## Mission 1

- Core master data: branches, routes, pricing rules.
- Data inti paket: shipments dan shipment status logs.
- Data inti manifest: manifests dan manifest items.
- User roles: super_admin, admin_loket, agen_tujuan, owner.

## Mission 4

- Check-in paket tersedia melalui Filament resource `/admin/shipments/create`.
- Check-in menghitung ongkir dari `pricing_rules`, membuat receipt number, public tracking token, dan status log awal.

## Mission 5

- Manajemen paket tersedia di `/admin/shipments`.
- Detail shipment menampilkan data utama dan riwayat status.
- Update status, tandai bermasalah, dan batalkan shipment membuat status log baru.

## Mission 6

- Print resi tersedia dari detail shipment.
- Route print: `/admin/shipments/{shipment}/receipt`.
- QR code pada resi mengarah ke token tracking publik.

## Mission 7

- Public tracking tersedia di `/tracking`.
- Route langsung nomor resi: `/tracking/{receipt_number}`.
- Route QR token: `/t/{public_tracking_token}`.
- Data sensitif dimasking dan internal note tidak tampil ke publik.

## Mission 8

- Manifest pengiriman tersedia di `/admin/manifests`.
- Manifest number dibuat otomatis per rute dan tanggal.
- Paket eligible bisa dipilih dari status `waiting_departure`.
- Print manifest tersedia di `/admin/manifests/{manifest}/print`.
- Mark departed mengubah paket menjadi `in_transit`.
- Mark arrived mengubah paket menjadi `arrived_destination`.

## Mission 9

- Kartu dasbor menampilkan ringkasan paket dan ongkir hari ini.
- Laporan harian tersedia di `/admin/daily-report`.
- Filter laporan: tanggal, rute, status, admin, dan agen.

## Mission 10

- Policy dasar ditambahkan untuk Cabang, Rute, Tarif, Paket, dan Manifest.
- Deployment dan backup notes tersedia di `docs/DEPLOYMENT.md`.
- Index database utama terdokumentasi di `docs/DATABASE.md`.
