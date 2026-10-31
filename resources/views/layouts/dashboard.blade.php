{{-- Kerangka dashboard bersama untuk Admin, Klien, dan Pekerja. Variabel $role: admin | client | worker --}}
@extends('layouts.base')

@section('body')
<div class="flex min-h-screen">
    @include('components.sidebar-' . $role)

    <div class="flex min-w-0 flex-1 flex-col lg:pl-72" data-main>
        @include('components.header-dashboard', ['role' => $role])

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
            <div class="w-full">
                @include('components.form-errors')
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-line bg-white px-4 py-5 text-sm text-muted sm:px-6 lg:px-10 print:hidden">
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
                <span>temukerja — Menghubungkan kebutuhan dengan keahlian.</span>
                <span>&copy; {{ date('Y') }} PT Shaka Abadi Nusantara</span>
            </div>
        </footer>
    </div>
</div>
@endsection
