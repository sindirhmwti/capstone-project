@extends('layouts.guest')
@section('title', 'Temukan ART, Baby Sitter & Perawat Lansia Terpercaya')
@section('navbar-transparan', '1')

@push('styles')
    {{-- Preloader hanya sekali per sesi: sembunyikan sejak awal bila sudah pernah tampil --}}
    <script>try { if (sessionStorage.getItem('tk-preload')) document.documentElement.classList.add('tanpa-preload'); } catch (e) {}</script>
    <link rel="preload" as="image" href="{{ asset('images/photos/hero-keluarga.webp') }}" fetchpriority="high">
@endpush

@section('content')

@php
    // Angka statistik diambil langsung dari database (bukan angka karangan)
    $jumlahKriteria = \App\Models\Kriteria::count();
    $jumlahKategori = count(\App\Models\ProfilPekerja::KATEGORI_SINGKAT);
    $jumlahPenyaluran = \App\Models\Pemesanan::whereIn('status_pemesanan', \App\Models\Pemesanan::STATUS_PENYALURAN)->count();
    $contohKriteria = \App\Models\Kriteria::orderBy('id_kriteria')->take(6)->pluck('nama_kriteria');
@endphp

{{-- ================= 1. PRELOADER (sekali per sesi) ================= --}}
<div id="preloader" class="fixed inset-0 z-[100] flex flex-col items-center justify-center gap-5 bg-primary-darker" aria-hidden="true">
    <div data-preloader-logo class="flex items-center gap-3 opacity-0">
        <img src="{{ asset('images/logo-icon.svg') }}" alt="" class="h-14 w-14 rounded-2xl ring-2 ring-white/30">
        <span class="text-3xl font-extrabold tracking-tight text-white">temukerja<span class="text-mint">.</span></span>
    </div>
    <div class="h-1 w-40 overflow-hidden rounded-full bg-white/15"><div data-preloader-bar class="h-full origin-left scale-x-0 rounded-full bg-sun"></div></div>
</div>

{{-- ================= 2. HERO ================= --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary-darker via-primary-dark to-primary pt-20 text-white lg:pt-24">
    <div class="bg-grid-light pointer-events-none absolute inset-0"></div>
    <div class="pointer-events-none absolute -left-40 bottom-0 h-80 w-80 rounded-full bg-sun/10 blur-3xl"></div>

    <div class="container-page relative grid items-center gap-12 pb-16 pt-10 lg:min-h-[min(calc(88vh-6rem),860px)] lg:grid-cols-[45fr_55fr] lg:gap-14 lg:py-14 2xl:gap-20">
        <div>
            <span data-hero-item class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-mint ring-1 ring-white/15">
                <span class="h-2 w-2 animate-pulse rounded-full bg-sun"></span> Layanan resmi PT Shaka Abadi Nusantara
            </span>
            <h1 data-split class="mt-6 text-[clamp(2.6rem,4.5vw,4.75rem)] font-extrabold leading-[1.05] tracking-tight text-white">
                Bantuan rumah tangga yang <span class="text-sun">tepat</span> untuk keluarga Anda.
            </h1>
            <p data-hero-item class="mt-6 max-w-2xl text-lg leading-relaxed text-white/85 lg:text-xl">
                Temukan ART, Baby Sitter, dan Perawat Lansia yang sudah diverifikasi admin dan dicocokkan dengan kebutuhan khusus rumah Anda.
            </p>

            {{-- Kotak pencarian besar --}}
            <form data-hero-item action="{{ route('workers.index') }}" method="GET" class="mt-9 rounded-[1.75rem] bg-white p-3 text-ink shadow-lift sm:p-4 lg:p-5">
                <div class="grid gap-3 sm:grid-cols-[1fr_auto] lg:grid-cols-1 2xl:grid-cols-[1fr_auto]">
                    <label class="flex items-center gap-3 rounded-2xl bg-canvas px-4 transition focus-within:ring-4 focus-within:ring-primary/15">
                        <x-icon name="search" class="h-5 w-5 text-primary" />
                        <span class="sr-only">Cari nama atau lokasi</span>
                        <input type="text" name="search" placeholder="Cari nama atau lokasi..." class="w-full bg-transparent py-4 text-base outline-none placeholder:text-gray-400 lg:py-5 lg:text-lg">
                    </label>
                    <label class="flex items-center gap-2 rounded-2xl bg-canvas px-4 transition focus-within:ring-4 focus-within:ring-primary/15">
                        <x-icon name="briefcase" class="h-5 w-5 text-primary" />
                        <span class="sr-only">Kategori</span>
                        <select name="category" class="w-full cursor-pointer bg-transparent py-4 pr-2 text-base font-semibold text-ink outline-none sm:w-52 lg:w-full lg:py-5 lg:text-lg 2xl:w-52">
                            <option value="">Semua kategori</option>
                            <option value="ART">ART</option>
                            <option value="Baby Sitter">Baby Sitter</option>
                            <option value="Perawat Lansia">Perawat Lansia</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn-primary btn-lg mt-3 w-full lg:min-h-16 lg:text-lg"><x-icon name="search" class="h-5 w-5" /> Cari Pekerja <x-icon name="arrow-right" class="h-5 w-5" /></button>
            </form>
            <div data-hero-item class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-[15px] text-white/75">
                <span class="flex items-center gap-2"><x-icon name="lock" class="h-4 w-4 text-mint" /> Katalog lengkap untuk klien terdaftar</span>
                <a href="{{ route('register.pekerja') }}" class="group flex items-center gap-1.5 font-semibold text-sun hover:text-white">Ingin bekerja? Daftar di sini <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1" /></a>
            </div>
        </div>

        {{-- Foto hero + kartu melayang --}}
        <div class="relative">
            <div data-hero-parallax class="relative">
                <div class="relative aspect-[4/3.4] overflow-hidden rounded-[2.5rem] shadow-[0_40px_80px_-30px_rgba(0,0,0,0.6)] ring-4 ring-white/10 sm:aspect-[4/3] lg:aspect-auto lg:h-[min(70vh,720px)]">
                    <img data-hero-foto src="{{ asset('images/photos/hero-keluarga.webp') }}" width="1600" height="1200" fetchpriority="high"
                         alt="Keluarga Indonesia menikmati makan bersama di rumah dengan suasana hangat"
                         class="h-full w-full object-cover object-center will-change-transform">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-deep/50 via-transparent to-transparent"></div>
                </div>

                <div data-hero-kartu class="melayang-1 absolute -left-3 top-6 flex items-center gap-3 rounded-2xl bg-white p-3 pr-4 text-ink shadow-lift sm:-left-8 sm:top-10 lg:p-4 lg:pr-5">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-soft text-primary lg:h-12 lg:w-12"><x-icon name="shield-check" class="h-6 w-6" /></span>
                    <div class="leading-tight"><p class="text-[15px] font-extrabold lg:text-base">Terverifikasi admin</p><p class="text-xs text-muted lg:text-sm">KTP & sertifikat diperiksa</p></div>
                </div>
                <div data-hero-kartu class="melayang-2 absolute -right-2 top-1/2 w-52 rounded-2xl bg-white p-3.5 text-ink shadow-lift sm:-right-6 lg:w-60 lg:p-4">
                    <div class="flex items-center justify-between text-sm"><span class="flex items-center gap-1.5 font-bold"><x-icon name="sparkles" class="h-4 w-4 text-sun-dark" /> Skor kecocokan</span><span class="font-extrabold text-primary">92%</span></div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-primary-soft"><div class="skor-bar h-2 rounded-full bg-gradient-to-r from-primary-accent to-primary" style="--skor: 92%"></div></div>
                    <p class="mt-1.5 text-[11px] text-muted">Contoh tampilan rekomendasi</p>
                </div>
                <div data-hero-kartu class="melayang-3 absolute -bottom-5 left-6 flex items-center gap-3 rounded-2xl bg-sun p-3 pr-4 text-ink shadow-lift sm:left-10 lg:p-4 lg:pr-5">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/60"><x-icon name="grid" class="h-6 w-6" /></span>
                    <div class="leading-tight"><p class="text-[15px] font-extrabold">{{ $jumlahKategori }} kategori layanan</p><p class="text-xs text-ink/70">ART · Baby Sitter · Lansia</p></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris statistik dari database --}}
    <div class="relative border-t border-white/10 bg-primary-deep/40">
        <div class="container-page grid grid-cols-2 gap-6 py-8 lg:grid-cols-4 lg:py-10">
            @foreach ([
                [$jumlahPekerja ?? 0, 'Pekerja terverifikasi', 'shield-check'],
                [$jumlahKategori, 'Kategori layanan', 'grid'],
                [$jumlahKriteria, 'Kriteria khusus', 'list-checks'],
                [$jumlahPenyaluran, 'Penyaluran disetujui', 'hand-heart'],
            ] as [$angka, $label, $ikon])
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-sun lg:h-16 lg:w-16"><x-icon :name="$ikon" class="h-6 w-6 lg:h-8 lg:w-8" /></span>
                    <div>
                        <p class="text-3xl font-extrabold leading-none text-white lg:text-[2.75rem]" data-count="{{ $angka }}">{{ $angka }}</p>
                        <p class="mt-1.5 text-sm font-medium text-white/70 lg:text-base">{{ $label }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= 3. PITA BERJALAN ================= --}}
<div class="marquee-wadah overflow-hidden bg-sun py-4" aria-label="Keunggulan temukerja">
    <div class="marquee">
        @for ($ulang = 0; $ulang < 2; $ulang++)
            <ul class="flex shrink-0 items-center gap-8 pr-8 text-lg font-extrabold text-ink" @if ($ulang) aria-hidden="true" @endif>
                @foreach (['ART terverifikasi', 'Baby Sitter bersertifikat', 'Perawat Lansia berpengalaman', 'Pencocokan kriteria khusus', 'Skor kecocokan otomatis', 'Pantau status pesanan', 'Dokumen tersimpan aman'] as $teks)
                    <li class="flex shrink-0 items-center gap-8 whitespace-nowrap">{{ $teks }} <x-icon name="sparkles" class="h-5 w-5 text-primary" /></li>
                @endforeach
            </ul>
        @endfor
    </div>
</div>

{{-- ================= 4. KATEGORI LAYANAN ================= --}}
<section id="layanan" class="section scroll-mt-20 bg-white">
    <div class="container-page">
        <x-section-heading eyebrow="Layanan kami" title="Satu tempat untuk tiga kebutuhan rumah tangga"
                           lead="Pilih jenis pekerja sesuai kebutuhan keluarga Anda. Semua pekerja sudah melalui pemeriksaan data oleh admin." />

        @php
            $layanan = [
                ['ART', 'Asisten Rumah Tangga', 'Membantu pekerjaan rumah sehari-hari agar rumah tetap bersih dan nyaman.', 'layanan-art', 'Asisten rumah tangga sedang memasak di dapur yang rapi',
                    ['Bersih-bersih, mencuci, menyetrika', 'Bisa dipilih yang pandai memasak', 'Persyaratan cukup KTP'], 'bg-primary'],
                ['Baby Sitter', 'Pengasuh Anak', 'Mengasuh bayi dan anak dengan penuh perhatian dan kesabaran.', 'layanan-babysitter', 'Pengasuh menggendong bayi yang tersenyum',
                    ['Berpengalaman merawat bayi/anak', 'Wajib sertifikat pelatihan', 'Sertifikat diperiksa admin'], 'bg-sun'],
                ['Perawat Lansia', 'Pendamping Lansia', 'Mendampingi aktivitas harian dan perawatan orang tua tercinta.', 'layanan-lansia', 'Pendamping membantu lansia berjalan dengan alat bantu di rumah',
                    ['Berpengalaman merawat lansia', 'Wajib sertifikat pelatihan', 'Sertifikat diperiksa admin'], 'bg-sky-500'],
            ];
        @endphp

        <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3 2xl:gap-8" data-stagger>
            @foreach ($layanan as [$kode, $nama, $teks, $foto, $alt, $poin, $aksen])
                <article class="group flex flex-col overflow-hidden rounded-3xl border border-line bg-white shadow-soft transition duration-500 hover:-translate-y-2 hover:shadow-lift">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('images/photos/' . $foto . '.webp') }}" alt="{{ $alt }}" loading="lazy" width="800" height="533"
                             class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div>
                        <span class="absolute left-4 top-4 rounded-full {{ $aksen }} px-3 py-1 text-xs font-extrabold uppercase tracking-wider {{ $aksen === 'bg-sun' ? 'text-ink' : 'text-white' }}">{{ $kode }}</span>
                        <p class="absolute bottom-4 left-5 text-sm font-semibold text-white/90">{{ $nama }}</p>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="text-2xl font-extrabold text-ink 2xl:text-[1.75rem]">{{ $kode }}</h3>
                        <p class="mt-2 text-base text-muted">{{ $teks }}</p>
                        <ul class="mt-5 space-y-2.5">
                            @foreach ($poin as $p)
                                <li class="flex items-center gap-2.5 text-[15px] font-medium text-ink">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary"><x-icon name="check" class="h-4 w-4" stroke="3" /></span>{{ $p }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('workers.index', ['category' => $kode]) }}" class="btn-primary mt-6 w-full">Lihat Pekerja <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= 5. UNTUK KELUARGA / CALON PEKERJA ================= --}}
<section id="tentang-kami" class="section scroll-mt-20 overflow-hidden bg-mint-soft">
    <div class="container-page">
        <x-section-heading eyebrow="Tentang temukerja" title="Dibuat untuk keluarga dan calon pekerja" />

        @php
            $panel = [
                ['untuk-keluarga', 'Ibu memeluk anaknya dengan gembira di rumah', 'Untuk Keluarga', 'Cari pekerja yang benar-benar cocok dengan rumah Anda',
                    'Tidak perlu lagi khawatir pekerja tidak betah karena kebiasaan rumah yang berbeda. Sampaikan kebutuhan khusus Anda sejak awal.',
                    [['list-checks', 'Pilih kriteria khusus', 'Hewan peliharaan, rumah bertingkat, bersedia menginap, dan lainnya.'],
                     ['bell', 'Pantau status pesanan', 'Setiap perubahan status dikabarkan lewat notifikasi.']],
                    [route('register.klien'), 'Daftar sebagai Klien'], [route('workers.index'), 'Cari Pekerja'], 'house'],
                ['untuk-pekerja', 'Perempuan tersenyum menggunakan laptop di ruangan penuh tanaman, siap mendaftar sebagai pekerja', 'Untuk Calon Pekerja', 'Tampilkan keahlian Anda kepada keluarga yang membutuhkan',
                    'Daftar gratis, lengkapi profil dan dokumen, lalu profil Anda tampil di katalog setelah diverifikasi admin yayasan.',
                    [['id-card', 'Profil profesional', 'Pengalaman, keahlian, dan kriteria Anda tampil rapi di katalog.'],
                     ['shield-check', 'Diverifikasi admin', 'Dokumen diperiksa sehingga klien lebih percaya kepada Anda.'],
                     ['bell', 'Kabar langsung', 'Hasil verifikasi dan pesanan dikabarkan lewat notifikasi.']],
                    [route('register.pekerja'), 'Daftar sebagai Pekerja'], [route('login'), 'Sudah punya akun? Masuk'], 'briefcase'],
            ];
        @endphp

        <div class="mt-14 space-y-16 lg:space-y-24">
            @foreach ($panel as $i => [$foto, $alt, $label, $judul, $teks, $manfaat, $aksi1, $aksi2, $ikonLabel])
                @php $balik = $i % 2 === 1; @endphp
                <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                    <div class="relative {{ $balik ? 'lg:order-2' : '' }}" data-masuk="{{ $balik ? 'kanan' : 'kiri' }}">
                        <div class="absolute -inset-3 rounded-[2.5rem] {{ $balik ? 'bg-sun/30 -rotate-2' : 'bg-primary/15 rotate-2' }}" aria-hidden="true"></div>
                        <img src="{{ asset('images/photos/' . $foto . '.webp') }}" alt="{{ $alt }}" loading="lazy" width="1000" height="667"
                             class="relative aspect-[4/3] w-full rounded-[2rem] object-cover shadow-lift">
                        <span class="absolute -bottom-4 {{ $balik ? 'left-6' : 'right-6' }} flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-[15px] font-extrabold text-ink shadow-lift">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white"><x-icon :name="$ikonLabel" class="h-4 w-4" /></span>{{ $label }}
                        </span>
                    </div>
                    <div data-masuk="{{ $balik ? 'kiri' : 'kanan' }}">
                        <p class="eyebrow"><span class="h-1.5 w-6 rounded-full bg-primary-accent"></span>{{ $label }}</p>
                        <h3 class="mt-3 text-3xl font-extrabold leading-tight text-ink sm:text-[2.1rem]">{{ $judul }}</h3>
                        <p class="mt-4 text-lg text-muted">{{ $teks }}</p>
                        <ul class="mt-8 space-y-5">
                            @foreach ($manfaat as [$ikon, $j, $t])
                                <li class="group flex gap-4">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary text-white shadow-glow transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110"><x-icon :name="$ikon" class="h-6 w-6" /></span>
                                    <div>
                                        <p class="text-lg font-bold text-ink">{{ $j }}</p>
                                        <p class="text-base text-muted">{{ $t }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $aksi1[0] }}" class="btn-primary btn-lg">{{ $aksi1[1] }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                            <a href="{{ $aksi2[0] }}" class="btn-secondary btn-lg">{{ $aksi2[1] }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= 6. CARA KERJA (timeline mengikuti scroll) ================= --}}
<section id="cara-pemesanan" class="section scroll-mt-20 bg-white">
    <div class="container-page">
        <x-section-heading eyebrow="Cara kerja" title="Empat langkah, dari daftar sampai pekerja datang"
                           lead="Prosesnya jelas dan bisa dipantau setiap saat." />

        @php
            $langkah = [
                ['user-plus', 'Daftar akun', 'Buat akun sebagai klien atau calon pekerja dalam beberapa menit.'],
                ['shield-check', 'Lengkapi & verifikasi', 'Pekerja mengisi profil, kriteria khusus, dan dokumen, lalu admin memeriksanya.'],
                ['search', 'Cari & cocokkan', 'Pilih kriteria rumah Anda, sistem menampilkan skor kecocokan setiap pekerja.'],
                ['clipboard', 'Pesan pekerja', 'Ajukan pesanan dan pantau statusnya sampai disetujui admin.'],
            ];
        @endphp

        <div class="relative mt-14" data-timeline>
            <div class="pointer-events-none absolute left-[12.5%] right-[12.5%] top-10 hidden h-1 rounded-full bg-line lg:block" aria-hidden="true">
                <div data-timeline-garis="x" class="h-full origin-left rounded-full bg-gradient-to-r from-primary-accent via-sun to-primary"></div>
            </div>
            <div class="pointer-events-none absolute bottom-10 left-10 top-10 w-1 rounded-full bg-line lg:hidden" aria-hidden="true">
                <div data-timeline-garis="y" class="h-full w-full origin-top rounded-full bg-gradient-to-b from-primary-accent via-sun to-primary"></div>
            </div>
            <ol class="relative grid gap-8 lg:grid-cols-4 lg:gap-6">
                @foreach ($langkah as $i => [$ikon, $judul, $teks])
                    <li data-langkah class="group relative flex gap-5 lg:flex-col lg:items-center lg:text-center">
                        <div class="relative z-10 flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-primary text-white shadow-glow ring-8 ring-white transition-transform duration-300 group-hover:-translate-y-1 group-hover:rotate-3">
                            <x-icon :name="$ikon" class="h-9 w-9" />
                            <span class="absolute -right-2 -top-2 flex h-9 w-9 items-center justify-center rounded-full bg-sun text-base font-extrabold text-ink ring-4 ring-white">{{ $i + 1 }}</span>
                        </div>
                        <div class="rounded-3xl bg-canvas p-5 transition-colors duration-300 group-hover:bg-primary-soft lg:mt-6 lg:w-full">
                            <h3 class="text-xl font-extrabold text-ink">{{ $judul }}</h3>
                            <p class="mt-2 text-base text-muted">{{ $teks }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

{{-- ================= 7. KENAPA TEMUKERJA ================= --}}
<section id="keunggulan" class="section scroll-mt-20 bg-primary-darker text-white">
    <div class="container-page">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
            <x-section-heading eyebrow="Kenapa temukerja?" title="Mengurangi ketidakcocokan sejak awal" align="left" :light="true" class="lg:col-span-7" />
            <p class="reveal text-lg text-white/75 lg:col-span-5">
                PT Shaka Abadi Nusantara berupaya menekan pengembalian pekerja akibat ketidakcocokan. Karena itu setiap profil memuat kriteria khusus yang bisa dicocokkan.
            </p>
        </div>

        @php
            $keunggulan = [
                ['shield-check', 'Pekerja terverifikasi admin', 'KTP dan sertifikat pelatihan diperiksa sebelum pekerja tampil di katalog.', 'bg-primary-accent'],
                ['paw', 'Kriteria khusus', 'Nyaman dengan hewan peliharaan, tidak mudah mabuk darat, bersedia menginap, dan lainnya.', 'bg-sun'],
                ['sparkles', 'Skor kecocokan', 'Rekomendasi diurutkan dari skor kecocokan kriteria Anda dengan profil pekerja.', 'bg-[#F07F5C]'],
                ['lock', 'Dokumen tersimpan aman', 'Dokumen hanya dapat dibuka oleh pemiliknya dan admin yayasan.', 'bg-sky-400'],
                ['clipboard', 'Pemesanan jelas', 'Ajukan pesanan lengkap dengan tanggal mulai dan kebutuhan khusus Anda.', 'bg-violet-400'],
                ['bell', 'Pantau status pesanan', 'Riwayat status dan notifikasi tersedia dari pesanan masuk sampai selesai.', 'bg-mint'],
            ];
        @endphp
        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 2xl:gap-7" data-stagger>
            @foreach ($keunggulan as [$ikon, $judul, $teks, $warna])
                <div class="group rounded-3xl bg-white/[0.06] p-6 ring-1 ring-white/10 transition duration-300 hover:-translate-y-1.5 hover:bg-white/10 hover:ring-white/25">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $warna }} text-ink transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6"><x-icon :name="$ikon" class="h-7 w-7" /></span>
                    <h3 class="mt-5 text-xl font-bold text-white">{{ $judul }}</h3>
                    <p class="mt-2 text-base text-white/70">{{ $teks }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= 8. SOROTAN PENCOCOKAN ================= --}}
<section class="section overflow-hidden bg-mint-soft">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div data-masuk="kiri">
            <p class="eyebrow"><span class="h-1.5 w-6 rounded-full bg-primary-accent"></span>Fitur pencocokan</p>
            <h2 class="section-title mt-3">Kebutuhan Anda dicocokkan dengan profil pekerja</h2>
            <p class="section-lead mt-4">Anda memilih kriteria yang penting bagi rumah Anda. Sistem membandingkannya dengan kriteria setiap pekerja memakai metode Cosine Similarity, lalu menampilkan skor kecocokan dalam persen.</p>
            <ol class="mt-8 space-y-4">
                @foreach (['Pilih kriteria khusus yang Anda butuhkan.', 'Sistem menghitung kemiripan kriteria Anda dengan setiap pekerja.', 'Pekerja dengan skor tertinggi tampil paling atas.'] as $i => $t)
                    <li class="flex items-center gap-4 text-lg font-medium text-ink">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary text-base font-extrabold text-white">{{ $i + 1 }}</span>{{ $t }}
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('workers.index') }}" class="btn-primary btn-lg mt-8">Coba Cari Pekerja <x-icon name="arrow-right" class="h-5 w-5" /></a>
        </div>

        {{-- Visual contoh (ilustrasi tampilan, bukan data pekerja sungguhan) --}}
        <div class="relative" data-masuk="kanan">
            <div class="absolute -inset-4 rotate-2 rounded-[2.5rem] bg-primary/10" aria-hidden="true"></div>
            <div class="relative rounded-[2rem] bg-white p-6 shadow-lift sm:p-8">
                <p class="text-xs font-bold uppercase tracking-wider text-muted">Kriteria yang Anda pilih</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($contohKriteria->take(4) as $k)
                        <span class="chip chip-on cursor-default"><x-icon name="check" class="h-4 w-4" /> {{ $k }}</span>
                    @endforeach
                </div>
                <div class="my-6 flex items-center gap-3 text-sm font-semibold text-primary">
                    <span class="h-px flex-1 bg-line"></span><x-icon name="sparkles" class="h-5 w-5" /> Hasil pencocokan<span class="h-px flex-1 bg-line"></span>
                </div>
                @foreach ([['Contoh Pekerja A', 92], ['Contoh Pekerja B', 71], ['Contoh Pekerja C', 45]] as $i => [$nama, $skor])
                    <div class="flex items-center gap-4 rounded-2xl {{ $i === 0 ? 'bg-primary-soft ring-2 ring-primary/20' : 'bg-canvas' }} p-4 {{ $i ? 'mt-3' : '' }}">
                        <x-avatar :name="$nama" size="md" />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate font-bold text-ink">{{ $nama }}</p>
                                <span class="text-base font-extrabold text-primary">{{ $skor }}%</span>
                            </div>
                            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white">
                                <div class="skor-bar h-2.5 rounded-full {{ $i === 0 ? 'bg-primary' : 'bg-primary-accent/70' }}" style="--skor: {{ $skor }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <p class="mt-4 text-center text-xs text-muted">Gambaran tampilan. Skor sebenarnya dihitung dari data pekerja di katalog.</p>
            </div>
        </div>
    </div>
</section>

{{-- ================= 9. GALERI SUASANA LAYANAN (Swiper) ================= --}}
<section class="section overflow-hidden bg-white">
    <div class="container-page">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <x-section-heading eyebrow="Suasana layanan" title="Bantuan yang membuat rumah terasa lebih ringan" align="left" />
            <p class="reveal max-w-sm text-base text-muted">Geser untuk melihat ragam pekerjaan rumah tangga yang dapat dibantu oleh pekerja temukerja.</p>
        </div>
        @php
            $galeri = [
                ['galeri-membersihkan', 'Mengepel lantai ruang tamu hingga bersih', 'Kebersihan rumah'],
                ['galeri-pengasuh', 'Pengasuh menggendong bayi dengan penuh kasih', 'Pengasuhan anak'],
                ['galeri-memasak', 'Menyiapkan masakan rumahan di dapur', 'Memasak'],
                ['galeri-pendamping-lansia', 'Pendamping lansia mengobrol dengan ramah', 'Pendampingan lansia'],
                ['galeri-keluarga-makan', 'Keluarga berkumpul di meja makan', 'Waktu keluarga'],
                ['galeri-lansia-di-rumah', 'Dua lansia bersantai di ruang tamu', 'Lansia di rumah'],
                ['galeri-merapikan', 'Merapikan ruang keluarga yang nyaman', 'Rumah rapi'],
                ['galeri-pasangan', 'Pasangan muda tersenyum melihat ponsel di rumah', 'Pesan dari rumah'],
            ];
        @endphp
        <div class="swiper mt-10 !overflow-visible" data-galeri data-masuk="bawah">
            <div class="swiper-wrapper">
                @foreach ($galeri as [$foto, $alt, $label])
                    <figure class="swiper-slide group relative overflow-hidden rounded-3xl">
                        <img src="{{ asset('images/photos/' . $foto . '.webp') }}" alt="{{ $alt }}" loading="lazy" width="900" height="600"
                             class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-105">
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/75 to-transparent p-5 pt-12 text-white">
                            <span class="text-lg font-extrabold">{{ $label }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- ================= PEKERJA BERPENGALAMAN (data asli, tanpa foto stok) ================= --}}
@if ($featuredWorkers->isNotEmpty())
<section class="section bg-canvas">
    <div class="container-page">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <x-section-heading eyebrow="Pekerja berpengalaman" title="Siap membantu keluarga Anda" align="left" />
            <a href="{{ route('workers.index') }}" class="reveal btn-outline shrink-0">Lihat semua pekerja <x-icon name="arrow-right" class="h-5 w-5" /></a>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:gap-8" data-stagger>
            @foreach ($featuredWorkers as $worker)
                <div class="card card-hover rounded-3xl p-6">
                    <div class="flex items-center gap-4">
                        <x-avatar :name="$worker->name" size="lg" />
                        <div class="min-w-0">
                            <p class="truncate text-lg font-bold text-ink">{{ $worker->name }}</p>
                            <x-badge color="brand" :dot="false">{{ $worker->category }}</x-badge>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-2.5 text-[15px] text-muted">
                        <p class="flex items-center gap-2.5"><x-icon name="map-pin" class="h-5 w-5 text-primary" /> {{ $worker->location }}</p>
                        <p class="flex items-center gap-2.5"><x-icon name="briefcase" class="h-5 w-5 text-primary" /> {{ $worker->experience }} tahun pengalaman</p>
                        <p class="flex items-center gap-2.5 font-semibold text-green-700"><x-icon name="shield-check" class="h-5 w-5" /> Terverifikasi admin</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= 10. FAQ ================= --}}
<section id="faq" class="section scroll-mt-20 bg-white">
    <div class="container-page grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-section-heading eyebrow="Pertanyaan umum" title="Masih ada yang ingin ditanyakan?" align="left"
                               lead="Jawaban singkat untuk pertanyaan yang paling sering diajukan." />
            <div class="reveal mt-8 rounded-3xl bg-primary p-6 text-white">
                <x-icon name="message" class="h-8 w-8 text-sun" />
                <p class="mt-3 text-lg font-bold">Butuh bantuan lain?</p>
                <p class="mt-1 text-white/75">Hubungi admin yayasan melalui kontak di bagian bawah halaman.</p>
                <a href="#hubungi-kami" class="btn-sun btn-sm mt-4">Lihat kontak <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </div>

        @php
            $faq = [
                ['Bagaimana cara mendaftar?', 'Klik "Daftar Sekarang", pilih akun Klien (pencari pekerja) atau Calon Pekerja, lalu isi formulir pendaftaran. Calon pekerja juga memilih jenis pekerjaan: ART, Baby Sitter, atau Perawat Lansia.'],
                ['Dokumen apa saja yang diperlukan calon pekerja?', 'Semua calon pekerja mengunggah KTP. Untuk ART cukup KTP, sedangkan Baby Sitter dan Perawat Lansia juga wajib mengunggah sertifikat pelatihan.'],
                ['Mengapa Baby Sitter dan Perawat Lansia wajib punya sertifikat?', 'Pekerjaan mengasuh anak dan merawat lansia membutuhkan keterampilan khusus. Sertifikat pelatihan diperiksa admin agar keluarga mendapat pekerja yang kompeten.'],
                ['Bagaimana cara memesan pekerja?', 'Masuk sebagai klien, buka katalog, pilih kriteria yang Anda butuhkan, lalu buka profil pekerja dan tekan "Pesan Pekerja". Status pesanan dapat dipantau di menu Riwayat Pesanan.'],
                ['Berapa lama proses verifikasi?', 'Setelah profil dan dokumen lengkap, calon pekerja mengajukan verifikasi. Admin memeriksa data dan dokumen, lalu hasilnya dikabarkan lewat notifikasi.'],
                ['Bagaimana dengan biaya dan kontak?', 'Informasi biaya dan kesepakatan kerja disampaikan langsung oleh admin PT Shaka Abadi Nusantara. Silakan hubungi kontak kantor kami di bagian bawah halaman.'],
            ];
        @endphp
        <div class="space-y-3 lg:col-span-8" data-stagger>
            @foreach ($faq as $i => [$tanya, $jawab])
                <details class="group rounded-3xl bg-canvas ring-1 ring-line transition-colors duration-300 open:bg-white open:shadow-soft open:ring-primary/30" @if ($i === 0) open @endif>
                    <summary class="flex cursor-pointer items-center justify-between gap-4 p-5 text-left text-lg font-bold text-ink sm:p-6">
                        {{ $tanya }}
                        <span class="faq-ikon flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-primary shadow-sm transition duration-300"><x-icon name="plus" class="h-5 w-5" /></span>
                    </summary>
                    <div class="faq-isi"><div><p class="px-5 pb-6 text-base leading-relaxed text-muted sm:px-6">{{ $jawab }}</p></div></div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= 11. CTA (foto + overlay + parallax) ================= --}}
<section class="relative overflow-hidden py-20 lg:py-28">
    <img data-parallax="10" src="{{ asset('images/photos/ruang-keluarga.webp') }}" alt="" loading="lazy" width="1440" height="810"
         class="absolute inset-x-0 -top-[12%] h-[124%] w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-primary-deep/95 via-primary-dark/90 to-primary/75"></div>
    <div class="container-page relative grid items-center gap-10 lg:grid-cols-12">
        <div class="lg:col-span-8" data-masuk="kiri">
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">Siap memulai bersama temukerja?</h2>
            <p class="mt-4 max-w-2xl text-lg text-white/85">Cari pekerja yang cocok untuk keluarga Anda, atau daftarkan diri Anda sebagai pekerja dan tampil di katalog setelah diverifikasi.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row lg:col-span-4 lg:flex-col" data-masuk="kanan">
            <a href="{{ route('workers.index') }}" class="btn-sun btn-lg w-full"><x-icon name="search" class="h-5 w-5" /> Cari Pekerja <x-icon name="arrow-right" class="h-5 w-5" /></a>
            <a href="{{ route('register.pekerja') }}" class="btn-outline-light btn-lg w-full"><x-icon name="user-plus" class="h-5 w-5" /> Daftar sebagai Pekerja</a>
        </div>
    </div>
</section>

@endsection
