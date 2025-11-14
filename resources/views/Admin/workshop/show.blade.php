@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <li><a href="{{ route('admin.workshop.index') }}" class="hover:text-gray-900">Manajemen Workshop</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">
                        <a>{{ $workshop->judul }}</a>
                    </li>
                </ol>
            </nav>

            <!-- Title Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-[#068B4B]"><a>{{ $workshop->judul }}</a></h1>
                </div>
            </div>
        </div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Cover / Poster -->
                <div class="bg-white rounded-lg overflow-hidden relative h-80">
                    @if(!empty($workshop->sampul_poster_url))
                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" 
                             alt="{{ $workshop->judul ?? 'Poster Workshop' }}" 
                             class="w-full h-full object-contain">
                    @else
                        <img src="https://via.placeholder.com/800x400/e2e8f0/94a3b8?text=Poster+Workshop" 
                             alt="Workshop" class="w-full h-full object-contain">
                    @endif
                </div>

                <!-- Deskripsi -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Workshop</h2>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $workshop->deskripsi ?? 'Belum ada deskripsi untuk workshop ini.' }}
                    </p>
                </div>

                <!-- Materi -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Materi yang Akan Dipelajari</h2>
                    
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

                    <a href="{{ route('admin.workshop.pendaftar', $workshop->workshop_id) }}" 
                        class="inline-flex items-center px-6 py-3 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg font-medium transition-colors mt-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lihat Daftar Pendaftar
                        </a>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Detail Event</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Judul Workshop</p>
                            <p class="text-gray-900">{{ $workshop->judul ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pemateri</p>
                            <p class="text-gray-900">{{ $workshop->pemateri->nama ?? 'Tidak diketahui' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal</p>
                            <p class="text-gray-900">
                                @if($workshop->tanggal)
                                    {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Waktu</p>
                            <p class="text-gray-900">
                                @if($workshop->waktu)
                                    @php
                                        $jamMulai = \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i');
                                        $jamSelesai = $workshop->jam_selesai ? \Carbon\Carbon::parse($workshop->jam_selesai)->setTimezone('Asia/Jakarta')->format('H.i') : null;
                                    @endphp
                                    {{ $jamSelesai ? ($jamMulai . ' – ' . $jamSelesai . ' WIB') : ($jamMulai . ' WIB') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lokasi</p>
                            <p class="text-gray-900">{{ $workshop->lokasi ?? '-' }}</p>
                        </div>

                        @php
                            $current = $workshop->kuota_terisi !== null ? $workshop->kuota_terisi : $workshop->pendaftaran()->count();
                            $max = $workshop->kuota ?? 100;
                            $percentage = $max > 0 ? ($current / $max) * 100 : 0;
                        @endphp

                        <div>
                            <p class="text-sm font-medium text-gray-500">Kuota Tersedia</p>
                            <p class="text-gray-900 mb-2">{{ $current }}/{{ $max }} Peserta</p>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-linier-to-r from-green-400 to-green-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Workshop</p>
                            <p class="text-gray-900">
                                @if($workshop->status_workshop === 'aktif')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                        Nonaktif
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Ubah Status Workshop</h4>
                        <form action="{{ route('admin.workshop.updateStatus', $workshop->workshop_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="flex gap-2">
                                <select name="status_workshop" 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#068B4B] focus:border-transparent text-sm">
                                    <option value="aktif" {{ $workshop->status_workshop === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ ($workshop->status_workshop === 'nonaktif' || $workshop->status_workshop === null) ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <button type="submit" 
                                        class="px-4 py-2 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg font-medium text-sm transition-colors">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 space-y-3">
                        <a href="{{ route('admin.workshop.edit', $workshop->workshop_id) }}" 
                           class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 rounded-lg font-medium text-center inline-block transition-colors">
                            EDIT
                        </a>
                        <button onclick="cancelWorkshop()" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                            HAPUS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembatalan -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi Penghapusan</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin <span class="font-semibold text-red-600">menghapus</span> workshop ini secara permanen?  
            Data yang dihapus tidak dapat dikembalikan.
        </p>
        <div class="flex space-x-3">
            <button onclick="closeModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg font-medium">
                Batal
            </button>
            <form action="{{ route('admin.workshop.destroy', $workshop->workshop_id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg font-medium">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
function cancelWorkshop() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}
function closeModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
