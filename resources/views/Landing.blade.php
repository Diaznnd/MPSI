<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Workshop')</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO UNAND.PNG') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="bg-gray-100">
    <nav class="sticky top-0 flex justify-between items-center bg-white p-4 px-40 shadow-md">
        <div class="flex items-center">
            <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Universitas Andalas Logo" class="w-12 h-auto mr-4">
            <div>
                <span class="text-lg font-bold text-[#057A55]">SIMAWA UNAND</span>
                <span class="text-sm font-bold text-black block">UPT Perpustakaan Universitas Andalas</span>
            </div>
        </div>
        <ul class="flex space-x-6">
            <li><a href="/" class="text-gray-600 hover:text-[#057A55]">Beranda</a></li>
            <li><a href="/login" class="bg-[#057A55] text-white px-4 py-2 rounded-md hover:bg-[#016545]">Login</a></li>
        </ul>
    </nav>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Workshop Stats Section -->
            <div class="p-3 mb-12">
                <div class="flex items-center justify-between">
                    <div class="w-1/2">
                        <h1 class="text-6xl font-extrabold text-gray-900 py-5">Sistem Informasi Workshop</h1>
                        <h2 class="text-2xl font-bold text-[#057A55]">UPT PERPUSTAKAAN UNIVERSITAS ANDALAS</h2>
                        <p class="mt-4 text-lg text-gray-600">Temukan workshop yang sesuai dengan minat dan kemampuan Anda.</p>
                        <p class="text-lg font-semibold text-[#000000]">Jumlah Workshop Aktif : </p>
                        <span class="text-lg font-semibold text-[#057A55]">{{ $statistics['total_workshop_aktif'] }} Workshop</span>
                    </div>
                    <!-- Gambar -->
                    <div class="w-1/2">
                        <img src="{{ asset('images/perpustakaan.jpg') }}" alt="Workshop" class="w-full rounded-lg shadow-md">
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="bg-[#F8FCFA] rounded-lg p-8 mb-12">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Tentang Kami</h2>
                <p class="text-lg text-gray-600">
                    Kami menyediakan berbagai workshop berkualitas di berbagai bidang. Platform ini memungkinkan Anda untuk belajar dari pemateri ahli dan mengembangkan keterampilan Anda.
                    Temukan workshop yang sesuai dengan kebutuhan Anda dan mulailah perjalanan pembelajaran Anda dengan kami.
                </p>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-8">
                <a href="{{ route('login') }}" class="bg-[#057A55] hover:bg-[#016545] text-white px-6 py-3 rounded-lg text-lg font-medium">
                    Mulai Belajar Sekarang
                </a>
            </div>
        </div>
    </div>
    <footer class="bg-white shadow-mt text-black py-4 mt-12">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-sm">&copy; 2025 Kelompok 3 Matakuliah MPSI. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

