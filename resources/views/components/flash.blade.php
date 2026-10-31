{{-- Pesan sukses / error / info yang dikirim melalui session --}}
@foreach (['success' => 'bg-[#E4F2E8] border-[#176B4D]/20 text-[#104C38]', 'error' => 'bg-red-50 border-red-200 text-red-700', 'info' => 'bg-blue-50 border-blue-200 text-blue-700'] as $jenis => $kelas)
    @if (session($jenis))
        <div class="mb-6 border rounded-xl px-4 py-3 text-sm flex items-start gap-2 print:hidden {{ $kelas }}" data-auto-dismiss role="alert">
            <span class="font-semibold">{{ ['success' => '✓', 'error' => '!', 'info' => 'i'][$jenis] }}</span>
            <span>{{ session($jenis) }}</span>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="mb-6 border rounded-xl px-4 py-3 text-sm bg-red-50 border-red-200 text-red-700 print:hidden" role="alert">
        <p class="font-semibold mb-1">Data belum valid:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $pesan)
                <li>{{ $pesan }}</li>
            @endforeach
        </ul>
    </div>
@endif
