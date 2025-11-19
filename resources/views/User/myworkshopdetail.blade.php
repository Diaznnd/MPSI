@extends('User.Layout.app')

@section('title', 'Detail Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('pengguna.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li><a href="{{ route('pengguna.my-workshop') }}" class="hover:text-gray-900">My Workshop</a></li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Detail Workshop</li>
            </ol>
        </nav>

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

        <!-- Workshop Header Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Workshop Image -->
                <div class="lg:w-1/3">
                    @if($workshop->sampul_poster_url)
                        @php
                            $imagePath = str_starts_with($workshop->sampul_poster_url, 'http') 
                                ? $workshop->sampul_poster_url 
                                : asset('storage/' . $workshop->sampul_poster_url);
                        @endphp
                        <img src="{{ $imagePath }}" 
                             alt="{{ $workshop->judul }}" 
                             class="w-full h-64 object-cover rounded-lg"
                             onerror="this.onerror=null; this.src='{{ asset('images/perpustakaan.jpg') }}';">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-[#057A55] to-[#016545] rounded-lg"></div>
                    @endif
                </div>

                <!-- Workshop Info -->
                <div class="lg:w-2/3">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $workshop->judul }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Pemateri</h4>
                                <p class="text-gray-600">{{ $workshop->pemateri ? $workshop->pemateri->nama : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Tanggal</h4>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Waktu</h4>
                                <p class="text-gray-600">
                                    @if($workshop->waktu)
                                        {{ \Carbon\Carbon::parse($workshop->waktu)->format('H:i') }} WIB
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Lokasi</h4>
                                <p class="text-gray-600">{{ $workshop->lokasi ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Kuota</h4>
                                <p class="text-gray-600">{{ $workshop->kuota_terisi ?? 0 }} / {{ $workshop->kuota ?? 0 }} peserta</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Terdaftar Pada</h4>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Section -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" style="color: #057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Absensi
                        </h3>
                        
                        @if($hasAttended)
                            <!-- Already Attended -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-800 font-medium mb-1">✓ Anda sudah mengambil absensi</p>
                                        <p class="text-sm text-green-600">Waktu absensi: {{ $attendanceStatus['waktu_absensi'] }}</p>
                                        <p class="text-sm text-green-600">Status: {{ ucfirst($attendanceStatus['status_absensi']) }}</p>
                                    </div>
                                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <!-- Attendance Available or Not Available -->
                            <div id="attendanceContainer">
                                <div class="mb-4">
                                    <p id="attendanceMessage" class="text-gray-700 mb-4">{{ $attendanceMessage }}</p>
                                </div>
                                
                                @if($canTakeAttendance)
                                    <button id="attendanceButton" 
                                            onclick="submitAttendance({{ $workshop->workshop_id }})"
                                            class="w-full px-6 py-3 text-white rounded-lg font-medium transition-colors flex items-center justify-center space-x-2"
                                            style="background-color: #057A55;"
                                            onmouseover="this.style.backgroundColor='#068b4b';" 
                                            onmouseout="this.style.backgroundColor='#057A55';">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Ambil Absensi</span>
                                    </button>
                                @else
                                    <button disabled
                                            class="w-full px-6 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        <span>Absensi Tidak Tersedia</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Countdown Timer (if attendance is available) -->
                            @if($canTakeAttendance)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-600">Sisa waktu absensi:</p>
                                    <p id="countdownTimer" class="text-lg font-bold" style="color: #057A55;"></p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Workshop Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Workshop</h2>
                    <p class="text-gray-600 whitespace-pre-wrap leading-relaxed">{{ $workshop->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>

                <!-- Keywords -->
                @if($workshop->keywords && $workshop->keywords->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Materi yang Dipelajari</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($workshop->keywords as $keyword)
                            <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-sm font-medium border border-green-200">
                                {{ $keyword->keyword }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Materi -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Materi Workshop</h2>
                    @if($workshop->materi && $workshop->materi->count() > 0)
                        <div class="space-y-3">
                            @foreach($workshop->materi as $materi)
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center flex-1">
                                        <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $materi->nama_file }}</p>
                                            @if($materi->tanggal_upload)
                                                <p class="text-xs text-gray-500">Diupload: {{ \Carbon\Carbon::parse($materi->tanggal_upload)->translatedFormat('d F Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('pengguna.materi.download', $materi->materi_id) }}" 
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
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Belum ada materi yang diupload oleh pemateri</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Forum Diskusi -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Forum Diskusi</h2>
                    <p class="text-gray-600 text-sm mb-4">Diskusikan workshop ini dengan peserta lainnya</p>
                    <a href="{{ route('pengguna.forum.index', $workshop->workshop_id) }}" 
                       class="block w-full text-center px-4 py-2 text-white rounded-lg font-medium transition-colors"
                       style="background-color: #057A55;"
                       onmouseover="this.style.backgroundColor='#068b4b';"
                       onmouseout="this.style.backgroundColor='#057A55';">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Buka Forum
                    </a>
                </div>
                
                <!-- Sertifikat -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Sertifikat</h2>
                    @if($hasAttended)
                        <p class="text-gray-600 text-sm mb-4">Unduh sertifikat partisipasi workshop Anda</p>
                        <a href="{{ route('pengguna.certificate.download', $workshop->workshop_id) }}" 
                           class="block w-full text-center px-4 py-2 text-white rounded-lg font-medium transition-colors"
                           style="background-color: #057A55;"
                           onmouseover="this.style.backgroundColor='#068b4b';"
                           onmouseout="this.style.backgroundColor='#057A55';">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Unduh Sertifikat
                        </a>
                    @else
                        <p class="text-gray-600 text-sm mb-4">Sertifikat akan tersedia setelah Anda mengikuti absensi workshop</p>
                        <button disabled
                                class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed flex items-center justify-center">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Sertifikat Belum Tersedia
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const workshopId = {{ $workshop->workshop_id }};
    const canTakeAttendance = {{ $canTakeAttendance ? 'true' : 'false' }};
    const endTime = new Date('{{ $endTime->toIso8601String() }}');
    
    // Countdown timer
    if (canTakeAttendance && !{{ $hasAttended ? 'true' : 'false' }}) {
        function updateCountdown() {
            const now = new Date();
            const diff = endTime - now;
            
            if (diff <= 0) {
                document.getElementById('countdownTimer').textContent = 'Waktu habis';
                document.getElementById('attendanceButton').disabled = true;
                document.getElementById('attendanceButton').classList.remove('cursor-pointer');
                document.getElementById('attendanceButton').classList.add('cursor-not-allowed');
                document.getElementById('attendanceButton').style.backgroundColor = '#9CA3AF';
                return;
            }
            
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('countdownTimer').textContent = `${minutes} menit ${seconds} detik`;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    function submitAttendance(workshopId) {
        const button = document.getElementById('attendanceButton');
        const messageDiv = document.getElementById('attendanceMessage');
        
        // Disable button during request
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span>';
        
        fetch(`/pengguna/my-workshop/${workshopId}/attendance`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI to show success
                const attendanceContainer = document.getElementById('attendanceContainer');
                attendanceContainer.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-800 font-medium mb-1">✓ Absensi berhasil diambil!</p>
                                <p class="text-sm text-green-600">Waktu absensi: ${data.attendance.waktu_absensi}</p>
                                <p class="text-sm text-green-600">Status: ${data.attendance.status_absensi.charAt(0).toUpperCase() + data.attendance.status_absensi.slice(1)}</p>
                            </div>
                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                `;
                
                // Hide countdown if exists
                const countdownDiv = document.getElementById('countdownTimer')?.parentElement;
                if (countdownDiv) {
                    countdownDiv.style.display = 'none';
                }
            } else {
                alert(data.message || 'Gagal mengambil absensi');
                button.disabled = false;
                button.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Ambil Absensi</span>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil absensi. Silakan coba lagi.');
            button.disabled = false;
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Ambil Absensi</span>
            `;
        });
    }
</script>
@endpush
@endsection

