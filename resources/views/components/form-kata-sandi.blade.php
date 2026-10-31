{{-- Bagian ganti kata sandi (opsional) pada formulir edit profil --}}
<div class="border-t border-gray-100 pt-5">
    <p class="font-semibold text-[#172B23] mb-1">Ganti kata sandi <span class="text-xs font-normal text-[#52645B]">(opsional)</span></p>
    <p class="text-xs text-[#52645B] mb-4">Kosongkan bila tidak ingin mengganti kata sandi. Kata sandi baru minimal 8 karakter berisi huruf dan angka.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1.5" for="kata_sandi_lama">Kata sandi lama</label>
            <input id="kata_sandi_lama" type="password" name="kata_sandi_lama" class="{{ $input }}" autocomplete="current-password">
            @error('kata_sandi_lama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="kata_sandi_baru">Kata sandi baru</label>
            <input id="kata_sandi_baru" type="password" name="kata_sandi_baru" class="{{ $input }}" autocomplete="new-password">
            @error('kata_sandi_baru') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="kata_sandi_baru_confirmation">Konfirmasi kata sandi baru</label>
            <input id="kata_sandi_baru_confirmation" type="password" name="kata_sandi_baru_confirmation" class="{{ $input }}" autocomplete="new-password">
        </div>
    </div>
</div>
