# Catatan Resolusi Bug (Buglog.md)
*Dokumen ini mencatat daftar error krusial yang terjadi selama pengembangan sistem Lisanna Logistic agar dapat dihindari di masa depan.*

---

## 1. Fatal Error 500: `Cannot redeclare non static Filament\Widgets\Widget::$view`
- **Tanggal:** 1 Juni 2026
- **Lokasi:** `app/Filament/Widgets/QuickActionsWidget.php`
- **Dampak:** Seluruh sistem gagal dimuat (Error 500 di *Landing Page* dan *Admin Dashboard*).
- **Penyebab:** Pada pembuatan widget Filament kustom, properti `$view` dideklarasikan menggunakan modifier `static` (`protected static string $view`), padahal pada _class_ induk `Filament\Widgets\Widget`, properti tersebut adalah tipe _instance_ biasa (`protected string $view`). Karena mekanisme *auto-discover* Filament memuat seluruh file widget secara global setiap ada *request*, ketidaksesuaian deklarasi ini merusak (crash) keseluruhan aplikasi.
- **Resolusi:** Menghapus kata kunci `static` pada file tersebut menjadi `protected string $view = '...';`.

## 2. Fatal Error 500: `Class "App\Models\Route" not found`
- **Tanggal:** 1 Juni 2026
- **Lokasi:** 
    - `app/Filament/Pages/DailyReport.php`
    - `app/Services/ReceiptNumberGenerator.php`
    - `app/Services/ManifestNumberGenerator.php`
- **Dampak:** Terjadi crash dan *exception* ketika membuka panel admin.
- **Penyebab:** *Refactoring* besar-besaran yang kita lakukan mengubah arsitektur sistem pengiriman logistik dari sebelumnya menggunakan model rute statis tunggal (`Route`) menjadi model geografis bertingkat yang lebih modern dan luas skalanya (`Branch`, `City`, `Province`). Model `Route` tersebut telah dihapus secara fisik, tetapi beberapa file masih memanggil `use App\Models\Route;`.
- **Resolusi:** Menulis ulang dan memigrasi seluruh kode fungsi generator resi, generator manifes, serta antarmuka `DailyReport.php` agar menggunakan model `Branch` (`origin_branch_id` & `destination_branch_id`). Penomoran kini menggunakan identifikasi Cabang (misal: `BR001-260601-001`).

## 3. Namespace Error: Filament Grid & Section Component
- **Tanggal:** (Sebelumnya)
- **Lokasi:** `app/Filament/Resources/ShipmentResource.php` dan skema formulirnya.
- **Dampak:** UI form rusak karena kesalahan referensi namespace *Grid*.
- **Penyebab:** Kesalahan _import_ menggunakan namespace v2/v3 yang lama (`Forms\Components\Grid`), sementara sistem *form builder* menghendaki namespace terstandarisasi.
- **Resolusi:** Merapikan dan memperbarui pemanggilan komponen formulir seperti `Filament\Forms\Components\Grid`, `Section`, dan `TextInput` secara spesifik di atas file.

## 4. Keamanan Container/Server: `Protocol error (Target.setDiscoverTargets): Target closed`
- **Tanggal:** 1 Juni 2026
- **Lokasi:** Plugin `chrome-devtools-mcp` dan *Headless Chrome*.
- **Dampak:** DevTools tak bisa otomatis memantau halaman via agen jarak jauh (AI).
- **Penyebab:** Aplikasi dijalankan dari lingkungan Docker/VPS yang menggunakan akses `root`. Secara _default_, *Chromium kernel* akan menolak berjalan dengan akun super-admin untuk mencegah eksploitasi (*sandbox violation*). Meski diberikan argumen `--no-sandbox` dan `--disable-dev-shm-usage`, perlindungan kernel lingkungan *hosting* ini sangat ketat.
- **Resolusi:** Penelusuran *error console/network* dilakukan menggunakan inspeksi langsung `storage/logs/laravel.log` serta validasi *frontend* secara reguler oleh *developer* via Chrome DevTools lokal (komputer *developer*).

## 5. Fatal Error 500 (Berulang): `Cannot redeclare non static Filament\Widgets\Widget::$view`
- **Tanggal:** 1 Juni 2026
- **Lokasi:** `app/Filament/Widgets/DashboardHeroWidget.php` & `DashboardTrackingWidget.php`
- **Dampak:** Error 500 berulang di seluruh aplikasi.
- **Penyebab:** Human-error pengulangan masalah Bug No.1 saat merombak desain widget baru.
- **Resolusi:** Segera menghapus kembali kata kunci `static`. Menjadi pengingat absolut bahwa *custom widget* di Filament v3 **tidak boleh** memakai properti static pada `$view`.

## 6. Fatal Error 500: Ketidakcocokan Tipe Return `getColumns()`
- **Tanggal:** 1 Juni 2026
- **Lokasi:** `app/Filament/Widgets/DashboardStatsWidget.php`
- **Dampak:** Error 500 saat boot aplikasi.
- **Penyebab:** Pada Filament v3, kelas turunan `StatsOverviewWidget` mewajibkan *return type declaration* yang persis sama dengan induknya, yaitu `array | int | null`. Namun, secara tidak sengaja terdefinisi sebagai `int | string | array`. Perbedaan urutan dan union type di PHP >= 8 akan menyebabkan fatal error saat *inheritance*.
- **Resolusi:** Mengubah fungsi menjadi `protected function getColumns(): array | int | null`. Menjadi standar bahwa setiap *override* metode inti Filament harus memperhatikan deklarasi asli *vendor* secara detail.

---
*Log akan terus diperbarui jika ditemukan anomali baru pada sistem.*
