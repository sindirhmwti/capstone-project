{{-- Penunjuk langkah registrasi. Parameter: $aktif (1-3) --}}
@php $daftar = ['Jenis akun', 'Kategori', 'Data akun']; @endphp
<ol class="mb-8 flex items-center gap-2" aria-label="Langkah pendaftaran">
    @foreach ($daftar as $i => $nama)
        @php $no = $i + 1; $selesai = $no < $aktif; $kini = $no === $aktif; @endphp
        <li class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}" @if ($kini) aria-current="step" @endif>
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-bold
                         {{ $selesai ? 'bg-primary text-white' : ($kini ? 'bg-primary text-white ring-4 ring-primary/15' : 'bg-canvas text-muted ring-1 ring-line') }}">
                @if ($selesai) <x-icon name="check" class="h-4 w-4" /> @else {{ $no }} @endif
            </span>
            <span class="hidden text-sm font-medium sm:inline {{ $kini || $selesai ? 'text-ink' : 'text-muted' }}">{{ $nama }}</span>
            @unless ($loop->last)
                <span class="h-0.5 flex-1 rounded {{ $selesai ? 'bg-primary' : 'bg-line' }}"></span>
            @endunless
        </li>
    @endforeach
</ol>
