@extends('Admin.Layout.app')

@section('content')
<div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Request Workshop</li>
                </ol>
            </nav>

            <!-- Title Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Daftar Request Workshop</h1>
                    <p class="mt-2 text-sm text-gray-600">Ketahui Keinginan Pengguna Melalui Request Workshop</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Request Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Request</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_request']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium {{ $stats['total_request']['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                        ↑ {{ $stats['total_request']['change'] }}%
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Last 7 days</span>
                </div>
            </div>

            <!-- Request Menunggu Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Menunggu</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['request_menunggu']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-yellow-600">
                        Menunggu
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Status</span>
                </div>
            </div>

            <!-- Request Disetujui Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Disetujui</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['request_disetujui']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-green-600">
                        Disetujui
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Status</span>
                </div>
            </div>

            <!-- Request Ditolak Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Ditolak</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['request_ditolak']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-red-600">
                        Ditolak
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Status</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 flex justify-end items-end">
            <form method="GET" action="{{ route('admin.request.index') }}" class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <select name="date" 
                            class="border-none focus:outline-none focus:ring-0 text-sm text-gray-700">
                        <option value="today" {{ $filterDate === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $filterDate === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $filterDate === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="all" {{ $filterDate === 'all' ? 'selected' : '' }}>All Time</option>
                    </select>
                </div>

                <div class="flex items-center border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <select name="status" 
                            class="border-none focus:outline-none focus:ring-0 text-sm text-gray-700">
                        <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="menunggu" {{ $filterStatus === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ $filterStatus === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $filterStatus === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <button type="submit" 
                        class="px-6 py-2 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>

                @if($filterDate !== 'all' || $filterStatus !== 'all')
                <a href="{{ route('admin.request.index') }}" 
                   class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Request List -->
        <div class="space-y-4">
            @forelse($requests as $request)
            <div class="bg-white border-2 border-green-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                <!-- Title -->
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $request->judul }}</h3>
                
                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                    {{ $request->deskripsi }}
                </p>
                
                <!-- Requester and Date -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-sm text-gray-600">Oleh {{ $request->user->email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">
                            @if($request->tanggal_tanggapan)
                                {{ \Carbon\Carbon::parse($request->tanggal_tanggapan)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- Status and Action -->
                <div class="flex items-center justify-between">
                    <div>
                        @if($request->status_request === 'menunggu' || $request->status_request === null || $request->status_request === '')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">
                                Menunggu
                            </span>
                        @elseif($request->status_request === 'disetujui')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                Disetujui
                            </span>
                        @elseif($request->status_request === 'ditolak')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                Ditolak
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                {{ ucfirst($request->status_request) }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('admin.request.show', $request->request_id) }}" 
                       class="px-4 py-2 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg text-sm font-medium transition-colors">
                        Review
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                <p class="text-gray-500">Tidak ada request workshop yang ditemukan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

