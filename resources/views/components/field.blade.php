{{--
    Kolom formulir seragam: label, input/textarea/select, keterangan, dan pesan error.
    Pemakaian: <x-field name="nama" label="Nama lengkap" required :value="$pengguna->nama" />
               <x-field name="catatan" label="Catatan" type="textarea" rows="4" />
               <x-field name="jk" label="Jenis kelamin" type="select" :options="['L' => 'Laki-laki']" />
--}}
@props([
    'name', 'label', 'type' => 'text', 'value' => null, 'required' => false,
    'placeholder' => null, 'help' => null, 'options' => [], 'rows' => 3, 'errorKey' => null,
])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], '_', $name));
    $kunci = $errorKey ?? str_replace(['[', ']'], ['.', ''], $name);
    $isi = old($kunci, $value);
    $kelas = 'input' . ($errors->has($kunci) ? ' input-error' : '');
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
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : $isi }}" placeholder="{{ $placeholder }}"
               class="{{ $kelas }}" @if ($required) required @endif {{ $attributes->except(['class', 'id']) }}>
    @endif

    @if ($help)
        <p class="text-helper mt-1.5">{{ $help }}</p>
    @endif
    @error($kunci) <p class="field-error">{{ $message }}</p> @enderror
</div>
