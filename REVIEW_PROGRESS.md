# Review Progress TaniSync

Tanggal review: 7 Mei 2026

## Ringkasan Status

Fitur inti TaniSync sudah berjalan untuk alur utama admin dan petani. Aplikasi sudah memiliki landing page, autentikasi dengan role, dashboard admin, dashboard petani, input panen, update harga, verifikasi panen, laporan ekspor, persetujuan akses admin, dan activity log.

Hasil verifikasi terakhir:

- `php artisan test`: 46 test passed, 177 assertions.
- `npm run build`: berhasil.

## Fitur Yang Sudah Selesai

### Landing Page

- Halaman utama tersedia di route `/`.
- Hero landing sudah memakai visual TaniSync.
- CTA menuju login dan register sudah tersedia.
- Section fitur utama sudah tampil.

### Autentikasi

- Login dengan pilihan role `admin` dan `petani`.
- Register dengan pilihan role.
- Petani aktif otomatis setelah register.
- Admin baru masuk status pending dan harus disetujui admin aktif.
- Login sudah memvalidasi email, password, dan role.
- Halaman login sudah diperbarui dengan asset `public/images/tanisync/login-farmer.png`.

### Role dan Akses

- Route admin dibatasi middleware `role:admin`.
- Route petani dibatasi middleware `role:petani`.
- Admin pending atau rejected diarahkan ke halaman status akun.
- Petani tidak bisa mengakses fitur admin.

### Admin

- Dashboard admin menampilkan metrik operasional desa.
- Manajemen komoditas tersedia.
- Update harga komoditas tersedia.
- Monitoring panen tersedia.
- Verifikasi status panen tersedia.
- Laporan panen tersedia dengan filter.
- Ekspor laporan tersedia dalam format print view, PDF, XLSX, dan CSV.
- Persetujuan akses admin tersedia.
- Activity log tersedia.

### Petani

- Dashboard petani tersedia.
- Petani bisa mencatat panen.
- Riwayat panen petani tersedia dan hanya menampilkan data milik sendiri.
- Halaman harga referensi tersedia.

### Data dan Keamanan

- Seeder demo tersedia untuk admin, petani, kategori, komoditas, pasar, harga, dan panen.
- Activity log mencatat aksi penting.
- Validasi tanggal panen menolak tanggal masa depan.
- Validasi harga menolak data tidak valid.
- Test coverage sudah mencakup autentikasi, approval admin, laporan, filter, export, dan isolasi data petani.

## Progress Yang Belum Selesai

### 1. Harga Petani Perlu Difilter Hanya Verified

Halaman harga petani saat ini mengambil semua data harga harian tanpa memfilter status. Karena admin bisa menyimpan harga dengan status `draft`, `submitted`, atau `verified`, petani berisiko melihat harga yang belum final.

File terkait:

- `app/Http/Controllers/FarmerController.php`
- `resources/views/petani/prices.blade.php`

Rekomendasi:

- Filter `DailyPrice` di sisi petani agar hanya mengambil status `verified`.
- Tambahkan test agar harga `draft` dan `submitted` tidak tampil di halaman petani.

### 2. Halaman Profil Belum Menyatu Dengan Layout TaniSync

Route `/profile` sudah tersedia, tetapi view profil masih memakai pola Breeze bawaan dengan `<x-app-layout>`. Layout aplikasi TaniSync saat ini memakai `@extends('layouts.app')` dan `@section('content')`.

File terkait:

- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/update-profile-information-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`
- `resources/views/layouts/app.blade.php`

Rekomendasi:

- Ubah halaman profil agar memakai `layouts.app`.
- Sesuaikan tampilan profil dengan design system TaniSync.
- Tambahkan link Profil di sidebar atau topbar.
- Tambahkan assertion test yang memastikan konten profil benar-benar tampil, bukan hanya status 200.

### 3. Forgot Password, Reset Password, Confirm Password, dan Verify Email Masih Template Breeze

Beberapa halaman auth masih memakai template Breeze bawaan, berbahasa Inggris, dan belum sesuai gaya visual TaniSync.

File terkait:

- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/confirm-password.blade.php`
- `resources/views/auth/verify-email.blade.php`
- `resources/views/layouts/guest.blade.php`

Rekomendasi:

- Ubah halaman tersebut agar memakai layout auth TaniSync.
- Terjemahkan copy ke Bahasa Indonesia.
- Tambahkan navigasi kembali ke login atau landing.
- Pastikan tampilan mobile tetap rapi.

### 4. Link Lupa Kata Sandi Belum Ada Di Login

Route lupa kata sandi sudah tersedia, tetapi halaman login belum menampilkan link ke fitur tersebut.

File terkait:

- `resources/views/auth/login.blade.php`
- `routes/auth.php`

Rekomendasi:

- Tambahkan link `Lupa kata sandi?` di area form login.
- Arahkan ke route `password.request`.

### 5. Manajemen Pasar Belum Ada

Data pasar dipakai saat admin update harga, tetapi belum ada halaman CRUD untuk mengelola pasar.

File terkait:

- `app/Models/Market.php`
- `database/migrations/2026_04_23_025114_create_markets_table.php`
- `resources/views/admin/prices.blade.php`

Rekomendasi:

- Tambahkan halaman admin untuk tambah, edit, aktif/nonaktif pasar.
- Tambahkan route dan controller action terkait pasar.
- Tambahkan test CRUD pasar.

### 6. Manajemen Kategori Belum Ada

Komoditas membutuhkan kategori aktif, tetapi belum ada halaman CRUD kategori.

File terkait:

- `app/Models/Category.php`
- `database/migrations/2026_04_23_025113_create_categories_table.php`
- `resources/views/admin/commodities.blade.php`

Rekomendasi:

- Tambahkan halaman admin untuk kategori komoditas.
- Tambahkan kemampuan aktif/nonaktif kategori.
- Tentukan aturan jika kategori masih dipakai komoditas aktif.

### 7. Harga Acuan Komoditas Belum Bisa Diedit Dari Tabel

Saat menambah komoditas, harga acuan bisa diisi. Namun pada tabel edit komoditas, harga acuan hanya dikirim sebagai hidden input sehingga admin tidak bisa mengubahnya langsung dari UI.

File terkait:

- `resources/views/admin/commodities.blade.php`
- `app/Http/Controllers/AdminController.php`

Rekomendasi:

- Tampilkan input harga acuan di baris edit komoditas.
- Pastikan layout tabel tetap rapi di mobile.
- Tambahkan test update harga acuan.

### 8. Empty State Harga Petani Belum Lengkap

Halaman harga petani memakai `@foreach` tanpa `@forelse`. Jika tidak ada harga atau komoditas aktif, tabel dapat terlihat kosong tanpa pesan.

File terkait:

- `resources/views/petani/prices.blade.php`

Rekomendasi:

- Ganti `@foreach` menjadi `@forelse`.
- Tambahkan pesan seperti `Belum ada harga referensi yang tersedia.`

### 9. Landing Masih Menggunakan Data Statis

Landing page memakai data statis dari service mock dan beberapa angka hardcoded di view.

File terkait:

- `app/Services/TaniSyncMockData.php`
- `app/Http/Controllers/LandingController.php`
- `resources/views/landing/index.blade.php`

Rekomendasi:

- Jika landing harus menampilkan data real, ambil ringkasan dari database.
- Jika landing hanya marketing, pindahkan data statis ke konfigurasi yang lebih jelas atau langsung ke view.
- Hapus method mock yang sudah tidak dipakai.

### 10. File Bawaan Laravel Yang Tidak Terpakai Masih Ada

Beberapa file Breeze/Laravel bawaan masih tersisa dan dapat membingungkan saat maintenance.

File terkait:

- `resources/views/welcome.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/layouts/guest.blade.php`

Rekomendasi:

- Hapus jika benar-benar tidak dipakai.
- Atau migrasikan agar konsisten dengan desain TaniSync.

### 11. Visual QA Browser Belum Dilakukan Menyeluruh

Build dan test backend sudah lulus, tetapi visual QA untuk semua halaman desktop dan mobile belum dilakukan secara lengkap.

Rekomendasi:

- Cek halaman berikut di desktop dan mobile:
  - Landing
  - Login
  - Register
  - Account pending
  - Dashboard admin
  - Komoditas admin
  - Harga admin
  - Panen admin
  - Laporan admin
  - Access requests
  - Activity logs
  - Dashboard petani
  - Catat panen
  - Riwayat panen
  - Harga petani
  - Profil
  - Forgot password
  - Reset password

## Prioritas Pengerjaan Berikutnya

1. Filter harga petani agar hanya menampilkan status `verified`.
2. Rapikan halaman profil dan masukkan ke navigasi.
3. Redesign halaman forgot/reset/verify/confirm password.
4. Tambahkan link lupa kata sandi di login.
5. Lengkapi UI edit harga acuan komoditas.
6. Tambahkan empty state harga petani.
7. Tambahkan CRUD pasar dan kategori jika dibutuhkan scope capstone.
8. Bersihkan file bawaan yang tidak dipakai.
9. Lakukan visual QA desktop dan mobile.

## Catatan Perubahan Terakhir

Perubahan terakhir yang belum dikomit:

- `resources/views/auth/login.blade.php`
- `public/images/tanisync/login-farmer.png`

Perubahan tersebut berisi pembaruan asset dan layout halaman login.
