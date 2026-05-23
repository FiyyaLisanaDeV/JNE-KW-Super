# Receipt Print

Mission 6 membuat print resi thermal 80mm.

Route:

```text
/admin/shipments/{shipment}/receipt
```

Isi resi:

- Nama usaha
- Nomor resi
- QR code tracking ke `/t/{public_tracking_token}`
- Tanggal check-in
- Rute
- Pengirim dan penerima
- Info barang, koli, berat/kategori
- Total ongkir
- Status pembayaran
- Estimasi tiba
- Catatan layanan singkat

Layout:

- Lebar utama `80mm`.
- Tombol/action disembunyikan saat print melalui `@media print`.
- Bisa dibuka ulang dari halaman detail shipment.
