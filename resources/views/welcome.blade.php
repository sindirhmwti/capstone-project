@php
    // Redirect root welcome to public home
    header('Location: ' . route('home'));
    exit();
@endphp
