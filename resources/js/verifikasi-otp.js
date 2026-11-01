/**
 * Halaman "Verifikasi Nomor WhatsApp": 4 kotak digit, verifikasi lewat fetch (AJAX),
 * animasi berhasil/gagal, hitung mundur kirim ulang & kunci, serta confetti ringan.
 * Tanpa JavaScript, formulir tetap terkirim sebagai POST biasa.
 */
const kurangiGerakan = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function mulaiVerifikasiOtp(wadah) {
    const form = wadah.querySelector('[data-otp-form]');
    const kotak = [...wadah.querySelectorAll('[data-otp-digit]')];
    const tombol = wadah.querySelector('[data-otp-tombol]');
    const teksTombol = wadah.querySelector('[data-otp-tombol-teks]');
    const pesan = wadah.querySelector('[data-otp-pesan]');
    const pesanTeks = wadah.querySelector('[data-otp-pesan-teks]');
    const wadahKotak = wadah.querySelector('[data-otp-kotak-wadah]');
    const kunci = wadah.querySelector('[data-otp-kunci]');
    const timer = wadah.querySelector('[data-otp-timer]');
    const timerAngka = wadah.querySelector('[data-otp-timer-angka]');
    const formKirim = wadah.querySelector('[data-otp-kirim-form]');
    const sukses = wadah.querySelector('[data-otp-sukses]');
    let sedangProses = false;

    /* ─── Input digit ─────────────────────────────────────────────── */
    const isiKode = () => kotak.map((k) => k.value).join('');
    const isiSemua = (angka) => {
        kotak.forEach((k, i) => { k.value = angka[i] ?? ''; if (k.value) pop(k); });
        const kosong = kotak.find((k) => !k.value);
        (kosong ?? kotak[kotak.length - 1]).focus();
        if (isiKode().length === kotak.length) kirim();
    };
    const pop = (k) => { if (kurangiGerakan) return; k.classList.remove('otp-isi'); void k.offsetWidth; k.classList.add('otp-isi'); };

    kotak.forEach((k, i) => {
        k.addEventListener('input', () => {
            const angka = k.value.replace(/\D/g, '');
            if (angka.length > 1) { isiSemua(angka.slice(0, kotak.length)); return; } // isian otomatis dari HP
            k.value = angka;
            sembunyikanPesan();
            if (angka) {
                pop(k);
                if (i < kotak.length - 1) kotak[i + 1].focus();
                else if (isiKode().length === kotak.length) kirim();
            }
        });
        k.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !k.value && i > 0) { e.preventDefault(); kotak[i - 1].value = ''; kotak[i - 1].focus(); }
            if (e.key === 'ArrowLeft' && i > 0) { e.preventDefault(); kotak[i - 1].focus(); }
            if (e.key === 'ArrowRight' && i < kotak.length - 1) { e.preventDefault(); kotak[i + 1].focus(); }
            if (e.key.length === 1 && !/\d/.test(e.key) && !e.ctrlKey && !e.metaKey) e.preventDefault(); // hanya angka
        });
        k.addEventListener('paste', (e) => {
            const angka = (e.clipboardData?.getData('text') || '').replace(/\D/g, '');
            if (!angka) return;
            e.preventDefault();
            isiSemua(angka.slice(0, kotak.length));
        });
        k.addEventListener('focus', () => k.select());
    });

    form.addEventListener('submit', (e) => { e.preventDefault(); kirim(); });

    /* ─── Kirim kode lewat fetch ──────────────────────────────────── */
    async function kirim() {
        if (sedangProses) return;
        if (isiKode().length !== kotak.length) { tampilkanGagal('Masukkan ' + kotak.length + ' digit kode verifikasi.', false); return; }

        sedangProses = true;
        const dataForm = new FormData(form); // ambil sebelum kotak dinonaktifkan (input disabled tidak ikut terkirim)
        aturMemproses(true);
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: dataForm,
                credentials: 'same-origin',
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok && data.ok) { tampilkanSukses(data.redirect); return; }
            if (data.redirect) { tampilkanGagal(data.pesan, false); setTimeout(() => { window.location.href = data.redirect; }, 1600); return; }
            if (res.status === 429) { tampilkanGagal('Terlalu banyak permintaan. Coba lagi sebentar lagi.'); return; }
            tampilkanGagal(data.pesan || 'Kode verifikasi salah.');
            if (data.kunci > 0) mulaiKunci(data.kunci);
        } catch (err) {
            tampilkanGagal('Koneksi bermasalah. Periksa internet Anda lalu coba lagi.', false);
        } finally {
            if (!wadah.dataset.berhasil) { sedangProses = false; aturMemproses(false); }
        }
    }

    function aturMemproses(aktif) {
        tombol.disabled = aktif;
        kotak.forEach((k) => { k.disabled = aktif || wadah.dataset.terkunci === '1'; });
        tombol.classList.toggle('is-loading', aktif);
        const ikon = tombol.querySelector('svg, .spinner');
        if (aktif) {
            ikon?.replaceWith(Object.assign(document.createElement('span'), { className: 'spinner' }));
            teksTombol.textContent = 'Memverifikasi...';
        } else {
            const spin = tombol.querySelector('.spinner');
            if (spin) spin.outerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 shrink-0" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>';
            teksTombol.textContent = 'Verifikasi';
        }
    }

    /* ─── Animasi gagal ───────────────────────────────────────────── */
    function tampilkanGagal(teks, kosongkan = true) {
        pesanTeks.textContent = teks;
        pesan.classList.remove('hidden');
        pesan.classList.add('inline-flex');
        if (!kurangiGerakan) {
            pesan.classList.remove('animate-fade-up'); void pesan.offsetWidth; pesan.classList.add('animate-fade-up');
            const silang = pesan.querySelector('.otp-silang');
            silang.classList.remove('pop'); void silang.offsetWidth; silang.classList.add('pop');
            wadahKotak.classList.remove('otp-goyang'); void wadahKotak.offsetWidth; wadahKotak.classList.add('otp-goyang');
        }
        kotak.forEach((k) => k.classList.add('otp-salah'));
        if (kosongkan) {
            setTimeout(() => {
                kotak.forEach((k) => { k.value = ''; });
                if (wadah.dataset.terkunci !== '1') kotak[0].focus();
            }, kurangiGerakan ? 0 : 450);
        }
    }

    function sembunyikanPesan() {
        pesan.classList.add('hidden');
        pesan.classList.remove('inline-flex');
        kotak.forEach((k) => k.classList.remove('otp-salah'));
    }

    /* ─── Animasi berhasil ────────────────────────────────────────── */
    function tampilkanSukses(tujuan) {
        wadah.dataset.berhasil = '1';
        sembunyikanPesan();
        kotak.forEach((k, i) => setTimeout(() => k.classList.add('otp-benar'), kurangiGerakan ? 0 : i * 110));
        setTimeout(() => {
            sukses.classList.remove('hidden');
            sukses.classList.add('flex');
            if (!kurangiGerakan) { sukses.classList.add('animate-fade-up'); confetti(); }
        }, kurangiGerakan ? 0 : kotak.length * 110 + 150);
        setTimeout(() => { window.location.href = tujuan; }, kurangiGerakan ? 1200 : 2400);
    }

    /* ─── Hitung mundur kunci dengan progress melingkar ───────────── */
    function mulaiKunci(detik) {
        const lama = Number(wadah.dataset.lamaKunci || 60);
        const lingkar = kunci.querySelector('[data-otp-kunci-lingkar]');
        const angka = kunci.querySelector('[data-otp-kunci-detik]');
        const keliling = 119.4;
        wadah.dataset.terkunci = '1';
        kunci.classList.remove('hidden');
        kunci.classList.add('flex');
        if (!kurangiGerakan) { kunci.classList.remove('animate-fade-up'); void kunci.offsetWidth; kunci.classList.add('animate-fade-up'); }
        kotak.forEach((k) => { k.disabled = true; });
        tombol.disabled = true;
        const selesai = Date.now() + detik * 1000;
        const langkah = () => {
            const sisa = Math.max(0, (selesai - Date.now()) / 1000);
            angka.textContent = Math.ceil(sisa);
            lingkar.style.strokeDashoffset = String(keliling * (1 - sisa / lama));
            if (sisa > 0) { requestAnimationFrame(langkah); return; }
            wadah.dataset.terkunci = '0';
            kunci.classList.add('hidden');
            kunci.classList.remove('flex');
            kotak.forEach((k) => { k.disabled = false; k.value = ''; });
            tombol.disabled = false;
            sembunyikanPesan();
            kotak[0].focus();
        };
        requestAnimationFrame(langkah);
    }

    /* ─── Timer kirim ulang ───────────────────────────────────────── */
    let intervalTimer;
    function mulaiTimer(detik) {
        clearInterval(intervalTimer);
        let sisa = detik;
        const tulis = () => {
            const teks = String(Math.floor(sisa / 60)).padStart(2, '0') + ':' + String(sisa % 60).padStart(2, '0');
            if (timerAngka.textContent !== teks) {
                timerAngka.textContent = teks;
                if (!kurangiGerakan) { timerAngka.classList.remove('otp-detak'); void timerAngka.offsetWidth; timerAngka.classList.add('otp-detak'); }
            }
        };
        if (sisa <= 0) { tampilkanKirimUlang(); return; }
        timer.classList.remove('hidden');
        formKirim.classList.add('js-sembunyi');
        tulis();
        intervalTimer = setInterval(() => {
            sisa -= 1;
            tulis();
            if (sisa <= 0) { clearInterval(intervalTimer); tampilkanKirimUlang(); }
        }, 1000);
    }
    function tampilkanKirimUlang() {
        timer.classList.add('hidden');
        formKirim.classList.remove('js-sembunyi');
        if (!kurangiGerakan) { formKirim.classList.remove('animate-fade-up'); void formKirim.offsetWidth; formKirim.classList.add('animate-fade-up'); }
    }

    formKirim.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = formKirim.querySelector('button');
        btn.disabled = true;
        try {
            const res = await fetch(formKirim.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(formKirim),
                credentials: 'same-origin',
            });
            const data = await res.json().catch(() => ({}));
            if (data.redirect) { toast(data.pesan, 'error'); setTimeout(() => { window.location.href = data.redirect; }, 1600); return; }
            toast(data.pesan || (res.ok ? 'Kode baru telah dikirim.' : 'Gagal mengirim ulang kode.'), res.ok ? 'success' : 'error');
            mulaiTimer(Number(data.tunggu || 60));
            kotak.forEach((k) => { k.value = ''; });
            sembunyikanPesan();
            kotak[0].focus();
        } catch (err) {
            toast('Koneksi bermasalah. Coba lagi.', 'error');
        } finally {
            btn.disabled = false;
        }
    });

    /* ─── Keadaan awal ────────────────────────────────────────────── */
    mulaiTimer(Number(wadah.dataset.sisaKirim || 0));
    if (Number(wadah.dataset.sisaKunci) > 0) mulaiKunci(Number(wadah.dataset.sisaKunci));
}

/* ─── Toast sederhana (selaras dengan komponen flash) ───────────── */
function toast(teks, jenis = 'success') {
    let area = document.getElementById('toast-area');
    if (!area) {
        area = Object.assign(document.createElement('div'), { id: 'toast-area', className: 'pointer-events-none fixed inset-x-4 top-4 z-[70] flex flex-col items-end gap-3 sm:left-auto sm:right-6 sm:w-96' });
        document.body.appendChild(area);
    }
    const ok = jenis === 'success';
    const el = document.createElement('div');
    el.className = `toast toast-hide pointer-events-auto flex w-full items-start gap-3 rounded-2xl border bg-white p-4 shadow-lift ${ok ? 'border-green-200' : 'border-red-200'}`;
    el.setAttribute('role', ok ? 'status' : 'alert');
    el.innerHTML = `<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full ${ok ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'} text-sm font-bold">${ok ? '✓' : '!'}</span>
        <div class="min-w-0 flex-1"><p class="text-sm font-semibold text-ink">${ok ? 'Berhasil' : 'Gagal'}</p><p class="text-sm text-muted"></p></div>`;
    el.querySelector('p.text-muted').textContent = teks;
    area.appendChild(el);
    requestAnimationFrame(() => requestAnimationFrame(() => el.classList.remove('toast-hide')));
    setTimeout(() => { el.classList.add('toast-hide'); setTimeout(() => el.remove(), 300); }, 4000);
}

/* ─── Confetti ringan hijau-kuning (canvas, ±1,3 detik) ─────────── */
function confetti() {
    const kanvas = document.createElement('canvas');
    kanvas.className = 'pointer-events-none fixed inset-0 z-[90]';
    kanvas.setAttribute('aria-hidden', 'true');
    document.body.appendChild(kanvas);
    const ctx = kanvas.getContext('2d');
    const rasio = window.devicePixelRatio || 1;
    kanvas.width = innerWidth * rasio; kanvas.height = innerHeight * rasio;
    kanvas.style.width = innerWidth + 'px'; kanvas.style.height = innerHeight + 'px';
    ctx.scale(rasio, rasio);

    const warna = ['#246C54', '#3BA877', '#B9DECF', '#F5B83D', '#FFD978'];
    const asalX = innerWidth / 2, asalY = innerHeight * 0.42;
    const partikel = Array.from({ length: 130 }, () => {
        const sudut = Math.random() * Math.PI * 2;
        const laju = 4 + Math.random() * 8;
        return {
            x: asalX, y: asalY, vx: Math.cos(sudut) * laju, vy: Math.sin(sudut) * laju - 5,
            w: 6 + Math.random() * 6, h: 8 + Math.random() * 8, r: Math.random() * Math.PI, vr: (Math.random() - 0.5) * 0.4,
            c: warna[Math.floor(Math.random() * warna.length)],
        };
    });
    const mulai = performance.now();
    const durasi = 1400;
    const gambar = (t) => {
        const p = (t - mulai) / durasi;
        ctx.clearRect(0, 0, innerWidth, innerHeight);
        partikel.forEach((q) => {
            q.vy += 0.28; q.vx *= 0.985; q.x += q.vx; q.y += q.vy; q.r += q.vr;
            ctx.save();
            ctx.globalAlpha = Math.max(0, 1 - p * p);
            ctx.translate(q.x, q.y); ctx.rotate(q.r);
            ctx.fillStyle = q.c; ctx.fillRect(-q.w / 2, -q.h / 2, q.w, q.h);
            ctx.restore();
        });
        if (p < 1) requestAnimationFrame(gambar); else kanvas.remove();
    };
    requestAnimationFrame(gambar);
}
