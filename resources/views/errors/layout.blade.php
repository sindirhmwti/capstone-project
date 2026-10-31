<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('kode') · @yield('judul') — Temukerja</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icon.svg') }}">
    {{-- Gaya ditulis langsung agar halaman galat tetap tampil walau aset Vite tidak tersedia --}}
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #F6F8F7; font-family: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif; color: #13201B; padding: 24px; box-sizing: border-box; }
        .kartu { background: #fff; border: 1px solid #eef1ef; border-radius: 20px; padding: 40px 32px; max-width: 460px; width: 100%; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .logo { font-weight: 700; font-size: 20px; margin-bottom: 24px; }
        .logo span { color: #246C54; }
        .kode { font-size: 64px; font-weight: 800; color: #246C54; line-height: 1; margin: 0; }
        h1 { font-size: 20px; margin: 12px 0 8px; }
        p { color: #4D5C55; font-size: 14px; line-height: 1.6; margin: 0; }
        .aksi { margin-top: 28px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        a { text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 18px; border-radius: 12px; }
        .utama { background: #246C54; color: #fff; }
        .kedua { border: 1px solid #dfe7e2; color: #4D5C55; }
    </style>
</head>
<body>
    <div class="kartu">
        <div class="logo"><img src="{{ asset('images/logo-temukerja.svg') }}" alt="temukerja." height="40"></div>
        <p class="kode">@yield('kode')</p>
        <h1>@yield('judul')</h1>
        <p>@yield('pesan')</p>
        <div class="aksi">
            <a href="javascript:history.back()" class="kedua">← Kembali</a>
            @auth
                <a href="{{ route(auth()->user()->routeDashboard()) }}" class="utama">Ke Dashboard</a>
            @else
                <a href="{{ route('home') }}" class="utama">Ke Beranda</a>
            @endauth
        </div>
    </div>
</body>
</html>
