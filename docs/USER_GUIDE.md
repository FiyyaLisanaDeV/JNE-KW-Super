# User Guide

Panduan singkat penggunaan MVP Logistic Vendor.

## Login Admin

URL lokal:

```text
http://127.0.0.1:8000/admin
```

User development:

```text
superadmin@example.com / password
```

Ganti password sebelum production.

## Data Master

Menu master:

- Cabang: data cabang/agen.
- Rute: rute antar kota/pelabuhan.
- Tarif: tarif per rute dan kategori paket.

Tarif aktif dipakai otomatis saat check-in paket.

## Check-in Paket

Masuk ke:

```text
/admin/shipments/create
```

Isi:

- Rute.
- Data pengirim.
- Data penerima.
- Deskripsi paket.
- Kategori, berat, dimensi, dan jumlah koli.
- Opsi pickup, delivery, packing, handling, diskon, dan pembayaran.

Saat disimpan, sistem otomatis:

- Menghitung ongkir.
- Membuat nomor resi.
- Membuat token tracking publik.
- Membuat status awal `checked_in`.
- Membuat log status awal.

## Manajemen Paket

Masuk ke:

```text
/admin/shipments
```

Operator bisa:

- Mencari resi, pengirim, penerima, dan nomor HP.
- Filter status, rute, dan status pembayaran.
- Melihat detail paket.
- Update status.
- Tandai bermasalah.
- Batalkan paket.
- Print ulang resi.

## Cetak Resi

Dari detail paket, klik `Cetak Resi`.

Resi memakai layout thermal 80mm dan QR code ke tracking publik.

## Tracking Publik

URL:

```text
/tracking
/tracking/{nomor_resi}
/t/{public_tracking_token}
```

Halaman publik menampilkan:

- Status terakhir.
- Timeline status.
- Data pengirim/penerima yang sudah dimasking.

Internal note tidak ditampilkan di publik.

## Manifest

Masuk ke:

```text
/admin/manifests
```

Alur:

1. Buat manifest.
2. Pilih rute, tanggal berangkat, agen tujuan, dan paket eligible.
3. Print manifest.
4. Mark departed saat kapal/armada berangkat.
5. Mark arrived saat paket tiba di tujuan.

Status paket ikut berubah otomatis:

- Mark departed -> `in_transit`.
- Mark arrived -> `arrived_destination`.

## Dasbor & Laporan Harian

Dasbor:

```text
/admin
```

Laporan harian:

```text
/admin/daily-report
```

Filter report:

- Tanggal.
- Rute.
- Status.
- Admin.
- Agen.
