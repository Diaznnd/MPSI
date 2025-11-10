@extends('User.Layout.app')

@section('title', 'Materi Workshop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Materi Workshop</h1>
        <p class="text-gray-600">Kelola materi workshop yang Anda ajarkan</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($workshops->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($workshops as $workshop)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $workshop->judul }}</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            <span class="font-medium">Tanggal:</span> 
                            {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d F Y') }}
                        </p>
                        
                        <!-- Materi List -->
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Materi yang diupload:</h4>
                            @if($workshop->materi->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($workshop->materi as $materi)
                                        <li class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                            <span class="text-sm text-gray-700 truncate flex-1">{{ $materi->nama_file }}</span>
                                            <form action="{{ route('pemateri.materi.destroy', $materi->materi_id) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="ml-2 text-red-600 hover:text-red-800 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">Belum ada materi yang diupload</p>
                            @endif
                        </div>

                        <!-- Upload Button -->
                        <a href="{{ route('pemateri.materi.create', $workshop->workshop_id) }}" 
                           class="block w-full text-center px-4 py-2 text-white rounded-lg font-medium transition-colors" 
                           style="background-color: #057A55;"
                           onmouseover="this.style.backgroundColor='#068b4b';"
                           onmouseout="this.style.backgroundColor='#057A55';">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Upload Materi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Workshop</h3>
            <p class="text-gray-600">Anda belum memiliki workshop yang dapat dikelola.</p>
        </div>
    @endif
</div>
@endsection
