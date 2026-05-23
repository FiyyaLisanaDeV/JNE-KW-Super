# PROJECT.md
# MVP Web Pengiriman Paket Antar Pelabuhan
## Rute Kendari – Raha – Baubau

---

## 0. Instruksi Eksekusi untuk Codex

File ini adalah panduan utama untuk coding agent.

Codex harus mengerjakan proyek secara bertahap, tidak langsung membangun semua fitur sekaligus.

Prioritas utama:

1. Sistem web mobile friendly.
2. Cepat dan reliable.
3. Manajemen paket.
4. Check-in paket.
5. Estimasi ongkir saat check-in.
6. Generate nomor resi.
7. Print resi.
8. Tracking resi publik.
9. Update status paket.
10. Struktur kode bersih dan mudah dikembangkan.

Jangan menambah fitur di luar scope tanpa instruksi eksplisit.

---

## 1. Ringkasan Produk

Produk ini adalah aplikasi web untuk jasa pengiriman paket perorangan antar pelabuhan dengan rute awal:

- Kendari → Raha
- Raha → Kendari
- Kendari → Baubau
- Baubau → Kendari
- Raha → Baubau
- Baubau → Raha

Masalah yang diselesaikan:

- Pengiriman masih informal.
- Harga tidak tetap.
- Tidak ada pencatatan paket yang rapi.
- Tidak ada nomor resi.
- Tidak ada tracking.
- Tidak ada bukti pengiriman yang proper.
- Tidak ada dashboard manajemen paket.

Solusi:

Aplikasi web mobile friendly untuk mencatat paket, menghitung ongkir, mencetak resi, mengelola status pengiriman, dan menyediakan halaman tracking publik.

---

## 2. Scope MVP

## 2.1 Fitur Wajib MVP

MVP wajib memiliki:

1. Login admin.
2. Dashboard admin.
3. Manajemen rute.
4. Manajemen tarif.
5. Check-in paket.
6. Estimasi ongkir saat check-in.
7. Generate nomor resi otomatis.
8. Manajemen paket.
9. Update status paket.
10. Print resi.
11. Tracking resi publik.
12. Riwayat status paket.
13. Manifest pengiriman sederhana.
14. Laporan harian sederhana.

## 2.2 Fitur yang Tidak Dikerjakan Dulu

Jangan kerjakan dulu:

- Aplikasi Android native.
- Aplikasi iOS native.
- Payment gateway.
- WhatsApp API otomatis.
- GPS tracking real-time.
- Customer login.
- Sistem klaim/asuransi kompleks.
- Akuntansi penuh.
- Multi perusahaan.
- Integrasi jadwal kapal otomatis.

---

## 3. Stack Teknologi

Gunakan stack berikut:

- Backend: Laravel 11 atau versi stabil terbaru.
- Admin Panel: Filament.
- Database: PostgreSQL atau MySQL.
- Frontend admin: Filament responsive UI.
- Public tracking page: Blade atau Livewire.
- Styling: Tailwind CSS.
- Print: CSS print layout.
- QR Code: package QR code Laravel.
- Deployment target: VPS Linux.

Rekomendasi utama:

> Laravel + Filament + PostgreSQL/MySQL + Blade public tracking page.

Alasan:

- Cepat untuk CRUD operasional.
- Cocok untuk admin dashboard.
- Mudah digunakan developer lokal.
- Reliable untuk MVP.
- Mudah deployment di VPS.
- Cocok untuk mobile-friendly web admin.

---

## 4. Struktur Repository

Gunakan struktur standar Laravel.

Tambahkan file dokumentasi berikut:

```text
/
├── PROJECT.md
├── AGENTS.md
├── README.md
├── docs/
│   ├── DATABASE.md
│   ├── MODULES.md
│   ├── RECEIPT_PRINT.md
│   ├── TRACKING.md
│   ├── PRICING_RULES.md
│   └── DEPLOYMENT.md
```

---

## 5. AGENTS.md

Buat file `AGENTS.md` di root repository.

Isi minimal:

```md
# AGENTS.md

## Project Rules

- Ikuti PROJECT.md sebagai sumber utama scope.
- Jangan membuat fitur di luar MVP tanpa instruksi.
- Gunakan Laravel + Filament.
- Prioritaskan mobile friendly.
- Semua form wajib memiliki validasi.
- Semua nomor resi wajib unik.
- Semua perubahan status paket wajib masuk ke status log.
- Jangan hardcode tarif di controller.
- Gunakan pricing_rules untuk perhitungan ongkir.
- Print resi harus bisa dibuka ulang dari detail paket.
- Tracking publik tidak boleh menampilkan nomor HP lengkap dan alamat lengkap.
- Semua migration, model, policy, resource, dan test harus konsisten.
- Setelah setiap mission, jalankan test atau minimal validasi manual.
```

---

## 6. Model Data

## 6.1 Entity Utama

Entity MVP:

1. users
2. branches
3. routes
4. pricing_rules
5. shipments
6. shipment_status_logs
7. manifests
8. manifest_items

Opsional setelah MVP:

1. payments
2. shipment_photos
3. complaints
4. pickup_requests
5. delivery_requests

---

# 7. Database Schema

## 7.1 users

Field:

- id
- name
- email
- password
- role
- branch_id
- created_at
- updated_at

Role:

- super_admin
- admin_loket
- agen_tujuan
- owner

---

## 7.2 branches

Field:

- id
- name
- city
- address
- phone
- is_active
- created_at
- updated_at

Contoh:

- Kendari Main Branch
- Raha Agent Point
- Baubau Agent Point

---

## 7.3 routes

Field:

- id
- origin_city
- destination_city
- origin_code
- destination_code
- route_code
- estimated_duration_hours
- is_active
- created_at
- updated_at

Contoh route_code:

- KDI-RHA
- RHA-KDI
- KDI-BBU
- BBU-KDI
- RHA-BBU
- BBU-RHA

---

## 7.4 pricing_rules

Field:

- id
- route_id
- package_category
- base_price
- price_per_kg
- minimum_weight
- volume_divisor
- pickup_fee
- delivery_fee
- packing_fee
- handling_fee
- is_active
- created_at
- updated_at

Package category:

- kecil
- sedang
- besar_ringan
- khusus

Catatan:

- Jangan hardcode tarif di controller.
- Semua estimasi ongkir harus membaca tabel ini.

---

## 7.5 shipments

Field:

- id
- receipt_number
- sender_name
- sender_phone
- sender_city
- sender_address
- receiver_name
- receiver_phone
- receiver_city
- receiver_address
- route_id
- item_description
- package_category
- koli_count
- actual_weight
- length_cm
- width_cm
- height_cm
- volume_weight
- chargeable_weight
- base_price
- pickup_fee
- delivery_fee
- packing_fee
- handling_fee
- discount_amount
- total_shipping_cost
- payment_status
- payment_method
- status
- estimated_departure_at
- estimated_arrival_at
- checked_in_at
- completed_at
- created_by
- destination_agent_id
- public_tracking_token
- internal_note
- customer_note
- created_at
- updated_at

payment_status:

- unpaid
- paid
- cancelled

status:

- checked_in
- waiting_departure
- in_transit
- arrived_destination
- ready_for_pickup
- completed
- problem
- cancelled

---

## 7.6 shipment_status_logs

Field:

- id
- shipment_id
- status
- note
- created_by
- created_at
- updated_at

Rule:

- Setiap perubahan status wajib membuat log baru.
- Tracking publik mengambil data dari log ini.

---

## 7.7 manifests

Field:

- id
- manifest_number
- route_id
- departure_date
- status
- origin_admin_id
- destination_agent_id
- note
- created_at
- updated_at

status:

- draft
- departed
- arrived
- closed
- cancelled

---

## 7.8 manifest_items

Field:

- id
- manifest_id
- shipment_id
- created_at
- updated_at

Rule:

- Satu shipment hanya boleh masuk ke satu manifest aktif.
- Paket dengan status completed/cancelled tidak boleh masuk manifest baru.

---

# 8. Nomor Resi

## 8.1 Format Resi

Gunakan format:

```text
{ORIGIN}-{DESTINATION}-{YYMMDD}-{SEQUENCE}
```

Contoh:

```text
KDI-RHA-260526-001
RHA-KDI-260526-001
KDI-BBU-260526-001
```

## 8.2 Rule Generate Resi

- Resi dibuat saat shipment disimpan.
- Resi harus unik.
- Sequence di-reset per tanggal dan per rute.
- Jika gagal generate karena collision, sistem harus retry.
- Simpan juga public_tracking_token untuk URL tracking yang aman.

---

# 9. Estimasi Ongkir

## 9.1 Tujuan

Fitur estimasi ongkir wajib tersedia pada halaman check-in.

Admin harus bisa mengetahui total ongkir sebelum menyimpan paket.

## 9.2 Input

Input estimasi:

- Rute
- Kategori paket
- Jumlah koli
- Berat aktual
- Panjang
- Lebar
- Tinggi
- Pickup yes/no
- Delivery yes/no
- Packing yes/no
- Handling khusus yes/no
- Diskon manual, opsional

## 9.3 Rumus

Berat volume:

```text
volume_weight = panjang_cm * lebar_cm * tinggi_cm / volume_divisor
```

Default volume_divisor:

```text
6000
```

Berat tagihan:

```text
chargeable_weight = max(actual_weight, volume_weight, minimum_weight)
```

Total ongkir:

```text
total =
  base_price
  + max(0, chargeable_weight - minimum_weight) * price_per_kg
  + pickup_fee_if_selected
  + delivery_fee_if_selected
  + packing_fee_if_selected
  + handling_fee_if_selected
  - discount_amount
```

## 9.4 Output Estimasi

Tampilkan:

- Rute
- Kategori paket
- Berat aktual
- Berat volume
- Berat tagihan
- Tarif dasar
- Biaya pickup
- Biaya delivery
- Biaya packing
- Biaya handling
- Diskon
- Total ongkir

## 9.5 Rule Penting

- Estimasi harus muncul sebelum tombol simpan final.
- Admin boleh override diskon, tetapi tidak boleh override total tanpa catatan.
- Total ongkir wajib tersimpan di shipment.
- Tarif transaksi lama tidak boleh berubah meskipun pricing_rules berubah.

---

# 10. Manajemen Paket

## 10.1 Halaman Daftar Paket

Tampilkan tabel:

- Nomor resi
- Tanggal check-in
- Pengirim
- Penerima
- Rute
- Kategori
- Total ongkir
- Status pembayaran
- Status paket
- Aksi

Aksi:

- Detail
- Update status
- Print resi
- Masukkan ke manifest
- Tandai bermasalah
- Batalkan

## 10.2 Filter

Filter:

- Nomor resi
- Nama pengirim
- Nama penerima
- Nomor HP
- Rute
- Status
- Payment status
- Tanggal check-in
- Agen tujuan

## 10.3 Detail Paket

Detail harus menampilkan:

- Data pengirim
- Data penerima
- Data barang
- Data tarif
- Status terakhir
- Riwayat status
- Tombol print resi
- Tombol update status
- Tombol tracking publik
- Catatan internal

---

# 11. Check-in Paket

## 11.1 Halaman Check-in

Form check-in terdiri dari bagian:

1. Data rute
2. Data pengirim
3. Data penerima
4. Data paket
5. Estimasi ongkir
6. Pembayaran
7. Konfirmasi akhir

## 11.2 Flow

1. Admin buka Check-in Paket.
2. Admin pilih rute.
3. Admin isi data pengirim.
4. Admin isi data penerima.
5. Admin isi detail paket.
6. Sistem hitung estimasi ongkir.
7. Admin konfirmasi total ke pelanggan.
8. Admin pilih status pembayaran.
9. Admin simpan.
10. Sistem generate nomor resi.
11. Sistem buat status log `checked_in`.
12. Admin print resi.

## 11.3 Validasi

Wajib:

- Rute
- Nama pengirim
- Nomor HP pengirim
- Nama penerima
- Nomor HP penerima
- Jenis barang
- Jumlah koli
- Kategori paket
- Total ongkir
- Payment status

Opsional:

- Alamat pengirim
- Alamat penerima
- Dimensi
- Catatan

---

# 12. Print Resi

## 12.1 Format Print

Buat 2 layout:

1. Thermal 80mm.
2. A5.

Prioritas MVP:

> Thermal 80mm dulu.

## 12.2 Isi Resi

Resi berisi:

- Nama usaha
- Nomor resi
- QR code tracking
- Tanggal check-in
- Rute
- Pengirim
- Penerima
- Jenis barang
- Jumlah koli
- Berat/kategori
- Total ongkir
- Status pembayaran
- Estimasi tiba
- Kontak admin
- Catatan layanan singkat

## 12.3 Syarat Print

- Bisa print dari detail paket.
- Bisa print setelah check-in selesai.
- Bisa print ulang.
- Layout bersih saat `window.print()`.
- Tidak menampilkan tombol/action saat mode print.

---

# 13. Tracking Resi Publik

## 13.1 URL

Format URL:

```text
/tracking
/tracking/{receipt_number}
```

Atau dengan token:

```text
/t/{public_tracking_token}
```

Rekomendasi:

- Untuk pencarian manual, gunakan receipt_number.
- Untuk QR code, gunakan public_tracking_token.

## 13.2 Form Cek Resi

Input:

- Nomor resi

Output:

- Jika ditemukan, tampilkan detail tracking.
- Jika tidak ditemukan, tampilkan pesan jelas.

## 13.3 Informasi yang Ditampilkan

Tampilkan:

- Nomor resi
- Status terakhir
- Rute
- Tanggal check-in
- Estimasi tiba
- Riwayat status
- Titik ambil jika tersedia
- Kontak admin

Jangan tampilkan:

- Nomor HP lengkap
- Alamat lengkap
- Catatan internal
- Nilai barang
- Data biaya internal

## 13.4 Masking Data

Contoh:

```text
Pengirim: Mul****
Penerima: Ahm****
HP: 0812****8899
```

---

# 14. Manifest Pengiriman

## 14.1 Fungsi

Manifest digunakan untuk mengelompokkan paket dalam satu keberangkatan.

## 14.2 Flow

1. Admin buat manifest berdasarkan rute.
2. Admin pilih paket dengan status `waiting_departure`.
3. Admin menambahkan paket ke manifest.
4. Admin print manifest.
5. Admin tandai manifest departed.
6. Sistem update semua paket menjadi `in_transit`.
7. Agen tujuan tandai manifest arrived.
8. Sistem update paket menjadi `arrived_destination`.

## 14.3 Print Manifest

Isi print manifest:

- Nomor manifest
- Tanggal
- Rute
- Petugas asal
- Agen tujuan
- Daftar resi
- Nama penerima
- Jumlah koli
- Jenis barang
- Catatan

---

# 15. Dashboard

## 15.1 Card Dashboard

Tampilkan:

- Paket masuk hari ini
- Paket menunggu keberangkatan
- Paket dalam perjalanan
- Paket tiba
- Paket selesai hari ini
- Paket bermasalah
- Total ongkir hari ini

## 15.2 Quick Action

Tombol:

- Check-in Paket
- Cari Resi
- Buat Manifest
- Paket Bermasalah
- Print Ulang Resi

---

# 16. Laporan Sederhana

## 16.1 Laporan Harian

Tampilkan:

- Tanggal
- Jumlah paket
- Total ongkir
- Rute
- Status pembayaran
- Paket selesai
- Paket bermasalah

## 16.2 Filter

Filter:

- Tanggal
- Rute
- Status
- Admin
- Agen tujuan

Export:

- CSV
- Excel, opsional

---

# 17. Security dan Reliability

## 17.1 Security

- Gunakan auth Laravel.
- Password wajib hashed.
- Gunakan role-based access.
- Public tracking hanya read-only.
- Jangan tampilkan data sensitif di halaman publik.
- Validasi semua input.
- Gunakan CSRF protection.
- Batasi akses resource berdasarkan role.

## 17.2 Reliability

- Nomor resi tidak boleh duplikat.
- Perubahan status harus atomic.
- Check-in harus menggunakan database transaction.
- Status log wajib dibuat bersamaan dengan shipment.
- Backup database harian.
- Error harus tercatat di log.
- Hindari proses berat di request utama.
- Foto paket opsional agar sistem tetap ringan.

---

# 18. UI/UX Requirement

## 18.1 Mobile Friendly

Semua halaman utama harus nyaman digunakan di HP:

- Dashboard
- Check-in paket
- Daftar paket
- Detail paket
- Print resi
- Tracking publik

## 18.2 Form

Form harus:

- Ringkas
- Label jelas
- Input besar untuk layar HP
- Validasi error mudah dipahami
- Tidak terlalu banyak field wajib
- Ada preview total ongkir

## 18.3 Warna Status

Gunakan badge status:

- checked_in: gray
- waiting_departure: amber
- in_transit: blue
- arrived_destination: purple
- ready_for_pickup: indigo
- completed: green
- problem: red
- cancelled: dark

---

# 19. Seed Data

Buat seed data:

## 19.1 Branches

- Kendari Main Branch
- Raha Agent Point
- Baubau Agent Point

## 19.2 Routes

- KDI-RHA
- RHA-KDI
- KDI-BBU
- BBU-KDI
- RHA-BBU
- BBU-RHA

## 19.3 Pricing Rules

Template awal:

| Rute | Kecil | Sedang | Besar Ringan |
|---|---:|---:|---:|
| KDI-RHA | 25000 | 40000 | 60000 |
| RHA-KDI | 25000 | 40000 | 60000 |
| KDI-BBU | 35000 | 50000 | 75000 |
| BBU-KDI | 35000 | 50000 | 75000 |
| RHA-BBU | 30000 | 45000 | 70000 |
| BBU-RHA | 30000 | 45000 | 70000 |

## 19.4 Users

- superadmin@example.com / password
- admin.kendari@example.com / password
- agen.raha@example.com / password
- agen.baubau@example.com / password
- owner@example.com / password

Gunakan password aman di environment production.

---

# 20. Testing Requirement

Minimal test:

1. Bisa login.
2. Bisa membuat route.
3. Bisa membuat pricing rule.
4. Bisa check-in paket.
5. Estimasi ongkir benar.
6. Nomor resi unik.
7. Status log dibuat saat check-in.
8. Bisa update status.
9. Status log bertambah saat update status.
10. Bisa print resi.
11. Bisa cek resi publik.
12. Data sensitif termasking di tracking publik.
13. Manifest bisa dibuat.
14. Paket bisa masuk manifest.
15. Saat manifest departed, status paket berubah in_transit.

---

# 21. Mission Plan untuk Codex

## Mission 0 — Setup Project

Tujuan:

- Buat project Laravel.
- Install Filament.
- Setup database.
- Setup auth.
- Buat struktur docs.

Task:

1. Initialize Laravel project.
2. Install Filament.
3. Configure database.
4. Create README.md.
5. Create AGENTS.md.
6. Create docs folder.
7. Confirm app can run locally.

Acceptance criteria:

- Laravel app running.
- Filament admin accessible.
- Login works.
- AGENTS.md exists.
- PROJECT.md exists.

---

## Mission 1 — Core Database & Models

Tujuan:

Membuat struktur database utama.

Task:

1. Create migrations:
   - branches
   - routes
   - pricing_rules
   - shipments
   - shipment_status_logs
   - manifests
   - manifest_items
2. Create models.
3. Add relationships.
4. Add enum-like constants for status.
5. Create seeders for routes, branches, pricing, users.

Acceptance criteria:

- `php artisan migrate:fresh --seed` works.
- All models have relationships.
- Seed data exists.

---

## Mission 2 — Admin Resource: Routes, Branches, Pricing

Tujuan:

Admin bisa mengelola rute, cabang, dan tarif.

Task:

1. Create Filament resource for branches.
2. Create Filament resource for routes.
3. Create Filament resource for pricing_rules.
4. Add validation.
5. Add active/inactive toggle.
6. Add filters.

Acceptance criteria:

- Admin can create/edit/delete routes.
- Admin can create/edit pricing rule.
- Pricing rule belongs to route.
- Invalid input rejected.

---

## Mission 3 — Shipping Cost Estimator

Tujuan:

Membuat service perhitungan ongkir.

Task:

1. Create `ShippingCostCalculator` service.
2. Input:
   - route_id
   - package_category
   - actual_weight
   - length_cm
   - width_cm
   - height_cm
   - pickup_selected
   - delivery_selected
   - packing_selected
   - handling_selected
   - discount_amount
3. Calculate:
   - volume_weight
   - chargeable_weight
   - base_price
   - extra fees
   - total
4. Add unit tests.

Acceptance criteria:

- Calculator returns correct breakdown.
- Uses pricing_rules.
- Does not hardcode route prices.
- Handles missing dimensions.
- Handles zero discount.
- Rejects invalid route/category.

---

## Mission 4 — Check-in Paket

Tujuan:

Admin bisa membuat paket baru.

Task:

1. Create Shipment Filament Resource.
2. Create custom create/check-in form.
3. Add sender fields.
4. Add receiver fields.
5. Add package fields.
6. Add route/category selection.
7. Show live ongkir estimation.
8. Save shipment with DB transaction.
9. Generate receipt number.
10. Create initial status log.
11. Redirect to detail/print page.

Acceptance criteria:

- Admin can check-in package.
- Ongkir appears before save.
- Receipt number generated.
- Status starts as `checked_in`.
- Status log created.
- Total shipping cost stored.

---

## Mission 5 — Manajemen Paket

Tujuan:

Admin bisa melihat dan mengelola paket.

Task:

1. Build shipment list table.
2. Add filters.
3. Add search by receipt number, sender, receiver, phone.
4. Add detail page.
5. Add status update action.
6. Add problem/cancel action.
7. Add status log display.

Acceptance criteria:

- Package searchable.
- Package filterable.
- Status can be updated.
- Every status update creates log.
- Completed/cancelled package cannot be edited freely.

---

## Mission 6 — Print Resi

Tujuan:

Admin bisa mencetak resi paket.

Task:

1. Create receipt print route.
2. Create 80mm print layout.
3. Add QR code.
4. Add print button.
5. Add CSS print media.
6. Hide UI controls when printing.
7. Add A5 layout if time permits.

Acceptance criteria:

- Receipt printable.
- Receipt can be reprinted.
- QR code points to public tracking URL.
- Layout works on mobile/laptop browser.
- 80mm layout readable.

---

## Mission 7 — Public Tracking

Tujuan:

Pelanggan bisa cek resi tanpa login.

Task:

1. Create public tracking page.
2. Create search form.
3. Create tracking result page.
4. Add route `/tracking/{receipt_number}`.
5. Add route `/t/{public_tracking_token}`.
6. Show status timeline.
7. Mask sensitive data.
8. Handle not found state.

Acceptance criteria:

- Public user can search receipt.
- QR code opens tracking page.
- Status timeline visible.
- Phone and address masked.
- Internal notes hidden.

---

## Mission 8 — Manifest Pengiriman

Tujuan:

Admin bisa membuat manifest keberangkatan.

Task:

1. Create Manifest Filament Resource.
2. Create manifest number generator.
3. Add route/departure date.
4. Allow selecting eligible packages.
5. Print manifest.
6. Action: mark departed.
7. Action: mark arrived.
8. Auto-update package statuses.

Acceptance criteria:

- Manifest can be created.
- Packages can be added.
- Manifest can be printed.
- Mark departed updates package status to `in_transit`.
- Mark arrived updates package status to `arrived_destination`.

---

## Mission 9 — Dashboard & Reports

Tujuan:

Owner/admin bisa melihat ringkasan operasional.

Task:

1. Create dashboard cards.
2. Count packages by status.
3. Show total shipping cost today.
4. Show route activity.
5. Create simple daily report.
6. Add filters.

Acceptance criteria:

- Dashboard loads.
- Counts are correct.
- Daily total is correct.
- Report filter works.

---

## Mission 10 — Hardening & Cleanup

Tujuan:

Menyiapkan MVP agar layak dipakai operasional.

Task:

1. Add authorization policies.
2. Add form validation.
3. Add database indexes.
4. Add backup notes in docs.
5. Add deployment docs.
6. Add tests for critical flows.
7. Clean unused code.
8. Review mobile display.
9. Review print layout.

Acceptance criteria:

- Main flow stable.
- Tests pass.
- No duplicate receipt.
- No public data leak.
- Mobile display acceptable.
- Print receipt acceptable.
- Docs updated.

---

# 22. Prompt Eksekusi Codex per Mission

Gunakan prompt kecil per mission.

## Prompt Mission 0

```text
Read PROJECT.md and AGENTS.md. Execute Mission 0 only.

Set up the Laravel + Filament project structure for the MVP Web Pengiriman Paket Antar Pelabuhan.

Do not implement shipment features yet.

After finishing, summarize:
1. Files created/changed
2. Commands run
3. How to run locally
4. Any issue or assumption
```

## Prompt Mission 1

```text
Read PROJECT.md and AGENTS.md. Execute Mission 1 only.

Create the core database migrations, models, relationships, constants, and seeders for:
branches, routes, pricing_rules, shipments, shipment_status_logs, manifests, and manifest_items.

Do not build Filament resources yet except if required for basic testing.

Run migration and seed validation.

After finishing, summarize:
1. Files created/changed
2. Schema created
3. Seeder data
4. Validation result
```

## Prompt Mission 2

```text
Read PROJECT.md and AGENTS.md. Execute Mission 2 only.

Build Filament resources for Branches, Routes, and Pricing Rules.

Focus on CRUD, validation, filters, and active/inactive state.

Do not build shipment check-in yet.

After finishing, summarize:
1. Files created/changed
2. Admin pages available
3. Validation rules
4. Manual test result
```

## Prompt Mission 3

```text
Read PROJECT.md and AGENTS.md. Execute Mission 3 only.

Create the ShippingCostCalculator service and tests.

The calculator must read pricing_rules and return a full cost breakdown:
volume_weight, chargeable_weight, base_price, extra fees, discount, and total.

Do not build UI yet.

After finishing, summarize:
1. Files created/changed
2. Calculator behavior
3. Test cases
4. Test result
```

## Prompt Mission 4

```text
Read PROJECT.md and AGENTS.md. Execute Mission 4 only.

Build the package check-in flow using Filament.

The form must support:
sender data, receiver data, package data, route selection, live shipping cost estimation, payment status, receipt number generation, and initial status log.

Use database transaction when saving shipment.

After finishing, summarize:
1. Files created/changed
2. Check-in flow
3. Validation rules
4. Manual test result
```

## Prompt Mission 5

```text
Read PROJECT.md and AGENTS.md. Execute Mission 5 only.

Build package management:
shipment list, search, filters, detail page, update status action, problem/cancel action, and status log display.

Every status update must create shipment_status_logs.

After finishing, summarize:
1. Files created/changed
2. Package management features
3. Status transition behavior
4. Manual test result
```

## Prompt Mission 6

```text
Read PROJECT.md and AGENTS.md. Execute Mission 6 only.

Build print receipt feature.

Create 80mm thermal print layout first.
Receipt must include receipt number, QR tracking code, route, sender, receiver, package info, total shipping cost, payment status, and estimated arrival.

After finishing, summarize:
1. Files created/changed
2. Print route
3. Receipt layout
4. Manual print test result
```

## Prompt Mission 7

```text
Read PROJECT.md and AGENTS.md. Execute Mission 7 only.

Build public receipt tracking.

Create:
- /tracking page
- /tracking/{receipt_number}
- /t/{public_tracking_token}

Show status timeline and mask sensitive data.

After finishing, summarize:
1. Files created/changed
2. Public routes
3. Privacy masking behavior
4. Manual test result
```

## Prompt Mission 8

```text
Read PROJECT.md and AGENTS.md. Execute Mission 8 only.

Build manifest management.

Admin can create manifest, add eligible packages, print manifest, mark departed, and mark arrived.
Status of related packages must update automatically and create status logs.

After finishing, summarize:
1. Files created/changed
2. Manifest flow
3. Status automation
4. Manual test result
```

## Prompt Mission 9

```text
Read PROJECT.md and AGENTS.md. Execute Mission 9 only.

Build dashboard and simple daily reports.

Show cards for package counts by status and total shipping cost today.
Add report filters by date, route, status, admin, and agent.

After finishing, summarize:
1. Files created/changed
2. Dashboard metrics
3. Report filters
4. Manual test result
```

## Prompt Mission 10

```text
Read PROJECT.md and AGENTS.md. Execute Mission 10 only.

Perform hardening and cleanup:
authorization policies, validation review, database indexes, critical flow tests, mobile display review, print layout review, and deployment docs.

Do not add new features.

After finishing, summarize:
1. Files changed
2. Bugs fixed
3. Tests run
4. Remaining risks
```

---

# 23. Definition of Done Final

Project MVP selesai jika:

1. Admin bisa login.
2. Admin bisa kelola rute.
3. Admin bisa kelola tarif.
4. Admin bisa check-in paket.
5. Sistem menghitung estimasi ongkir.
6. Sistem generate nomor resi unik.
7. Admin bisa print resi 80mm.
8. QR code resi membuka tracking publik.
9. Pelanggan bisa cek resi tanpa login.
10. Admin bisa update status.
11. Riwayat status tersimpan.
12. Admin bisa buat manifest.
13. Manifest bisa diprint.
14. Dashboard menampilkan ringkasan operasional.
15. Data sensitif tidak bocor di halaman publik.
16. Tampilan nyaman di HP.
17. Database bisa di-backup.
18. Dokumentasi dasar tersedia.

---

# 24. Catatan Bisnis

Aplikasi ini hanya alat bantu operasional.

Keberhasilan bisnis tetap bergantung pada:

- SOP penerimaan paket.
- PIC asal dan tujuan.
- Tarif yang masuk akal.
- Jadwal kapal.
- Keamanan barang.
- Komunikasi pelanggan.
- Kecepatan update status.
- Kepercayaan pelanggan.

Jangan membangun fitur terlalu jauh sebelum rute dan tarif terbukti berjalan.
