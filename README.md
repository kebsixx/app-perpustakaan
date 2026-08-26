# App Perpustakaan

Aplikasi manajemen perpustakaan sederhana yang dibangun menggunakan [Laravel](https://laravel.com) sebagai bagian dari tugas praktikum mata kuliah Framework. Aplikasi ini bertujuan untuk mengelola data buku, anggota, serta transaksi peminjaman dan pengembalian buku di perpustakaan.

## Cara Menjalankan Project Secara Lokal

1. Clone repository ini:

   ```bash
   git clone https://github.com/username/app-perpustakaan.git
   cd app-perpustakaan
   ```

2. Install dependencies PHP:

   ```bash
   composer install
   ```

3. Salin file environment dan generate application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. (Opsional) Sesuaikan konfigurasi database pada file `.env`. Secara default project ini menggunakan SQLite, jadi cukup siapkan file database-nya:

   ```bash
   touch database/database.sqlite
   ```

5. Jalankan migrasi database:

   ```bash
   php artisan migrate
   ```

6. Jalankan server pengembangan:

   ```bash
   php artisan serve
   ```

7. Buka aplikasi di browser melalui [http://localhost:8000](http://localhost:8000).

## Pemahaman MVC

**Model** adalah komponen yang mengelola data dan logika bisnis aplikasi, misalnya berinteraksi dengan tabel database seperti data buku atau anggota. **View** bertugas menampilkan antarmuka kepada pengguna, yaitu halaman HTML yang dilihat di browser. **Controller** menjadi penghubung di antara keduanya: menerima request dari pengguna, memanggil Model untuk mengolah data, lalu mengirimkan hasilnya ke View untuk ditampilkan.
