@extends('Pemateri.Layout.app')

@section('title', 'Workshop Saya')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl mb-2 font-bold text-gray-900">Sistem Informasi Workshop UPT Pustaka Unand</h1>
        </div>
        
        <!-- Search Bar Minimalis -->
        <div class="w-full md:w-64">
            <form action="{{ route('pemateri.workshop.index') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}" 
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

    <!-- Upcoming section -->
    <div class="bg-white rounded-xl shadow p-5 mb-8">
        <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">Bersiaplah untuk mengisi materi workshop Anda!</h2>
        @if(($upcomingWorkshops ?? collect())->count() > 0)
            <div class="relative">
                <button type="button" aria-label="Prev" onclick="scrollCarousel('upcoming', -1)" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div id="carousel-upcoming" class="flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth px-1">
                    @foreach($upcomingWorkshops as $workshop)
                        <div class="min-w-[280px] md:min-w-[320px] bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden snap-start">
                            <!-- Workshop Image -->
                            <div class="relative h-44 bg-gray-200 overflow-hidden">
                                @if($workshop->sampul_poster_url)
                                    @php
                                        $imagePath = str_starts_with($workshop->sampul_poster_url, 'http') 
                                            ? $workshop->sampul_poster_url 
                                            : asset('storage/' . $workshop->sampul_poster_url);
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="{{ $workshop->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-[#057A55] to-[#016545] flex items-center justify-center">
                                        <svg class="w-14 h-14 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-yellow-400"></div>
                            </div>
                            <!-- Workshop Content -->
                            <div class="p-4">
                                <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-2">{{ $workshop->judul }}</h3>
                                <div class="space-y-2 text-sm text-gray-600">
                                    @if($workshop->waktu)
                                    <div class="flex items-center gap-2"><svg class="w-4 h-4" style="color:#057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i') }} WIB</span></div>
                                    @endif
                                    @if($workshop->tanggal)
                                    <div class="flex items-center gap-2"><svg class="w-4 h-4" style="color:#057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') }}</span></div>
                                    @endif
                                    @if($workshop->lokasi)
                                    <div class="flex items-center gap-2"><svg class="w-4 h-4" style="color:#057A55;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="line-clamp-1">{{ $workshop->lokasi }}</span></div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('pemateri.workshop.show', $workshop->workshop_id) }}" class="block text-center px-4 py-2 text-white rounded-lg font-medium transition-colors" style="background-color:#057A55;" onmouseover="this.style.backgroundColor='#068b4b';" onmouseout="this.style.backgroundColor='#057A55';">Detail & Upload Materi</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" aria-label="Next" onclick="scrollCarousel('upcoming', 1)" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-8">
                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum Ada Workshop Mendatang</h3>
                <p class="text-gray-600 text-sm">Workshop Anda yang aktif akan tampil di sini.</p>
            </div>
        @endif
    </div>

    <!-- History section -->
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">Riwayat Workshop Anda</h2>
        @if(($historyWorkshops ?? collect())->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($historyWorkshops as $workshop)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="h-40 bg-gray-200 overflow-hidden">
                            @if($workshop->sampul_poster_url)
                                @php
                                    $imagePath = str_starts_with($workshop->sampul_poster_url, 'http') 
                                        ? $workshop->sampul_poster_url 
                                        : asset('storage/' . $workshop->sampul_poster_url);
                                @endphp
                                <img src="{{ $imagePath }}" alt="{{ $workshop->judul }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-1">{{ $workshop->judul }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $workshop->deskripsi ? Str::limit(strip_tags($workshop->deskripsi), 120) : '' }}</p>
                            <div class="flex items-center justify-between">
                                <button type="button" class="px-3 py-1.5 text-xs rounded-full bg-gray-100 text-gray-700">SELESAI</button>
                                <a href="{{ route('pemateri.workshop.show', $workshop->workshop_id) }}" class="px-4 py-2 text-white rounded-lg text-sm" style="background-color:#057A55;">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-8">
                <p class="text-gray-600 text-sm">Belum ada riwayat workshop.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<script>
    const upcomingData = @json(collect($upcomingWorkshops ?? [])->keyBy('workshop_id'));
    const historyData = @json(collect($historyWorkshops ?? [])->keyBy('workshop_id'));
    const workshopsData = { ...upcomingData, ...historyData };

    function scrollCarousel(section, dir) {
        const el = document.getElementById(`carousel-${section}`);
        if (!el) return;
        const move = Math.max(el.clientWidth * 0.8, 260);
        el.scrollBy({ left: dir * move, behavior: 'smooth' });
    }

    function openWorkshopModal(workshopId) {
        const ws = workshopsData[workshopId];
        if (!ws) return;

        // Build materi list
        const materiList = (ws.materi && ws.materi.length)
            ? ws.materi.map(m => `<li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span class="truncate">${m.judul_topik}</span>
                </li>`).join('')
            : '<p class="text-sm text-gray-500">Belum ada materi yang diupload</p>';

        const modal = document.getElementById('pemateriWorkshopModal');
        document.getElementById('wsModalTitle').textContent = 'Detail Workshop';

        const imgUrl = ws.sampul_poster_url
            ? (ws.sampul_poster_url.startsWith('http') ? ws.sampul_poster_url : `${"{{ asset('storage') }}"}/${ws.sampul_poster_url}`)
            : null;

        const pendaftar = ws.pendaftaran_count ?? 0;
        const kuota = ws.kuota ?? 0;
        const kuotaTerisi = ws.kuota_terisi ?? pendaftar;
        const percent = kuota > 0 ? Math.min(100, Math.round((kuotaTerisi/kuota)*100)) : 0;

        document.getElementById('wsModalBody').innerHTML = `
            <div class="space-y-5">
                <!-- Banner -->
                <div class="relative rounded-xl overflow-hidden bg-gray-100">
                    ${imgUrl ? `<img src="${imgUrl}" alt="${ws.judul ?? ''}" class="w-full h-56 md:h-64 object-cover">` : `<div class='h-56 md:h-64 bg-gradient-to-br from-[#057A55] to-[#016545]'></div>`}
                    <div class="absolute inset-0 bg-black bg-opacity-25 flex items-end">
                        <h2 class="text-white text-xl md:text-2xl font-bold p-4">${ws.judul ?? 'Judul Workshop'}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Left content -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Workshop</h3>
                        <div class="prose prose-sm max-w-none text-gray-700">${ws.deskripsi ?? '-'}</div>

                        <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">Materi yang Akan Dipelajari</h3>
                        <ul class="space-y-2">${materiList}</ul>
                    </div>
                    <!-- Right sidebar -->
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100 h-fit">
                        <h4 class="text-base font-semibold text-gray-800 mb-3">Detail Event</h4>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14M5 11h14M5 15h14"/></svg>
                                <div>
                                    <div class="text-gray-500">Judul Workshop</div>
                                    <div class="font-medium">${ws.judul ?? '-'}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <div class="text-gray-500">Pemateri</div>
                                    <div class="font-medium">${(ws.pemateri && ws.pemateri.name) ? ws.pemateri.name : '-'}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <div>
                                    <div class="text-gray-500">Tanggal</div>
                                    <div class="font-medium">${ws.tanggal ?? '-'}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <div class="text-gray-500">Waktu</div>
                                    <div class="font-medium">${ws.waktu ? `${ws.waktu} WIB` : '-'}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <div class="text-gray-500">Lokasi</div>
                                    <div class="font-medium">${ws.lokasi ?? '-'}</div>
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500 mb-1">Kuota Tersedia</div>
                                <div class="w-full h-2 bg-yellow-100 rounded">
                                    <div class="h-2 rounded" style="width:${percent}%; background-color:#d4a017;"></div>
                                </div>
                                <div class="text-xs text-gray-600 mt-1">${kuotaTerisi}/${kuota || '∞'} Peserta</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button type="button" class="px-3 py-2 rounded bg-yellow-500 text-white font-medium" onclick="document.getElementById('uploadForm')?.scrollIntoView({behavior:'smooth'})">Upload Materi</button>
                            <button type="button" class="px-3 py-2 rounded bg-yellow-700 text-white font-medium opacity-80">Unduh Sertifikat</button>
                        </div>
                    </div>
                </div>

                <!-- Upload section -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Upload Materi</h4>
                    <form action="{{ route('pemateri.workshop.store', ['workshop_id' => 0]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul/Topik</label>
                            <input type="text" name="judul_topik" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#057A55]">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Materi</label>
                            <input type="file" name="materi_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar" required class="w-full text-sm">
                            <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (maks 10MB)</p>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50" onclick="closeWorkshopModal()">Batal</button>
                            <button type="submit" class="px-4 py-2 text-white rounded-lg font-medium" style="background-color:#057A55;" onmouseover="this.style.backgroundColor='#068b4b';" onmouseout="this.style.backgroundColor='#057A55';">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // Fix form action with current workshop id (new workshop routes)
        const form = document.getElementById('uploadForm');
        form.action = `{{ url('/pemateri/workshop') }}/${workshopId}/store`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeWorkshopModal() {
        const modal = document.getElementById('pemateriWorkshopModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
</script>

<!-- Modal -->
<div id="pemateriWorkshopModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4" onclick="closeWorkshopModal()" style="z-index: 1000;">
    <div class="bg-white rounded-lg max-w-3xl w-full" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 id="wsModalTitle" class="text-xl font-bold text-gray-900">Detail Workshop</h3>
            <button class="text-gray-400 hover:text-gray-600" onclick="closeWorkshopModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="wsModalBody" class="p-4 max-h-[70vh] overflow-y-auto"></div>
    </div>
</div>
@endpush
