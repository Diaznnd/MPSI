@extends('Admin.Layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li><a href="{{ route('admin.request.index') }}" class="hover:text-gray-900">Request Workshop</a></li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Detail Request</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Request Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Info Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $request->judul }}</h1>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($request->status_request === 'menunggu' || $request->status_request === null || $request->status_request === '') 
                                bg-yellow-100 text-yellow-700
                            @elseif($request->status_request === 'disetujui')
                                bg-green-100 text-green-700
                            @elseif($request->status_request === 'ditolak')
                                bg-red-100 text-red-700
                            @else
                                bg-gray-100 text-gray-700
                            @endif">
                            @if($request->status_request === 'menunggu' || $request->status_request === null || $request->status_request === '')
                                Menunggu
                            @elseif($request->status_request === 'disetujui')
                                Disetujui
                            @elseif($request->status_request === 'ditolak')
                                Ditolak
                            @else
                                {{ ucfirst($request->status_request) }}
                            @endif
                        </span>
                    </div>

                    <!-- Request Info -->
                    <div class="space-y-4 mb-6">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Oleh</span>
                            <p class="text-gray-900">{{ $request->user->nama ?? 'N/A' }} ({{ $request->user->email ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tanggal Request</span>
                            <p class="text-gray-900">
                                @if($request->tanggal_tanggapan)
                                    {{ \Carbon\Carbon::parse($request->tanggal_tanggapan)->translatedFormat('d F Y') }}
                                @else
                                    Belum ada tanggal
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi Request</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $request->deskripsi }}</p>
                        </div>
                    </div>

                    <!-- Admin Notes (if exists) -->
                    @if($request->catatan_admin)
                    <div class="mt-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Catatan Admin</h2>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $request->catatan_admin }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Update Status Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Update Status</h2>
                    
                    <form action="{{ route('admin.request.updateStatus', $request->request_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Status Selection -->
                        <div class="mb-6">
                            <label for="status_request" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Request
                            </label>
                            <select name="status_request" id="status_request" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#068B4B] focus:border-transparent">
                                <option value="menunggu" {{ ($request->status_request === 'menunggu' || $request->status_request === null || $request->status_request === '') ? 'selected' : '' }}>
                                    Menunggu
                                </option>
                                <option value="disetujui" {{ $request->status_request === 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="ditolak" {{ $request->status_request === 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                            @error('status_request')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Admin Notes -->
                        <div class="mb-6">
                            <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Admin (Opsional)
                            </label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#068B4B] focus:border-transparent"
                                      placeholder="Tambahkan catatan untuk request ini...">{{ old('catatan_admin', $request->catatan_admin) }}</textarea>
                            @error('catatan_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg font-medium transition-colors">
                            Update Status
                        </button>
                    </form>

                    <!-- Back Button -->
                    <a href="{{ route('admin.request.index') }}" 
                       class="block mt-4 text-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

