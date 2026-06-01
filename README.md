<div align="center">
  <img src="public/images/logo.png" alt="Lisanna Logistic Logo" width="200" />
  <h1>📦 Lisanna Logistic (Logistik Nusantara)</h1>
  <p><strong>Sistem Manajemen Pengiriman & Operasional Logistik Cerdas</strong></p>

  [![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![Filament](https://img.shields.io/badge/Filament-v3-EAB308?style=for-the-badge&logo=laravel&logoColor=white)](https://filamentphp.com)
  [![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
</div>

<hr/>

## ✨ Fitur Unggulan

- 🚛 **Manajemen Cabang & Wilayah**: Integrasi API data wilayah otomatis untuk area Sulawesi (Sulawesi Selatan, Tengah, dan Tenggara).
- 📦 **Pembuatan Resi (AWB) Dinamis**: Pembuatan nomor resi otomatis yang cerdas dan anti-bentrok.
- 💰 **Kalkulasi Ongkir Cerdas**: Penghitungan tarif otomatis dengan *Pricing Rules* berdasarkan wilayah asal dan tujuan.
- 🚚 **Manifest Kendaraan**: Kelola jadwal keberangkatan, rute, dan daftar muatan setiap armada/kurir.
- 🔍 **Tracking Instan**: Pelacakan posisi paket secara *real-time* via Dasbor operasional yang *clean* dan super cepat.
- 📊 **Dashboard Analitik**: Ringkasan performa pengiriman hari ini (jumlah paket masuk, proses, tiba, hingga ongkir terkumpul) yang menggunakan gaya desain modern.

---

## 🚀 Memulai Instalasi Lokal

Siapkan kopi Anda ☕, lalu pastikan sistem sudah memiliki **PHP**, **Composer**, **Node.js**, **Git**, dan **MySQL**.

### 1. Kloning & Persiapan
Buka terminal dan eksekusi perintah ini:
```bash
git clone https://github.com/FiyyaLisanaDeV/JNE-KW-Super.git
cd JNE-KW-Super

composer install
npm install
```

### 2. Konfigurasi Lingkungan
Buat *file* konfigurasi lokal (*environment*):
```bash
cp .env.example .env
php artisan key:generate
```
Edit file `.env` dan pastikan konfigurasi *database* sesuai dengan sistem lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logistic_vendor
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Sinkronisasi Wilayah
Jalankan migrasi *database* untuk membuat semua struktur tabel:
```bash
php artisan migrate
```
**Penting:** Tarik data Provinsi, Kota/Kabupaten, dan Kecamatan langsung dari API Resmi Emsifa!
```bash
php artisan app:sync-regions
```

### 4. Nyalakan Mesin!
Buka terminal dan jalankan server PHP bawaan:
```bash
php artisan serve
```
Buka **terminal baru** untuk menjalankan kompilator aset tampilan (TailwindCSS):
```bash
npm run dev
```

---

## 🔐 Akses Admin Panel

Masuk ke markas besar operasional Anda:
👉 **[http://localhost:8000/admin](http://localhost:8000/admin)**

**Kredensial Default Development:**
- **Email:** `superadmin@example.com`
- **Password:** `password`

> [!WARNING]  
> **Keamanan:** Segera ganti *password* bawaan ini apabila sistem sudah masuk ke lingkungan *Production*! 🚨

---

## 📚 Pusat Dokumentasi

Ingin menggali lebih dalam? Silakan baca direktori dokumentasi internal di `docs/`:
- 📖 **[Panduan Pengguna (USER_GUIDE)](docs/USER_GUIDE.md)** - Cara sakti pakai aplikasi.
- 🗄️ **[Arsitektur Database (DATABASE)](docs/DATABASE.md)** - Struktur tabel & relasi (*schema*).
- 🚀 **[Panduan Rilis (DEPLOYMENT)](docs/DEPLOYMENT.md)** - *Checklist* rahasia sebelum terbang ke *production*.
- 🧪 **[Catatan Uji Coba (QA)](docs/QA.md)** - Laporan pengujian aplikasi terbaru.

---
<div align="center">
  Dibuat dengan ❤️ untuk masa depan operasional logistik.
</div>
