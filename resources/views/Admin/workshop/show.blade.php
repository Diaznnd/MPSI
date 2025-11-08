@extends('Admin.layout.app')

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Cover / Poster -->
                <div class="bg-gray-100 rounded-lg overflow-hidden relative h-80">
                    @if(!empty($workshop->sampul_poster_url))
                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" 
                             alt="{{ $workshop->judul ?? 'Poster Workshop' }}" 
                             class="w-full h-full object-cover">
                    @else
                        <img src="https://via.placeholder.com/800x400/e2e8f0/94a3b8?text=Poster+Workshop" 
                             alt="Workshop" class="w-full h-full object-cover">
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
                                        $jamMulai = $workshop->waktu;
                                        $jamSelesai = $workshop->jam_selesai ?? null;
                                        $time = $jamSelesai ? "{$jamMulai} – {$jamSelesai}" : $jamMulai;
                                    @endphp
                                    {{ $time }}
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
                    </div>

                    <div class="mt-8 space-y-3">
                        <a href="{{ route('admin.workshop.edit', $workshop->workshop_id) }}" 
                           class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 rounded-lg font-medium text-center inline-block transition-colors">
                            EDIT
                        </a>
                        <button onclick="cancelWorkshop()" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                            BATALKAN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembatalan -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi Pembatalan</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin membatalkan workshop ini? Tindakan ini tidak dapat dibatalkan.</p>
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
                    Ya, Batalkan
                </button>
            </form>
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
