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
    <nav class="sticky top-0 z-50 flex justify-between items-center bg-white p-4 px-40 shadow-md">
        <div class="flex items-center">
            <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Universitas Andalas Logo" class="w-12 h-auto mr-4">
            <div>
                <span class="text-lg font-bold text-[#057A55]">SIMAWA UNAND</span>
                <span class="text-sm font-bold text-black block">UPT Perpustakaan Universitas Andalas</span>
            </div>
        </div>
        <ul class="flex items-center space-x-6">
            <li><a href="/" class="flex items-center text-gray-600 hover:text-[#057A55]">Beranda</a></li>
            <li>
                <a href="/login"class="flex items-center bg-[#057A55] text-white px-4 py-2 rounded-md hover:bg-[#016545]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Login
                </a>
            </li>
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
                        <div class="bg-white rounded-lg shadow-sm p-6 mr-12">
                            <div class="mb-2">
                                <p class="text-5xl font-semibold text-[#057A55]">{{ $statistics['total_workshop_aktif'] }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Gambar -->
                    <div class="w-1/2">
                        <img src="{{ asset('images/perpustakaan.jpg') }}" alt="Workshop" class="w-full rounded-lg shadow-md">
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="bg-[#F8FCFA] rounded-lg p-8 mb-20">
                <h2 class="text-xl text-center font-bold text-gray-900 mb-6">Tentang Kami</h2>
                <p class="text-l text-center text-gray-600">
                    Kami menyediakan berbagai workshop berkualitas di berbagai bidang. Platform ini memungkinkan Anda untuk belajar dari pemateri ahli dan mengembangkan keterampilan Anda.
                    Temukan workshop yang sesuai dengan kebutuhan Anda dan mulailah perjalanan pembelajaran Anda dengan kami.
                </p>
            </div>

            <!-- Popular Workshops Section -->
            <div class="bg-[#ffffff] rounded-lg p-8 mb-12 relative z-10">
                <h2 class="text-xl text-center font-bold text-gray-900 mb-6">Workshop Populer</h2>
                <p class="text-l text-center text-gray-600 mb-8">Workshop dengan pendaftar terbanyak yang sedang dibuka</p>

                @if($popular_workshops->count() > 0)
                <div class="scroll-wrapper">
                    <div class="scroll-loop gap-6 hover-pause-animation">
                        @foreach($popular_workshops as $workshop)
                        <a href="{{ route('admin.workshop.show', $workshop->workshop_id) }}" class="block w-80 mt-4 mb-4 shrink-0 workshop-card">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 cursor-pointer hover:shadow-2xl hover:scale-105 hover:ring-4 hover:ring-[#057A55] hover:ring-opacity-50">
                                <div class="h-48 bg-white overflow-hidden">
                                    @if($workshop->sampul_poster_url)
                                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" 
                                            alt="{{ $workshop->judul }}" 
                                            class="w-full h-full object-contain transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-linier-to-br from-[#057A55] to-[#016545]">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-l font-bold text-gray-900 mb-2 truncate">{{ $workshop->judul }}</h3>
                                    <div class="flex items-center mb-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $workshop->pemateri->nama ?? 'Tidak diketahui' }}
                                    </div>

                                    <div class="flex items-center mb-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                        <span class="text-sm font-semibold text-[#057A55]">{{ $workshop->pendaftaran_count }} Pendaftar</span>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach

                        {{-- Duplicate untuk efek loop tanpa putus --}}
                        @foreach($popular_workshops as $workshop)
                        <a href="{{ route('admin.workshop.show', $workshop->workshop_id) }}" class="block w-80 mt-4 mb-4 shrink-0 workshop-card">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 cursor-pointer hover:shadow-2xl hover:scale-105 hover:ring-4 hover:ring-[#057A55] hover:ring-opacity-50">
                                <div class="h-48 bg-gray-200 overflow-hidden">
                                    @if($workshop->sampul_poster_url)
                                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" 
                                            alt="{{ $workshop->judul }}" 
                                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-linier-to-br from-[#057A55] to-[#016545]">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-l font-bold text-gray-900 mb-2 truncate">{{ $workshop->judul }}</h3>
                                    <div class="flex items-center mb-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $workshop->pemateri->nama ?? 'Tidak diketahui' }}
                                    </div>

                                    <div class="flex items-center mb-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                        <span class="text-sm font-semibold text-[#057A55]">{{ $workshop->pendaftaran_count }} Pendaftar</span>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <p class="text-center text-gray-500">Tidak ada workshop populer saat ini.</p>
                @endif
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-gray-600 mb-12">
                Nikmati kemudahan dalam mengikuti dan mengelola workshop di SIM Workshop
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-white shadow-sm rounded-2xl p-6 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-green-50 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h3.18a1 1 0 01.707.293l1.414 1.414A1 1 0 0010.414 5H20a1 1 0 011 1v11a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                    </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Katalog Workshop</h3>
                    <p class="text-gray-600 text-sm">
                    Jelajahi berbagai workshop yang tersedia dengan mudah dan cepat.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow-sm rounded-2xl p-6 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-blue-50 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c0-1.1.9-2 2-2h6m-6 0a2 2 0 012-2V5a2 2 0 00-2-2h-1a1 1 0 00-.707.293L8.586 6H4a1 1 0 00-1 1v10a1 1 0 001 1h8" />
                    </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pendaftaran Digital</h3>
                    <p class="text-gray-600 text-sm">
                    Daftar workshop secara online tanpa perlu mengisi formulir manual.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white shadow-sm rounded-2xl p-6 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-yellow-50 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6l4 2m5 8H3a1 1 0 01-1-1V5a1 1 0 011-1h7l2 2h9a1 1 0 011 1v14a1 1 0 01-1 1z" />
                    </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Materi Setelah Workshop</h3>
                    <p class="text-gray-600 text-sm">
                    Akses materi workshop yang telah kamu ikuti secara mudah kapan saja.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-white shadow-sm rounded-2xl p-6 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m2-5a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2h12z" />
                    </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sertifikat Digital</h3>
                    <p class="text-gray-600 text-sm">
                    Dapatkan sertifikat digital otomatis setelah workshop selesai.
                    </p>
                </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-20">
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

