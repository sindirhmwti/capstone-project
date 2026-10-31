import './bootstrap';

/**
 * Temukerja — interaksi ringan tanpa library tambahan.
 * Semua animasi dimatikan bila pengguna mengaktifkan "kurangi gerakan" (prefers-reduced-motion).
 */
const kurangiGerakan = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('DOMContentLoaded', () => {
    aturSidebar();
    aturMenuPublik();
    aturToast();
    aturReveal();
    aturHitungNaik();
    aturTombolSubmit();
    aturKonfirmasi();
    aturLihatSandi();
    aturPilihBerkas();
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
    const tombol = document.querySelector('[data-menu-toggle]');
    const menu = document.getElementById('menu-publik');
    if (!tombol || !menu) return;

    tombol.addEventListener('click', () => {
        const terbuka = tombol.getAttribute('aria-expanded') === 'true';
        tombol.setAttribute('aria-expanded', String(!terbuka));
        menu.classList.toggle('hidden', terbuka);
        tombol.querySelector('[data-ikon-buka]')?.classList.toggle('hidden', !terbuka);
        tombol.querySelector('[data-ikon-tutup]')?.classList.toggle('hidden', terbuka);
    });
    menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => tombol.click()));
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
        entri.forEach((e) => {
            if (!e.isIntersecting) return;
            const jeda = Number(e.target.dataset.revealDelay || 0);
            setTimeout(() => e.target.classList.add('is-visible'), jeda);
            pengamat.unobserve(e.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    elemen.forEach((el) => pengamat.observe(el));
}

/* ─── Angka berjalan pada kartu statistik ──────────────────────── */
function aturHitungNaik() {
    document.querySelectorAll('[data-count]').forEach((el) => {
        const target = Number(el.dataset.count);
        if (kurangiGerakan || !Number.isFinite(target) || target <= 0) {
            el.textContent = el.dataset.count;
            return;
        }
        const durasi = 700;
        const mulai = performance.now();
        const langkah = (sekarang) => {
            const p = Math.min((sekarang - mulai) / durasi, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(langkah);
        };
        el.textContent = '0';
        requestAnimationFrame(langkah);
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
function aturKonfirmasi() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        }, { capture: true });
    });
    document.querySelectorAll('button[data-confirm]').forEach((tombol) => {
        tombol.addEventListener('click', (e) => {
            if (!window.confirm(tombol.dataset.confirm)) e.preventDefault();
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
