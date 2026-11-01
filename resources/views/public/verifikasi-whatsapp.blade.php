@extends('layouts.auth')
@section('title', 'Verifikasi Nomor WhatsApp')
@section('lebar', 'max-w-lg')
@section('panel-judul', 'Satu langkah lagi untuk bergabung.')
@section('panel-teks', 'Verifikasi nomor WhatsApp Anda agar akun aman dan admin mudah menghubungi Anda.')

@section('content')
<div data-otp
     data-url-verifikasi="{{ route('register.verifikasi.post') }}"
     data-url-kirim-ulang="{{ route('register.verifikasi.kirim-ulang') }}"
     data-sisa-kirim="{{ $sisaKirimUlang }}"
     data-sisa-kunci="{{ $sisaKunci }}"
     data-lama-kunci="{{ config('otp.lama_kunci_detik', 60) }}"
     class="relative">

    @include('components.langkah-daftar', ['aktif' => 3])

    <div class="animate-fade-up rounded-3xl border border-line bg-white p-6 text-center shadow-soft sm:p-8">
        {{-- Ikon chat WhatsApp --}}
        <div class="otp-pantul mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#25D366]/10 text-[#1DA851]">
            <svg viewBox="0 0 24 24" class="h-11 w-11" fill="currentColor" aria-hidden="true">
                <path d="M12.04 2a9.9 9.9 0 0 0-8.5 15l-1.4 5 5.2-1.36A9.9 9.9 0 1 0 12.04 2Zm0 18.1a8.2 8.2 0 0 1-4.2-1.15l-.3-.18-3.08.8.82-3-.2-.31a8.2 8.2 0 1 1 6.96 3.84Zm4.5-6.14c-.25-.12-1.46-.72-1.69-.8-.22-.08-.39-.12-.55.13-.16.24-.63.8-.78.96-.14.16-.29.18-.53.06a6.7 6.7 0 0 1-3.32-2.9c-.25-.43.25-.4.72-1.33.08-.16.04-.3-.02-.42l-.75-1.8c-.2-.48-.4-.41-.55-.42h-.47a.9.9 0 0 0-.65.3 2.74 2.74 0 0 0-.86 2.04 4.76 4.76 0 0 0 1 2.53 10.9 10.9 0 0 0 4.17 3.68c1.55.67 2.16.73 2.94.61.47-.07 1.46-.6 1.66-1.17.21-.58.21-1.07.14-1.17-.06-.1-.22-.16-.47-.28Z"/>
            </svg>
        </div>

        <h1 class="mt-5 text-2xl font-extrabold text-ink sm:text-3xl">Verifikasi Nomor WhatsApp</h1>
        <p class="mt-2 text-base text-muted">
            Kami telah mengirim kode {{ $panjangKode }} digit ke WhatsApp
            <strong class="whitespace-nowrap font-bold text-ink">{{ $nomorSamaran }}</strong>
        </p>

        <form method="POST" action="{{ route('register.verifikasi.post') }}" class="mt-7" data-otp-form data-no-loading novalidate>
            @csrf
            <fieldset class="flex justify-center gap-3 sm:gap-4" data-otp-kotak-wadah>
                <legend class="sr-only">Kode verifikasi {{ $panjangKode }} digit</legend>
                @for ($i = 0; $i < $panjangKode; $i++)
                    <input type="text" name="digit[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" required
                           autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}" aria-label="Digit ke-{{ $i + 1 }}"
                           class="otp-kotak h-16 w-14 rounded-2xl border-2 border-line bg-canvas text-center text-3xl font-extrabold text-ink outline-none sm:h-[4.5rem] sm:w-16 @error('kode') otp-salah @enderror"
                           data-otp-digit @if ($i === 0) autofocus @endif>
                @endfor
            </fieldset>

            {{-- Pesan gagal / berhasil --}}
            <div class="mt-4 min-h-[1.75rem]" aria-live="assertive">
                <p data-otp-pesan class="{{ $errors->has('kode') ? 'inline-flex' : 'hidden' }} items-center gap-2 text-[15px] font-semibold text-red-600">
                    <span class="otp-silang flex h-6 w-6 items-center justify-center rounded-full bg-red-100"><x-icon name="x" class="h-4 w-4" stroke="3" /></span>
                    <span data-otp-pesan-teks>{{ $errors->first('kode') }}</span>
                </p>
            </div>

            {{-- Hitung mundur kunci (setelah terlalu banyak percobaan) --}}
            <div data-otp-kunci class="hidden mx-auto mt-2 w-fit items-center gap-4 rounded-2xl bg-red-50 px-4 py-3 text-left">
                <div class="relative h-14 w-14 shrink-0">
                    <svg viewBox="0 0 44 44" class="h-14 w-14 -rotate-90" aria-hidden="true">
                        <circle cx="22" cy="22" r="19" fill="none" stroke="#FECACA" stroke-width="4"/>
                        <circle data-otp-kunci-lingkar cx="22" cy="22" r="19" fill="none" stroke="#DC2626" stroke-width="4" stroke-linecap="round" stroke-dasharray="119.4" stroke-dashoffset="0"/>
                    </svg>
                    <span data-otp-kunci-detik class="absolute inset-0 flex items-center justify-center text-sm font-extrabold text-red-700">60</span>
                </div>
                <p class="text-sm font-semibold text-red-800">Terlalu banyak percobaan.<br><span class="font-normal">Silakan tunggu sebentar sebelum mencoba lagi.</span></p>
            </div>

            <button type="submit" class="btn-primary btn-lg mt-5 w-full" data-otp-tombol>
                <x-icon name="shield-check" class="h-5 w-5" /> <span data-otp-tombol-teks>Verifikasi</span>
            </button>
        </form>

        {{-- Kirim ulang kode --}}
        <div class="mt-6 text-[15px] text-muted">
            <p data-otp-timer class="{{ $sisaKirimUlang > 0 ? '' : 'hidden' }}">
                Kirim ulang kode dalam <span data-otp-timer-angka class="inline-block min-w-[3.2rem] font-bold tabular-nums text-ink">{{ sprintf('%02d:%02d', intdiv($sisaKirimUlang, 60), $sisaKirimUlang % 60) }}</span>
            </p>
            <form method="POST" action="{{ route('register.verifikasi.kirim-ulang') }}" data-otp-kirim-form data-no-loading class="{{ $sisaKirimUlang > 0 ? 'js-sembunyi' : '' }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-bold text-primary transition hover:bg-primary-soft" data-otp-kirim-ulang>
                    <x-icon name="rotate-ccw" class="h-4 w-4" /> Kirim ulang kode
                </button>
            </form>
        </div>

        <a href="{{ route('register.verifikasi.ubah') }}" class="mt-3 inline-flex items-center gap-1.5 text-[15px] font-semibold text-muted hover:text-primary">
            <x-icon name="pencil" class="h-4 w-4" /> Ubah nomor WhatsApp
        </a>
    </div>

    {{-- Animasi sukses (ditampilkan oleh JavaScript) --}}
    <div data-otp-sukses class="absolute inset-0 z-20 hidden items-center justify-center rounded-3xl bg-white/95 p-6 text-center backdrop-blur-sm" role="status" aria-live="polite">
        <div>
            <div class="riak relative mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-green-600 text-white">
                <svg viewBox="0 0 52 52" class="h-14 w-14" aria-hidden="true"><path class="centang-gambar" d="M14 27l8 8 16-17" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <p class="mt-6 text-xl font-extrabold text-ink">Nomor berhasil diverifikasi!</p>
            <p class="mt-1 text-[15px] text-muted">Mengarahkan ke dashboard...</p>
            <div class="mx-auto mt-4 h-2 w-56 overflow-hidden rounded-full bg-primary-soft"><div class="otp-bar-isi h-full rounded-full bg-gradient-to-r from-primary-accent to-primary"></div></div>
        </div>
    </div>
</div>
@endsection
