{{-- Layout halaman publik: navbar + konten + footer. Section opsional 'navbar-transparan' untuk navbar di atas hero berwarna. --}}
@extends('layouts.base')

@section('body-class', 'bg-white')
@section('halaman-publik', '1')

@section('body')
    @include('components.navbar-guest', ['transparan' => View::hasSection('navbar-transparan')])
    <main class="overflow-x-clip @if (View::hasSection('navbar-transparan')) -mt-20 lg:-mt-24 @endif">@yield('content')</main>
    @include('components.footer')
@endsection
