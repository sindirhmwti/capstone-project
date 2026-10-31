{{-- Kerangka dashboard bersama untuk Admin, Klien, dan Pekerja. Variabel $role: admin | client | worker --}}
@extends('layouts.base')

@section('body')
<div class="flex min-h-screen">
    @include('components.sidebar-' . $role)

    <div class="flex min-w-0 flex-1 flex-col lg:pl-72">
        @include('components.header-dashboard', ['role' => $role])

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mx-auto w-full max-w-7xl">
                @include('components.form-errors')
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-line px-4 py-4 text-xs text-muted sm:px-6 lg:px-8 print:hidden">
            <div class="mx-auto flex max-w-7xl flex-col gap-1 sm:flex-row sm:justify-between">
                <span>Temukerja — Menghubungkan kebutuhan dengan keahlian.</span>
                <span>&copy; {{ date('Y') }} PT Shaka Abadi Nusantara</span>
            </div>
        </footer>
    </div>
</div>
@endsection
