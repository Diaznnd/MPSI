@extends('Pemateri.Layout.app')

@section('title', 'Detail Workshop')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl mb-2 font-bold text-gray-900">{{ $workshop->judul }}</h1>
        <div class="h-0.5 w-24 bg-[#057A55]"></div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <!-- Banner -->
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
            <!-- Left: Deskripsi & Materi -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Workshop</h3>
                <div class="prose prose-sm max-w-none text-gray-700">{!! $workshop->deskripsi !!}</div>

                <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">Materi Workshop</h3>
                @if($workshop->materi && $workshop->materi->count())
                <div class="space-y-3">
                    @foreach($workshop->materi as $m)
                        <div class="flex items-center justify-between rounded-lg px-3 py-2">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2V8.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0011.5 2H6a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <div>
                                    <a href="{{ route('pengguna.materi.view', $m->materi_id) }}" data-url="{{ route('pengguna.materi.view', $m->materi_id) }}" class="open-materi-modal font-medium text-gray-500 hover:text-gray-900">{{ $m->judul_topik ?: basename($m->file_materi_url) }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pengguna.materi.download', $m->materi_id) }}" class="px-3 py-1.5 text-sm font-bold text-gray-400 hover:text-gray-800">Download</a>
                                <form action="{{ route('pemateri.workshop.destroy', $m->materi_id) }}" method="POST" onsubmit="return confirm('Hapus materi ini? Tindakan tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-sm font-bold text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="text-gray-500 italic">Belum ada materi diunggah.</div>
                @endif

                <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">Materi yang Akan Dipelajari</h3>
                @php
                        $keywords = $workshop->keywords->pluck('keyword')->toArray();
                    @endphp

                    @if(count($keywords) > 0)
                    <div class="space-y-4">
                        @foreach($keywords as $keyword)
                        <div class="flex items-start space-x-3">
                            <div class="shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 flex-1">{{ $keyword }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-gray-500 italic">
                        Belum ada kata kunci/materi yang ditambahkan untuk workshop ini.
                    </div>
                    @endif


            </div>

            <!-- Right: Detail Event -->
            <div class="bg-green-50 rounded-xl p-4 border border-green-100 h-fit">
                <h4 class="text-base font-semibold text-gray-800 mb-3">Detail Event</h4>
                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14M5 11h14M5 15h14"/></svg>
                        <div>
                            <div class="text-gray-500">Judul Workshop</div>
                            <div class="font-medium">{{ $workshop->judul }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <div class="text-gray-500">Pemateri</div>
                            <div class="font-medium">{{ $workshop->pemateri->nama ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div>
                            <div class="text-gray-500">Tanggal</div>
                            <div class="font-medium">{{ $workshop->tanggal ? \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') : '-' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <div class="text-gray-500">Waktu</div>
                            <div class="font-medium">{{ $workshop->waktu ? \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i') . ' WIB' : '-' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <div class="text-gray-500">Lokasi</div>
                            <div class="font-medium">{{ $workshop->lokasi ?? '-' }}</div>
                        </div>
                    </div>
                    @php
                        $pendaftar = $workshop->pendaftaran_count ?? 0;
                        $kuota = $workshop->kuota ?? 0;
                        $kuotaTerisi = $workshop->kuota_terisi ?? $pendaftar;
                        $percent = $kuota > 0 ? min(100, round(($kuotaTerisi/$kuota)*100)) : 0;
                    @endphp
                    <div>
                        <div class="text-gray-500 mb-1">Kuota Tersedia</div>
                        <div class="w-full h-2 bg-yellow-100 rounded">
                            <div class="h-2 rounded" style="width: {{ $percent }}%; background-color:#d4a017;"></div>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">{{ $kuotaTerisi }}/{{ $kuota ?: '∞' }} Peserta</div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="px-3 py-2 rounded bg-yellow-600 text-white font-medium text-center hover:bg-yellow-500">
                        Upload Materi
                        </button>
                        <button type="button" class="px-3 py-2 rounded bg-yellow-800 text-white font-medium hover:bg-yellow-700">Unduh Sertifikat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('uploadModal').classList.add('hidden')"></div>
    <div class="relative mx-auto mt-20 w-11/12 max-w-xl bg-white rounded-2xl shadow-2xl p-6">
        <div class="text-center mb-4">
            <div class="text-2xl font-bold text-gray-900">Upload Materi</div>
            <div class="text-gray-500 text-sm">File should be pdf, docs, ppt, zip, rar. Max 10MB</div>
        </div>
        <form id="materiForm" action="{{ route('pemateri.workshop.store', $workshop->workshop_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input id="materi_file_input" name="materi_file" type="file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar" required>
            <div id="dropzone" class="border-2 border-dashed border-green-600/60 rounded-xl p-8 text-center bg-green-50 hover:bg-green-100 transition cursor-pointer">
                <div class="flex flex-col items-center gap-2">
                    <img src="{{ asset('images/document.png') }}" alt="icon" class="w-12 h-12">
                    <div id="dropzoneText" class="text-gray-600">Drag & Drop your files here</div>
                    <div class="text-xs text-gray-500">or click to browse</div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-4 py-2 rounded border border-gray-300 text-gray-700">Batal</button>
                <button type="submit" class="px-5 py-2 rounded bg-green-700 text-white font-semibold">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Materi View Modal -->
<div id="materiViewModal" class="fixed inset-0 z-50 hidden">
    <div id="materiViewOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="relative mx-auto mt-10 w-11/12 max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <div class="font-semibold text-gray-800">Lihat Materi</div>
            <button id="materiViewClose" type="button" class="text-gray-500 hover:text-gray-800">✕</button>
        </div>
        <div class="w-full h-[70vh]">
            <iframe id="materiViewFrame" src="about:blank" class="w-full h-full" frameborder="0"></iframe>
        </div>
    </div>
    <span class="sr-only">Modal backdrop</span>
    </div>

<script>
    (function(){
        const dz = document.getElementById('dropzone');
        const input = document.getElementById('materi_file_input');
        const text = document.getElementById('dropzoneText');
        if(!dz || !input) return;

        const updateText = (file) => {
            if(file){ text.textContent = file.name; }
        };

        dz.addEventListener('click', () => input.click());
        input.addEventListener('change', (e) => {
            const f = e.target.files && e.target.files[0];
            updateText(f);
        });

        ['dragenter','dragover'].forEach(evt => dz.addEventListener(evt, (e)=>{
            e.preventDefault();
            e.stopPropagation();
            dz.classList.add('bg-green-100');
        }));
        ['dragleave','drop'].forEach(evt => dz.addEventListener(evt, (e)=>{
            e.preventDefault();
            e.stopPropagation();
            dz.classList.remove('bg-green-100');
        }));
        dz.addEventListener('drop', (e)=>{
            const files = e.dataTransfer.files;
            if(files && files.length){
                input.files = files;
                updateText(files[0]);
            }
        });

        // Materi view modal logic
        const materiLinks = document.querySelectorAll('.open-materi-modal');
        const materiModal = document.getElementById('materiViewModal');
        const materiFrame = document.getElementById('materiViewFrame');
        const materiClose = document.getElementById('materiViewClose');
        const materiOverlay = document.getElementById('materiViewOverlay');

        const openMateriModal = (url) => {
            if(!materiModal || !materiFrame) return;
            materiFrame.src = url;
            materiModal.classList.remove('hidden');
        };
        const closeMateriModal = () => {
            if(!materiModal || !materiFrame) return;
            materiModal.classList.add('hidden');
            materiFrame.src = 'about:blank';
        };

        materiLinks.forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const url = a.getAttribute('data-url') || a.getAttribute('href');
                if(url){ openMateriModal(url); }
            });
        });

        if(materiClose){ materiClose.addEventListener('click', closeMateriModal); }
        if(materiOverlay){ materiOverlay.addEventListener('click', closeMateriModal); }

        // Close modals on Esc
        document.addEventListener('keydown', (e)=>{
            if(e.key === 'Escape'){
                const up = document.getElementById('uploadModal');
                if(up){ up.classList.add('hidden'); }
                closeMateriModal();
            }
        });
    })();
</script>
@endsection
