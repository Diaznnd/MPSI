@extends('User.Layout.app')

@section('title', 'My Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Workshop Saya</h1>
            <p class="text-gray-600 mt-1">Daftar workshop yang telah Anda daftarkan</p>
        </div>
        
        <!-- Search Bar Minimalis -->
        <div class="w-full md:w-64">
            <form action="{{ route('pengguna.my-workshop') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari Workshop..." 
                       class="w-full px-4 py-2 pr-10 rounded-md border border-gray-300 bg-white focus:border-[#057A55] focus:outline-none focus:ring-1 focus:ring-[#057A55] text-sm">
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#057A55] transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Workshop Cards Grid -->
    @if($pendaftarans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($pendaftarans as $pendaftaran)
                @php
                    $workshop = $pendaftaran->workshop;
                @endphp
                @if($workshop)
                    <a href="{{ route('pengguna.my-workshop.detail', $workshop->workshop_id) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 block group">
                        <!-- Workshop Image -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($workshop->sampul_poster_url)
                                @php
                                    $imagePath = str_starts_with($workshop->sampul_poster_url, 'http') 
                                        ? $workshop->sampul_poster_url 
                                        : asset('storage/' . $workshop->sampul_poster_url);
                                @endphp
                                <img src="{{ $imagePath }}" 
                                     alt="{{ $workshop->judul }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='{{ asset('images/perpustakaan.jpg') }}';">
                            @else
                                <div class="w-full h-full bg-linier-to-br from-[#057A55] to-[#016545]"></div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute top-2 right-2">
                                @if($workshop->status_workshop === 'aktif')
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Selesai</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Workshop Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-[#057A55] transition-colors truncate">
                                {{ $workshop->judul }}
                            </h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">{{ $workshop->pemateri ? $workshop->pemateri->nama : '-' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $workshop->lokasi ?: '-' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Terdaftar: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d M Y H:i') }}</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                {{ $workshop->deskripsi ?: 'Tidak ada deskripsi' }}
                            </p>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $pendaftarans->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada workshop yang didaftarkan</h3>
            <p class="text-gray-600 mb-6">Mulai daftarkan diri Anda untuk workshop yang tersedia</p>
            <a href="{{ route('pengguna.daftar-workshop') }}" 
               class="inline-flex items-center px-6 py-3 text-white rounded-lg font-medium transition-colors" 
               style="background-color: #057A55;" 
               onmouseover="this.style.backgroundColor='#068b4b';" 
               onmouseout="this.style.backgroundColor='#057A55';">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Daftar Workshop
            </a>
        </div>
    @endif
</div>
@endsection

