{{-- Layout halaman publik: navbar + konten + footer --}}
@extends('layouts.base')

@section('body-class', 'bg-white')

@section('body')
    @include('components.navbar-guest')
    <main>@yield('content')</main>
    @include('components.footer')
@endsection
