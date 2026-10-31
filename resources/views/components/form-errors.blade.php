{{-- Ringkasan kesalahan validasi formulir --}}
@if ($errors->any())
    <div class="mb-6 flex gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800 animate-fade-up print:hidden" role="alert">
        <x-icon name="alert-circle" class="mt-0.5 h-5 w-5 text-red-600" />
        <div>
            <p class="font-semibold">Data belum valid. Periksa kembali isian berikut:</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-5">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
