@extends('User.Layout.app')

@section('title', 'Daftar Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Ayo Daftar Sekarang!!!</h1>
        </div>
        
        <!-- Search Bar Minimalis -->
        <div class="w-full md:w-64">
            <form action="{{ route('pengguna.daftar-workshop') }}" method="GET" class="relative">
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
    @if($workshops->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($workshops as $workshop)
                <a href="{{ route('pengguna.workshop.detail', $workshop->workshop_id) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 block group">
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
                            <div class="w-full h-full bg-linier-to-br from-[#057A55] to-[#016545] flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <!-- Golden line separator -->
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-yellow-400"></div>
                        
                        <!-- Kuota Badge -->
                        @if($workshop->kuota)
                            @php
                                $kuotaTerisi = $workshop->kuota_terisi ?? 0;
                                $isFull = $kuotaTerisi >= $workshop->kuota;
                            @endphp
                            <div class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold {{ $isFull ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                {{ $kuotaTerisi }}/{{ $workshop->kuota }}
                            </div>
                        @endif
                    </div>

                    <!-- Workshop Content -->
                    <div class="p-5">
                        <!-- Workshop Title -->
                        <h3 class="text-lg font-bold text-gray-900 mb-4 line-clamp-2 min-h-14">
                            {{ $workshop->judul }}
                        </h3>

                        <!-- Workshop Details -->
                        <div class="space-y-3">
                            <!-- Pemateri -->
                            @if($workshop->pemateri)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Pemateri:</span> {{ $workshop->pemateri->nama }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Time -->
                            @if($workshop->waktu)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i') }} WIB
                                    </p>
                                </div>
                            @endif

                            <!-- Date -->
                            @if($workshop->tanggal)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') }}
                                    </p>
                                </div>
                            @endif

                            <!-- Location -->
                            @if($workshop->lokasi)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $workshop->lokasi }}
                                    </p>
                                </div>
                            @endif

                            <!-- Deskripsi (Preview) -->
                            @if($workshop->deskripsi)
                                <div class="pt-2 border-t border-gray-200">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($workshop->deskripsi, 100) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $workshops->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada workshop tersedia</h3>
            <p class="text-gray-600">
                @if($search)
                    Tidak ada workshop yang ditemukan untuk pencarian "{{ $search }}"
                @else
                    Belum ada workshop yang tersedia saat ini.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection

