# Portal Akademik Mandiri — Dashboard & RBAC Implementation (WEEK 10)

> Proyek Portal Akademik Mandiri berbasis **Laravel 11/12**, diintegrasikan dengan kerangka visual Backoffice **AdminLTE v4**, sistem autentikasi **Laravel Breeze**, dan sistem otorisasi dinamis **Spatie Laravel Permission (Role-Based Access Control / RBAC)**.

---

## 📋 Daftar Isi
1. [Fitur & Pemenuhan Spesifikasi Teknis](#fitur--pemenuhan-spesifikasi-teknis)
2. [Tantangan Tambahan (Nilai Plus A+)](#tantangan-tambahan-nilai-plus-a)
3. [Detail Akun Uji Coba (Auto-Login Credentials)](#detail-akun-uji-coba-auto-login-credentials)
4. [Matriks Otorisasi Peran (RBAC Matrix)](#matriks-otorisasi-peran-rbac-matrix)
5. [Panduan Instalasi & Menjalankan Proyek](#panduan-instalasi--menjalankan-proyek)
6. [Tangkapan Layar Antarmuka (Screenshots)](#tangkapan-layar-antarmuka-screenshots)
7. [Struktur Kode & Keamanan](#struktur-kode--keamanan)

---

## 🎯 Fitur & Pemenuhan Spesifikasi Teknis

| No | Spesifikasi Teknis Tugas | Status | Keterangan Implementasi |
|:--:|:-------------------------|:------:|:------------------------|
| **1** | **Integrasi UI Backoffice** | ✅ **Selesai** | Menggunakan paket visual resmi `jeroennoten/laravel-adminlte` di mana seluruh halaman admin mewarisi `@extends('adminlte::page')`. |
| **2** | **Sistem Autentikasi** | ✅ **Selesai** | Dilindungi di bawah otentikasi resmi **Laravel Breeze** (Blade Stack), lengkap dengan proteksi session, verifikasi login, dan logout. |
| **3** | **Otorisasi DB Spatie RBAC** | ✅ **Selesai** | Menggunakan paket `spatie/laravel-permission` dengan tabel database fisik (`roles`, `permissions`, `model_has_roles`, `role_has_permissions`). Dua peran utama: **Admin** (Akses Penuh) & **Staf** (Akses Terbatas). |
| **4** | **Widget Dashboard Agregat** | ✅ **Selesai** | Beranda backoffice menampilkan **4 widget box statistik dinamis** hasil kueri model `count()`: Total Mahasiswa, Total Dosen, Total Mata Kuliah, dan Total Pengguna Sistem. |
| **5** | **Pengaman Controller** | ✅ **Selesai** | Diterapkan pengamanan berlapis ganda: interface controller `HasMiddleware` / `middleware('permission:...')` dan `abort_if(!auth()->user()->can(...), 403)` demi mencegah manipulasi URL langsung. |
| **6** | **Hak Eksklusif Admin** | ✅ **Selesai** | Aksi **Hapus Data** hanya dirender di Blade jika memiliki hak akses (`@can('delete-academic-data')` / `@role('Admin')`), dan jika diakses paksa oleh Staf via URL/HTTP method maka server mengembalikan respon `403 Forbidden`. |

---

## 🌟 Tantangan Tambahan (Nilai Plus A+)

1. **Filter Pencarian dan Kueri `when()`**:
   - Seluruh pencarian data pada tabel Mahasiswa, Dosen, dan Mata Kuliah menggunakan kueri kondisional Eloquent `->when($request->filled('...'), ...)` sehingga kodingan Controller bersih tanpa if-else bersarang.
2. **Pengunci Link Pagination (`withQueryString()`)**:
   - Chaining method `->withQueryString()` pada pagination di controller, memastikan kata kunci pencarian, filter jurusan, dan semester tidak hilang saat pengguna berpindah halaman tabel (misal dari halaman 1 ke halaman 2).
3. **Auto-Seeder Faker Realistis (Indonesian Academic Data)**:
   - Seeder otomatis (`AcademicSeeder` & `RolePermissionSeeder`) langsung menyiram puluhan data dummy realistis berbahasa Indonesia (NIM ITS format 5025xxxxxx, NIDN dosen, nama lengkap dengan gelar, program studi ITS, dan silabus mata kuliah) menggunakan generator Faker `id_ID`.
4. **Auto-Login Test Accounts (1-Click Instant Login)**:
   - Halaman login awal menyediakan tombol kustom **"🚀 Login Cepat sebagai Admin"** dan **"👤 Login Cepat sebagai Staf"** untuk mempermudah evaluasi demo dosen/asdos di kelas hanya dengan satu kali klik.

---

## 🔑 Detail Akun Uji Coba (Auto-Login Credentials)

Untuk sesi demonstrasi atau evaluasi live coding di kelas, Anda dapat menggunakan tombol cepat di halaman login atau mengetik manual kredensial berikut:

| Peran (Role) | Email Akun | Kata Sandi (Password) | Hak Akses Utama |
|:---|:---|:---:|:---|
| **Admin** *(Akses Penuh)* | `admin@akademik.its.ac.id` | `password` | Akses Dashboard, Tambah, Ubah, **Hapus Data**, serta **Kelola Hak Akses Pengguna**. |
| **Staf** *(Akses Terbatas)* | `staf@akademik.its.ac.id` | `password` | Akses Dashboard, Tambah, dan Ubah Data. **Tombol Hapus & Menu Kelola User Terkunci (403)**. |
| **Staf Variasi 2** | `budi.staf@akademik.its.ac.id` | `password` | Akun staf kedua untuk keperluan pengujian multi-user. |

---

## 🛡️ Matriks Otorisasi Peran (RBAC Matrix)

| Izin (Permission) | Deskripsi Fungsionalitas | Peran Admin | Peran Staf |
|:---|:---|:---:|:---:|
| `view-dashboard` | Melihat statistik agregat widget box beranda | ✅ Ya | ✅ Ya |
| `view-academic-data` | Melihat tabel data Mahasiswa, Dosen, MK | ✅ Ya | ✅ Ya |
| `create-academic-data` | Menambah data baru | ✅ Ya | ✅ Ya |
| `edit-academic-data` | Memperbarui informasi data akademik | ✅ Ya | ✅ Ya |
| `delete-academic-data` | **Hapus data permanen (Hak Eksklusif)** | ✅ **Ya** | ❌ **DITOLAK (403)** |
| `manage-users` | **Kelola role & hak akses pengguna** | ✅ **Ya** | ❌ **DITOLAK (403)** |

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek

### 1. Klon Repositori & Masuk Direktori
```bash
git clone <url-repository-anda>
cd WEEK10
```

### 2. Instal Dependensi Composer & Node
```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` ke `.env` dan buat encryption key aplikasi:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi & Seeder Database Otomatis
Jalankan migrasi tabel Spatie Permission dan siram database dengan data realistis:
```bash
php artisan migrate:fresh --seed
```
> **Catatan**: Secara default proyek dikonfigurasi menggunakan SQLite (`database/database.sqlite`), sehingga **tidak memerlukan konfigurasi manual database server**. Cukup jalankan perintah di atas dan aplikasi siap pakai.

### 5. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui peramban web di: `http://127.0.0.1:8000`

---

## 🧪 Menjalankan Automated Tests (PHPUnit)

Proyek dilengkapi dengan 38 automated tests (102 assertions) yang menguji fungsionalitas CRUD, pencarian `when()`, pagination, dan validasi RBAC:
```bash
php artisan test
```

Verifikasi kerapian kode standar PSR-12 menggunakan Laravel Pint:
```bash
php vendor/bin/pint --test
```

---

## 📸 Tangkapan Layar Antarmuka (Screenshots)

Berikut representasi visual antarmuka sistem:

### 1. Halaman Login dengan Tombol 1-Click Auto-Login
Halaman autentikasi terverifikasi Laravel Breeze yang telah dilengkapi tombol instan `Login sebagai ADMIN` dan `Login sebagai STAF`.
```
+-------------------------------------------------------------+
|               Sistem Informasi Akademik                     |
|    Portal Akademik Mandiri - RBAC & Dashboard Integration   |
|                                                             |
|  [ 🚀 Login sebagai ADMIN (Akses Penuh) ]                   |
|  [ 👤 Login sebagai STAF (Akses Terbatas) ]                |
|                                                             |
|  Atau Masuk Manual:                                         |
|  Email: [ admin@akademik.its.ac.id                        ] |
|  Password: [ ******************                           ] |
|  [ Masuk Portal ]                                           |
+-------------------------------------------------------------+
```

### 2. Beranda Dashboard Backoffice (AdminLTE Layout)
Menampilkan 4 Box Statistik Agregat, Badge Status Peran, Ringkasan Mahasiswa Terbaru, dan Grafik Distribusi Prodi.
```
+-----------------------------------------------------------------------------------+
| SIAKAD ITS  |  Dashboard  |  Mahasiswa  |  Dosen  |  Mata Kuliah  |  Kelola User |
+-----------------------------------------------------------------------------------+
| Halo, Administrator Akademik! [ Badge: ADMINISTRATOR (Akses Penuh) ]              |
|                                                                                   |
| [ TOTAL MAHASISWA ]   [ TOTAL DOSEN ]   [ MATA KULIAH ]   [ PENGGUNA SISTEM ]     |
|      40 Mahasiswa          20 Dosen         20 Silabus          3 Pengguna        |
|                                                                                   |
| [ Mahasiswa Aktif: 28 ]    [ Mahasiswa Cuti: 7 ]    [ Mahasiswa Lulus: 5 ]        |
|                                                                                   |
| +--- Registrasi Mahasiswa Terbaru -----+ +--- Distribusi Mahasiswa per Prodi ---+ |
| | 5025211001  Muhammad Azizurrohman   | | Teknik Informatika (32%)               | |
| | 5025211002  Adinda Putri Maharani   | | Sistem Informasi   (26%)               | |
| | 5026221015  Bintang Alamsyah P.     | | Sains Data         (22%)               | |
| +--------------------------------------+ +--------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

### 3. Proteksi Visual & Controller Aksi Hapus (Admin vs Staf)
- **Saat login sebagai Admin**: Tombol berwarna merah `Hapus Data` aktif dan dapat menghapus baris data.
- **Saat login sebagai Staf**: Tombol `Hapus Data` disembunyikan/dikunci dengan ikon gembok `<i class="bi bi-lock-fill"></i> Terkunci`. Jika Staf mengirimkan request DELETE langsung melalui manipulasi devtools/Postman, controller langsung mengembalikan `HTTP 403 Forbidden`.

---

## 🔒 Konfigurasi Git Aman
Proyek ini mengamankan berkas rahasia dan dependensi besar melalui berkas `.gitignore`:
- Berkas `.env` tidak masuk ke repositori.
- Folder `vendor/` dan `node_modules/` diabaikan secara ketat.
- Database lokal `database/*.sqlite` diabaikan dan di-generate melalui seeder otomatis.

---
Dibuat untuk memenuhi Tugas Mandiri Minggu 10 — Pemrograman Berbasis Kerangka Kerja (Framework Programming).
