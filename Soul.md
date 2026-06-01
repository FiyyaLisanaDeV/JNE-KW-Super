# Lisanna Logistic - The Soul of the System

Dokumen ini adalah ringkasan komprehensif, panduan filosofis, arsitektur, fungsi, dan peta jalan (roadmap) dari aplikasi **Lisanna Logistic**. Dibuat sebagai "nyawa" dan acuan utama bagi seluruh pengembang, pemangku kepentingan, dan operator sistem.

---

## 1. Filosofi & Visi

### Visi Utama
Menjadi urat nadi logistik terpercaya di seluruh pelosok Sulawesi (khususnya Kendari, Unahaa, Kolaka Timur, Kolaka, Kolaka Utara, Konawe Selatan, Konawe Utara, Morowali/Bahodopi, hingga Makassar dan kota-kota di Sulawesi Selatan). 

Lisanna Logistic bertransformasi dari sekadar sistem kurir *port-to-port* sederhana menjadi **Layanan Logistik Skala Penuh (General Logistic Service)** yang mampu melayani pengiriman paket satuan (ritel) hingga kargo skala besar (B2B).

### Nilai Inti (Core Values)
1. **Fleksibilitas (Flexibility):** Sistem harus mampu menangani berbagai jenis layanan (Reguler, Ekonomis, B2B) dengan perhitungan volumetrik dan berat aktual yang otomatis.
2. **Keterlacakan (Traceability):** Setiap pergerakan paket—mulai dari *Drop Point* ke *Hub*, perjalanan lintas kota, hingga dibawa kurir—wajib tercatat secara mendetail dan *real-time*.
3. **Kemudahan Akses (Accessibility):** Antarmuka (UI/UX) dirancang seintuitif mungkin, baik untuk operator admin cabang yang sibuk, maupun pelanggan yang melacak resi melalui perangkat seluler (*Mobile Friendly*).

---

## 2. Arsitektur & Teknologi (Tech Stack)

Aplikasi ini dibangun di atas fondasi teknologi modern yang kuat dan terukur:
- **Core Framework:** Laravel 11 / PHP 8.3
- **Admin Dashboard:** Filament PHP v3 (Menyediakan antarmuka CRUD yang kaya, interaktif, dan modular).
- **Frontend / Landing Page:** Blade Templating Engine + Vanilla CSS / Tailwind CSS (Desain modern, estetika hijau-putih yang elegan, responsif, SEO-ready).
- **Database:** MySQL / MariaDB (Relasional yang kuat untuk menjaga integritas data hierarki wilayah dan paket).
- **Server Environment:** Nginx pada Linux (Optimasi performa produksi).

---

## 3. Fitur Utama & Fungsionalitas

Sistem ini digerakkan oleh **Service Layer** yang cerdas, memisahkan logika bisnis kompleks dari *Controller*:

### A. Manajemen Wilayah & Jaringan Cabang (Geographic & Network)
*   **Hierarki Wilayah:** Data dikelola secara terstruktur melalui `Provinces` -> `Cities` -> `Districts`.
*   **Branches (Cabang):** Mengelola titik operasi yang dibedakan jenisnya, seperti *Hub* (Pusat Transit) dan *Drop Point* (Titik Penerimaan). Setiap cabang terikat pada Kota/Kecamatan tertentu.

### B. Mesin Pentarifan Cerdas (Smart Pricing Engine - `PricingService`)
*   Tarif tidak lagi statis/hardcode. Tarif ditentukan berdasarkan matriks **Kota Asal** ke **Kota Tujuan**.
*   Mendukung jenis layanan berbeda: **Reguler** (Satuan), **B2B** (Kargo), dan **Ekonomis**.
*   **Perhitungan Volumetrik Otomatis:** Sistem membandingkan Berat Aktual vs Berat Volume (Panjang x Lebar x Tinggi / Divisor) dan menetapkan *Chargeable Weight* (Berat yang Dihitung) yang paling menguntungkan perusahaan secara wajar.
*   Biaya tambahan (Pickup, Delivery, Packing, Handling) diintegrasikan langsung dalam konfigurasi harga.

### C. Manajemen Pengiriman Paket (Shipment Management)
*   Formulir input paket yang dioptimalkan UX-nya di Filament Admin (dibagi menjadi kolom Data Pengirim, Penerima, Detail Paket, dan Biaya).
*   Pembuatan **Nomor Resi (AWB)** secara otomatis dan unik per transaksi.
*   Pencetakan label resi pengiriman yang terstandardisasi.

### D. Pelacakan & Log Status (Tracking System - `ShipmentTrackingService`)
*   Pergerakan paket (*Checked In, In Transit, Arrived, Out for Delivery, Completed*) selalu tersimpan di `ShipmentStatusLogs`.
*   Pelacakan Publik (Public Tracking) yang aman privasi: menyembunyikan nomor HP dan alamat lengkap agar tidak disalahgunakan pihak ketiga.

### E. Manajemen Perjalanan / Manifest (Linehaul & Dispatch)
*   Memasukkan banyak paket ke dalam satu *Manifest* perjalanan.
*   Mengelola informasi Supir (*Driver*), Kendaraan (*Vehicle*), Cabang Asal, dan Cabang Tujuan transit.

---

## 4. Struktur Database Inti

Refactoring besar-besaran (melalui migrasi `refactor_logistic_general_schema`) memastikan integritas data. Model utama meliputi:
*   `Province`, `City`, `District` : Referensi wilayah.
*   `Branch` : Titik lokasi logistik.
*   `PricingRule` : Aturan harga antarkota & layanan.
*   `Shipment` : Data inti resi, pengirim, penerima, dan tarif total.
*   `ShipmentStatusLog` : Riwayat pelacakan (*history*).
*   `Manifest` & `ManifestItem` : Penggabungan resi untuk perjalanan antar kota/hub.

---

## 5. Peta Jalan Pengembangan (Roadmap)

Perjalanan *Lisanna Logistic* ke depan dirancang melalui tahapan terstruktur:

### Fase 1: Fondasi Skala Penuh (SELESAI ✅)
- [x] Migrasi skema dari sistem rute statis menjadi hierarki wilayah geografis.
- [x] Pembaruan Model Eloquent (Branch, PricingRule, Shipment, Manifest).
- [x] Desain ulang Landing Page yang responsif dengan maskot dan warna identitas perusahaan.

### Fase 2: Mesin Inti (Core Engine) (SELESAI ✅)
- [x] Pembuatan `PricingService` untuk perhitungan *Chargeable Weight* dan dinamisasi tarif.
- [x] Pembuatan `ShipmentTrackingService` untuk integritas log status paket.

### Fase 3: Modernisasi Antarmuka Admin (SELESAI ✅)
- [x] Regenerasi Filament Resources untuk skema database baru.
- [x] Perombakan UX/UI Formulir *Shipment* (Pembagian *Grid* logis untuk Pengirim, Penerima, Biaya).

### Fase 4: Optimasi Opersional (SEGERA DILAKUKAN 🚀)
- [ ] **Pencetakan Label (Waybill Printing):** Sistem *print* resi termal/A4 dengan barcode yang dapat dibuka langsung dari detail paket di Admin.
- [ ] **Barcode Scanning:** Memungkinkan operator *scan* resi untuk memperbarui status transit atau memuat paket ke dalam Manifest secara massal (Bulk Action).
- [ ] **Role & Permissions:** Pembatasan hak akses (Admin Cabang hanya bisa melihat/menginput paket dari cabangnya sendiri, Kurir hanya melihat paket *out_for_delivery*, Pusat melihat semua).

### Fase 5: Ekspansi Pelanggan & Integrasi Bisnis (MASA DEPAN 🌟)
- [ ] **Client Portal B2B:** Dashboard khusus untuk klien perusahaan besar melacak ribuan paket mereka secara mandiri, *request pickup*, dan melihat rekap tagihan bulanan.
- [ ] **Integrasi API:** Membuka jalur API untuk e-commerce lokal di Sulawesi agar ongkir Lisanna Logistic muncul otomatis saat proses *checkout*.
- [ ] **Mobile App Kurir:** Aplikasi khusus kurir (*driver app*) untuk tanda tangan penerima digital (*e-POD*), foto bukti pengiriman, dan update koordinat GPS.

---
*Dokumen ini adalah jantung dari evolusi teknologi Lisanna Logistic. Setiap baris kode yang ditulis harus tunduk pada visi dan aturan operasional di dalam dokumen ini.*
