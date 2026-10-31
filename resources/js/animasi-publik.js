/**
 * Animasi halaman publik (beranda, login, registrasi): GSAP + ScrollTrigger, Lenis, dan Swiper.
 * Dimuat terpisah (dynamic import) agar dashboard tetap ringan.
 * Semua efek dilewati bila pengguna mengaktifkan "kurangi gerakan" (prefers-reduced-motion).
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade, Keyboard, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

gsap.registerPlugin(ScrollTrigger);

const kurangiGerakan = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const EASE = 'power3.out';

export function mulaiAnimasiPublik() {
    aturGaleri();

    if (kurangiGerakan) {
        tampilkanSemua();
        return;
    }

    const lenis = aturLenis();
    const jedaPreloader = aturPreloader();
    animasiHero(jedaPreloader);
    animasiAuth();
    animasiScroll();
    animasiTimeline();
    animasiParallax();

    // Hitung ulang posisi setelah gambar selesai dimuat
    window.addEventListener('load', () => ScrollTrigger.refresh());
    return lenis;
}

/* ─── Tanpa animasi: pastikan semua elemen terlihat ─────────────── */
function tampilkanSemua() {
    document.getElementById('preloader')?.remove();
    document.querySelectorAll('[data-hero-item], [data-auth-item], [data-split]').forEach((el) => { el.style.opacity = 1; });
}

/* ─── Smooth scroll (Lenis) + sinkron dengan ScrollTrigger ──────── */
function aturLenis() {
    document.documentElement.classList.remove('scroll-smooth');
    const lenis = new Lenis({ duration: 1.1, smoothWheel: true, anchors: { offset: -80 } });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((waktu) => lenis.raf(waktu * 1000));
    gsap.ticker.lagSmoothing(0);
    // Hentikan smooth scroll saat panel menu HP / modal terbuka (body overflow-hidden)
    new MutationObserver(() => {
        document.body.classList.contains('overflow-hidden') ? lenis.stop() : lenis.start();
    }).observe(document.body, { attributes: true, attributeFilter: ['class'] });
    return lenis;
}

/* ─── Loading awal: logo muncul lalu memudar (beranda, sekali per sesi) ─ */
function aturPreloader() {
    const el = document.getElementById('preloader');
    if (!el) return 0;
    try { sessionStorage.setItem('tk-preload', '1'); } catch (e) { /* abaikan */ }
    const tl = gsap.timeline({ onComplete: () => el.remove() });
    tl.fromTo(el.querySelector('[data-preloader-logo]'), { scale: 0.8, autoAlpha: 0 }, { scale: 1, autoAlpha: 1, duration: 0.35, ease: 'back.out(1.7)' })
      .to(el.querySelector('[data-preloader-bar]'), { scaleX: 1, duration: 0.3, ease: 'power1.inOut' }, '<0.05')
      .to(el, { autoAlpha: 0, duration: 0.25, ease: 'power2.in' }, '+=0.05');
    return 0.7;
}

/* ─── Pecah teks menjadi kata agar bisa muncul per kata dari bawah ─ */
function pecahKata(el) {
    const kata = [];
    const proses = (node) => {
        [...node.childNodes].forEach((anak) => {
            if (anak.nodeType === Node.TEXT_NODE) {
                const frag = document.createDocumentFragment();
                anak.textContent.split(/(\s+)/).forEach((bagian) => {
                    if (!bagian) return;
                    if (/^\s+$/.test(bagian)) { frag.appendChild(document.createTextNode(bagian)); return; }
                    const luar = document.createElement('span');
                    luar.className = 'inline-block overflow-hidden align-bottom pb-[0.08em] -mb-[0.08em]';
                    const isi = document.createElement('span');
                    isi.className = 'inline-block will-change-transform';
                    isi.textContent = bagian;
                    luar.appendChild(isi);
                    frag.appendChild(luar);
                    kata.push(isi);
                });
                anak.replaceWith(frag);
            } else if (anak.nodeType === Node.ELEMENT_NODE && !anak.matches('svg')) {
                proses(anak);
            }
        });
    };
    proses(el);
    return kata;
}

/* ─── Hero beranda ──────────────────────────────────────────────── */
function animasiHero(jeda) {
    const judul = document.querySelector('[data-split]');
    const foto = document.querySelector('[data-hero-foto]');
    if (!judul && !foto) return;

    const tl = gsap.timeline({ delay: jeda, defaults: { ease: EASE } });
    if (foto) tl.fromTo(foto, { scale: 1.12 }, { scale: 1, duration: 1.8, ease: 'power2.out' }, 0);
    if (judul) {
        const kata = pecahKata(judul);
        gsap.set(judul, { opacity: 1 });
        tl.from(kata, { yPercent: 110, duration: 0.8, stagger: 0.05 }, 0.1);
    }
    tl.fromTo('[data-hero-item]', { y: 28, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, stagger: 0.1 }, 0.45);
    tl.from('[data-hero-kartu]', { y: 30, opacity: 0, scale: 0.9, duration: 0.6, stagger: 0.12, ease: 'back.out(1.6)', clearProps: 'transform' }, 0.8);

    // Parallax ringan foto hero saat di-scroll
    const bingkai = document.querySelector('[data-hero-parallax]');
    if (bingkai) {
        gsap.to(bingkai, { yPercent: 10, ease: 'none', scrollTrigger: { trigger: bingkai, start: 'top top', end: 'bottom top', scrub: true } });
    }
}

/* ─── Login / registrasi: teks sambutan bertahap ────────────────── */
function animasiAuth() {
    const item = document.querySelectorAll('[data-auth-item]');
    if (!item.length) return;
    gsap.fromTo(item, { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, stagger: 0.12, ease: EASE, delay: 0.15 });
}

/* ─── Elemen yang muncul saat di-scroll ─────────────────────────── */
function animasiScroll() {
    // Grup kartu bertahap: <div data-stagger> ...anak... </div>
    document.querySelectorAll('[data-stagger]').forEach((grup) => {
        const anak = [...grup.children];
        // Transisi CSS (hover) dimatikan sementara agar tidak bentrok dengan GSAP, lalu dikembalikan
        gsap.set(anak, { transition: 'none', y: 50, opacity: 0 });
        ScrollTrigger.create({
            trigger: grup, start: 'top 88%', once: true,
            onEnter: () => gsap.to(anak, {
                y: 0, opacity: 1, duration: 0.8, ease: EASE, stagger: 0.12,
                onComplete: () => gsap.set(anak, { clearProps: 'transition,transform,opacity' }),
            }),
        });
    });
    // Masuk dari samping: data-masuk="kiri" | "kanan" | "bawah"
    document.querySelectorAll('[data-masuk]').forEach((el) => {
        const arah = el.dataset.masuk;
        gsap.from(el, {
            x: arah === 'kiri' ? -70 : arah === 'kanan' ? 70 : 0,
            y: arah === 'bawah' ? 40 : 0,
            opacity: 0, duration: 0.9, ease: EASE,
            scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        });
    });
}

/* ─── Garis timeline "Cara Kerja" tergambar mengikuti scroll ────── */
function animasiTimeline() {
    const wadah = document.querySelector('[data-timeline]');
    if (!wadah) return;
    const desktop = window.matchMedia('(min-width: 1024px)').matches;
    const garis = wadah.querySelector(desktop ? '[data-timeline-garis="x"]' : '[data-timeline-garis="y"]');
    if (garis) {
        gsap.fromTo(garis, desktop ? { scaleX: 0 } : { scaleY: 0 }, {
            [desktop ? 'scaleX' : 'scaleY']: 1, ease: 'none',
            scrollTrigger: { trigger: wadah, start: 'top 75%', end: 'bottom 60%', scrub: 0.6 },
        });
    }
    wadah.querySelectorAll('[data-langkah]').forEach((langkah, i) => {
        gsap.from(langkah, {
            y: 40, opacity: 0, duration: 0.7, ease: EASE, delay: desktop ? i * 0.12 : 0,
            scrollTrigger: { trigger: desktop ? wadah : langkah, start: 'top 80%', once: true },
        });
    });
}

/* ─── Parallax latar (CTA, dll.): data-parallax="12" (persen) ───── */
function animasiParallax() {
    document.querySelectorAll('[data-parallax]').forEach((el) => {
        const besar = Number(el.dataset.parallax || 12);
        gsap.fromTo(el, { yPercent: -besar }, {
            yPercent: besar, ease: 'none',
            scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
        });
    });
}

/* ─── Galeri foto (Swiper) ──────────────────────────────────────── */
function aturGaleri() {
    document.querySelectorAll('[data-galeri]').forEach((el) => {
        new Swiper(el, {
            modules: [Autoplay, Pagination, Keyboard, A11y, EffectFade],
            loop: true,
            speed: kurangiGerakan ? 0 : 800,
            spaceBetween: 20,
            slidesPerView: 1.15,
            centeredSlides: false,
            grabCursor: true,
            keyboard: { enabled: true },
            autoplay: kurangiGerakan ? false : { delay: 3800, disableOnInteraction: false, pauseOnMouseEnter: true },
            pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
            breakpoints: { 640: { slidesPerView: 2.1 }, 1024: { slidesPerView: 3 }, 1536: { slidesPerView: 3.4 } },
            a11y: { prevSlideMessage: 'Foto sebelumnya', nextSlideMessage: 'Foto berikutnya', paginationBulletMessage: 'Ke foto {{index}}' },
        });
    });
}
