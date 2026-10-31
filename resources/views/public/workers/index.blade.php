<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Pekerja - Temukerja</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

    <div class="min-h-screen p-8">
        <h1 class="text-3xl font-bold text-green-800">
            Katalog Pekerja
        </h1>

        <p class="mt-2 text-gray-600">
            Temukan pekerja profesional sesuai kebutuhan Anda.
        </p>

        <a href="{{ route('home') }}" class="mt-6 inline-block text-green-700 hover:underline">
            ← Kembali ke Beranda
        </a>
    </div>

</body>

</html>