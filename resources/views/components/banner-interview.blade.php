{{--
    Banner undangan interview via WhatsApp untuk klien / calon pekerja yang login.
    Pemakaian: <x-banner-interview :jadwal="$jadwal" pihak="klien" />   (pihak: klien | pekerja)
    Hanya dipakai pada halaman milik pengguna sendiri (detail pesanan klien / beranda pekerja).
--}}
@props(['jadwal', 'pihak'])

@php
    $status = $jadwal->konfirmasi($pihak);
    $penerima = $jadwal->penerima($pihak);
    $nomorSamaran = \App\Support\WhatsApp::samarkan($penerima->no_telepon);
    $dikirim = $jadwal->undanganDikirimPada($pihak);
    $linkHadir = $jadwal->linkKeAdmin($pihak, 'hadir');
    $linkUbah = $jadwal->linkKeAdmin($pihak, 'ubah');
    $idUnik = 'interview-' . $jadwal->id_interview;

    [$kotak, $ikonBg] = match ($status) {
        'hadir' => ['border-green-200 bg-green-50', 'bg-green-600 text-white'],
        'minta_ubah' => ['border-sky-200 bg-sky-50', 'bg-sky-600 text-white'],
        'tidak_merespons' => ['border-red-200 bg-red-50', 'bg-red-600 text-white'],
        default => ['border-amber-200 bg-sun-soft', 'bg-[#1DA851] text-white'],
    };
    $pembuka = $pihak === 'klien' ? 'Pesanan Anda telah disetujui.' : 'Anda dipilih oleh klien.';
@endphp

<section {{ $attributes->merge(['class' => "reveal overflow-hidden rounded-3xl border-2 $kotak"]) }} id="{{ $idUnik }}" aria-labelledby="{{ $idUnik }}-judul">
    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:p-6">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $ikonBg }} {{ $status === 'menunggu' ? 'otp-pantul' : '' }}">
            @if ($status === 'hadir')
                <x-icon name="check-circle" class="h-6 w-6" />
            @else
                <x-ikon-whatsapp class="h-6 w-6" />
            @endif
        </span>

        <div class="min-w-0 flex-1">
            @if ($jadwal->pernahDiubah() && $status === 'menunggu')
                <p class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-wide text-sun-dark ring-1 ring-amber-200">
                    <x-icon name="rotate-ccw" class="h-3.5 w-3.5" /> Jadwal interview diperbarui. Cek pesan WhatsApp terbaru dari admin.
                </p>
            @endif

            <h2 id="{{ $idUnik }}-judul" class="text-lg font-extrabold text-ink">Undangan interview · Pesanan {{ $jadwal->pemesanan->kode() }}</h2>

            <p class="mt-1.5 text-[15px] leading-relaxed text-ink">
                @if ($dikirim)
                    {{ $pembuka }} Admin telah mengirim undangan interview melalui WhatsApp pada <strong>{{ \App\Models\JadwalInterview::tampilWib($dikirim) }}</strong>
                    ke nomor <strong class="whitespace-nowrap">{{ $nomorSamaran }}</strong>. Silakan cek WhatsApp Anda dan balas pesan dari admin.
                @else
                    {{ $pembuka }} Admin akan mengirim undangan interview melalui WhatsApp ke nomor <strong class="whitespace-nowrap">{{ $nomorSamaran }}</strong>.
                    Silakan cek WhatsApp Anda dan balas pesan dari admin.
                @endif
            </p>

            <div class="mt-4">
                @include('interview._ringkasan', ['jadwal' => $jadwal, 'ringkas' => true])
            </div>

            {{-- Status konfirmasi pihak ini --}}
            <div class="mt-4 flex flex-wrap items-center gap-2 text-[15px]">
                <span class="font-semibold text-muted">Status Anda:</span>
                @if ($status === 'hadir')
                    <x-badge color="green">Hadir</x-badge>
                    <span class="font-semibold text-green-800">Kehadiran Anda telah dikonfirmasi.</span>
                @elseif ($status === 'minta_ubah')
                    <x-badge color="blue">Minta Ubah Jadwal</x-badge>
                    <span class="font-semibold text-sky-800">Permintaan ubah jadwal sedang ditinjau admin.</span>
                @elseif ($status === 'tidak_merespons')
                    <x-badge color="red">Belum ada balasan</x-badge>
                    <span class="font-semibold text-red-800">Admin belum menerima balasan Anda - silakan balas pesan WhatsApp dari admin.</span>
                @else
                    <x-badge color="amber">Menunggu</x-badge>
                    <span class="font-semibold text-amber-900">Belum dikonfirmasi - silakan balas pesan WhatsApp dari admin.</span>
                @endif
            </div>

            {{-- Tombol ke WhatsApp admin (disembunyikan bila ADMIN_WHATSAPP belum diisi) --}}
            @if ($linkHadir && $status !== 'hadir')
                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ $linkHadir }}" target="_blank" rel="noopener noreferrer" class="btn bg-[#1DA851] text-white shadow-sm hover:bg-[#178A43]">
                        <x-ikon-whatsapp class="h-5 w-5" /> Konfirmasi via WhatsApp
                    </a>
                    @if ($status !== 'minta_ubah')
                        <a href="{{ $linkUbah }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                            <x-icon name="calendar" class="h-5 w-5" /> Minta Ubah Jadwal via WhatsApp
                        </a>
                    @endif
                </div>
            @elseif ($linkUbah && $status === 'hadir')
                <p class="mt-4 text-sm text-muted">Berhalangan hadir? <a href="{{ $linkUbah }}" target="_blank" rel="noopener noreferrer" class="font-bold text-primary hover:underline">Minta ubah jadwal via WhatsApp</a></p>
            @endif
        </div>
    </div>
</section>
