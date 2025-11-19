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
        <!-- Poster -->
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
                            @php $percent = $kuotaMax > 0 ? min(100, round(($kuotaTerisi / $kuotaMax) * 100)) : 0; @endphp
                            <div class="w-full h-3 bg-yellow-100 rounded-full overflow-hidden">
                                <div class="h-3 bg-yellow-400" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-700 mt-1">{{ $kuotaTerisi }}/{{ $kuotaMax }} Peserta</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        @if($userRegistered)
                            <button disabled class="w-full px-6 py-3 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed">Anda Sudah Terdaftar</button>
                        @elseif($isFull)
                            <button disabled class="w-full px-6 py-3 bg-red-500 text-white rounded-lg font-medium cursor-not-allowed">Kuota Sudah Penuh</button>
                        @else
                            <button id="btnRegister" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">Daftar Sekarang</button>
                        @endif
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
</script>
@endpush
