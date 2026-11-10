import './bootstrap';

/**
 * Temukerja — interaksi ringan tanpa library tambahan.
 * Semua animasi dimatikan bila pengguna mengaktifkan "kurangi gerakan" (prefers-reduced-motion).
 */
const kurangiGerakan = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('DOMContentLoaded', () => {
    aturSidebar();
    aturCiutSidebar();
    aturTab();
    aturPanelFilter();
    aturDropdown();
    aturMenuPublik();
    aturNavbarScroll();
    aturToast();
    aturReveal();
    aturHitungNaik();
    aturTombolSubmit();
    aturKonfirmasi();
    aturLihatSandi();
    aturPilihBerkas();
    aturSkorBar();
    aturFavoritPop();
    aturModalForm();
    aturKirimWhatsApp();
    aturFotoProfil();

    // Halaman verifikasi nomor WhatsApp (OTP) registrasi
    const otp = document.querySelector('[data-otp]');
    if (otp) import('./verifikasi-otp.js').then((m) => m.mulaiVerifikasiOtp(otp));

    // Animasi berat (GSAP, Lenis, Swiper) hanya dimuat di halaman publik / login / registrasi
    if (document.body.dataset.halaman === 'publik') {
        import('./animasi-publik.js')
            .then((m) => m.mulaiAnimasiPublik())
            .catch(() => document.querySelectorAll('[data-hero-item],[data-auth-item],[data-split]').forEach((el) => { el.style.opacity = 1; }));
    }
});

/* ─── Tampilkan nama berkas yang dipilih ───────────────────────── */
function aturPilihBerkas() {
    document.querySelectorAll('[data-file-input]').forEach((input) => {
        const label = input.closest('label')?.querySelector('[data-file-name]');
        input.addEventListener('change', () => {
            const berkas = input.files[0];
            if (!label) return;
            label.textContent = berkas ? `${berkas.name} (${(berkas.size / 1024 / 1024).toFixed(2)} MB)` : 'Belum ada berkas dipilih';
            label.classList.toggle('text-ink', !!berkas);
            label.classList.toggle('font-medium', !!berkas);
        });
    });
}

/* ─── Sidebar desktop: diciutkan / dilebarkan (diingat di browser) ─ */
function aturCiutSidebar() {
    document.querySelectorAll('[data-sidebar-collapse]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            const ciut = document.documentElement.classList.toggle('sidebar-ciut');
            tombol.setAttribute('aria-label', ciut ? 'Lebarkan menu' : 'Ciutkan menu');
            try { localStorage.setItem('sidebar-ciut', ciut ? '1' : '0'); } catch (e) { /* abaikan */ }
        });
    });
}

/* ─── Tab sederhana: [data-tabs] > [data-tab="x"] + [data-tab-panel="x"] ─ */
function aturTab() {
    document.querySelectorAll('[data-tabs]').forEach((wadah) => {
        const tombol = wadah.querySelectorAll('[data-tab]');
        const panel = wadah.querySelectorAll('[data-tab-panel]');
        tombol.forEach((t) => t.addEventListener('click', () => {
            tombol.forEach((x) => {
                const aktif = x === t;
                x.setAttribute('aria-selected', String(aktif));
                x.classList.toggle('pill-active', aktif);
            });
            panel.forEach((p) => {
                const tampil = p.dataset.tabPanel === t.dataset.tab;
                p.classList.toggle('hidden', !tampil);
                if (tampil && !kurangiGerakan) { p.classList.remove('animate-fade-up'); void p.offsetWidth; p.classList.add('animate-fade-up'); }
            });
        }));
    });
}

/* ─── Dropdown <details>: tutup saat klik di luar atau tekan Escape ─ */
function aturDropdown() {
    const daftar = () => document.querySelectorAll('header details[open]');
    document.addEventListener('click', (e) => daftar().forEach((d) => { if (!d.contains(e.target)) d.open = false; }));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') daftar().forEach((d) => { d.open = false; }); });
}

/* ─── Panel filter katalog: terlipat di HP, selalu terbuka di desktop ─ */
function aturPanelFilter() {
    const panel = document.querySelector('[data-filter-panel]');
    if (!panel) return;
    const desktop = window.matchMedia('(min-width: 1024px)');
    if (!desktop.matches) panel.open = false;
    desktop.addEventListener('change', (e) => { if (e.matches) panel.open = true; });
}

/* ─── Sidebar dashboard (buka-tutup di HP) ─────────────────────── */
function aturSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (!sidebar || !overlay) return;

    const buka = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
        document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
    };
    const tutup = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
        setTimeout(() => overlay.classList.add('hidden'), kurangiGerakan ? 0 : 300);
    };

    document.querySelectorAll('[data-sidebar-open]').forEach((el) => el.addEventListener('click', buka));
    document.querySelectorAll('[data-sidebar-close]').forEach((el) => el.addEventListener('click', tutup));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') tutup(); });
}

/* ─── Menu navbar publik di HP ─────────────────────────────────── */
function aturMenuPublik() {
    const panel = document.getElementById('menu-publik');
    const latar = document.getElementById('menu-publik-latar');
    const navbar = document.querySelector('[data-navbar]');
    if (!panel) return;
    const tombolBuka = document.querySelectorAll('[data-menu-toggle]');

    const atur = (buka) => {
        tombolBuka.forEach((t) => t.setAttribute('aria-expanded', String(buka)));
        if (buka) {
            panel.classList.remove('hidden'); latar?.classList.remove('hidden');
            requestAnimationFrame(() => requestAnimationFrame(() => { panel.classList.remove('panel-tutup'); latar?.classList.remove('panel-tutup'); }));
            document.body.classList.add('overflow-hidden');
        } else {
            panel.classList.add('panel-tutup'); latar?.classList.add('panel-tutup');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => { if (panel.classList.contains('panel-tutup')) { panel.classList.add('hidden'); latar?.classList.add('hidden'); } }, kurangiGerakan ? 0 : 300);
        }
        navbar?.classList.toggle('is-open', buka);
    };

    tombolBuka.forEach((t) => t.addEventListener('click', () => atur(true)));
    document.querySelectorAll('[data-menu-close]').forEach((t) => t.addEventListener('click', () => atur(false)));
    panel.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => atur(false)));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') atur(false); });
}

/* ─── Navbar publik: bayangan & latar lebih solid saat di-scroll ── */
function aturNavbarScroll() {
    const navbar = document.querySelector('[data-navbar]');
    if (!navbar) return;
    const cek = () => navbar.classList.toggle('is-scrolled', window.scrollY > 8);
    cek();
    window.addEventListener('scroll', cek, { passive: true });
}

/* ─── Toast: muncul halus lalu hilang otomatis ─────────────────── */
function aturToast() {
    document.querySelectorAll('[data-toast]').forEach((toast, i) => {
        const hilang = () => {
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 300);
        };
        setTimeout(() => toast.classList.remove('toast-hide'), 60 + i * 120);
        toast.querySelector('[data-toast-close]')?.addEventListener('click', hilang);
        // Pesan error ditampilkan lebih lama agar sempat dibaca
        setTimeout(hilang, toast.getAttribute('role') === 'alert' ? 8000 : 5000);
    });
}

/* ─── Bagian halaman muncul saat di-scroll ─────────────────────── */
function aturReveal() {
    const elemen = document.querySelectorAll('.reveal');
    if (!elemen.length) return;

    if (kurangiGerakan || !('IntersectionObserver' in window)) {
        elemen.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const pengamat = new IntersectionObserver((entri) => {
        // Elemen yang muncul bersamaan (mis. kartu pekerja) ditampilkan bergantian/stagger
        let urutan = 0;
        entri.forEach((e) => {
            if (!e.isIntersecting) return;
            const jeda = e.target.dataset.revealDelay !== undefined
                ? Number(e.target.dataset.revealDelay)
                : Math.min(urutan++ * 80, 480);
            setTimeout(() => e.target.classList.add('is-visible'), jeda);
            pengamat.unobserve(e.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    elemen.forEach((el) => pengamat.observe(el));
}

/* ─── Angka berjalan (count-up) saat angka terlihat di layar ───── */
function aturHitungNaik() {
    const semua = document.querySelectorAll('[data-count]');
    const jalankan = (el) => {
        const target = Number(el.dataset.count);
        if (kurangiGerakan || !Number.isFinite(target) || target <= 0) { el.textContent = el.dataset.count; return; }
        const durasi = 900;
        const mulai = performance.now();
        const langkah = (sekarang) => {
            const p = Math.min((sekarang - mulai) / durasi, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(langkah);
        };
        requestAnimationFrame(langkah);
    };
    if (kurangiGerakan || !('IntersectionObserver' in window)) { semua.forEach(jalankan); return; }
    const pengamat = new IntersectionObserver((entri) => {
        entri.forEach((e) => { if (e.isIntersecting) { jalankan(e.target); pengamat.unobserve(e.target); } });
    }, { threshold: 0.4 });
    semua.forEach((el) => {
        const target = Number(el.dataset.count);
        if (!kurangiGerakan && Number.isFinite(target) && target > 0) el.textContent = '0';
        pengamat.observe(el);
    });
}

/* ─── Tombol submit: "Memproses..." dan cegah klik dobel ───────── */
function aturTombolSubmit() {
    document.querySelectorAll('form').forEach((form) => {
        // Form GET (pencarian/filter) dan form yang ditandai data-no-loading tidak perlu indikator
        if ((form.method || 'get').toLowerCase() === 'get' || form.hasAttribute('data-no-loading')) return;

        form.addEventListener('submit', (e) => {
            if (e.defaultPrevented) return;
            const tombol = e.submitter || form.querySelector('button[type="submit"], button:not([type])');
            if (!tombol || tombol.dataset.loading) return;

            // Simpan nilai tombol (mis. name="keputusan") karena tombol nonaktif tidak ikut terkirim
            if (tombol.name) {
                const tersembunyi = document.createElement('input');
                tersembunyi.type = 'hidden';
                tersembunyi.name = tombol.name;
                tersembunyi.value = tombol.value;
                form.appendChild(tersembunyi);
            }

            tombol.dataset.loading = '1';
            tombol.style.minWidth = tombol.offsetWidth + 'px';
            tombol.innerHTML = '<span class="spinner" aria-hidden="true"></span><span>Memproses...</span>';
            tombol.classList.add('is-loading');
            setTimeout(() => { tombol.disabled = true; }, 0);
        });
    });
}

/* ─── Konfirmasi sebelum aksi penting (data-confirm="Pesan") ──── */
// Memakai modal sendiri; setelah "Ya" form dikirim ulang dengan tombol yang sama sehingga alurnya tidak berubah.
function tanyaKonfirmasi(pesan) {
    const modal = document.getElementById('modal-konfirmasi');
    if (!modal) return Promise.resolve(window.confirm(pesan));
    modal.querySelector('[data-modal-pesan]').textContent = pesan;
    modal.classList.remove('hidden'); modal.classList.add('flex');
    requestAnimationFrame(() => requestAnimationFrame(() => modal.classList.remove('modal-tutup')));
    const tombolYa = modal.querySelector('[data-modal-ya]');
    tombolYa.focus();

    return new Promise((selesai) => {
        const tutup = (hasil) => {
            modal.classList.add('modal-tutup');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, kurangiGerakan ? 0 : 200);
            tombolYa.removeEventListener('click', ya);
            modal.querySelectorAll('[data-modal-batal]').forEach((b) => b.removeEventListener('click', batal));
            document.removeEventListener('keydown', esc);
            selesai(hasil);
        };
        const ya = () => tutup(true);
        const batal = () => tutup(false);
        const esc = (e) => { if (e.key === 'Escape') tutup(false); };
        tombolYa.addEventListener('click', ya);
        modal.querySelectorAll('[data-modal-batal]').forEach((b) => b.addEventListener('click', batal));
        document.addEventListener('keydown', esc);
    });
}

function aturKonfirmasi() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (form.dataset.dikonfirmasi) { delete form.dataset.dikonfirmasi; return; }
            e.preventDefault();
            e.stopImmediatePropagation();
            const pengirim = e.submitter;
            tanyaKonfirmasi(form.dataset.confirm).then((ok) => {
                if (!ok) return;
                form.dataset.dikonfirmasi = '1';
                pengirim ? form.requestSubmit(pengirim) : form.requestSubmit();
            });
        }, { capture: true });
    });
    document.querySelectorAll('button[data-confirm]').forEach((tombol) => {
        tombol.addEventListener('click', (e) => {
            if (tombol.dataset.dikonfirmasi) { delete tombol.dataset.dikonfirmasi; return; }
            e.preventDefault();
            tanyaKonfirmasi(tombol.dataset.confirm).then((ok) => {
                if (!ok) return;
                if (tombol.form) { tombol.form.requestSubmit(tombol); return; }
                tombol.dataset.dikonfirmasi = '1';
                tombol.click();
            });
        });
    });
}

/* ─── Tampilkan / sembunyikan kata sandi ───────────────────────── */
function aturLihatSandi() {
    document.querySelectorAll('[data-toggle-password]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            const input = document.getElementById(tombol.dataset.togglePassword);
            if (!input) return;
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            tombol.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            tombol.querySelector('[data-ikon-lihat]')?.classList.toggle('hidden', tampil);
            tombol.querySelector('[data-ikon-sembunyi]')?.classList.toggle('hidden', !tampil);
        });
    });
}

/* ─── Progress bar skor kecocokan terisi pelan saat terlihat ───── */
function aturSkorBar() {
    const bar = document.querySelectorAll('.skor-bar');
    if (!bar.length) return;
    if (kurangiGerakan || !('IntersectionObserver' in window)) {
        bar.forEach((el) => el.classList.add('is-filled'));
        return;
    }
    const pengamat = new IntersectionObserver((entri) => {
        entri.forEach((e) => {
            if (!e.isIntersecting) return;
            setTimeout(() => e.target.classList.add('is-filled'), 250);
            pengamat.unobserve(e.target);
        });
    }, { threshold: 0.5 });
    bar.forEach((el) => pengamat.observe(el));
}

/* ─── Tombol favorit (ikon hati): efek pop saat diklik ─────────── */
function aturFavoritPop() {
    document.querySelectorAll('[data-favorit]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            const ikon = tombol.querySelector('svg') || tombol;
            ikon.classList.remove('pop');
            void ikon.offsetWidth; // mulai ulang animasi
            ikon.classList.add('pop');
        });
    });
}

/* ─── Modal berisi formulir (mis. jadwal interview admin) ──────── */
// [data-modal-buka="id"] membuka #id; [data-modal-tutup] / Escape / klik latar menutup.
// Modal ber-atribut data-modal-otomatis langsung dibuka (dipakai saat ada error validasi).
function aturModalForm() {
    const semua = document.querySelectorAll('[data-modal-form]');
    if (!semua.length) return;

    const buka = (modal) => {
        modal.classList.remove('hidden'); modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => requestAnimationFrame(() => modal.classList.remove('modal-tutup')));
        const pertama = modal.querySelector('.input-error') || modal.querySelector('input:not([type=hidden]):not([type=radio]), textarea');
        setTimeout(() => pertama?.focus(), kurangiGerakan ? 0 : 220);
    };
    const tutup = (modal) => {
        modal.classList.add('modal-tutup');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, kurangiGerakan ? 0 : 200);
    };

    document.querySelectorAll('[data-modal-buka]').forEach((tombol) => {
        const modal = document.getElementById(tombol.dataset.modalBuka);
        if (!modal) return;
        tombol.addEventListener('click', () => {
            // Modal bersama (mis. "Akhiri Penyaluran"): aksi & isian diambil dari tombol yang diklik
            if (tombol.dataset.modalAksi) {
                const form = modal.querySelector('form');
                if (form) form.action = tombol.dataset.modalAksi;
                const id = modal.querySelector('[data-modal-isi-id]');
                if (id) id.value = tombol.dataset.modalId || '';
                modal.querySelectorAll('[data-modal-isi-nama]').forEach((el) => { el.textContent = tombol.dataset.modalNama || ''; });
                const namaInput = modal.querySelector('[data-modal-isi-nama-input]');
                if (namaInput) namaInput.value = tombol.dataset.modalNama || '';
                const tanggal = modal.querySelector('[data-modal-isi-min]');
                if (tanggal && tombol.dataset.modalMin) tanggal.min = tombol.dataset.modalMin;
            }
            buka(modal);
        });
    });

    semua.forEach((modal) => {
        modal.querySelectorAll('[data-modal-tutup]').forEach((el) => el.addEventListener('click', () => tutup(modal)));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) tutup(modal); });

        // Label & placeholder lokasi mengikuti metode (Offline = lokasi, Online = link meeting)
        const input = modal.querySelector('[data-lokasi-input]');
        const label = input ? modal.querySelector(`label[for="${input.id}"]`) : null;
        const aturMetode = (nilai) => {
            if (!input) return;
            const online = nilai === 'online';
            input.type = online ? 'url' : 'text';
            input.placeholder = online ? 'https://meet.google.com/abc-defg-hij' : 'Contoh: Kantor PT Shaka Abadi Nusantara, Jl. ...';
            if (label?.firstChild) label.firstChild.textContent = (online ? 'Link meeting' : 'Lokasi interview') + ' ';
        };
        modal.querySelectorAll('[data-metode-pilih]').forEach((radio) => {
            radio.addEventListener('change', () => aturMetode(radio.value));
            if (radio.checked) aturMetode(radio.value);
        });

        if (modal.hasAttribute('data-modal-otomatis')) buka(modal);
    });
}

/* ─── Tombol "Kirim Undangan via WhatsApp" (admin) ─────────────── */
// Form ber-target _blank: server mencatat waktu undangan lalu mengarahkan tab baru ke wa.me.
// Halaman ini dimuat ulang sesaat kemudian agar "Undangan dikirim pada ..." langsung tampil.
function aturKirimWhatsApp() {
    document.querySelectorAll('form[data-kirim-wa]').forEach((form) => {
        form.addEventListener('submit', () => {
            const tombol = form.querySelector('button[type="submit"]');
            if (tombol) { tombol.classList.add('is-loading'); tombol.setAttribute('aria-busy', 'true'); }
            setTimeout(() => window.location.reload(), 1500);
        });
    });
}

/* ─── Foto profil: pratinjau bulat + pemeriksaan awal sebelum diunggah ─ */
// Server tetap memvalidasi ulang (format, 2 MB, minimal 300x300 piksel).
function aturFotoProfil() {
    document.querySelectorAll('[data-foto-profil]').forEach((kartu) => {
        const input = kartu.querySelector('[data-foto-input]');
        const sekarang = kartu.querySelector('[data-foto-sekarang]');
        const pratinjau = kartu.querySelector('[data-foto-pratinjau]');
        const label = kartu.querySelector('[data-foto-label-pratinjau]');
        const simpan = kartu.querySelector('[data-foto-simpan]');
        const batal = kartu.querySelector('[data-foto-batal]');
        const nama = kartu.querySelector('[data-foto-nama]');
        const galat = kartu.querySelector('[data-foto-galat]');
        const galatTeks = kartu.querySelector('[data-foto-galat-teks]');
        if (!input || !pratinjau) return;

        let urlLama = null;
        const tampilGalat = (teks) => {
            galatTeks.textContent = teks;
            galat.classList.toggle('hidden', !teks);
        };
        const reset = () => {
            input.value = '';
            if (urlLama) { URL.revokeObjectURL(urlLama); urlLama = null; }
            pratinjau.classList.add('hidden'); pratinjau.removeAttribute('src');
            label?.classList.add('hidden');
            sekarang.classList.remove('hidden');
            nama.classList.add('hidden');
            batal.classList.add('hidden');
            simpan.disabled = true;
        };

        simpan.disabled = true; // aktif setelah file valid dipilih
        batal.addEventListener('click', () => { reset(); tampilGalat(''); });

        input.addEventListener('change', () => {
            const berkas = input.files[0];
            if (!berkas) { reset(); return; }

            const jenisValid = ['image/jpeg', 'image/png', 'image/webp'].includes(berkas.type);
            if (!jenisValid) { reset(); tampilGalat('File harus berupa gambar JPG, JPEG, PNG, atau WEBP.'); return; }
            if (berkas.size > 2 * 1024 * 1024) { reset(); tampilGalat('Ukuran foto maksimal 2 MB.'); return; }

            if (urlLama) URL.revokeObjectURL(urlLama);
            urlLama = URL.createObjectURL(berkas);
            const gambar = new Image();
            gambar.onload = () => {
                if (gambar.naturalWidth < 300 || gambar.naturalHeight < 300) {
                    reset();
                    tampilGalat('Ukuran foto minimal 300 x 300 piksel (foto Anda ' + gambar.naturalWidth + ' x ' + gambar.naturalHeight + ').');
                    return;
                }
                tampilGalat('');
                pratinjau.src = urlLama;
                pratinjau.classList.remove('hidden');
                label?.classList.remove('hidden');
                sekarang.classList.add('hidden');
                nama.textContent = berkas.name + ' (' + (berkas.size / 1024 / 1024).toFixed(2) + ' MB) - klik "Simpan Foto" untuk menyimpan.';
                nama.classList.remove('hidden');
                batal.classList.remove('hidden');
                simpan.disabled = false;
                if (!kurangiGerakan) { pratinjau.classList.remove('pop'); void pratinjau.offsetWidth; pratinjau.classList.add('pop'); }
            };
            gambar.onerror = () => { reset(); tampilGalat('File tidak dapat dibaca sebagai gambar.'); };
            gambar.src = urlLama;
        });
    });
}
