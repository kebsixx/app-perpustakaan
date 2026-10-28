# Panduan Skenario Demo UAS — Perpustakaan Digital Kampus

File ini berisi urutan langkah demo fungsionalitas aplikasi `app-perpustakaan` untuk evaluasi akhir (UAS). Demo dilakukan secara *live* melalui Browser dan API Client (Postman/Bruno).

---

## Persiapan Sebelum Demo

1. **Jalankan Database PostgreSQL** (pastikan service aktif).
2. **Reset & Seed Database**:
   ```bash
   php artisan migrate:fresh --seed
   ```
3. **Nyalakan Dua Instance Server**:
   - Terminal 1 (Aplikasi Web & Browser):
     ```bash
     php artisan serve --port=8000
     ```
   - Terminal 2 (Internal API Consumer):
     ```bash
     php artisan serve --port=8011
     ```

---

## Akun Pengujian

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| Admin | `admin@pens.ac.id` | `password` | Seluruh fitur (Dashboard, Buku, Kategori, Anggota, Peminjaman, Laporan, Profil) |
| Petugas | `petugas1@pens.ac.id` | `password` | Tanpa akses kelola Kategori (403 Forbidden) |

---

## Urutan Skenario Demo

### 1. Autentikasi & Hak Akses
1. Buka browser: `http://127.0.0.1:8000/login`.
2. Login dengan akun **Admin** (`admin@pens.ac.id` / `password`).
3. Verifikasi:
   - Pengguna diarahkan ke halaman **Dashboard** (`/`).
   - Navbar menampilkan nama pengguna **Bambang Sutrisno (Admin)**.
   - Menu **Kategori** terlihat di navbar.

### 2. Dashboard (Konsumsi API Internal)
1. Periksa 3 kartu ringkasan data di Dashboard:
   - **Total Buku**: 20
   - **Total Anggota**: 15
   - **Peminjaman Aktif**: sesuai data seeder (±2–4)
2. *Penjelasan Teknis*: Angka statistik diambil secara asynchronous dari endpoint `GET /api/stats` lewat Laravel HTTP Client (`Http::get()`) di `DashboardController`, bukan query langsung model di controller web.

### 3. Manajemen Kategori (Khusus Admin)
1. Klik menu **Kategori** (`/categories`).
2. Klik **+ Tambah Kategori**:
   - Nama: `Kecerdasan Buatan`
   - Deskripsi: `Buku seputar machine learning, neural networks, dan AI.`
3. Submit form, pastikan flash message sukses muncul dan kategori baru tampil di tabel.

### 4. Manajemen Buku (CRUD & Pagination)
1. Klik menu **Buku** (`/books`).
2. Tunjukkan **Pagination** di bagian bawah tabel:
   - Format link teks bersih (`« Sebelumnya`, `1`, `2`, `Berikutnya »`).
   - Klik halaman `2` untuk verifikasi transisi data halaman kedua.
3. Klik **+ Tambah Buku**:
   - Judul: `Hands-On Machine Learning`
   - Penulis: `Aurélien Géron`
   - Penerbit: `O'Reilly Media`
   - Tahun: `2023`
   - ISBN: `9781098125974`
   - Stok: `5`
   - Kategori: Pilih `Kecerdasan Buatan`
4. Submit form, cek detail buku pada tombol **Detail**.
5. Coba fitur **Edit** stok atau judul buku, lalu simpan.

### 5. Manajemen Anggota
1. Klik menu **Anggota** (`/members`).
2. Klik **+ Tambah Anggota**:
   - Nama: `Fajar Pratama`
   - NIM: `3122600020`
   - Email: `fajar@student.pens.ac.id`
   - Nomor Telepon: `081234567899`
   - Alamat: `Jl. Raya ITS, Surabaya`
   - Status: `aktif`
3. Simpan dan pastikan anggota baru muncul di daftar anggota.

### 6. Transaksi Peminjaman Baru
1. Klik menu **Peminjaman** (`/loans`).
2. Klik **+ Tambah Peminjaman**:
   - Anggota: Pilih `Fajar Pratama`
   - Petugas: Otomatis tercatat petugas yang sedang login.
   - Tanggal Pinjam: Hari ini
   - Tanggal Kembali: 7 hari ke depan
   - Pilih 1–2 buku yang dipinjam.
3. Simpan transaksi peminjaman.
4. Verifikasi transaksi baru muncul di tabel dengan badge status warna kuning (`dipinjam`).

### 7. Proses Pengembalian Buku
1. Pada tabel peminjaman (`/loans`), temukan transaksi yang masih berstatus `dipinjam`.
2. Klik tombol **Kembalikan**.
3. Status berubah menjadi `dikembalikan` (badge hijau) dan tanggal dikembalikan otomatis terisi tanggal hari ini.

### 8. Laporan Peminjaman (Konsumsi API Internal)
1. Klik menu **Laporan** (`/loans/report`).
2. Tunjukkan tabel data laporan peminjaman yang diambil langsung dari endpoint `GET /api/loans`.
3. Tunjukkan pagination laporan berjalan normal berbasis payload metadata API.

### 9. Demo REST API (Postman / Bruno / Curl)
Uji endpoint publik di `http://127.0.0.1:8000/api` dengan header `Accept: application/json`:
1. `GET /api/books` — Mengembalikan JSON list buku lengkap dengan pagination meta & links.
2. `GET /api/books?judul=laskar` — Filter pencarian case-insensitive (PostgreSQL `ILIKE`).
3. `GET /api/stats` — Mengembalikan statistik aggregate `{ total_buku, total_anggota, peminjaman_aktif }`.
4. `POST /api/loans` — Payload transaksi peminjaman baru dalam format JSON.
5. `GET /api/books/999` — Uji penanganan error `404 Not Found`.
6. `POST /api/books` dengan body `{}` — Uji validasi `422 Unprocessable Entity`.
