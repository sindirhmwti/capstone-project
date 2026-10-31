@extends('layouts.guest')
@section('title', 'Temukan ART, Baby Sitter & Perawat Lansia Terpercaya')
@section('content')

{{-- ================= HERO ================= --}}
<section class="relative overflow-hidden bg-gradient-to-b from-primary-soft/70 via-white to-white">
    <div class="pointer-events-none absolute -right-40 -top-40 h-[480px] w-[480px] rounded-full bg-mint/30 blur-3xl"></div>
    <div class="container-page relative grid items-center gap-12 py-14 lg:grid-cols-2 lg:py-20">
        <div class="animate-fade-up">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary/15 bg-white px-3.5 py-1.5 text-xs font-semibold text-primary-dark shadow-sm">
                <span class="h-2 w-2 rounded-full bg-primary-accent"></span> PT Shaka Abadi Nusantara
            </span>
            <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight text-ink sm:text-5xl lg:text-[3.4rem]">
                Temukan bantuan yang <span class="text-primary">tepat</span> untuk keluarga Anda.
            </h1>
            <p class="mt-5 max-w-xl text-lg leading-relaxed text-muted">
                ART, Baby Sitter, dan Perawat Lansia yang sudah diverifikasi admin, dicocokkan dengan kebutuhan khusus rumah Anda — dari hewan peliharaan sampai kebiasaan sehari-hari.
            </p>

            {{-- Pencarian --}}
            <form action="{{ route('workers.index') }}" method="GET" class="mt-8 flex flex-col gap-2 rounded-2xl border border-line bg-white p-2 shadow-lift sm:flex-row">
                <label class="flex flex-1 items-center gap-2.5 px-3">
                    <x-icon name="search" class="h-5 w-5 text-muted" />
                    <span class="sr-only">Cari nama atau lokasi</span>
                    <input type="text" name="search" placeholder="Cari nama atau lokasi..." class="w-full bg-transparent py-3 text-base outline-none placeholder:text-gray-400">
                </label>
                <label class="flex items-center border-t border-line px-3 sm:border-l sm:border-t-0">
                    <span class="sr-only">Kategori</span>
                    <select name="category" class="w-full cursor-pointer bg-transparent py-3 text-sm font-medium text-ink outline-none">
                        <option value="">Semua kategori</option>
                        <option value="ART">ART</option>
                        <option value="Baby Sitter">Baby Sitter</option>
                        <option value="Perawat Lansia">Perawat Lansia</option>
                    </select>
                </label>
                <button type="submit" class="btn-primary btn-lg">Cari Pekerja</button>
            </form>

            <div class="mt-8 flex flex-wrap gap-x-8 gap-y-4">
                <div>
                    <p class="text-2xl font-extrabold text-ink"><span data-count="{{ $jumlahPekerja ?? 0 }}">{{ $jumlahPekerja ?? 0 }}</span>+</p>
                    <p class="text-sm text-muted">Pekerja terverifikasi</p>
                </div>
                <div class="hidden h-12 w-px bg-line sm:block"></div>
                <div>
                    <p class="text-2xl font-extrabold text-ink">3</p>
                    <p class="text-sm text-muted">Kategori layanan</p>
                </div>
                <div class="hidden h-12 w-px bg-line sm:block"></div>
                <div>
                    <p class="text-2xl font-extrabold text-ink">12</p>
                    <p class="text-sm text-muted">Kriteria khusus</p>
                </div>
            </div>
        </div>

        <div class="relative animate-fade-up [animation-delay:120ms]">
            <img src="{{ asset('images/illustrations/hero.svg') }}" alt="Ilustrasi keluarga dan pekerja rumah tangga yang dipertemukan Temukerja" class="mx-auto w-full max-w-xl" width="520" height="440">
        </div>
    </div>
</section>

{{-- ================= LAYANAN ================= --}}
<section id="layanan" class="scroll-mt-20 py-16 lg:py-24">
    <div class="container-page">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="eyebrow">Layanan kami</p>
            <h2 class="section-title mt-3">Apa yang Anda butuhkan?</h2>
            <p class="mt-4 text-lg text-muted">Pilih jenis pekerja sesuai kebutuhan rumah tangga Anda.</p>
        </div>

        @php
            $layanan = [
                ['ART', 'Asisten Rumah Tangga', 'Membantu membersihkan rumah, mencuci, menyetrika, dan memasak sehari-hari.', 'service-art', 'Cukup KTP'],
                ['Baby Sitter', 'Pengasuh Anak', 'Mengasuh bayi dan anak dengan penuh perhatian, didukung sertifikat pelatihan.', 'service-babysitter', 'Wajib sertifikat pelatihan'],
                ['Perawat Lansia', 'Pendamping Lansia', 'Mendampingi aktivitas harian dan perawatan orang tua tercinta.', 'service-lansia', 'Wajib sertifikat pelatihan'],
            ];
        @endphp
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($layanan as $i => [$kode, $judul, $teks, $gambar, $syarat])
                <a href="{{ route('workers.index', ['category' => $kode]) }}" class="reveal card card-hover group flex flex-col p-6" data-reveal-delay="{{ $i * 90 }}">
                    <img src="{{ asset('images/illustrations/' . $gambar . '.svg') }}" alt="Ilustrasi {{ $kode }}" class="h-36 w-full object-contain" loading="lazy">
                    <h3 class="mt-5 text-xl font-bold text-ink">{{ $kode }}</h3>
                    <p class="text-sm font-medium text-primary">{{ $judul }}</p>
                    <p class="mt-3 flex-1 text-base text-muted">{{ $teks }}</p>
                    <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-muted"><x-icon name="file-check" class="h-4 w-4 text-primary" /> {{ $syarat }}</span>
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary">Lihat <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" /></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= CARA KERJA ================= --}}
<section id="cara-pemesanan" class="scroll-mt-20 bg-canvas py-16 lg:py-24">
    <div class="container-page">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="eyebrow">Cara kerja</p>
            <h2 class="section-title mt-3">Empat langkah mudah</h2>
            <p class="mt-4 text-lg text-muted">Dari pendaftaran sampai pekerja datang ke rumah Anda.</p>
        </div>

        @php
            $langkah = [
                ['user-plus', 'Daftar akun', 'Buat akun sebagai klien atau calon pekerja dalam beberapa menit.'],
                ['shield-check', 'Lengkapi & verifikasi', 'Pekerja mengisi profil, kriteria khusus, dan dokumen; admin memeriksanya.'],
                ['search', 'Cari & cocokkan', 'Pilih kriteria rumah Anda, sistem menampilkan skor kecocokan setiap pekerja.'],
                ['clipboard', 'Pesan pekerja', 'Ajukan pesanan dan pantau statusnya sampai disetujui admin.'],
            ];
        @endphp
        <div class="relative mt-14">
            <div class="pointer-events-none absolute left-[12.5%] right-[12.5%] top-8 hidden border-t-2 border-dashed border-primary/25 lg:block" aria-hidden="true"></div>
            <ol class="relative grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($langkah as $i => [$ikon, $judul, $teks])
                    <li class="reveal text-center" data-reveal-delay="{{ $i * 100 }}">
                        <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-white shadow-lift">
                            <x-icon :name="$ikon" class="h-7 w-7" />
                            <span class="absolute -right-2 -top-2 flex h-7 w-7 items-center justify-center rounded-full bg-white text-sm font-bold text-primary ring-2 ring-primary-soft">{{ $i + 1 }}</span>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-ink">{{ $judul }}</h3>
                        <p class="mx-auto mt-2 max-w-60 text-base text-muted">{{ $teks }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

{{-- ================= KEUNGGULAN ================= --}}
<section id="tentang-kami" class="scroll-mt-20 py-16 lg:py-24">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2">
        <div class="reveal">
            <p class="eyebrow">Mengapa Temukerja</p>
            <h2 class="section-title mt-3">Mengurangi ketidakcocokan sejak awal.</h2>
            <p class="mt-4 text-lg text-muted">
                PT Shaka Abadi Nusantara menyalurkan tenaga kerja domestik dan berupaya menekan pengembalian pekerja akibat ketidakcocokan.
                Karena itu, setiap profil memuat kriteria khusus seperti hewan peliharaan, mabuk darat, dan kondisi rumah.
            </p>
            <a href="{{ route('register') }}" class="btn-primary btn-lg mt-8">Mulai Sekarang <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>

        @php
            $keunggulan = [
                ['shield-check', 'Diverifikasi admin', 'KTP dan sertifikat pelatihan diperiksa sebelum pekerja tampil di katalog.'],
                ['sparkles', 'Rekomendasi cerdas', 'Skor kecocokan dihitung dengan Cosine Similarity dari kriteria Anda.'],
                ['list-checks', 'Kriteria khusus', 'Hewan peliharaan, mabuk darat, ruangan ber-AC, bersedia menginap, dan lainnya.'],
                ['bell', 'Status transparan', 'Notifikasi di setiap perubahan status verifikasi dan pesanan.'],
            ];
        @endphp
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($keunggulan as $i => [$ikon, $judul, $teks])
                <div class="reveal card p-5" data-reveal-delay="{{ $i * 80 }}">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon :name="$ikon" /></span>
                    <h3 class="mt-4 text-base font-bold text-ink">{{ $judul }}</h3>
                    <p class="mt-1.5 text-sm text-muted">{{ $teks }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= PEKERJA UNGGULAN ================= --}}
<section id="pekerja-unggulan" class="scroll-mt-20 bg-canvas py-16 lg:py-24">
    <div class="container-page">
        <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Pekerja unggulan</p>
                <h2 class="section-title mt-3">Siap membantu keluarga Anda</h2>
            </div>
            <a href="{{ route('workers.index') }}" class="btn-outline">Lihat semua pekerja <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>

        @if ($featuredWorkers->isEmpty())
            <div class="card mt-10"><x-empty-state illustration="kosong" title="Data pekerja belum tersedia." message="Pekerja terverifikasi akan tampil di sini." /></div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredWorkers as $i => $worker)
                    <div class="reveal card card-hover p-6" data-reveal-delay="{{ $i * 90 }}">
                        <div class="flex items-center gap-4">
                            <x-avatar :name="$worker->name" size="lg" />
                            <div class="min-w-0">
                                <p class="truncate text-lg font-bold text-ink">{{ $worker->name }}</p>
                                <x-badge color="brand" :dot="false">{{ $worker->category }}</x-badge>
                            </div>
                        </div>
                        <div class="mt-5 space-y-2 text-sm text-muted">
                            <p class="flex items-center gap-2"><x-icon name="map-pin" class="h-4 w-4 text-primary" /> {{ $worker->location }}</p>
                            <p class="flex items-center gap-2"><x-icon name="briefcase" class="h-4 w-4 text-primary" /> {{ $worker->experience }} tahun pengalaman</p>
                            <p class="flex items-center gap-2"><x-icon name="shield-check" class="h-4 w-4 text-primary" /> Terverifikasi admin</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ================= AJAKAN DAFTAR ================= --}}
<section class="py-16 lg:py-24">
    <div class="container-page">
        <div class="reveal relative overflow-hidden rounded-3xl bg-primary px-6 py-12 text-center sm:px-12 lg:py-16">
            <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-10 h-72 w-72 rounded-full bg-mint/10"></div>
            <h2 class="relative text-3xl font-extrabold tracking-tight text-white lg:text-4xl">Siap memulai bersama Temukerja?</h2>
            <p class="relative mx-auto mt-4 max-w-xl text-lg text-white/80">Cari pekerja yang cocok untuk keluarga Anda, atau daftarkan diri Anda sebagai pekerja profesional.</p>
            <div class="relative mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('register.klien') }}" class="btn btn-lg bg-white text-primary-dark hover:bg-primary-soft">Saya mencari pekerja</a>
                <a href="{{ route('register.pekerja') }}" class="btn btn-lg border border-white/40 text-white hover:bg-white/10">Saya ingin bekerja</a>
            </div>
        </div>
    </div>
</section>

@endsection
