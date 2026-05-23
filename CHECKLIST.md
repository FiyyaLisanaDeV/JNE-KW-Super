# CHECKLIST.md

Checklist eksekusi MVP Web Pengiriman Paket Antar Pelabuhan.

## Status Legend

- [x] Selesai
- [ ] Belum dikerjakan

## Mission 0 - Setup Project

- [x] Initialize Laravel project
- [x] Install Filament
- [x] Configure database
- [x] Create README.md
- [x] Create AGENTS.md
- [x] Create docs folder
- [x] Confirm app can run locally

## Mission 1 - Core Database & Models

- [x] Create migrations for core entities
- [x] Create models
- [x] Add relationships
- [x] Add enum-like constants for roles/status/category
- [x] Create seeders for branches, routes, pricing, and users
- [x] Validate `php artisan migrate:fresh --seed`

## Mission 2 - Admin Resource: Routes, Branches, Pricing

- [x] Create Filament resource for branches
- [x] Create Filament resource for routes
- [x] Create Filament resource for pricing_rules
- [x] Add validation
- [x] Add active/inactive toggle
- [x] Add filters
- [x] Validate admin CRUD manually

## Mission 3 - Shipping Cost Estimator

- [x] Create `ShippingCostCalculator` service
- [x] Read pricing from `pricing_rules`
- [x] Calculate volume weight
- [x] Calculate chargeable weight
- [x] Calculate fee breakdown and total
- [x] Handle missing dimensions and zero discount
- [x] Reject invalid route/category
- [x] Add unit tests

## Mission 4 - Check-in Paket

- [x] Create Shipment Filament Resource
- [x] Create check-in form
- [x] Add sender fields
- [x] Add receiver fields
- [x] Add package fields
- [x] Add route/category selection
- [x] Show live ongkir estimation
- [x] Save shipment with DB transaction
- [x] Generate receipt number
- [x] Create initial status log
- [x] Redirect to detail/print page

## Mission 5 - Manajemen Paket

- [x] Build shipment list table
- [x] Add filters
- [x] Add search by receipt, sender, receiver, phone
- [x] Add detail page
- [x] Add status update action
- [x] Add problem/cancel action
- [x] Display status logs
- [x] Ensure every status update creates log

## Mission 6 - Print Resi

- [x] Create receipt print route
- [x] Create 80mm print layout
- [x] Add QR code
- [x] Add print button
- [x] Add print CSS
- [x] Hide UI controls in print mode
- [x] Validate reprint flow

## Mission 7 - Public Tracking

- [x] Create `/tracking` page
- [x] Create `/tracking/{receipt_number}`
- [x] Create `/t/{public_tracking_token}`
- [x] Add receipt search form
- [x] Show status timeline
- [x] Mask sensitive data
- [x] Hide internal notes
- [x] Handle not found state

## Mission 8 - Manifest Pengiriman

- [x] Create Manifest Filament Resource
- [x] Create manifest number generator
- [x] Add route/departure date fields
- [x] Allow selecting eligible packages
- [x] Print manifest
- [x] Mark departed
- [x] Mark arrived
- [x] Auto-update package statuses and logs

## Mission 9 - Dashboard & Reports

- [x] Create dashboard cards
- [x] Count packages by status
- [x] Show total shipping cost today
- [x] Show route activity
- [x] Create simple daily report
- [x] Add filters by date, route, status, admin, agent

## Mission 10 - Hardening & Cleanup

- [x] Add authorization policies
- [x] Review form validation
- [x] Add database indexes where needed
- [x] Add backup notes
- [x] Add deployment docs
- [x] Add critical flow tests
- [x] Review mobile display
- [x] Review print layout
- [x] Clean unused code

## UI Polish - Admin Redesign

- [x] Terapkan brand `Logistik Nusantara`
- [x] Terapkan palet hijau-putih dari UI Kit
- [x] Rapikan sidebar, topbar, table, filter, button, input, badge, modal, dan empty state
- [x] Tambah kartu ringkasan di halaman tarif
- [x] Buat widget dashboard langsung tampil tanpa lazy load
- [x] Validasi build frontend
- [x] Validasi test backend
- [x] Smoke test admin desktop dan mobile

## UI Polish Lanjutan - Konsistensi Halaman Admin

- [x] Tambah heading dan subheading untuk Cabang
- [x] Tambah heading dan subheading untuk Rute
- [x] Tambah heading dan subheading untuk Paket
- [x] Tambah heading dan subheading untuk Manifest
- [x] Samakan table heading dan deskripsi tiap resource
- [x] Pindahkan filter Cabang, Rute, Paket, Manifest ke atas tabel
- [x] Ubah status aktif/nonaktif menjadi badge teks
- [x] Ubah status paket, pembayaran, dan manifest menjadi badge teks berwarna
- [x] Smoke test Cabang, Rute, Paket, Manifest, dan Tarif

## Production Readiness

- [x] Sanitasi `.env.example` agar aman untuk production
- [x] Lengkapi dokumentasi deployment server
- [x] Tambah dokumentasi backup, restore, dan rollback
- [x] Tambah go-live checklist
- [x] Tambah `docs/PRODUCTION_READINESS.md`
- [x] Tambah command `php artisan app:production-check`
- [x] Tambah command `php artisan app:set-user-password`
- [x] Validasi command production readiness
- [x] Jalankan `php artisan test`
- [x] Jalankan `npm run build`
- [x] Smoke test `/admin/login`
- [x] Smoke test `/tracking`

## Server Go-Live

- [ ] Siapkan domain production
- [ ] Siapkan SSL/HTTPS
- [ ] Siapkan database production dan user DB kuat
- [ ] Isi `.env` production di server
- [ ] Deploy kode ke server
- [ ] Jalankan migration production
- [ ] Generate password production untuk semua user
- [ ] Jalankan `php artisan app:production-check` sampai tidak ada FAIL
- [ ] Test printer thermal fisik
- [ ] Test QR tracking dari HP
- [ ] Buat dan uji restore backup pertama
