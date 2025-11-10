@extends('User.Layout.app')

@section('title', 'Daftar Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer group" 
                     onclick="showWorkshopDetail({{ $workshop->workshop_id }})">
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
                            <div class="w-full h-full bg-gradient-to-br from-[#057A55] to-[#016545] flex items-center justify-center">
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
                        <h3 class="text-lg font-bold text-gray-900 mb-4 line-clamp-2 min-h-[3.5rem]">
                            {{ $workshop->judul }}
                        </h3>

                        <!-- Workshop Details -->
                        <div class="space-y-3">
                            <!-- Pemateri -->
                            @if($workshop->pemateri)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">
                                        {{ date('H.i', strtotime($workshop->waktu)) }} WIB
                                    </p>
                                </div>
                            @endif

                            <!-- Date -->
                            @if($workshop->tanggal)
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                </div>
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

<!-- Workshop Detail Modal -->
<div id="workshopModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" onclick="closeWorkshopModal(event)" style="z-index: 1000;">
    <div class="bg-white rounded-lg max-w-4xl w-full flex flex-col" onclick="event.stopPropagation()" style="max-height: 90vh;">
        <!-- Modal Header - Fixed -->
        <div class="flex justify-between items-start p-6 border-b border-gray-200 flex-shrink-0">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 pr-4"></h2>
            <button onclick="closeWorkshopModal()" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Modal Body - Scrollable -->
        <div id="modalContent" class="flex-1 overflow-y-auto p-6" style="max-height: calc(90vh - 180px);">
            <!-- Content will be loaded via JavaScript -->
        </div>
        <!-- Modal Footer - Fixed (for buttons) -->
        <div id="modalFooter" class="p-6 border-t border-gray-200 flex-shrink-0 bg-white">
            <!-- Buttons will be loaded via JavaScript -->
        </div>
    </div>
</div>

<!-- Confirmation Modal - Harus di depan workshop modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" onclick="closeConfirmModal(event)" style="z-index: 2000 !important;">
    <div class="bg-white rounded-lg max-w-md w-full shadow-xl" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full" style="background-color: rgba(5, 122, 85, 0.1);">
                <svg class="w-8 h-8" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Pendaftaran</h3>
            <p id="confirmMessage" class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin mendaftar pada workshop ini?</p>
            <div class="flex space-x-3">
                <button onclick="closeConfirmModal(null)" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                    Batal
                </button>
                <button id="confirmButton" onclick="confirmRegistration()" class="flex-1 px-4 py-2.5 text-white rounded-lg font-medium transition-colors" style="background-color: #057A55;" onmouseover="this.style.backgroundColor='#068b4b';" onmouseout="this.style.backgroundColor='#057A55';">
                    Ya, Daftar
                </button>
            </div>
        </div>
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
    
    /* Toast Animation */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .toast-enter {
        animation: slideInRight 0.3s ease-out;
    }
    
    .toast-exit {
        animation: slideOutRight 0.3s ease-in;
    }
    
    /* Modal z-index - Pastikan confirm modal di depan workshop modal */
    #workshopModal {
        z-index: 1000 !important;
        position: fixed !important;
    }
    
    /* Modal scrollable content */
    #workshopModal #modalContent {
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }
    
    #workshopModal #modalContent::-webkit-scrollbar {
        width: 8px;
    }
    
    #workshopModal #modalContent::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #workshopModal #modalContent::-webkit-scrollbar-thumb {
        background: #057A55;
        border-radius: 4px;
    }
    
    #workshopModal #modalContent::-webkit-scrollbar-thumb:hover {
        background: #068b4b;
    }
    
    #confirmModal {
        z-index: 2000 !important;
        position: fixed !important;
    }
    
    /* Pastikan inner content modal juga memiliki z-index yang benar */
    #confirmModal > div {
        position: relative !important;
        z-index: 2001 !important;
    }
    
    /* Toast Container - Pastikan z-index tertinggi */
    #toastContainer {
        z-index: 99999 !important;
        position: fixed !important;
        top: 1rem !important;
        right: 1rem !important;
        pointer-events: none !important;
    }
    
    #toastContainer > div {
        pointer-events: auto !important;
        z-index: 99999 !important;
    }
</style>
<script>
    // Store current workshop ID for confirmation
    let pendingWorkshopId = null;
    
    // Handler untuk tombol daftar workshop
    function handleRegisterClick() {
        const btnRegister = document.getElementById('btnRegister');
        const workshopId = btnRegister ? btnRegister.getAttribute('data-workshop-id') : null;
        
        console.log('handleRegisterClick called, workshopId from button:', workshopId);
        console.log('window.currentWorkshopId:', window.currentWorkshopId);
        
        // Use workshop ID from button data attribute, or fallback to window.currentWorkshopId
        const idToUse = workshopId || window.currentWorkshopId;
        
        if (!idToUse) {
            console.error('No workshop ID found in button or window');
            showToast('Workshop ID tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }
        
        console.log('Calling showConfirmModal with ID:', idToUse);
        showConfirmModal(idToUse);
    }
    
    function showWorkshopDetail(workshopId) {
        // Fetch workshop detail via AJAX
        fetch(`/pengguna/workshop/${workshopId}/detail`)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || err.message || 'Gagal memuat detail workshop');
                    });
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('modalTitle').textContent = data.judul;
                
                // Store workshop ID for registration
                window.currentWorkshopId = data.workshop_id;
                
                let imageUrl = data.sampul_poster_url 
                    ? (data.sampul_poster_url.startsWith('http') 
                        ? data.sampul_poster_url 
                        : '/storage/' + data.sampul_poster_url)
                    : null;
                
                let content = `
                    <div class="space-y-6">
                        ${imageUrl ? `
                            <div class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden">
                                <img src="${imageUrl}" alt="${data.judul}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/perpustakaan.jpg';">
                            </div>
                        ` : '<div class="w-full h-48 bg-gradient-to-br from-[#057A55] to-[#016545] rounded-lg"></div>'}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Pemateri</h4>
                                    <p class="text-gray-600">${data.pemateri ? data.pemateri.nama : '-'}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Tanggal</h4>
                                    <p class="text-gray-600">${data.tanggal_formatted}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Waktu</h4>
                                    <p class="text-gray-600">${data.waktu_formatted}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Lokasi</h4>
                                    <p class="text-gray-600">${data.lokasi || '-'}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Kuota</h4>
                                    <p class="text-gray-600">${data.kuota_terisi || 0} / ${data.kuota || 0} peserta</p>
                                </div>
                            </div>
                        </div>
                        
                        ${data.keywords && data.keywords.length > 0 ? `
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Materi yang Dipelajari</h4>
                            <div class="flex flex-wrap gap-2">
                                ${data.keywords.map(keyword => `
                                    <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-sm font-medium border border-green-200">
                                        ${keyword}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Deskripsi Workshop</h4>
                            <p class="text-gray-600 whitespace-pre-wrap leading-relaxed">${data.deskripsi || 'Tidak ada deskripsi'}</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('modalContent').innerHTML = content;
                
                // Set footer buttons
                let footerContent = `
                    <div>
                        ${data.user_registered ? `
                            <button disabled class="w-full px-6 py-3 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Anda Sudah Terdaftar
                            </button>
                        ` : data.is_full ? `
                            <button disabled class="w-full px-6 py-3 bg-red-500 text-white rounded-lg font-medium cursor-not-allowed flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Kuota Sudah Penuh
                            </button>
                        ` : `
                            <button onclick="handleRegisterClick()" id="btnRegister" class="w-full px-6 py-3 text-white rounded-lg font-medium transition-colors flex items-center justify-center" style="background-color: #057A55;" onmouseover="this.style.backgroundColor='#068b4b';" onmouseout="this.style.backgroundColor='#057A55';" data-workshop-id="${data.workshop_id}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Daftar Workshop
                            </button>
                        `}
                    </div>
                `;
                document.getElementById('modalFooter').innerHTML = footerContent;
                
                const workshopModal = document.getElementById('workshopModal');
                workshopModal.style.setProperty('z-index', '1000', 'important');
                workshopModal.classList.remove('hidden');
                workshopModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                // Store workshop data for registration - ensure workshop_id exists
                if (!data.workshop_id) {
                    console.error('workshop_id is missing in response data:', data);
                    showToast('Data workshop tidak lengkap. Silakan refresh halaman.', 'error');
                    return;
                }
                
                window.currentWorkshopId = data.workshop_id;
                window.currentWorkshopData = data;
                
                console.log('Stored workshop ID:', window.currentWorkshopId);
                console.log('Full workshop data:', window.currentWorkshopData);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal memuat detail workshop. Silakan coba lagi.', 'error');
            });
    }
    
    function showConfirmModal(workshopId) {
        console.log('showConfirmModal called with workshopId:', workshopId);
        console.log('Type of workshopId:', typeof workshopId);
        
        // Ensure workshopId is valid
        if (!workshopId || workshopId === 'undefined' || workshopId === 'null') {
            console.error('Invalid workshopId:', workshopId);
            // Try to use window.currentWorkshopId as fallback
            if (window.currentWorkshopId) {
                workshopId = window.currentWorkshopId;
                console.log('Using fallback workshopId from window.currentWorkshopId:', workshopId);
            } else {
                showToast('Workshop ID tidak valid. Silakan refresh halaman.', 'error');
                return;
            }
        }
        
        // Convert to number if it's a string
        workshopId = parseInt(workshopId) || workshopId;
        pendingWorkshopId = workshopId;
        
        console.log('Set pendingWorkshopId to:', pendingWorkshopId);
        
        const confirmModal = document.getElementById('confirmModal');
        const workshopModal = document.getElementById('workshopModal');
        
        // Ensure confirm modal has higher z-index than workshop modal
        if (workshopModal && !workshopModal.classList.contains('hidden')) {
            // Keep workshop modal open but lower its z-index
            workshopModal.style.setProperty('z-index', '1000', 'important');
            // Make workshop modal backdrop slightly darker to emphasize confirm modal
            workshopModal.style.setProperty('background-color', 'rgba(0, 0, 0, 0.7)', 'important');
        }
        
        // Set confirm modal z-index very high
        confirmModal.style.setProperty('z-index', '2000', 'important');
        confirmModal.style.setProperty('position', 'fixed', 'important');
        
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeConfirmModal(event, clearPendingId = true) {
        if (!event || event.target.id === 'confirmModal') {
            const confirmModal = document.getElementById('confirmModal');
            const workshopModal = document.getElementById('workshopModal');
            
            confirmModal.classList.add('hidden');
            confirmModal.classList.remove('flex');
            
            // Restore workshop modal backdrop opacity if it's still open
            if (workshopModal && !workshopModal.classList.contains('hidden')) {
                workshopModal.style.setProperty('background-color', 'rgba(0, 0, 0, 0.5)', 'important');
            }
            
            // Restore body overflow only if workshop modal is also closed
            if (!workshopModal || workshopModal.classList.contains('hidden')) {
                document.body.style.overflow = '';
            }
            
            // Only clear pending ID if explicitly requested (not when called from confirmRegistration)
            if (clearPendingId) {
                pendingWorkshopId = null;
            }
        }
    }
    
    function confirmRegistration() {
        console.log('confirmRegistration called, pendingWorkshopId:', pendingWorkshopId);
        console.log('window.currentWorkshopId:', window.currentWorkshopId);
        
        // Get workshop ID from pendingWorkshopId or window.currentWorkshopId
        let workshopId = pendingWorkshopId || window.currentWorkshopId;
        
        if (!workshopId) {
            console.error('No workshop ID found');
            showToast('Workshop ID tidak valid. Silakan tutup dan buka kembali detail workshop.', 'error');
            return;
        }
        
        console.log('Using workshop ID:', workshopId);
        
        // Close confirmation modal but don't clear pending ID yet
        closeConfirmModal(null, false);
        
        // Small delay to ensure modal is closed before registering
        setTimeout(() => {
            console.log('Calling registerWorkshop with ID:', workshopId);
            registerWorkshop(workshopId);
            // Clear pending ID after starting registration
            pendingWorkshopId = null;
        }, 100);
    }
    
    function registerWorkshop(workshopId) {
        console.log('registerWorkshop called with workshopId:', workshopId);
        console.log('Type of workshopId:', typeof workshopId);
        
        // Convert to string first, then check if it's valid
        workshopId = String(workshopId).trim();
        
        if (!workshopId || workshopId === 'undefined' || workshopId === 'null' || workshopId === '') {
            console.error('No workshop ID provided or invalid:', workshopId);
            showToast('Workshop ID tidak valid. Silakan refresh halaman dan coba lagi.', 'error');
            return;
        }
        
        // Try to convert to number for database query
        const numericId = parseInt(workshopId);
        if (isNaN(numericId)) {
            console.error('Workshop ID is not a valid number:', workshopId);
            showToast('Workshop ID tidak valid. Silakan refresh halaman dan coba lagi.', 'error');
            return;
        }
        
        console.log('Using workshop ID (numeric):', numericId);
        workshopId = numericId;
        
        // Disable button
        const btnRegister = document.getElementById('btnRegister');
        if (btnRegister) {
            btnRegister.disabled = true;
            btnRegister.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mendaftar...';
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || document.querySelector('input[name="_token"]')?.value
            || '';
        
        if (!csrfToken) {
            console.error('CSRF token not found');
            showToast('Token keamanan tidak ditemukan. Silakan refresh halaman.', 'error');
            if (btnRegister) {
                btnRegister.disabled = false;
                btnRegister.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Daftar Workshop';
            }
            return;
        }
        
        console.log('Sending registration request to:', `/pengguna/workshop/${workshopId}/register`);
        
        // Send registration request
        fetch(`/pengguna/workshop/${workshopId}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                workshop_id: workshopId
            })
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                // Try to parse error response
                return response.json().then(errData => {
                    const errorMsg = errData.message || errData.error || `HTTP Error: ${response.status}`;
                    throw new Error(errorMsg);
                }).catch(() => {
                    // If JSON parsing fails, throw generic error
                    throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
                });
            }
            
            // Parse success response
            return response.json().then(data => {
                console.log('Response data:', data);
                return data;
            }).catch(err => {
                console.error('JSON parse error:', err);
                throw new Error('Gagal memparse response dari server');
            });
        })
        .then(data => {
            console.log('Registration response:', data);
            
            if (data && data.success) {
                // Show success toast first
                showToast(data.message || 'Pendaftaran berhasil!', 'success');
                
                // Update button to show registered status
                if (btnRegister) {
                    btnRegister.disabled = true;
                    btnRegister.classList.add('bg-gray-400', 'cursor-not-allowed');
                    btnRegister.classList.remove('hover:bg-[#068b4b]');
                    btnRegister.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Anda Sudah Terdaftar';
                }
                
                // Close workshop modal after a short delay to show toast
                setTimeout(() => {
                    closeWorkshopModal();
                    // Reload page after modal is closed to update status
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }, 2000);
            } else {
                // Show error toast
                const errorMsg = (data && data.message) ? data.message : 'Gagal mendaftar workshop. Silakan coba lagi.';
                showToast(errorMsg, 'error');
                
                // Re-enable button
                if (btnRegister) {
                    btnRegister.disabled = false;
                    btnRegister.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Daftar Workshop';
                }
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            
            // Try to parse error message
            let errorMessage = 'Gagal mendaftar workshop. Silakan coba lagi.';
            
            if (error.message) {
                errorMessage = error.message;
            } else if (typeof error === 'string') {
                errorMessage = error;
            }
            
            // Show error toast
            showToast(errorMessage, 'error');
            
            // Re-enable button
            if (btnRegister) {
                btnRegister.disabled = false;
                btnRegister.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Daftar Workshop';
            }
        });
    }
    
    function showToast(message, type = 'success') {
        console.log('showToast called:', message, type);
        
        // Get or create toast container - ensure it's at body level with highest z-index
        let container = document.getElementById('toastContainer');
        if (!container) {
            // Create container directly in body if not found
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'fixed top-4 right-4 space-y-2';
            document.body.appendChild(container);
            console.log('Toast container created');
        } else {
            // Ensure container is always at body level (not inside modal or other elements)
            if (container.parentElement !== document.body) {
                document.body.appendChild(container);
            }
        }
        
        // Force highest z-index and ensure it's visible
        container.style.setProperty('z-index', '99999', 'important');
        container.style.setProperty('position', 'fixed', 'important');
        container.style.setProperty('top', '1rem', 'important');
        container.style.setProperty('right', '1rem', 'important');
        container.style.setProperty('pointer-events', 'none', 'important');
        container.style.setProperty('display', 'block', 'important');
        container.style.setProperty('visibility', 'visible', 'important');
        container.style.setProperty('opacity', '1', 'important');
        
        const toastId = 'toast-' + Date.now();
        
        const bgColor = type === 'success' ? '#057A55' : '#EF4444';
        const icon = type === 'success' 
            ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
            : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'toast-enter bg-white rounded-lg shadow-xl p-4 flex items-center space-x-3 min-w-[300px] max-w-md';
        toast.setAttribute('style', `
            border-left: 4px solid ${bgColor}; 
            pointer-events: auto; 
            position: relative; 
            z-index: 99999;
            display: flex;
            visibility: visible;
            opacity: 1;
            margin-bottom: 0.5rem;
        `);
        toast.innerHTML = `
            <div style="color: ${bgColor};" class="flex-shrink-0">
                ${icon}
            </div>
            <p class="text-gray-800 flex-1 text-sm">${message}</p>
            <button onclick="removeToast('${toastId}')" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0" type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        container.appendChild(toast);
        console.log('Toast appended to container:', toastId);
        
        // Force reflow to trigger animation
        toast.offsetHeight;
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            removeToast(toastId);
        }, 5000);
    }
    
    function removeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    function closeWorkshopModal(event) {
        if (!event || event.target.id === 'workshopModal') {
            const workshopModal = document.getElementById('workshopModal');
            const confirmModal = document.getElementById('confirmModal');
            
            // Close workshop modal
            workshopModal.classList.add('hidden');
            workshopModal.classList.remove('flex');
            
            // Also close confirm modal if open
            if (confirmModal && !confirmModal.classList.contains('hidden')) {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                pendingWorkshopId = null;
            }
            
            // Restore body overflow
            document.body.style.overflow = '';
        }
    }
    

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const workshopModal = document.getElementById('workshopModal');
            const confirmModal = document.getElementById('confirmModal');
            
            if (!workshopModal.classList.contains('hidden')) {
                closeWorkshopModal();
            }
            if (!confirmModal.classList.contains('hidden')) {
                closeConfirmModal();
            }
        }
    });
</script>
@endpush
@endsection

