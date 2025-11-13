@extends('User.Layout.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-[#057A55] to-[#016545] rounded-xl shadow-lg p-6 md:p-8 mb-6 md:mb-8 text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-2 text-black">Selamat Datang, {{ Auth::user()->nama }}!</h2>
        <p class="text-sm md:text-base font-medium" style="color: #057A55;">Kelola workshop Anda dan daftarkan diri untuk workshop yang tersedia.</p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $request }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
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

