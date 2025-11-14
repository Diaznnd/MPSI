@extends('User.Layout.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="bg-ffffff rounded-xl shadow-lg p-3 md:p-8 mb-6 md:mb-8 text-white">
            <div class="flex items-center justify-between">
                <div class="w-1/2"> 
                    <h2 class="text-2xl md:text-3xl font-bold mb-2 text-black">Selamat Datang, {{ Auth::user()->nama }}!</h2>
                    <p class="text-sm md:text-base font-medium" style="color: #057A55;">Kelola workshop Anda dan daftarkan diri untuk workshop yang tersedia.</p>
                </div>
                <div class="w-1/2 flex justify-end">
                    <img src="{{ asset('images/perpustakaan.jpg') }}" alt="Universitas Andalas Logo" class="w-100 rounded-lg justify-end items-end shadow-md">
                </div> 
            </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4" style="border-left-color: #057A55;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Workshop Saya</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $workshopSaya }}</p>
                </div>
                <div class="p-3 rounded-full" style="background-color: rgba(5, 122, 85, 0.1);">
                    <svg class="w-8 h-8" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $terdaftar }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Request</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRequest }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#ffffff] rounded-lg p-8 mb-12 relative z-10">
                <h2 class="text-xl text-center font-bold text-gray-900 mb-6">Workshop Populer</h2>
                <p class="text-l text-center text-gray-600 mb-8">Workshop dengan pendaftar terbanyak yang sedang dibuka</p>

                @if($popular_workshops->count() > 0)
                <div class="scroll-wrapper">
                    <div class="scroll-loop gap-6 hover-pause-animation">
                        @foreach($popular_workshops as $workshop)
                        <a href="{{ route('pengguna.daftar-workshop') }}" class="block w-80 mt-4 mb-4 shrink-0 workshop-card">
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

                        {{-- Duplicate untuk efek loop tanpa putus --}}
                        @foreach($popular_workshops as $workshop)
                        <a href="{{ route('pengguna.daftar-workshop') }}" class="block w-80 mt-4 mb-4 shrink-0 workshop-card">
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

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Riwayat Workshop</h3>
            <a href="{{ route('pengguna.my-workshop') }}" class="text-sm text-[#057A55] hover:underline">Lihat Semua</a>
        </div>
        
        @if($riwayatWorkshop->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="border-b text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-6 py-3">Judul Workshop</th>
                            <th class="px-6 py-3">Pemateri</th>
                            <th class="px-6 py-3">Tanggal Workshop</th>
                            <th class="px-6 py-3">Tanggal Daftar</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatWorkshop as $pendaftaran)
                            @php
                                $workshop = $pendaftaran->workshop;
                                $statusColor = match($workshop->status_workshop ?? 'nonaktif') {
                                    'aktif' => 'bg-green-100 text-green-700 border-green-300',
                                    'penuh' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                    'selesai' => 'bg-blue-100 text-blue-700 border-blue-300',
                                    'nonaktif' => 'bg-red-100 text-red-700 border-red-300',
                                    default => 'bg-gray-100 text-gray-700 border-gray-300',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center px-4 py-3">{{ $loop->iteration }}.</td>
                                <td class="px-6 py-3 truncate">{{ $workshop->judul ?? '-' }}</td>
                                <td class="px-6 py-3 truncate">{{ $workshop->pemateri->nama ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    @if($workshop->tanggal)
                                        {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if($pendaftaran->tanggal_daftar)
                                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs border {{ $statusColor }}">
                                        {{ ucfirst($workshop->status_workshop ?? 'nonaktif') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Belum ada riwayat workshop</p>
                <a href="{{ route('pengguna.daftar-workshop') }}" class="mt-4 inline-block text-[#057A55] hover:underline">Daftar Workshop Sekarang</a>
            </div>
        @endif
    </div>
@endsection

