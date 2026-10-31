{{-- Modal konfirmasi pengganti kotak "confirm" bawaan browser. Dibuka oleh app.js untuk elemen ber-atribut data-confirm. --}}
<div id="modal-konfirmasi" class="modal-tutup fixed inset-0 z-[80] hidden items-end justify-center p-4 sm:items-center print:hidden" role="dialog" aria-modal="true" aria-labelledby="modal-judul">
    <div class="modal-latar absolute inset-0 bg-ink/50 backdrop-blur-sm" data-modal-batal></div>
    <div class="modal-kotak relative w-full max-w-md rounded-3xl bg-white p-6 shadow-lift sm:p-7">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sun-soft text-sun-dark"><x-icon name="help-circle" class="h-6 w-6" /></span>
        <h2 id="modal-judul" class="mt-4 text-xl font-extrabold text-ink">Konfirmasi tindakan</h2>
        <p class="mt-2 text-base text-muted" data-modal-pesan></p>
        <div class="mt-6 grid grid-cols-2 gap-3">
            <button type="button" class="btn-secondary" data-modal-batal>Batal</button>
            <button type="button" class="btn-primary" data-modal-ya>Ya, lanjutkan</button>
        </div>
    </div>
</div>
