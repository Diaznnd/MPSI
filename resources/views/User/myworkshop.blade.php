@extends('User.Layout.app')

@section('title', 'My Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
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
                                <div class="w-full h-full bg-gradient-to-br from-[#057A55] to-[#016545]"></div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute top-2 right-2">
                                @if($workshop->status_workshop === 'aktif')
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Workshop Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-[#057A55] transition-colors">
                                {{ $workshop->judul }}
                            </h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">{{ $workshop->pemateri ? $workshop->pemateri->nama : '-' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $workshop->lokasi ?: '-' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Terdaftar: {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d M Y H:i') }}</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                {{ $workshop->deskripsi ?: 'Tidak ada deskripsi' }}
                            </p>
                        </div>
                    </div>
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

<!-- Workshop Detail Modal (sama seperti di daftarworkshop.blade.php) -->
<div id="workshopModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" onclick="closeWorkshopModal(event)" style="z-index: 1000;">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] flex flex-col" onclick="event.stopPropagation()" style="max-height: 90vh;">
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
        <div id="modalContent" class="flex-1 overflow-y-auto p-6" style="max-height: calc(90vh - 140px);">
            <!-- Content will be loaded via JavaScript -->
        </div>
        <!-- Modal Footer - Fixed (for buttons) -->
        <div id="modalFooter" class="p-6 border-t border-gray-200 flex-shrink-0 bg-white">
            <!-- Buttons will be loaded via JavaScript -->
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
</style>
<script>
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
                        
                        ${data.materi && data.materi.length > 0 ? `
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Materi Workshop</h4>
                            <div class="space-y-2">
                                ${data.materi.map(materi => `
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        <div class="flex items-center flex-1">
                                            <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">${materi.nama_file}</p>
                                                ${materi.tanggal_upload ? `<p class="text-xs text-gray-500">Diupload: ${materi.tanggal_upload}</p>` : ''}
                                            </div>
                                        </div>
                                        <a href="/pengguna/materi/${materi.materi_id}/download" 
                                           class="ml-3 px-4 py-2 text-sm text-white rounded-lg font-medium transition-colors" 
                                           style="background-color: #057A55;"
                                           onmouseover="this.style.backgroundColor='#068b4b';"
                                           onmouseout="this.style.backgroundColor='#057A55';">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Unduh
                                        </a>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        ` : `
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Materi Workshop</h4>
                            <p class="text-gray-500 text-sm">Belum ada materi yang diupload oleh pemateri</p>
                        </div>
                        `}
                    </div>
                `;
                
                document.getElementById('modalContent').innerHTML = content;
                
                // Set footer buttons (only sertifikat if available)
                let footerContent = `
                    <div>
                        <!-- Tombol Unduh Sertifikat -->
                        <button onclick="downloadSertifikat(${data.workshop_id})" 
                                class="w-full flex items-center justify-center px-6 py-3 text-white rounded-lg font-medium transition-colors" 
                                style="background-color: #057A55;"
                                onmouseover="this.style.backgroundColor='#068b4b';"
                                onmouseout="this.style.backgroundColor='#057A55';">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Unduh Sertifikat
                        </button>
                    </div>
                `;
                document.getElementById('modalFooter').innerHTML = footerContent;
                
                const workshopModal = document.getElementById('workshopModal');
                workshopModal.style.setProperty('z-index', '1000', 'important');
                workshopModal.classList.remove('hidden');
                workshopModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail workshop. Silakan coba lagi.');
            });
    }
    
    function closeWorkshopModal(event) {
        if (!event || event.target.id === 'workshopModal') {
            const workshopModal = document.getElementById('workshopModal');
            workshopModal.classList.add('hidden');
            workshopModal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
    
    // Function untuk download sertifikat (placeholder)
    function downloadSertifikat(workshopId) {
        console.log('Download sertifikat untuk workshop ID:', workshopId);
        // TODO: Implementasi download sertifikat
        alert('Fitur download sertifikat akan segera tersedia untuk workshop ID: ' + workshopId);
    }
</script>
@endpush
@endsection

