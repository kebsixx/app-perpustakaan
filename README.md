# Perpustakaan Digital Kampus (`app-perpustakaan`)

Aplikasi manajemen perpustakaan berbasis **Laravel 12** yang dibangun secara bertahap melalui Modul Praktikum Mata Kuliah Pemrograman Framework (Pertemuan 1–10).

Aplikasi ini mencakup fitur autentikasi multi-role, CRUD buku, kategori, anggota, sistem transaksi peminjaman & pengembalian buku, RESTful API dengan API Resources, serta konsumsi API internal menggunakan Laravel HTTP Client untuk halaman Dashboard dan Laporan.

---

## Fitur Utama

- **Autentikasi & Otorisasi**: Login berbasis session, role-based access (`admin` & `petugas`), proteksi middleware, profile password update.
- **Manajemen Katalog**: CRUD Buku & Kategori dengan relasi Eloquent (`Category hasMany Books`), custom pagination text-based.
- **Sirkulasi Peminjaman**: Transaksi peminjaman multi-buku (`Loan hasMany LoanItems`), tracking tanggal kembali dan status keterlambatan, proses pengembalian buku sekali klik.
- **RESTful API**: 11 endpoint API siap pakai (`books`, `members`, `loans`, `stats`) dengan response seragam (`JsonResource`) dan skenario error handling (`404`, `422`).
- **Konsumsi API Internal (BFF Pattern)**: `DashboardController` dan `LoanController@report` mengonsumsi API sendiri via Laravel HTTP Client (`Http::get()`) dengan fallback `ConnectionException`.
- **Seeder & Factory Realistis**: Generator data lokal Indonesia (`APP_FAKER_LOCALE=id_ID`), kurasi nama buku/penerbit, dan domain email kampus `@pens.ac.id`.

---

## Kebutuhan Sistem

- **PHP**: ^8.2 (ekstensi: `pdo_pgsql`, `openssl`, `mbstring`, `curl`)
- **Composer**: ^2.x
- **Database**: PostgreSQL (direkomendasikan) atau MySQL / SQLite

---

## Instalasi & Setup Lokal

### 1. Clone Repository

```bash
git clone https://github.com/username/app-perpustakaan.git
cd app-perpustakaan
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Salin Environment File

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi `.env`

Pastikan pengaturan database dan port internal API terkonfigurasi dengan benar:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_perpustakaan
DB_USERNAME=postgres
DB_PASSWORD=your_password

APP_FAKER_LOCALE=id_ID

# Instance kedua untuk melayani HTTP Client internal (mencegah deadlock di built-in PHP server)
INTERNAL_API_URL=http://127.0.0.1:8011
```

### 5. Migrasi dan Seeding Data

Jalankan perintah berikut untuk membuat skema tabel dan mengisi data dummy realistis (20 buku, 15 anggota, 10 transaksi):

```bash
php artisan migrate:fresh --seed
```

---

## Menjalankan Aplikasi

> **PENTING — Dua Instance Server:**  
> PHP built-in web server (`php artisan serve`) hanya dapat memproses 1 request dalam satu waktu (single-threaded). Saat halaman Dashboard atau Laporan memanggil API aplikasi sendiri lewat `Http::get()`, request akan mengalami **deadlock** jika port server web dan server API sama.  
> Oleh karena itu, jalankan **dua instance server** di terminal terpisah:

### Terminal 1 — Server Utama (Web & Browser)
```bash
php artisan serve --port=8000
```
Akses aplikasi melalui: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Terminal 2 — Server API Internal
```bash
php artisan serve --port=8011
```
Melayani panggilan internal dari `DashboardController` dan `LoanController@report`.

---

## Kredensial Pengujian (Demo)

| Role | Email | Password |
|---|---|---|
| Admin | `admin@pens.ac.id` | `password` |
| Petugas 1 | `petugas1@pens.ac.id` | `password` |
| Petugas 2 | `petugas2@pens.ac.id` | `password` |

---

## Dokumentasi & Skenario Pengujian

- Dokumentasi seluruh endpoint REST API: lihat [API.md](./API.md).
- Skenario live demo evaluasi UAS: lihat [DEMO.md](./DEMO.md).
