@extends('User.Layout.app')

@section('title', $workshop->judul)

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Sistem Informasi Workshop UPT Pustaka Unand</h1>
        <div class="h-1 w-full mt-3 bg-green-200"></div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
        <!-- Banner / Poster -->
        <div class="relative rounded-xl overflow-hidden h-150 ">
            @if(!empty($workshop->sampul_poster_url))
                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" 
                             alt="{{ $workshop->judul ?? 'Poster Workshop' }}" 
                             class="w-full h-full object-contain">
                    @else
                        <img src="https://via.placeholder.com/800x400/e2e8f0/94a3b8?text=Poster+Workshop" 
                             alt="Workshop" class="w-full h-50 object-contain">
                    @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Left: Descriptions -->
            <div class="md:col-span-2">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ $workshop->judul }}</h2>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Workshop</h3>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $workshop->deskripsi ?? '-' }}</p>
                </div>

                @if(!empty($keywords))
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Materi yang Akan Dipelajari</h3>
                    <ul class="space-y-2">
                        @foreach($keywords as $kw)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-700">{{ $kw }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @php
                    $isSelesai = ($workshop->status_workshop === 'selesai');
                @endphp

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Materi Workshop</h3>
                    @if(!$isSelesai)
                        <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
                            Materi akan tersedia.
                        </div>
                    @else
                        @if($workshop->materi && $workshop->materi->count() > 0)
                            <div class="space-y-2">
                                @foreach($workshop->materi as $file)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        <button type="button"
                                                class="text-left text-sm font-medium text-green-700 hover:underline"
                                                onclick="openMateriViewer('{{ route('pengguna.materi.view', $file->materi_id) }}','{{ addslashes($file->file_materi_url) }}')">
                                            {{ $file->judul_topik }}
                                        </button>
                                        <a href="{{ route('pengguna.materi.download', $file->materi_id) }}"
                                           class="ml-3 px-3 py-2 text-xs md:text-sm text-white rounded-lg font-medium"
                                           style="background-color:#057A55;"
                                           onmouseover="this.style.backgroundColor='#068b4b';" onmouseout="this.style.backgroundColor='#057A55';">
                                            Unduh
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Belum ada materi yang diunggah.</p>
                        @endif
                    @endif
                </div>
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
            </div>

            <!-- Right: Detail Card -->
            <div>
                <div class="bg-green-50 border border-green-100 rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Event</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-gray-900 font-medium">Waktu</p>
                                <p class="text-gray-700">{{ $waktuFormatted }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="text-gray-900 font-medium">Tanggal</p>
                                <p class="text-gray-700">{{ $tanggalFormatted }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <div>
                                <p class="text-gray-900 font-medium">Pemateri</p>
                                <p class="text-gray-700">{{ $workshop->pemateri->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <p class="text-gray-900 font-medium">Lokasi</p>
                                <p class="text-gray-700">{{ $workshop->lokasi ?? '-' }}</p>
                            </div>
                        </div>
                        @if(($kuotaMax ?? 0) > 0)
                        <div class="pt-2">
                            <p class="text-gray-900 font-medium mb-1">Kuota Tersedia</p>
                            @php
                                $percent = $kuotaMax > 0 ? min(100, round(($kuotaTerisi / $kuotaMax) * 100)) : 0;
                            @endphp
                            <div class="w-full h-3 bg-yellow-100 rounded-full overflow-hidden">
                                <div class="h-3 bg-yellow-400" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-700 mt-1">{{ $kuotaTerisi }}/{{ $kuotaMax }} Peserta</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-5">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const btn = document.getElementById('btnRegister');
    if (!btn) return;
    btn.addEventListener('click', function(){
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        btn.disabled = true;
        btn.innerText = 'Mendaftar...';
        fetch("{{ route('pengguna.workshop.register', $workshop->workshop_id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ workshop_id: {{ $workshop->workshop_id }} })
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success) {
                window.location.href = "{{ route('pengguna.my-workshop') }}";
            } else {
                alert((data && data.message) ? data.message : 'Gagal mendaftar.');
                btn.disabled = false;
                btn.innerText = 'Daftar Sekarang';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan.');
            btn.disabled = false;
            btn.innerText = 'Daftar Sekarang';
        });
    });
})();

// Materi viewer modal
function openMateriViewer(fallbackUrl, filePathOrUrl){
    const modal = document.getElementById('materiViewerModal');
    const frame = document.getElementById('materiViewerFrame');
    const ttl = document.getElementById('materiViewerTitle');
    if (!modal || !frame) return;

    // Determine absolute public URL from file path or URL
    let absoluteUrl = filePathOrUrl || '';
    if (absoluteUrl && !/^https?:\/\//i.test(absoluteUrl)) {
        // Assume it's a storage-relative path
        absoluteUrl = window.location.origin.replace(/\/$/, '') + '/storage/' + absoluteUrl.replace(/^\/?storage\//, '');
    }

    // Determine extension
    const lower = (filePathOrUrl || '').toLowerCase();
    const extMatch = lower.match(/\.([a-z0-9]+)(?:$|\?)/);
    const ext = extMatch ? extMatch[1] : '';

    // Office formats to be viewed via Office Online Viewer
    const officeExts = ['ppt','pptx','doc','docx','xls','xlsx'];
    const imageExts = ['png','jpg','jpeg','gif','webp','bmp'];

    let viewUrl = fallbackUrl; // default to secure route
    if (absoluteUrl) {
        if (officeExts.includes(ext)) {
            viewUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(absoluteUrl);
        } else if (ext === 'pdf' || imageExts.includes(ext)) {
            viewUrl = absoluteUrl; // browser can render directly
        }
    }

    ttl.textContent = 'Pratinjau Materi';
    frame.src = viewUrl;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeMateriViewer(){
    const modal = document.getElementById('materiViewerModal');
    const frame = document.getElementById('materiViewerFrame');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (frame) frame.src = '';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeMateriViewer();
});

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

@push('scripts')
<style>
    .modal-backdrop{background: rgba(0,0,0,0.5)}
</style>
<div id="materiViewerModal" class="fixed inset-0 hidden items-center justify-center p-4 modal-backdrop" onclick="closeMateriViewer()" style="z-index: 2000;">
    <div class="bg-white rounded-lg w-full max-w-5xl h-[80vh] flex flex-col" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-3 border-b">
            <h3 id="materiViewerTitle" class="font-semibold text-gray-900 text-sm md:text-base">Pratinjau Materi</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeMateriViewer()" aria-label="Tutup">&times;</button>
        </div>
        <div class="flex-1">
            <iframe id="materiViewerFrame" class="w-full h-full" src="" frameborder="0"></iframe>
        </div>
    </div>
</div>
@endpush
