<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** Halaman login (satu halaman untuk admin, klien, dan pekerja). */
    public function showLogin()
    {
        return view('public.login');
    }

    /** Halaman pilih akun: Klien atau Pekerja. */
    public function showSelectRole()
    {
        return view('public.select-role');
    }

    /** Halaman pilih jenis pekerja: ART, Baby Sitter, atau Perawat Lansia. */
    public function showSelectCategory()
    {
        return view('public.select-category');
    }

    /** Formulir registrasi klien. */
    public function showRegisterKlien()
    {
        return view('public.register', ['peran' => Pengguna::PERAN_KLIEN, 'kategori' => null]);
    }

    /** Formulir registrasi pekerja sesuai kategori. */
    public function showRegisterPekerja(string $kategori)
    {
        abort_unless(array_key_exists($kategori, ProfilPekerja::KATEGORI), 404);

        return view('public.register', ['peran' => Pengguna::PERAN_PEKERJA, 'kategori' => $kategori]);
    }

    /** Memproses login. */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Batasi percobaan login: 5 kali per menit per email + IP
        $kunci = Str::lower($data['email']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($kunci)]),
            ]);
        }

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($kunci);

            throw ValidationException::withMessages(['email' => 'Email atau kata sandi salah.']);
        }

        RateLimiter::clear($kunci);
        /** @var Pengguna $pengguna */
        $pengguna = Auth::user();

        // Akun nonaktif tidak boleh masuk
        if (! $pengguna->isAktif()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withInput($request->only('email'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin PT Shaka Abadi Nusantara.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route($pengguna->routeDashboard()))
            ->with('success', 'Selamat datang kembali, ' . $pengguna->nama . '!');
    }

    /**
     * Memproses formulir registrasi klien maupun calon pekerja.
     * Akun BELUM dibuat di sini: data disimpan sementara di session (kata sandi sudah di-hash),
     * lalu pengguna diarahkan ke halaman verifikasi nomor WhatsApp (OTP).
     * Akun dibuat oleh VerifikasiWhatsAppController setelah kode benar.
     */
    public function register(Request $request, WhatsAppOtpService $otp)
    {
        $isPekerja = $request->input('peran') === Pengguna::PERAN_PEKERJA;

        // Rapikan penulisan nomor (spasi, tanda hubung, titik, kurung) sebelum divalidasi
        $request->merge(['no_telepon' => preg_replace('/[\s\-().]/', '', (string) $request->input('no_telepon'))]);

        $aturan = [
            // Peran admin ditolak walaupun formulir dimanipulasi
            'peran' => ['required', Rule::in([Pengguna::PERAN_KLIEN, Pengguna::PERAN_PEKERJA])],
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pengguna,email'],
            'no_telepon' => [
                'required',
                'regex:/^(\+62|62|0)8[0-9]{7,12}$/',
                function (string $atribut, mixed $nilai, \Closure $gagal) {
                    // Cek semua variasi penulisan (628..., +628..., 08...) agar data lama ikut diperiksa
                    $nomor = WhatsAppOtpService::normalisasi($nilai);
                    if (Pengguna::whereIn('no_telepon', WhatsAppOtpService::variasi($nomor))->exists()) {
                        $gagal('Nomor WhatsApp ini sudah terdaftar pada akun lain.');
                    }
                },
            ],
            'kata_sandi' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms' => ['accepted'],
        ];

        if ($isPekerja) {
            $aturan += [
                'kategori_pekerjaan' => ['required', Rule::in(array_keys(ProfilPekerja::KATEGORI))],
                'alamat' => ['required', 'string', 'max:255'],
                'keterampilan' => ['nullable', 'string', 'max:255'],
                'deskripsi_profil' => ['nullable', 'string', 'max:1000'],
            ];
        }

        $data = $request->validate($aturan, [
            'no_telepon.required' => 'Nomor WhatsApp wajib diisi.',
            'no_telepon.regex' => 'Nomor WhatsApp harus diawali 08 / 62 / +62 dan berisi 10-15 angka.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        // Data sementara: email huruf kecil, nomor seragam 628..., kata sandi sudah di-hash (bukan teks asli)
        unset($data['kata_sandi_confirmation'], $data['terms']);
        $data['email'] = Str::lower($data['email']);
        $data['no_telepon'] = WhatsAppOtpService::normalisasi($data['no_telepon']);
        $data['kata_sandi'] = Hash::make($data['kata_sandi']);

        $request->session()->put(VerifikasiWhatsAppController::SESI, [
            'data' => $data,
            'dibuat' => now()->timestamp,
            'kirim_terakhir' => now()->timestamp,
            'percobaan' => 0,
            'kunci_sampai' => 0,
        ]);

        $otp->kirimKode($data['no_telepon']);

        return redirect()->route('register.verifikasi');
    }

    /** Mengakhiri sesi pengguna. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
