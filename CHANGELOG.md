# Changelog

Semua perubahan yang signifikan pada proyek ini akan didokumentasikan dalam file ini.

## [Unreleased] - 2026-06-01

### Ditambahkan (Added)
- **Data Wilayah**: Fitur command `app:sync-regions` untuk menarik data Provinsi, Kota/Kabupaten, dan Kecamatan dari API Emsifa, difilter khusus untuk wilayah operasi logistik (Sulawesi Selatan, Sulawesi Tengah, Sulawesi Tenggara).
- **Dashboard Widgets**: Pembuatan arsitektur widget baru (`DashboardTrackingWidget`, `QuickActionsWidget`, `DashboardStatsWidget`) berbasis Filament v3.

### Diubah (Changed)
- **Bahasa & Label**: Internasionalisasi (I18n) / pelokalan label tabel, tombol, dan form di dalam *Admin Panel* ke bahasa Indonesia (contoh: *Resi Pengiriman*, *Daftar Cabang*).
- **Tampilan Dashboard (UI/UX)**: Implementasi desain antarmuka HTML *custom* (berbasis Tailwind) ke dalam sistem *widget* Filament, meliputi:
  - *Hero Section* (Pencarian/Lacak Resi AWB) dengan background dan *input field* modern.
  - *Menu Cepat Operasional* dengan gaya grid 4 kolom dan ikon fungsional.
  - *Ringkasan Hari Ini (Stats)* dengan tampilan statis (HTML Grid) menyesuaikan tema hijau/putih dengan elemen UI modern.
- **Navigasi Sidebar**: Menyembunyikan menu *Data Wilayah* (Provinsi, Kota/Kabupaten, Kecamatan) dari sidebar agar antarmuka fokus ke fungsi operasional utama logistik.

### Diperbaiki (Fixed)
- **Error 500 (Namespace Action)**: Memperbaiki masalah *namespace* class `EditAction` pada Filament Resources.
- **Error 500 (Type Hinting)**: Memperbaiki *type hint* `$navigationGroup` pada semua model `Resource` menjadi `\UnitEnum|string|null` agar kompatibel dengan standar Filament v3.
- **Error 500 (Static Property)**: Memperbaiki kesalahan deklarasi variabel `$view` yang menggunakan properti `static` pada pewarisan class `Widget` Filament.
