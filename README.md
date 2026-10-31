# Temukerja — Sistem Informasi Pencocokan Calon Pekerja dengan Klien

Capstone Project Program Studi S1 Sistem Informasi, Telkom University Kampus Jakarta, untuk **PT Shaka Abadi Nusantara**
(penyalur ART, Baby Sitter, dan Perawat Lansia).

- Framework: Laravel 12 (PHP 8.3), basis data MySQL, Tailwind CSS 4 (Vite)
- Autentikasi: session Laravel + bcrypt, hak akses per peran (admin, klien, pekerja)
- Rekomendasi pekerja: algoritma **Cosine Similarity** (`app/Services/RekomendasiPekerja.php`)

## Menjalankan dari awal

1. Nyalakan **Apache** dan **MySQL** di XAMPP, lalu buat database `capstone_project` di phpMyAdmin (jika belum ada).
2. Di folder proyek:

   ```bash
   composer install
   npm install
   copy .env.example .env      # hanya jika .env belum ada, lalu jalankan: php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

3. Jalankan dua terminal:

   ```bash
   php artisan serve
   npm run dev
   ```

4. Buka <http://127.0.0.1:8000>.

## Akun login (dibuat oleh seeder)

| Peran | Email | Kata sandi | Keterangan |
|-------|-------|------------|------------|
| Admin | admin@shakaabadi.com | Admin123! | Admin utama |
| Klien | klien@test.com | Klien123! | Memiliki riwayat pesanan |
| Klien | klien2@test.com | Klien123! | Klien kedua |
| Pekerja | art@test.com | Pekerja123! | ART, terverifikasi |
| Pekerja | babysitter@test.com | Pekerja123! | Baby Sitter, terverifikasi |
| Pekerja | lansia@test.com | Pekerja123! | Perawat Lansia, menunggu verifikasi |
| Pekerja | siti@test.com, wati@test.com | Pekerja123! | ART, terverifikasi |
| Pekerja | dewi@test.com | Pekerja123! | Baby Sitter, terverifikasi (sedang bertugas) |
| Pekerja | nurul@test.com, laila@test.com | Pekerja123! | Perawat Lansia, terverifikasi |
| Pekerja | rina@test.com | Pekerja123! | Baby Sitter, ditolak (ada catatan admin) |
| Pekerja | sri@test.com | Pekerja123! | ART, akun nonaktif (tidak bisa login) |

## Menambah akun admin lewat phpMyAdmin

Admin tidak memiliki halaman registrasi. Untuk menambah admin secara manual:

1. Buat hash bcrypt kata sandi di terminal:

   ```bash
   php -r "echo password_hash('KataSandiBaru123', PASSWORD_BCRYPT);"
   ```

2. Salin hasilnya (diawali `$2y$12$...`), lalu jalankan di tab **SQL** phpMyAdmin pada database `capstone_project`:

   ```sql
   INSERT INTO pengguna (nama, email, no_telepon, kata_sandi, peran, status_akun, tanggal_dibuat)
   VALUES ('Nama Admin Baru', 'adminbaru@shakaabadi.com', '081234567890',
           '$2y$12$TEMPEL_HASH_DI_SINI', 'admin', 'aktif', NOW());
   ```

3. Login dengan email tersebut dan kata sandi asli (bukan hash).

> **Peringatan:** `php artisan migrate:fresh` menghapus seluruh isi database, termasuk admin yang ditambahkan manual.
> Hanya admin dari `AdminSeeder` yang dibuat ulang otomatis.

## Pengujian

- Uji otomatis: `php artisan test` (uji perhitungan Cosine Similarity dan halaman autentikasi)
- Skenario Black Box Testing: [docs/BLACK_BOX_TESTING.md](docs/BLACK_BOX_TESTING.md)
