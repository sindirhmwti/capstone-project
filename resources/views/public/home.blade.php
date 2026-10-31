@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')

{{-- =========================================================
     SECTION 1: HERO
     ========================================================= --}}
<section class="py-20 lg:py-28" style="background: linear-gradient(135deg, #E4F2E8 0%, #ffffff 60%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

            {{-- Left side (60%) --}}
            <div class="w-full lg:w-3/5 flex flex-col gap-6">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-[#E4F2E8] border border-[#20A66A] text-[#176B4D] text-sm font-semibold px-4 py-2 rounded-full w-fit">
                    <span>🌿</span>
                    <span>Platform Terpercaya #1</span>
                </div>

                {{-- Heading --}}
                <h1 class="text-4xl lg:text-6xl font-bold text-[#172B23] leading-tight">
                    Temukan Pekerja yang Tepat untuk Kebutuhan Anda
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg text-[#52645B] leading-relaxed max-w-xl">
                    Cari ART, Baby Sitter, atau Perawat Lansia sesuai kebutuhan keluarga Anda. Tersedia pekerja terlatih &amp; terverifikasi.
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('workers.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl p-3 flex flex-col sm:flex-row gap-3 border border-gray-100">
                    <div class="flex items-center gap-2 flex-1 px-3 border-b sm:border-b-0 sm:border-r border-gray-200 pb-3 sm:pb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#52645B] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" name="search" placeholder="Cari nama atau lokasi..."
                            class="flex-1 outline-none text-[#172B23] placeholder-gray-400 text-sm bg-transparent" />
                    </div>
                    <div class="flex items-center gap-2 px-3 border-b sm:border-b-0 sm:border-r border-gray-200 pb-3 sm:pb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#52645B] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                        <select name="category" class="outline-none text-[#172B23] text-sm bg-transparent pr-2 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            <option value="ART">ART</option>
                            <option value="Baby Sitter">Baby Sitter</option>
                            <option value="Perawat Lansia">Perawat Lansia</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-[#176B4D] hover:bg-[#104C38] text-white font-semibold px-6 py-3 rounded-xl transition-colors duration-200 text-sm whitespace-nowrap flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        Cari Pekerja
                    </button>
                </form>

                {{-- Stat Badges --}}
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm">
                        <div class="w-2 h-2 rounded-full bg-[#20A66A]"></div>
                        <span class="text-sm font-medium text-[#172B23]">{{ $jumlahPekerja ?? 0 }} Pekerja Terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm">
                        <span class="text-[#176B4D] text-sm">✓</span>
                        <span class="text-sm font-medium text-[#172B23]">Pencocokan Kriteria Khusus</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-sm font-medium text-[#172B23]">100% Terverifikasi</span>
                    </div>
                </div>
            </div>

            {{-- Right side (40%) — Decorative Card --}}
            <div class="w-full lg:w-2/5 flex justify-center">
                <div class="relative w-72 h-96">

                    {{-- Main Card --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-[#176B4D] to-[#104C38] rounded-3xl shadow-2xl flex flex-col items-center justify-center gap-4 p-6">

                        {{-- Avatar Circle --}}
                        <div class="w-24 h-24 rounded-full bg-white/20 border-4 border-white/40 flex items-center justify-center overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        {{-- Worker Info --}}
                        <div class="text-center">
                            <h3 class="text-white font-bold text-xl">Siti Rahayu</h3>
                            <span class="text-[#20A66A] bg-white/10 text-xs font-semibold px-3 py-1 rounded-full mt-1 inline-block">ART</span>
                        </div>

                        {{-- Rating Stars --}}
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-400 text-lg">★</span>
                            <span class="text-yellow-400 text-lg">★</span>
                            <span class="text-yellow-400 text-lg">★</span>
                            <span class="text-yellow-400 text-lg">★</span>
                            <span class="text-yellow-400 text-lg">★</span>
                            <span class="text-white/80 text-sm ml-1">5.0</span>
                        </div>

                        {{-- Available Badge --}}
                        <div class="flex items-center gap-2 bg-[#20A66A]/30 border border-[#20A66A]/50 rounded-full px-4 py-1.5">
                            <div class="w-2 h-2 rounded-full bg-[#20A66A] animate-pulse"></div>
                            <span class="text-white text-xs font-semibold">Tersedia</span>
                        </div>
                    </div>

                    {{-- Floating Mini Card: Rating --}}
                    <div class="absolute -top-4 -right-6 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-2 z-10">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <span class="text-yellow-500 text-sm">★</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 leading-none">Rating</p>
                            <p class="text-sm font-bold text-[#172B23]">4.9 / 5.0</p>
                        </div>
                    </div>

                    {{-- Floating Mini Card: Workers --}}
                    <div class="absolute -bottom-4 -left-6 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-2 z-10">
                        <div class="w-8 h-8 rounded-full bg-[#E4F2E8] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0zM6 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 leading-none">Pekerja</p>
                            <p class="text-sm font-bold text-[#172B23]">{{ $jumlahPekerja ?? 0 }} Aktif</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- =========================================================
     SECTION 2: CATEGORIES
     ========================================================= --}}
<section id="layanan" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#172B23] mb-3">
                Apa yang Anda Butuhkan?
            </h2>
            <div class="w-16 h-1 bg-[#20A66A] rounded-full mx-auto mb-4"></div>
            <p class="text-[#52645B] text-lg">Pilih kategori pekerja sesuai kebutuhan keluarga Anda</p>
        </div>

        {{-- Cards Grid --}}
        <div class="grid md:grid-cols-3 gap-8">

            {{-- Card 1: ART --}}
            <a href="{{ route('workers.index', ['category' => 'ART']) }}"
                class="group bg-white border border-gray-100 rounded-2xl p-8 hover:shadow-xl hover:border-[#176B4D] transition-all duration-300 cursor-pointer flex flex-col gap-5">
                <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center group-hover:bg-[#176B4D] transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D] group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl font-bold text-[#172B23]">Asisten Rumah Tangga</h3>
                    <p class="text-[#52645B] text-sm leading-relaxed">
                        Tenaga profesional untuk kebersihan, memasak, dan perawatan rumah Anda sehari-hari.
                    </p>
                </div>
                <div class="mt-auto flex items-center gap-2 text-[#176B4D] font-semibold text-sm group-hover:gap-3 transition-all duration-200">
                    <span>Lihat Pekerja</span>
                    <span>→</span>
                </div>
            </a>

            {{-- Card 2: Baby Sitter --}}
            <a href="{{ route('workers.index', ['category' => 'Baby Sitter']) }}"
                class="group bg-white border border-gray-100 rounded-2xl p-8 hover:shadow-xl hover:border-[#176B4D] transition-all duration-300 cursor-pointer flex flex-col gap-5">
                <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center group-hover:bg-[#176B4D] transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D] group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl font-bold text-[#172B23]">Baby Sitter</h3>
                    <p class="text-[#52645B] text-sm leading-relaxed">
                        Pengasuh anak berpengalaman yang sabar, terlatih, dan terpercaya untuk buah hati Anda.
                    </p>
                </div>
                <div class="mt-auto flex items-center gap-2 text-[#176B4D] font-semibold text-sm group-hover:gap-3 transition-all duration-200">
                    <span>Lihat Pekerja</span>
                    <span>→</span>
                </div>
            </a>

            {{-- Card 3: Perawat Lansia --}}
            <a href="{{ route('workers.index', ['category' => 'Perawat Lansia']) }}"
                class="group bg-white border border-gray-100 rounded-2xl p-8 hover:shadow-xl hover:border-[#176B4D] transition-all duration-300 cursor-pointer flex flex-col gap-5">
                <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center group-hover:bg-[#176B4D] transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D] group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl font-bold text-[#172B23]">Perawat Lansia</h3>
                    <p class="text-[#52645B] text-sm leading-relaxed">
                        Tenaga perawat lansia yang kompeten untuk mendampingi orang tua Anda dengan penuh kasih.
                    </p>
                </div>
                <div class="mt-auto flex items-center gap-2 text-[#176B4D] font-semibold text-sm group-hover:gap-3 transition-all duration-200">
                    <span>Lihat Pekerja</span>
                    <span>→</span>
                </div>
            </a>

        </div>
    </div>
</section>

{{-- =========================================================
     SECTION 3: KEUNGGULAN
     ========================================================= --}}
<section id="tentang-kami" class="py-16 bg-[#F5F7F5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#172B23] mb-3">
                Mengapa Memilih Temukerja?
            </h2>
            <div class="w-16 h-1 bg-[#20A66A] rounded-full mx-auto mb-4"></div>
            <p class="text-[#52645B] text-lg max-w-xl mx-auto">
                Kami menghadirkan solusi terpercaya untuk mempertemukan keluarga dengan pekerja profesional.
            </p>
        </div>

        {{-- 6-Card Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

            {{-- 1: Terlatih & Kompeten --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Terlatih &amp; Kompeten</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Setiap pekerja telah melalui pelatihan profesional sesuai bidangnya.</p>
            </div>

            {{-- 2: Proses Verifikasi --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Proses Verifikasi</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Identitas dan latar belakang pekerja telah diverifikasi secara ketat.</p>
            </div>

            {{-- 3: Informasi Transparan --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Informasi Transparan</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Profil lengkap dan jujur tersedia untuk memudahkan pilihan Anda.</p>
            </div>

            {{-- 4: Rekomendasi Tepat --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Rekomendasi Tepat</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Sistem kami merekomendasikan pekerja terbaik sesuai kebutuhan Anda.</p>
            </div>

            {{-- 5: Pilihan Profesi --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Pilihan Profesi</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Tersedia 3 kategori profesi untuk memenuhi beragam kebutuhan keluarga.</p>
            </div>

            {{-- 6: Pemesanan Terstruktur --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#172B23] mb-2">Pemesanan Terstruktur</h4>
                <p class="text-[#52645B] text-sm leading-relaxed">Proses pemesanan yang rapi, mudah dipantau dari awal hingga selesai.</p>
            </div>

        </div>
    </div>
</section>

{{-- =========================================================
     SECTION 4: CARA PEMESANAN
     ========================================================= --}}
<section id="cara-pemesanan" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#172B23] mb-3">Cara Pemesanan</h2>
            <div class="w-16 h-1 bg-[#20A66A] rounded-full mx-auto mb-4"></div>
            <p class="text-[#52645B] text-lg max-w-xl mx-auto">Proses pemesanan mudah hanya dalam 3 langkah sederhana</p>
        </div>

        {{-- Steps --}}
        <div class="relative">
            {{-- Dashed connector line (desktop only) --}}
            <div class="hidden md:block absolute top-10 left-[16.66%] right-[16.66%] h-px border-t-2 border-dashed border-[#20A66A]/40 z-0"></div>

            <div class="grid md:grid-cols-3 gap-10 relative z-10">

                {{-- Step 1 --}}
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-[#176B4D] text-white flex items-center justify-center shadow-lg shadow-[#176B4D]/30 flex-shrink-0">
                        <div class="flex flex-col items-center leading-none">
                            <span class="text-xs font-semibold opacity-70">Step</span>
                            <span class="text-2xl font-black">01</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-[#172B23] mb-2">Cari Pekerja</h4>
                        <p class="text-[#52645B] text-sm leading-relaxed">
                            Gunakan fitur pencarian untuk menemukan pekerja sesuai kategori dan lokasi.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-[#176B4D] text-white flex items-center justify-center shadow-lg shadow-[#176B4D]/30 flex-shrink-0">
                        <div class="flex flex-col items-center leading-none">
                            <span class="text-xs font-semibold opacity-70">Step</span>
                            <span class="text-2xl font-black">02</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-[#172B23] mb-2">Pilih &amp; Ajukan Pemesanan</h4>
                        <p class="text-[#52645B] text-sm leading-relaxed">
                            Lihat profil pekerja, pilih yang cocok, dan ajukan pemesanan langsung dari platform.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-[#176B4D] text-white flex items-center justify-center shadow-lg shadow-[#176B4D]/30 flex-shrink-0">
                        <div class="flex flex-col items-center leading-none">
                            <span class="text-xs font-semibold opacity-70">Step</span>
                            <span class="text-2xl font-black">03</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-[#E4F2E8] rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-[#172B23] mb-2">Pantau Status Pesanan</h4>
                        <p class="text-[#52645B] text-sm leading-relaxed">
                            Lacak status pesanan Anda secara real-time melalui dashboard.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     SECTION 5: FEATURED WORKERS
     ========================================================= --}}
<section id="pekerja-unggulan" class="py-16 bg-[#F5F7F5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#172B23] mb-3">Pekerja Unggulan Kami</h2>
            <div class="w-16 h-1 bg-[#20A66A] rounded-full mx-auto mb-4"></div>
            <p class="text-[#52645B] text-lg">Temukan pekerja terbaik yang siap membantu keluarga Anda</p>
        </div>

        @if(isset($featuredWorkers) && $featuredWorkers->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($featuredWorkers as $worker)

            {{-- Determine category color --}}
            @php
                $categoryColors = [
                    'ART'            => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500'],
                    'Baby Sitter'    => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'dot' => 'bg-blue-500'],
                    'Perawat Lansia' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'dot' => 'bg-purple-500'],
                ];
                $color = $categoryColors[$worker->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-400'];

                // Generate initials from name
                $nameParts = explode(' ', trim($worker->name));
                $initials = '';
                foreach (array_slice($nameParts, 0, 2) as $part) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
            @endphp

            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col gap-4 group">

                {{-- Avatar --}}
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center flex-shrink-0 shadow-md">
                        <span class="text-white font-bold text-lg">{{ $initials }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-[#172B23] truncate">{{ $worker->name }}</h4>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $color['bg'] }} {{ $color['text'] }} mt-1">
                            <span class="w-1.5 h-1.5 rounded-full {{ $color['dot'] }}"></span>
                            {{ $worker->category }}
                        </span>
                    </div>
                </div>

                {{-- Pengalaman --}}
                <p class="text-sm text-[#52645B] font-medium">{{ $worker->experience }} tahun pengalaman</p>

                {{-- Location --}}
                @if($worker->location)
                <div class="flex items-center gap-1.5 text-[#52645B] text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $worker->location }}</span>
                </div>
                @endif

                {{-- Button --}}
                <a href="{{ route('workers.index') }}"
                    class="mt-auto block text-center bg-[#E4F2E8] hover:bg-[#176B4D] text-[#176B4D] hover:text-white font-semibold text-sm py-2.5 rounded-xl transition-all duration-200">
                    Lihat Katalog
                </a>

            </div>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="text-center mt-10">
            <a href="{{ route('workers.index') }}"
                class="inline-flex items-center gap-2 bg-[#176B4D] hover:bg-[#104C38] text-white font-semibold px-8 py-3.5 rounded-xl transition-colors duration-200 shadow-lg shadow-[#176B4D]/20">
                Lihat Semua Pekerja
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        @else
        {{-- Empty State --}}
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-[#E4F2E8] rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-[#172B23] mb-2">Belum Ada Pekerja Tersedia</h3>
            <p class="text-[#52645B]">Pekerja unggulan akan segera ditampilkan di sini.</p>
        </div>
        @endif

    </div>
</section>

{{-- =========================================================
     SECTION 6: CTA
     ========================================================= --}}
<section class="py-20" style="background: linear-gradient(135deg, #176B4D 0%, #104C38 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        {{-- Decorative top icon --}}
        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
        </div>

        <h2 class="text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
            Siap Menemukan Pekerja<br>Terpercaya?
        </h2>
        <p class="text-white/80 text-lg mb-10 max-w-xl mx-auto">
            Bergabunglah dengan ribuan keluarga yang telah mempercayai Temukerja untuk menemukan pekerja profesional terbaik.
        </p>

        <div class="flex gap-4 justify-center flex-wrap">
            <a href="{{ route('workers.index') }}"
                class="bg-white text-[#176B4D] font-bold px-8 py-4 rounded-xl hover:bg-[#E4F2E8] transition-colors duration-200 shadow-xl shadow-black/20 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                Cari Pekerja Sekarang
            </a>
            <a href="{{ route('register') }}"
                class="border-2 border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white/10 transition-colors duration-200 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Daftar Gratis
            </a>
        </div>

        {{-- Trust indicators --}}
        <div class="flex flex-wrap justify-center gap-6 mt-12">
            <div class="flex items-center gap-2 text-white/70 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Gratis Mendaftar
            </div>
            <div class="flex items-center gap-2 text-white/70 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Pekerja Terverifikasi
            </div>
            <div class="flex items-center gap-2 text-white/70 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Tanpa Biaya Tersembunyi
            </div>
        </div>

    </div>
</section>

@endsection
