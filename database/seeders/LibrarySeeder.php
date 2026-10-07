<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibrarySeeder extends Seeder
{
    /**
     * Sample categories, books, members, and loans.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            ['nama_kategori' => 'Pemrograman', 'deskripsi' => 'Buku bahasa pemrograman dan rekayasa perangkat lunak'],
            ['nama_kategori' => 'Jaringan', 'deskripsi' => 'Buku jaringan komputer dan keamanan'],
            ['nama_kategori' => 'Basis Data', 'deskripsi' => 'Buku desain dan administrasi basis data'],
            ['nama_kategori' => 'Elektronika', 'deskripsi' => 'Buku elektronika dan sistem tertanam'],
            ['nama_kategori' => 'Fiksi', 'deskripsi' => 'Novel dan karya sastra'],
        ];
        foreach ($categories as $c) {
            DB::table('categories')->insert($c + ['created_at' => $now, 'updated_at' => $now]);
        }
        $cat = DB::table('categories')->pluck('id', 'nama_kategori');

        // ponytail: max 10 per tabel agar pagination belum muncul (view pagination custom baru di Pertemuan 10)
        $books = [
            ['Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, '9780132350884', 5, 'Pemrograman'],
            ['The Pragmatic Programmer', 'Andrew Hunt', 'Addison-Wesley', 2019, '9780135957059', 3, 'Pemrograman'],
            ['Laravel: Up & Running', 'Matt Stauffer', "O'Reilly Media", 2023, '9781098153267', 4, 'Pemrograman'],
            ['Belajar Python untuk Pemula', 'Abdul Kadir', 'Andi Publisher', 2020, '9786230113415', 6, 'Pemrograman'],
            ['Computer Networking: A Top-Down Approach', 'James Kurose', 'Pearson', 2021, '9780136681557', 3, 'Jaringan'],
            ['Database System Concepts', 'Abraham Silberschatz', 'McGraw-Hill', 2019, '9780078022159', 4, 'Basis Data'],
            ['Elektronika Dasar', 'Malvino', 'Erlangga', 2016, '9789790991234', 5, 'Elektronika'],
            ['Programming Arduino', 'Simon Monk', 'McGraw-Hill', 2022, '9781264676989', 3, 'Elektronika'],
            ['Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '9789793062792', 4, 'Fiksi'],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 2005, '9789799731234', 3, 'Fiksi'],
        ];
        foreach ($books as [$judul, $penulis, $penerbit, $tahun, $isbn, $stok, $kategori]) {
            DB::table('books')->insert([
                'judul' => $judul,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun,
                'isbn' => $isbn,
                'stok' => $stok,
                'category_id' => $cat[$kategori],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $members = [
            ['Budi Santoso', '3122600001', 'budi@student.pens.ac.id', '081234567801', 'Jl. Keputih No. 1, Surabaya', 'aktif'],
            ['Siti Aminah', '3122600002', 'siti@student.pens.ac.id', '081234567802', 'Jl. Sukolilo No. 12, Surabaya', 'aktif'],
            ['Andi Pratama', '3122600003', 'andi@student.pens.ac.id', '081234567803', 'Jl. Mulyosari No. 5, Surabaya', 'aktif'],
            ['Dewi Lestari', '3122600004', 'dewi@student.pens.ac.id', '081234567804', 'Jl. Kertajaya No. 20, Surabaya', 'aktif'],
            ['Rizky Ramadhan', '3122600005', 'rizky@student.pens.ac.id', '081234567805', 'Jl. Gebang Lor No. 8, Surabaya', 'aktif'],
            ['Nur Halimah', '3122600006', 'nur@student.pens.ac.id', '081234567806', 'Jl. Klampis Jaya No. 3, Surabaya', 'nonaktif'],
        ];
        foreach ($members as [$nama, $nim, $email, $telp, $alamat, $status]) {
            DB::table('members')->insert([
                'nama' => $nama,
                'nim' => $nim,
                'email' => $email,
                'nomor_telepon' => $telp,
                'alamat' => $alamat,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $memberIds = DB::table('members')->orderBy('id')->pluck('id');
        $bookIds = DB::table('books')->orderBy('id')->pluck('id');
        $userId = User::where('email', 'admin@pens.ac.id')->value('id') ?? User::value('id');

        // [member index, days ago borrowed, returned days ago (null = belum), status, book indexes]
        $loans = [
            [0, 3, null, 'dipinjam', [0, 2]],
            [1, 5, null, 'dipinjam', [5]],
            [2, 20, null, 'terlambat', [4, 8]],
            [3, 14, 8, 'dikembalikan', [1]],
            [4, 30, 25, 'dikembalikan', [6, 9]],
            [0, 40, 33, 'dikembalikan', [3]],
        ];
        foreach ($loans as [$m, $ago, $returnedAgo, $status, $items]) {
            $pinjam = $now->copy()->subDays($ago);
            $loanId = DB::table('loans')->insertGetId([
                'member_id' => $memberIds[$m],
                'user_id' => $userId,
                'tanggal_pinjam' => $pinjam->toDateString(),
                'tanggal_kembali' => $pinjam->copy()->addDays(7)->toDateString(),
                'tanggal_dikembalikan' => $returnedAgo === null ? null : $now->copy()->subDays($returnedAgo)->toDateString(),
                'status' => $status,
                'created_at' => $pinjam,
                'updated_at' => $now,
            ]);
            foreach ($items as $b) {
                DB::table('loan_items')->insert([
                    'loan_id' => $loanId,
                    'book_id' => $bookIds[$b],
                    'created_at' => $pinjam,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
