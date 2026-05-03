# TaniSync

TaniSync adalah aplikasi web berbasis Laravel untuk membantu digitalisasi pencatatan panen dan harga komoditas pertanian di tingkat desa atau kelompok tani. Aplikasi ini dirancang agar admin desa/gapoktan dapat mengelola data komoditas, memperbarui harga harian, memantau catatan panen petani, dan melihat laporan ringkas dari data yang sudah masuk.

Pada sisi petani, TaniSync menyediakan alur sederhana untuk melihat harga komoditas terbaru dan mencatat hasil panen pribadi. Data panen yang dimasukkan petani akan masuk ke database, lalu dapat ditinjau dan diverifikasi oleh admin.

## Tujuan Project

Project ini dibuat sebagai MVP atau minimum viable product untuk sistem informasi pertanian desa. Fokus utama MVP adalah:

- Mengubah pencatatan panen dari manual menjadi digital.
- Menyimpan data komoditas, harga, pasar, petani, dan panen ke database.
- Membagi akses aplikasi berdasarkan role pengguna.
- Memberikan dashboard ringkas untuk admin dan petani.
- Menyiapkan dasar laporan panen dan harga untuk pengembangan berikutnya.

## Role Pengguna

TaniSync memiliki dua role utama:

### Admin

Admin adalah pengelola data desa atau gapoktan. Admin dapat:

- Login ke dashboard admin.
- Melihat ringkasan operasional desa.
- Mengelola master data komoditas.
- Menambah, mengubah, dan menonaktifkan komoditas.
- Menginput harga harian komoditas.
- Melihat seluruh catatan panen petani.
- Mengubah status verifikasi panen.
- Melihat laporan dasar berdasarkan data panen.

### Petani

Petani adalah pengguna yang mencatat hasil panennya sendiri. Petani dapat:

- Login ke dashboard petani.
- Melihat ringkasan panen pribadi.
- Melihat referensi harga komoditas terbaru.
- Mencatat hasil panen baru.
- Melihat riwayat panen pribadi.
- Menunggu data panen diverifikasi oleh admin.

## Fitur Utama

### 1. Autentikasi dan Role-Based Access

TaniSync menggunakan sistem autentikasi Laravel. Saat login, pengguna harus memilih role yang sesuai dengan akun:

- `admin`
- `petani`

Jika email, password, dan role tidak cocok, login akan ditolak. Setelah berhasil login, pengguna diarahkan ke dashboard sesuai role.

### 2. Dashboard Admin

Dashboard admin menampilkan ringkasan data dari database, seperti:

- Jumlah petani aktif.
- Total panen bulan berjalan.
- Jumlah komoditas aktif.
- Jumlah catatan panen yang masih menunggu verifikasi.
- Tren panen bulanan.
- Distribusi komoditas berdasarkan volume panen.

### 3. Dashboard Petani

Dashboard petani menampilkan informasi yang relevan untuk petani, seperti:

- Total panen pribadi bulan berjalan.
- Jumlah komoditas aktif.
- Harga komoditas terbaru.
- Jumlah riwayat panen tersimpan.
- Grafik sederhana hasil panen pribadi.

### 4. Manajemen Komoditas

Admin dapat mengelola data komoditas yang digunakan dalam form panen dan harga harian. Data komoditas meliputi:

- Nama komoditas.
- Kategori.
- Satuan.
- Harga acuan.
- Status aktif atau nonaktif.

Komoditas yang aktif akan muncul di form input panen petani dan form input harga admin.

### 5. Harga Harian Komoditas

Admin dapat menginput harga harian komoditas berdasarkan pasar tertentu. Data harga disimpan ke tabel harga harian dalam format JSON per komoditas.

Harga terbaru akan ditampilkan kepada petani sebagai referensi sebelum menjual hasil panen.

### 6. Catatan Panen Petani

Petani dapat mencatat hasil panen dengan data:

- Komoditas.
- Tanggal panen.
- Lokasi atau blok lahan.
- Jumlah panen.
- Satuan.
- Kualitas panen.
- Catatan tambahan.

Setelah disimpan, data masuk ke tabel `catatan_panen` dengan status awal `menunggu`.

### 7. Verifikasi Panen oleh Admin

Admin dapat melihat semua catatan panen dari petani dan mengubah statusnya menjadi:

- `menunggu`
- `terverifikasi`
- `butuh-review`

Status ini membantu admin memilah data yang sudah siap masuk laporan dan data yang masih perlu dicek.

### 8. Laporan Dasar

Halaman laporan admin menampilkan data panen dari database dengan filter dasar:

- Rentang tanggal.
- Komoditas.
- Petani.

Laporan menampilkan:

- Total panen.
- Jumlah catatan terverifikasi.
- Jumlah catatan menunggu.
- Daftar catatan panen terbaru sesuai filter.

Export PDF server-side, Excel native `.xlsx`, dan CSV kompatibel Excel sudah aktif.

## Cara Kerja Aplikasi

Alur kerja umum TaniSync:

1. Admin membuat atau memastikan data komoditas tersedia.
2. Admin menginput harga harian komoditas.
3. Petani login dan melihat harga terbaru.
4. Petani mencatat hasil panen.
5. Data panen tersimpan di database dengan status `menunggu`.
6. Admin memeriksa catatan panen yang masuk.
7. Admin mengubah status panen menjadi `terverifikasi` atau `butuh-review`.
8. Data yang sudah masuk dapat dilihat dalam dashboard dan laporan dasar.

## Teknologi yang Digunakan

Project ini menggunakan:

- Laravel 12
- PHP 8.2+
- MySQL/MariaDB
- phpMyAdmin untuk pengelolaan database lokal
- Laravel Breeze untuk autentikasi
- Blade template
- Tailwind CSS
- Vite
- PHPUnit untuk testing

## Struktur Database Utama

Database yang digunakan bernama:

```text
tanisync
```

Tabel utama:

| Tabel | Fungsi |
| --- | --- |
| `users` | Menyimpan akun admin dan petani |
| `kategori_komoditas` | Menyimpan kategori komoditas |
| `komoditas` | Menyimpan master data komoditas |
| `pasar` | Menyimpan data pasar |
| `harga_bapok_harian` | Menyimpan harga harian komoditas per pasar |
| `catatan_panen` | Menyimpan data panen petani |
| `sessions` | Menyimpan session login Laravel |
| `cache` | Menyimpan cache Laravel |
| `jobs` | Menyimpan job queue Laravel |

## Konfigurasi Database

Project ini menggunakan MySQL lokal dengan konfigurasi default XAMPP:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tanisync
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database `tanisync` sudah dibuat terlebih dahulu di phpMyAdmin sebelum menjalankan migration.

## Cara Menjalankan Project

### 1. Clone atau buka folder project

Masuk ke folder project:

```bash
cd D:\Semester_6\capstoneProject\TaniSync
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Siapkan file environment

Jika belum ada `.env`, salin dari `.env.example`:

```bash
copy .env.example .env
```

Lalu generate application key:

```bash
php artisan key:generate
```

### 5. Buat database di phpMyAdmin

Buka phpMyAdmin, lalu buat database baru:

```text
tanisync
```

### 6. Jalankan migration dan seeder

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:

- Menghapus tabel lama.
- Membuat ulang semua tabel.
- Mengisi data awal untuk demo.

### 7. Jalankan server Laravel

```bash
php artisan serve
```

Aplikasi biasanya berjalan di:

```text
http://127.0.0.1:8000
```

### 8. Jalankan Vite untuk development frontend

Di terminal lain:

```bash
npm run dev
```

Untuk build production asset:

```bash
npm run build
```

## Akun Demo

Seeder menyediakan akun demo berikut:

### Admin

```text
Email: admin@tanisync.id
Password: password123
Role: admin
```

### Petani

```text
Email: rahmat@tanisync.id
Password: password123
Role: petani
```

Tambahan petani demo:

```text
Email: sari@tanisync.id
Password: password123
Role: petani
```

## Data Awal dari Seeder

Seeder akan membuat data awal:

- 1 akun admin.
- 2 akun petani.
- 4 kategori komoditas.
- 5 komoditas.
- 1 pasar.
- 2 data harga harian.
- 4 catatan panen demo.

Data ini dapat dilihat langsung melalui phpMyAdmin setelah menjalankan:

```bash
php artisan migrate:fresh --seed
```

## Route Utama

Beberapa route utama aplikasi:

| Route | Role | Fungsi |
| --- | --- | --- |
| `/` | Publik | Landing page |
| `/login` | Publik | Login |
| `/register` | Publik | Registrasi |
| `/dashboard` | Auth | Redirect sesuai role |
| `/admin/dashboard` | Admin | Dashboard admin |
| `/admin/access-requests` | Admin | Persetujuan akses admin |
| `/admin/activity-logs` | Admin | Audit aktivitas sistem |
| `/admin/commodities` | Admin | Manajemen komoditas |
| `/admin/prices` | Admin | Input harga harian |
| `/admin/harvests` | Admin | Monitoring dan verifikasi panen |
| `/admin/reports` | Admin | Laporan dasar |
| `/admin/reports/print` | Admin | Versi cetak laporan |
| `/admin/reports/export-pdf` | Admin | Export laporan PDF |
| `/admin/reports/export-xlsx` | Admin | Export laporan Excel `.xlsx` |
| `/admin/reports/export-csv` | Admin | Export laporan CSV |
| `/petani/dashboard` | Petani | Dashboard petani |
| `/petani/prices` | Petani | Harga komoditas |
| `/petani/harvests` | Petani | Riwayat panen |
| `/petani/harvests/new` | Petani | Form catat panen |
| `/account/pending` | Admin pending | Status persetujuan akun |

## Status Implementasi Saat Ini

Status project setelah finalisasi Tahap 3:

- Auth dan role access: sudah berjalan.
- Persetujuan admin: akun admin baru masuk status menunggu dan harus disetujui admin aktif.
- Status akun: user mendukung status `active`, `pending`, dan `rejected`.
- Audit log: aktivitas penting pada akses admin, komoditas, harga, panen, print laporan, dan export laporan sudah tercatat.
- Seeder data awal: sudah tersedia untuk demo admin dan petani.
- Landing page: sudah dipoles dengan hero visual, CTA, fitur utama, dan footer profesional.
- Komoditas: admin dapat menambah, mengubah, mengaktifkan/nonaktifkan data, mencari data, memfilter status, dan memakai pagination.
- Harga harian: admin dapat menyimpan harga komoditas serta memfilter data berdasarkan pasar, tanggal, status, komoditas, dan pencarian.
- Catatan panen: petani dapat mencatat panen, melihat riwayat pribadi, mencari riwayat, memfilter status/tanggal, dan memakai pagination.
- Verifikasi panen: admin dapat mengubah status panen serta memfilter daftar panen berdasarkan petani, komoditas, status, tanggal, dan pencarian.
- Dashboard admin dan petani: sudah menampilkan ringkasan data operasional.
- Laporan dasar: sudah mendukung filter periode, komoditas, petani, status, pencarian, KPI ringkas, pagination, print view, export PDF, export Excel `.xlsx`, dan export CSV.
- UI admin dan petani: sudah dipoles agar siap untuk demo capstone.
- Validasi data: harga, panen, dan filter laporan sudah menolak input kosong, tidak aktif, tanggal tidak valid, tanggal masa depan, dan nilai tidak wajar.
- Test otomatis: flow data, security, filter laporan, audit export, dan export file sudah memiliki coverage feature test.
- Export laporan: PDF server-side, Excel native `.xlsx`, dan CSV kompatibel Excel sudah aktif.
- Filter lanjutan, pagination, dan pencarian data: sudah aktif pada halaman data utama.

Estimasi progress MVP demo: sekitar 96%.

## Testing

Jalankan test Laravel:

```bash
php artisan test
```

Jalankan build frontend:

```bash
npm run build
```

Jika keduanya berhasil, berarti fitur dasar dan asset frontend berada dalam kondisi valid untuk demo.

## Catatan Pengembangan Selanjutnya

Fitur yang direkomendasikan untuk tahap berikutnya:

- Grafik dashboard yang lebih informatif.
- Upload gambar komoditas atau pasar.
- Validasi data lanjutan berdasarkan musim tanam atau batas produksi per komoditas.
- Test otomatis lanjutan untuk laporan, filter, dan edge case data besar.
- Deployment ke hosting atau VPS.

## Ringkasan Singkat

TaniSync adalah sistem informasi pertanian desa yang membantu admin dan petani mengelola data panen serta harga komoditas. Admin bertugas mengelola komoditas, harga, verifikasi panen, dan laporan. Petani bertugas mencatat panen dan melihat harga terbaru. Seluruh data inti MVP sudah diarahkan ke database MySQL bernama `tanisync`.
