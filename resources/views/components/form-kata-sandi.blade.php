{{-- Bagian ganti kata sandi (opsional) pada formulir edit profil --}}
<div class="border-t border-line pt-6">
    <h2 class="card-title flex items-center gap-2"><x-icon name="lock" class="h-5 w-5 text-primary" /> Ganti kata sandi <span class="text-sm font-normal text-muted">(opsional)</span></h2>
    <p class="mb-5 mt-1 text-sm text-muted">Kosongkan bila tidak ingin mengganti. Kata sandi baru minimal 8 karakter berisi huruf dan angka.</p>
    <div class="grid gap-5 md:grid-cols-3">
        <x-field name="kata_sandi_lama" type="password" label="Kata sandi lama" autocomplete="current-password" />
        <x-field name="kata_sandi_baru" type="password" label="Kata sandi baru" autocomplete="new-password" />
        <x-field name="kata_sandi_baru_confirmation" type="password" label="Konfirmasi kata sandi baru" autocomplete="new-password" />
    </div>
</div>
