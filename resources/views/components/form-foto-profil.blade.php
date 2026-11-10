{{--
    Kartu unggah / ganti / hapus foto profil dengan pratinjau bulat sebelum disimpan.
    Pemakaian:
      <x-form-foto-profil :pengguna="$pengguna" :aksi="route('worker.profile.photo')" wajib
                          panduan="Gunakan foto wajah Anda sendiri ..." />
      <x-form-foto-profil :pengguna="$pengguna" :aksi="route('client.profile.photo')" :aksi-hapus="route('client.profile.photo.destroy')" />
--}}
@props(['pengguna', 'aksi', 'aksiHapus' => null, 'wajib' => false, 'panduan' => null])

@php
    $ada = $pengguna->punyaFotoProfil();
    $error = $errors->first('foto_profil');
@endphp

<section {{ $attributes->merge(['class' => 'card reveal']) }} id="foto-profil" data-foto-profil>
    <div class="card-head">
        <h2 class="card-title flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon name="user" /></span>
            {{ $wajib ? 'Foto Profil' : 'Foto Profil (opsional)' }}
            @if ($wajib)<span class="required">*</span>@endif
        </h2>
        @if ($wajib)
            <x-badge :color="$ada ? 'green' : 'red'">{{ $ada ? 'Sudah diunggah' : 'Wajib diunggah' }}</x-badge>
        @endif
    </div>

    <div class="card-body flex flex-col gap-6 sm:flex-row sm:items-center">
        {{-- Foto saat ini + pratinjau --}}
        <div class="relative mx-auto shrink-0 sm:mx-0">
            <div data-foto-sekarang>
                <x-avatar :name="$pengguna->nama" :src="$pengguna->urlFotoProfil()" size="3xl" class="ring-4 ring-primary-soft" :alt="'Foto profil ' . $pengguna->nama" />
            </div>
            <img data-foto-pratinjau src="" alt="Pratinjau foto profil" class="hidden h-32 w-32 rounded-full bg-canvas object-cover ring-4 ring-primary/30">
            <span data-foto-label-pratinjau class="absolute -bottom-2 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded-full bg-sun px-2.5 py-0.5 text-xs font-bold text-ink shadow-sm">Pratinjau</span>
        </div>

        <div class="min-w-0 flex-1 space-y-3">
            @if ($panduan)
                <p class="flex items-start gap-2 rounded-2xl bg-mint-soft px-4 py-3 text-[15px] text-primary-darker">
                    <x-icon name="info" class="mt-0.5 h-5 w-5 shrink-0" /> <span>{{ $panduan }}</span>
                </p>
            @endif
            <p class="text-sm text-muted">Format JPG, JPEG, PNG, atau WEBP · maksimal 2 MB · minimal 300 x 300 piksel.</p>

            <form method="POST" action="{{ $aksi }}" enctype="multipart/form-data" class="space-y-3" novalidate>
                @csrf
                <input type="file" name="foto_profil" id="input-foto-profil" accept="image/jpeg,image/png,image/webp" class="sr-only" data-foto-input required>

                <div class="flex flex-wrap gap-2">
                    <label for="input-foto-profil" class="btn-secondary cursor-pointer">
                        <x-icon name="upload" class="h-5 w-5" /> {{ $ada ? 'Ganti Foto' : 'Unggah Foto' }}
                    </label>
                    <button type="submit" class="btn-primary" data-foto-simpan>
                        <x-icon name="check" class="h-5 w-5" /> Simpan Foto
                    </button>
                    <button type="button" class="btn-ghost hidden" data-foto-batal>Batal</button>
                </div>

                <p data-foto-nama class="hidden text-sm font-medium text-ink"></p>
                <p data-foto-galat class="field-error {{ $error ? '' : 'hidden' }}" role="alert"><x-icon name="alert-circle" class="h-4 w-4" /> <span data-foto-galat-teks>{{ $error }}</span></p>
            </form>

            @if ($ada && $aksiHapus)
                <form method="POST" action="{{ $aksiHapus }}" data-confirm="Hapus foto profil? Avatar inisial nama akan ditampilkan.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-600 hover:underline"><x-icon name="x-circle" class="h-4 w-4" /> Hapus Foto</button>
                </form>
            @elseif ($ada && $wajib)
                <p class="text-helper">Foto profil wajib, sehingga hanya dapat diganti, tidak dapat dihapus.</p>
            @endif
        </div>
    </div>
</section>
