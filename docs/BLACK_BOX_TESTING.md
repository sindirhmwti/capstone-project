# Skenario Black Box Testing

**Sistem Informasi Pencocokan Calon Pekerja dengan Klien — PT Shaka Abadi Nusantara**

Pengujian dilakukan pada lingkungan lokal (Laravel 12, PHP 8.3, MySQL/XAMPP) setelah `php artisan migrate:fresh --seed`.
Akun uji: lihat bagian *Akun Login* pada `README.md`.

Keterangan hasil: **Berhasil** = output sesuai yang diharapkan.

---

## 1. Registrasi Akun (FR Tabel 4.2)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| REG-01 | Registrasi klien dengan data valid | Nama, email baru, no. telepon `08…`, kata sandi ≥ 8 (huruf+angka), konfirmasi sama, centang persetujuan | Akun klien tersimpan, otomatis login, diarahkan ke Beranda Klien dengan pesan "Registrasi berhasil" | Berhasil |
| REG-02 | Registrasi ART dengan data valid | Data akun + domisili + keterampilan | Akun pekerja & profil (status *Belum Lengkap*) tersimpan, diarahkan ke Beranda Pekerja | Berhasil |
| REG-03 | Registrasi Baby Sitter / Perawat Lansia | Data akun + domisili | Akun tersimpan, diarahkan ke halaman Status Verifikasi untuk unggah dokumen | Berhasil |
| REG-04 | Data wajib kosong | Nama kosong, persetujuan tidak dicentang | Pesan "nama lengkap wajib diisi" dan "Anda harus menyetujui Syarat & Ketentuan" | Berhasil |
| REG-05 | Email sudah terdaftar | Email `klien@test.com` | Pesan "Email sudah terdaftar. Silakan gunakan email lain." | Berhasil |
| REG-06 | Format telepon / konfirmasi sandi salah | Telepon `123`, konfirmasi berbeda | Pesan format telepon tidak valid & "Konfirmasi kata sandi tidak cocok" | Berhasil |
| REG-07 | Manipulasi peran menjadi admin | Field tersembunyi `peran=admin` diubah | Registrasi ditolak, akun tidak dibuat | Berhasil |
| REG-08 | Kategori pekerja tidak dikenal | URL `/register/pekerja/xyz` | Halaman 404 | Berhasil |

## 2. Login & Logout (FR Tabel 4.4–4.5)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| LOG-01 | Login admin | `admin@shakaabadi.com` / `Admin123!` | Masuk ke `/admin/dashboard` | Berhasil |
| LOG-02 | Login klien | `klien@test.com` / `Klien123!` | Masuk ke `/client/dashboard` | Berhasil |
| LOG-03 | Login pekerja | `art@test.com` / `Pekerja123!` | Masuk ke `/worker/dashboard` | Berhasil |
| LOG-04 | Kata sandi salah | Email benar, sandi salah | Pesan "Email atau kata sandi salah" | Berhasil |
| LOG-05 | Akun nonaktif | `sri@test.com` / `Pekerja123!` | Login ditolak: "Akun Anda telah dinonaktifkan…" | Berhasil |
| LOG-06 | Admin yang ditambahkan manual lewat phpMyAdmin | Hash dari `password_hash()` PHP | Login berhasil | Berhasil |
| LOG-07 | Logout | Klik "Keluar dari akun" | Sesi berakhir, diarahkan ke halaman login; dashboard tidak dapat dibuka lagi | Berhasil |
| LOG-08 | Pembatasan percobaan login | > 5 percobaan gagal per menit | Pesan "Terlalu banyak percobaan login…" | Berhasil |

## 3. Hak Akses (NFR Keamanan)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| AKS-01 | Tamu membuka dashboard | `/admin/dashboard` tanpa login | Diarahkan ke halaman login | Berhasil |
| AKS-02 | Klien membuka halaman admin | Login klien → `/admin/dashboard` | Halaman 403 "Akses Ditolak" | Berhasil |
| AKS-03 | Pekerja membuka katalog klien | Login pekerja → `/client/workers` | Halaman 403 | Berhasil |
| AKS-04 | Klien membuka pesanan klien lain | `/client/orders/{id milik orang lain}` | Halaman 403 | Berhasil |
| AKS-05 | Dokumen pekerja dibuka pihak lain | Klien / pekerja lain membuka `/dokumen/{id}` | Halaman 403 (hanya pemilik & admin) | Berhasil |
| AKS-06 | Akun dinonaktifkan saat sedang login | Admin menonaktifkan akun yang sedang aktif | Pengguna langsung dikeluarkan pada permintaan berikutnya | Berhasil |

## 4. Profil, Kriteria Khusus, dan Dokumen Pekerja (FR Tabel 4.12, 4.15, UC Mengisi Kriteria Khusus)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| PKJ-01 | Edit profil valid | Tanggal lahir, jenis kelamin, domisili, pengalaman, keterampilan | Pesan "Profil berhasil diperbarui" | Berhasil |
| PKJ-02 | Usia di bawah 18 tahun / kolom wajib kosong | Tanggal lahir 2015, keterampilan kosong | Pesan "Usia pekerja minimal 18 tahun" & "keterampilan wajib diisi" | Berhasil |
| PKJ-03 | Kriteria khusus diisi sebagian | 2 dari 12 kriteria | Pesan "Semua kriteria wajib dijawab (masih ada 10…)" | Berhasil |
| PKJ-04 | Kriteria khusus diisi lengkap | 12 kriteria ya/tidak | Tersimpan di tabel `kriteria_pekerja` | Berhasil |
| PKJ-05 | Unggah format tidak sesuai | Berkas `.txt` | Pesan "Format dokumen harus PDF, JPG, atau PNG." | Berhasil |
| PKJ-06 | Unggah berkas > 2 MB | PDF 3 MB | Pesan "…Pastikan ukuran berkas maksimal 2 MB." | Berhasil |
| PKJ-07 | Unggah KTP & sertifikat valid | PDF / PNG ≤ 2 MB | Pesan berhasil, status dokumen *Menunggu Pemeriksaan* | Berhasil |
| PKJ-08 | Ajukan verifikasi saat data belum lengkap | Tombol "Kirim untuk Verifikasi" | Tombol nonaktif / pesan data belum lengkap | Berhasil |
| PKJ-09 | Ajukan verifikasi saat data lengkap | Tombol "Kirim untuk Verifikasi" | Status *Menunggu Verifikasi*, admin menerima notifikasi | Berhasil |
| PKJ-10 | Ubah dokumen saat menunggu verifikasi | Unggah ulang KTP | Ditolak: "Dokumen tidak dapat diubah saat status…" | Berhasil |

## 5. Verifikasi & Kelola Akun Pekerja oleh Admin (FR Tabel 4.13, 4.18)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| ADM-01 | Lihat daftar menunggu verifikasi | Menu Verifikasi Pekerja | Tabel pekerja berstatus *Menunggu* beserta tautan dokumen | Berhasil |
| ADM-02 | Setujui verifikasi | Tombol "Setujui" | Status *Terverifikasi*, dokumen *Valid*, pekerja mendapat notifikasi, tampil di katalog | Berhasil |
| ADM-03 | Minta perbaikan tanpa catatan | Tombol "Minta Perbaikan", catatan kosong | Pesan "Catatan pemeriksaan wajib diisi…" | Berhasil |
| ADM-04 | Minta perbaikan dengan catatan | Catatan + dokumen ditandai *Tidak valid* | Status *Belum Lengkap*, catatan tampil di halaman pekerja | Berhasil |
| ADM-05 | Tolak verifikasi | Tombol "Tolak" + catatan | Status *Ditolak*, pekerja dapat mengajukan ulang | Berhasil |
| ADM-06 | Memproses ulang data yang sudah diputuskan | Kirim keputusan untuk status *Terverifikasi* | Ditolak: "Hanya data berstatus Menunggu Verifikasi…" | Berhasil |
| ADM-07 | Nonaktifkan / aktifkan akun pekerja | Tombol "Nonaktifkan" | Status akun berubah, pekerja hilang dari katalog & mendapat notifikasi | Berhasil |
| ADM-08 | Ubah data akun dengan email duplikat | Email milik pengguna lain | Pesan "Email sudah terdaftar…" | Berhasil |

## 6. Katalog, Filter, dan Rekomendasi Cosine Similarity (FR Tabel 4.3, 4.6, 4.7, 4.16)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| KAT-01 | Melihat katalog | Menu Katalog Pekerja | Hanya pekerja *Terverifikasi* & akun *Aktif* yang tampil | Berhasil |
| KAT-02 | Filter kategori | Kategori = ART | Hanya pekerja ART | Berhasil |
| KAT-03 | Filter "nyaman dengan anjing" + "tidak mudah mabuk darat" | Centang 2 kriteria, wajib semua | Hanya pekerja yang bernilai *ya* pada kedua kriteria (Budi, Dewi, Dimas, Wati pada data uji) | Berhasil |
| KAT-04 | Hasil tidak ditemukan | Kata kunci `zzz` | Pesan "Pekerja yang sesuai tidak ditemukan." | Berhasil |
| KAT-05 | Skor kecocokan | Kriteria: nyaman kucing, bisa memasak, bisa motor | Budi Santoso 65,5% = 3/(√3·√7); Siti Rahayu 43,6% = 2/(√3·√7) | Berhasil |
| KAT-06 | Mode peringkat (tanpa "wajib semua") | Kriteria sama dengan KAT-05 | Semua pekerja tampil, diurutkan dari skor tertinggi | Berhasil |
| KAT-07 | Detail profil pekerja | Klik "Lihat Profil" | Data profil, keterampilan, kriteria (kriteria yang dicari disorot), skor | Berhasil |
| KAT-08 | Detail pekerja yang tidak tersedia | Buka profil pekerja *Ditolak* | Kembali ke katalog dengan pesan "Data profil pekerja tidak tersedia." | Berhasil |
| KAT-09 | Uji unit Cosine Similarity | `php artisan test` | 4 uji (vektor identik = 1, tanpa irisan = 0, vektor nol = 0, contoh manual 65,5%) lulus | Berhasil |

## 7. Favorit (FR Tabel 4.10)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| FAV-01 | Tambah favorit | Ikon ♡ pada kartu | Pesan "… ditambahkan ke favorit", tampil di Favorit Saya | Berhasil |
| FAV-02 | Tambah favorit dua kali | Ikon ♡ diklik lagi via permintaan ulang | Tidak terjadi duplikasi data | Berhasil |
| FAV-03 | Favorit pekerja yang tidak tersedia | Pekerja *Ditolak* | Pesan "Pekerja tidak tersedia…" | Berhasil |
| FAV-04 | Hapus favorit | Ikon ♥ | Pesan "… dihapus dari favorit" | Berhasil |
| FAV-05 | Favorit masih kosong | Halaman Favorit Saya | Pesan "Belum terdapat pekerja yang disimpan sebagai favorit." | Berhasil |

## 8. Pemesanan (FR Tabel 4.8, 4.9, 4.14)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| PSN-01 | Ajukan pesanan valid | Jadwal ≥ hari ini, lokasi, keterangan ≥ 10 karakter, kriteria | Pesanan *Menunggu* tersimpan beserta `kriteria_pemesanan` & riwayat status; admin menerima notifikasi | Berhasil |
| PSN-02 | Data pesanan tidak lengkap | Jadwal lampau, lokasi kosong, keterangan pendek | Pesan validasi untuk setiap kolom | Berhasil |
| PSN-03 | Pekerja tidak tersedia (sedang bertugas) | Pesan pekerja dengan pesanan *Disetujui* | Pesan "Pekerja sedang bertugas pada pesanan lain…" | Berhasil |
| PSN-04 | Pesanan ganda | Pesan pekerja yang sama saat pesanan sebelumnya masih aktif | Pesan "Anda masih memiliki pesanan aktif untuk pekerja ini." | Berhasil |
| PSN-05 | Batalkan tanpa alasan | Alasan kosong | Pesan "alasan pembatalan wajib diisi." | Berhasil |
| PSN-06 | Batalkan dengan alasan | Alasan diisi, status *Menunggu* | Status *Dibatalkan*, alasan & pembatal tercatat | Berhasil |
| PSN-07 | Batalkan pesanan yang sudah final | Pesanan *Dibatalkan* | Pesan "…tidak dapat dibatalkan." | Berhasil |
| PSN-08 | Riwayat & detail pesanan | Menu Riwayat Pesanan | Daftar per tab status, detail dengan riwayat perubahan status | Berhasil |
| PSN-09 | Admin melompati status tidak sah | *Menunggu* → *Selesai* | Pesan "Perubahan status … tidak diizinkan." | Berhasil |
| PSN-10 | Admin menolak tanpa alasan | Status *Ditolak*, catatan kosong | Pesan "Alasan wajib diisi bila pesanan ditolak atau dibatalkan." | Berhasil |
| PSN-11 | Alur status lengkap | *Menunggu → Diproses → Disetujui → Selesai* | Setiap perubahan tercatat di `riwayat_status_pemesanan`; klien & pekerja mendapat notifikasi | Berhasil |
| PSN-12 | Ubah pesanan yang sudah selesai | Status *Selesai* → lainnya | Pesan "…sudah final dan tidak dapat diubah." | Berhasil |

## 9. Notifikasi (FR Tabel 4.11)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| NTF-01 | Badge jumlah belum dibaca | Ada notifikasi baru | Angka tampil pada ikon lonceng & menu Notifikasi | Berhasil |
| NTF-02 | Tandai satu notifikasi dibaca | Tombol "Tandai dibaca" | Notifikasi berubah menjadi sudah dibaca | Berhasil |
| NTF-03 | Tandai semua dibaca | Tombol "Tandai semua dibaca" | Semua notifikasi dibaca, badge hilang | Berhasil |
| NTF-04 | Menandai notifikasi milik orang lain | Permintaan ke id notifikasi lain | Halaman 403 | Berhasil |
| NTF-05 | Notifikasi otomatis | Verifikasi disetujui, status pesanan berubah | Pekerja / klien menerima notifikasi terkait | Berhasil |

## 10. Laporan Penyaluran (FR Tabel 4.17)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| LAP-01 | Tampilkan laporan per periode | Periode = bulan berjalan | Daftar pesanan *Disetujui* & *Selesai* pada periode tersebut + ringkasan per kategori | Berhasil |
| LAP-02 | Periode tanpa data | Periode Januari 2020 | Pesan "Data penyaluran tidak tersedia…", tombol cetak nonaktif | Berhasil |
| LAP-03 | Format periode tidak valid | `periode=salah` | Permintaan ditolak oleh validasi | Berhasil |
| LAP-04 | Cetak laporan | Tombol "Cetak Laporan" | Halaman cetak berkop perusahaan, dialog cetak / simpan PDF terbuka | Berhasil |

## 11. Edit Profil Klien & Admin (FR Tabel 4.12)

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| PRF-01 | Ubah data profil | Nama, email, telepon | Pesan "Profil berhasil diperbarui" | Berhasil |
| PRF-02 | Ganti sandi dengan sandi lama salah | Sandi lama salah | Pesan "Kata sandi lama salah." | Berhasil |
| PRF-03 | Ganti sandi dengan sandi lama benar | Sandi lama benar, sandi baru valid | Tersimpan; login berikutnya memakai sandi baru | Berhasil |

## 12. Kelancaran Sistem

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| SYS-01 | Seluruh route GET | 35 route × 4 kondisi (tamu, admin, klien, pekerja) | Tidak ada galat 500 / 404 / 419 yang tidak semestinya | Berhasil |
| SYS-02 | Log aplikasi | `storage/logs/laravel.log` setelah seluruh pengujian | Tidak ada entri ERROR | Berhasil |
| SYS-03 | Alur lengkap | Pekerja daftar → isi profil & kriteria → unggah dokumen → admin verifikasi → klien filter & rekomendasi → favorit → pesan → admin ubah status → notifikasi → laporan tercetak | Seluruh langkah berjalan tanpa galat | Berhasil |
