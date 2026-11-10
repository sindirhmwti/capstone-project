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

## 13. Undangan & Konfirmasi Interview via WhatsApp

Prasyarat: `php artisan migrate` (tabel `jadwal_interview`), `ADMIN_WHATSAPP` diisi di `.env` untuk INT-11, `WHATSAPP_DRIVER=demo`.

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| INT-01 | Registrasi tetap memakai OTP WhatsApp demo | Daftar klien/pekerja, kode `0000` | Akun dibuat seperti sebelumnya | |
| INT-02 | Admin menyetujui pesanan dengan jadwal | Detail pesanan → "Setujui & Jadwalkan Interview" → isi tanggal, waktu, metode, lokasi | Status "Disetujui", jadwal tersimpan, riwayat "Jadwal interview dibuat", notifikasi "Pesanan disetujui…" ke klien & pekerja, admin diarahkan ke panel interview | |
| INT-03 | Validasi jadwal | Tanggal kemarin / metode Online dengan link bukan URL / lokasi kosong | Pesan galat pada kolom terkait, modal terbuka kembali, status tidak berubah | |
| INT-04 | Dropdown status lama tidak bisa menyetujui | Paksa `status_pemesanan=disetujui` lewat form lama | Ditolak: "Gunakan tombol Setujui & Jadwalkan Interview…" | |
| INT-05 | Kirim undangan ke klien | Klik "Kirim Undangan via WhatsApp" (kartu Klien) | Tab baru membuka `wa.me/62…` dengan pesan undangan klien berbaris; "Undangan dikirim pada …" tampil; tombol berubah "Kirim Ulang via WhatsApp"; log berisi `[WHATSAPP DEMO] Pesan ke 62…` | |
| INT-06 | Kirim undangan ke calon pekerja | Klik tombol pada kartu Calon Pekerja | Pesan memuat "Anda dipilih oleh klien…" dan "Mohon membawa KTP asli dan sertifikat (jika ada)." | |
| INT-07 | Nomor WhatsApp kosong (akun lama) | Kosongkan `no_telepon` salah satu pihak | Tombol nonaktif "Nomor WhatsApp belum tersedia" | |
| INT-08 | Tandai Hadir | Klik "Tandai Hadir" | Badge hijau "Hadir", notifikasi "Kehadiran interview Anda telah dikonfirmasi admin", riwayat tercatat, banner pihak tsb hijau | |
| INT-09 | Tandai Minta Ubah Jadwal | Isi catatan → simpan (coba juga tanpa catatan) | Tanpa catatan: galat wajib diisi. Dengan catatan: badge oranye + catatan tampil, banner "Permintaan ubah jadwal sedang ditinjau admin.", indikator daftar "Perlu dihubungi" | |
| INT-10 | Tandai Tidak Merespons | Klik "Tandai Tidak Merespons" | Badge merah, notifikasi "Mohon balas undangan interview", indikator "Perlu dihubungi" | |
| INT-11 | Tombol konfirmasi di sisi klien/pekerja | Login klien/pekerja → klik "Konfirmasi via WhatsApp" / "Minta Ubah Jadwal via WhatsApp" | Membuka `wa.me/<ADMIN_WHATSAPP>` dengan pesan "Halo Admin Temukerja, saya … Saya konfirmasi HADIR…" / "…MENGUBAH JADWAL… karena". Bila `ADMIN_WHATSAPP` kosong, tombol tidak tampil | |
| INT-12 | Ubah jadwal | "Ubah Jadwal" → simpan jadwal baru | Konfirmasi kedua pihak kembali "Menunggu", waktu undangan di-reset, pesan berikutnya diawali "PERUBAHAN JADWAL INTERVIEW", banner "Jadwal interview diperbarui. Cek pesan WhatsApp terbaru dari admin." | |
| INT-13 | 24 jam tanpa respons | Ubah `undangan_klien_dikirim_pada` menjadi > 24 jam lalu di database, status masih menunggu | Badge merah "Perlu dihubungi ulang" di kartu; indikator daftar "Perlu dihubungi" | |
| INT-14 | Indikator daftar pesanan | Buka Kelola Pemesanan | Contoh: "2 belum diundang", "1 menunggu", "2/2 hadir", "Perlu dihubungi" | |
| INT-15 | Banner klien | Detail & riwayat pesanan klien yang disetujui | Banner "Pesanan Anda telah disetujui. Admin akan mengirim undangan interview melalui WhatsApp ke nomor 0812****6789…" + ringkasan jadwal + status | |
| INT-16 | Banner calon pekerja | Beranda pekerja | Banner "Anda dipilih oleh klien. Admin akan mengirim undangan…"; setelah dikirim: "Admin telah mengirim undangan interview melalui WhatsApp pada …" | |
| INT-17 | Hak akses | Klien lain membuka detail pesanan; pekerja lain membuka beranda; klien/pekerja memanggil URL aksi admin | 403 / banner tidak tampil / 403 | |
| INT-18 | Tidak memakai email | Seluruh skenario di atas | Tidak ada email yang terkirim (log tidak berisi email) | |

## 14. Foto Profil

Prasyarat: `php artisan migrate` (kolom `pengguna.foto_profil`) dan `php artisan storage:link`.

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| FTP-01 | Pekerja tanpa foto mengajukan verifikasi | Profil, kriteria, dan KTP lengkap; foto belum ada → buka Status Verifikasi | Tombol "Kirim untuk Verifikasi" nonaktif dengan keterangan "Unggah foto profil terlebih dahulu"; syarat "Foto profil sudah diunggah" belum tercentang | |
| FTP-02 | Validasi server pengajuan | Kirim POST ajukan verifikasi tanpa foto (tombol dimanipulasi) | Ditolak: "Unggah foto profil terlebih dahulu sebelum mengajukan verifikasi." | |
| FTP-03 | Unggah foto pekerja lalu ajukan | Edit Profil → Unggah Foto (JPG 400x400) → Simpan Foto → ajukan | Pesan "Foto profil berhasil diunggah"; pengajuan berhasil, status "Menunggu Verifikasi" | |
| FTP-04 | Pratinjau sebelum disimpan | Pilih file gambar | Pratinjau bulat tampil (label "Pratinjau"), tombol "Simpan Foto" aktif, tombol "Batal" mengembalikan foto semula | |
| FTP-05 | File bukan gambar | Unggah PDF | "File harus berupa gambar JPG, JPEG, PNG, atau WEBP." | |
| FTP-06 | File > 2 MB | Unggah gambar 3 MB | "Ukuran foto maksimal 2 MB." | |
| FTP-07 | Resolusi < 300x300 | Unggah gambar 200x250 | "Ukuran foto minimal 300 x 300 piksel." | |
| FTP-08 | Format PNG / WEBP | Unggah PNG 300x300 dan WEBP 500x320 | Diterima | |
| FTP-09 | Ganti foto | Unggah foto baru saat sudah punya foto | "Foto profil berhasil diganti"; file lama terhapus dari `storage/app/public/foto-profil`, nama file acak | |
| FTP-10 | Pekerja menghapus foto | Lihat halaman Edit Profil pekerja; coba DELETE `/worker/profile/photo` | Tidak ada tombol "Hapus Foto" (hanya "Ganti Foto"); permintaan hapus ditolak, foto tetap ada | |
| FTP-11 | Klien unggah, ganti, hapus foto | Edit Profil klien → "Foto Profil (opsional)" | Unggah/ganti berhasil; "Hapus Foto" menghapus file dan kembali ke avatar inisial | |
| FTP-12 | Admin unggah foto | Edit Profil admin | Foto tersimpan dan tampil di topbar & sidebar admin | |
| FTP-13 | Pengingat di beranda pekerja | Login pekerja tanpa foto (mis. `art@test.com`) | Banner "Foto profil wajib diunggah agar profil Anda dapat diverifikasi dan tampil di katalog" + tombol "Unggah Foto"; checklist memuat "Foto profil" | |
| FTP-14 | Akun lama terverifikasi tanpa foto | Klien membuka katalog | Pekerja tetap tampil dengan avatar inisial | |
| FTP-15 | Foto tampil di semua tempat | Setelah unggah: topbar/menu profil, sidebar, katalog klien, detail pekerja, daftar & detail pekerja admin, detail pesanan admin & klien, panel konfirmasi interview | Foto bulat (object-cover, tidak gepeng); avatar inisial bila kosong | |
| FTP-16 | Pemeriksaan admin | Admin membuka Pemeriksaan Dokumen pekerja | Foto profil besar tampil di samping data & dokumen; bila tidak jelas admin dapat "Minta Perbaikan"/"Tolak" dengan catatan | |
| FTP-17 | Akun lama tetap normal | Login `admin@shakaabadi.com`, `klien@test.com`, `art@test.com` | Login berhasil, alur lain tidak berubah | |

## 15. Hasil Interview, Penyaluran & Ketersediaan Pekerja

Prasyarat: `php artisan migrate` (kolom hasil di `jadwal_interview`, tabel `penyaluran`). Pesanan berstatus Disetujui dengan jadwal interview.

| ID | Skenario | Input | Output yang diharapkan | Hasil |
|----|----------|-------|------------------------|-------|
| PNY-01 | Bagian Hasil Interview tampil | Admin membuka detail pesanan Disetujui yang memiliki jadwal | Tombol "Cocok - Salurkan Pekerja" (hijau) dan "Tidak Cocok" (merah) | |
| PNY-02 | Catat Cocok | Klik "Cocok" → isi tanggal mulai kerja (+ catatan) → Salurkan | Pesanan "Selesai" dengan riwayat "Hasil interview cocok, pekerja tersalurkan"; data penyaluran "Aktif"; notifikasi "Penyaluran berhasil!…" ke klien dan "Selamat! Anda tersalurkan…" ke pekerja | |
| PNY-03 | Pesanan lain otomatis dibatalkan | Pekerja punya pesanan lain berstatus Menunggu/Diproses dari klien lain, lalu PNY-02 | Pesanan lain "Dibatalkan" dengan alasan "Pekerja telah tersalurkan ke klien lain" (dibatalkan oleh admin), tercatat di riwayat, klien pemiliknya mendapat notifikasi | |
| PNY-04 | Validasi Cocok | Tanggal mulai sebelum tanggal interview / kosong | Pesan galat, modal terbuka kembali, status tidak berubah | |
| PNY-05 | Catat Tidak Cocok | Klik "Tidak Cocok" → isi alasan (coba juga kosong) | Kosong: galat "Alasan wajib diisi". Diisi: pesanan "Dibatalkan" dengan alasan "Hasil interview tidak cocok: …"; notifikasi ke klien & pekerja; pekerja tetap di katalog | |
| PNY-06 | Kabari via WhatsApp | Setelah hasil dicatat → "Kabari Klien" / "Kabari Calon Pekerja" | wa.me terbuka dengan pesan sesuai hasil (cocok berisi tanggal mulai kerja; tidak cocok berisi info singkat dan sopan) | |
| PNY-07 | Dropdown lama | Pilih status "Selesai" pada pesanan Disetujui | Opsi tidak tersedia; bila dipaksa: "Gunakan tombol Cocok - Salurkan Pekerja…" | |
| PNY-08 | Katalog klien | Klien membuka katalog, mencari nama pekerja, memfilter kategori | Pekerja tersalurkan tidak tampil | |
| PNY-09 | Detail pekerja tersalurkan | Buka URL detail pekerja tersalurkan | Keterangan "Pekerja ini sudah tersalurkan dan tidak tersedia", tombol pesan menjadi "Sudah Tersalurkan" | |
| PNY-10 | Pesan lewat URL langsung | Buka `/client/workers/{id}/order` atau kirim form pesanan | Ditolak: "Pekerja ini sudah tersalurkan dan tidak tersedia." | |
| PNY-11 | Favorit | Klien yang memfavoritkan pekerja membuka Favorit | Kartu pekerja tetap tampil dengan badge abu-abu "Sudah Tersalurkan" dan tombol Pesan nonaktif | |
| PNY-12 | Beranda pekerja | Pekerja tersalurkan login | Banner hijau "Anda sedang bekerja sejak … Profil Anda tidak ditampilkan di katalog selama masa kerja."; status katalog "Tidak (sedang bekerja)" | |
| PNY-13 | Statistik beranda publik | Buka beranda | Jumlah pekerja hanya menghitung pekerja tersedia | |
| PNY-14 | Menu Penyaluran | Admin → menu "Penyaluran", cari nama, filter Aktif/Berakhir | Daftar pekerja (foto), kategori, klien, tanggal mulai, status | |
| PNY-15 | Akhiri Penyaluran | Klik "Akhiri Penyaluran" → tanggal berakhir + catatan (coba juga tanggal sebelum mulai kerja) | Tanggal salah: galat. Benar: status "Berakhir", pekerja kembali tampil di katalog, notifikasi "Masa kerja Anda telah berakhir. Profil Anda kembali tampil di katalog." | |
| PNY-16 | Ketersediaan di admin | Daftar & detail pekerja admin | Badge "Tersedia" (hijau) atau "Tersalurkan" (biru) + nama klien | |
| PNY-17 | Laporan penyaluran | Laporan per periode / kategori dan versi cetak | Menghitung pesanan Selesai (pekerja tersalurkan) berdasarkan tanggal mulai kerja; pesanan Disetujui tidak dihitung; kartu "Masih bekerja" menghitung penyaluran aktif | |
| PNY-18 | Hak akses | Klien/pekerja membuka `/admin/placements` atau mengirim aksi hasil interview | 403 | |
| PNY-19 | Akun lama tetap normal | Login `admin@shakaabadi.com`, `klien@test.com`, `art@test.com` | Login berhasil, alur lain tidak berubah | |

