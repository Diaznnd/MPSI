@extends('User.Layout.app')

@section('title', 'Request Workshop')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Request Workshop</h1>
        <p class="text-gray-600">Ajukan ide workshop yang ingin Anda ikuti. Admin akan meninjau request Anda.</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-red-700 font-medium mb-1">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Request Workshop Form -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <form action="{{ route('pengguna.request-workshop.store') }}" method="POST" id="requestWorkshopForm">
            @csrf

            <!-- Judul Workshop -->
            <div class="mb-6">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Workshop <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="judul" 
                       name="judul" 
                       value="{{ old('judul') }}"
                       required
                       maxlength="255"
                       placeholder="Contoh: Workshop Public Speaking untuk Mahasiswa"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-[#057A55] focus:outline-none focus:ring-2 focus:ring-[#057A55] focus:ring-opacity-20 transition-colors @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal 255 karakter</p>
            </div>

            <!-- Deskripsi Workshop -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Workshop <span class="text-red-500">*</span>
                </label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="8"
                          required
                          maxlength="2000"
                          placeholder="Jelaskan secara detail tentang workshop yang Anda inginkan. Misalnya: tujuan workshop, topik yang akan dibahas, manfaat yang akan didapat peserta, dll."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-[#057A55] focus:outline-none focus:ring-2 focus:ring-[#057A55] focus:ring-opacity-20 transition-colors resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-1 flex justify-between items-center">
                    <p class="text-xs text-gray-500">Maksimal 2000 karakter</p>
                    <p class="text-xs text-gray-500" id="charCount">0 / 2000 karakter</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium mb-1">Informasi Penting:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Request Anda akan ditinjau oleh admin</li>
                            <li>Admin akan memberikan tanggapan melalui catatan</li>
                            <li>Status request dapat dilihat di halaman ini</li>
                            <li>Pastikan judul dan deskripsi jelas dan detail</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 text-white rounded-lg font-medium transition-colors flex items-center justify-center" 
                        style="background-color: #057A55;" 
                        onmouseover="this.style.backgroundColor='#068b4b';" 
                        onmouseout="this.style.backgroundColor='#057A55';"
                        id="submitBtn">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Kirim Request
                </button>
                <a href="{{ route('pengguna.daftar-workshop') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors hover:bg-gray-300 text-center flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- My Requests Section -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Request Saya</h2>

        @if($myRequests->count() > 0)
            <div class="space-y-4">
                @foreach($myRequests as $req)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $req->judul }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $req->deskripsi }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500 flex-wrap gap-2">
                                    <span class="flex items-center">
                                        Status: 
                                        @if($req->status_request === 'menunggu' || !$req->status_request || $req->status_request === '')
                                            <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium">Menunggu</span>
                                        @elseif($req->status_request === 'disetujui')
                                            <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full font-medium">Disetujui</span>
                                        @elseif($req->status_request === 'ditolak')
                                            <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full font-medium">Ditolak</span>
                                        @else
                                            <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 rounded-full font-medium">{{ $req->status_request }}</span>
                                        @endif
                                    </span>
                                    @if($req->tanggal_tanggapan)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Tanggapan: {{ \Carbon\Carbon::parse($req->tanggal_tanggapan)->translatedFormat('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                                @if($req->catatan_admin)
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs font-medium text-gray-700 mb-1">Catatan Admin:</p>
                                        <p class="text-sm text-gray-600">{{ $req->catatan_admin }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Belum ada request workshop</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<script>
    // Character counter for deskripsi
    const deskripsiTextarea = document.getElementById('deskripsi');
    const charCount = document.getElementById('charCount');
    
    if (deskripsiTextarea && charCount) {
        // Update character count on input
        deskripsiTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength + ' / 2000 karakter';
            
            // Change color if approaching limit
            if (currentLength > 1800) {
                charCount.classList.add('text-red-600');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-600');
                charCount.classList.add('text-gray-500');
            }
        });
        
        // Set initial character count
        const initialLength = deskripsiTextarea.value.length;
        charCount.textContent = initialLength + ' / 2000 karakter';
    }
    
    // Form submission handler
    const form = document.getElementById('requestWorkshopForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
            `;
        });
    }
</script>
@endpush
@endsection

