{{--
    Kolom formulir seragam: label, input/textarea/select, keterangan, dan pesan error.
    Pemakaian: <x-field name="nama" label="Nama lengkap" required :value="$pengguna->nama" />
               <x-field name="catatan" label="Catatan" type="textarea" rows="4" />
               <x-field name="jk" label="Jenis kelamin" type="select" :options="['L' => 'Laki-laki']" />
--}}
@props([
    'name', 'label', 'type' => 'text', 'value' => null, 'required' => false,
    'placeholder' => null, 'help' => null, 'options' => [], 'rows' => 3, 'errorKey' => null, 'icon' => null,
])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], '_', $name));
    $kunci = $errorKey ?? str_replace(['[', ']'], ['.', ''], $name);
    $isi = old($kunci, $value);
    $kelas = 'input' . ($errors->has($kunci) ? ' input-error' : '') . ($icon ? ' pl-11' : '');
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="label">{{ $label }} @if ($required)<span class="required">*</span>@endif</label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" class="{{ $kelas }}"
                  @if ($required) required @endif {{ $attributes->except(['class', 'id']) }}>{{ $isi }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" class="{{ $kelas }}" @if ($required) required @endif {{ $attributes->except(['class', 'id']) }}>
            <option value="">— Pilih —</option>
            @foreach ($options as $nilai => $teks)
                <option value="{{ $nilai }}" @selected((string) $isi === (string) $nilai)>{{ $teks }}</option>
            @endforeach
        </select>
    @else
        @php $sandi = $type === 'password'; $bungkus = $icon || $sandi; @endphp
        @if ($bungkus)<div class="relative">@endif
        @if ($icon)<x-icon :name="$icon" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted/70" />@endif
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $sandi ? '' : $isi }}" placeholder="{{ $placeholder }}"
               class="{{ $kelas }}{{ $sandi ? ' pr-12' : '' }}" @if ($required) required @endif {{ $attributes->except(['class', 'id']) }}>
        @if ($sandi)
            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg p-2 text-muted hover:bg-canvas hover:text-ink" data-toggle-password="{{ $id }}" aria-label="Tampilkan kata sandi">
                <x-icon name="eye" class="h-5 w-5" data-ikon-lihat />
                <x-icon name="eye-off" class="hidden h-5 w-5" data-ikon-sembunyi />
            </button>
        @endif
        @if ($bungkus)</div>@endif
    @endif

    @if ($help)
        <p class="text-helper mt-1.5">{{ $help }}</p>
    @endif
    @error($kunci) <p class="field-error"><x-icon name="alert-circle" class="h-4 w-4" /> {{ $message }}</p> @enderror
</div>
