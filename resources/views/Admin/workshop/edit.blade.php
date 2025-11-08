@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Edit Workshop</h1>
            <div class="mt-2 h-1 w-24 bg-yellow-500 mx-auto rounded"></div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form action="{{ route('admin.workshop.update', $workshop->workshop_id) }}" method="POST" enctype="multipart/form-data" id="editWorkshopForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Judul Workshop -->
                        <div>
                            <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul Workshop
                            </label>
                            <input type="text" 
                                   id="judul" 
                                   name="judul" 
                                   class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                   value="{{ old('judul', $workshop->judul ?? '') }}"
                                   required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Workshop -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi Workshop
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4" 
                                      class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                      required>{{ old('deskripsi', $workshop->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemateri -->
                        <div>
                            <label for="pemateri_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pemateri
                            </label>
                            <select id="pemateri_id" 
                                    name="pemateri_id" 
                                    class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    required>
                                <option value="">Pilih Pemateri</option>
                                @foreach($pemateriUsers as $pemateri)
                                    <option value="{{ $pemateri->user_id }}" 
                                            {{ old('pemateri_id', $workshop->pemateri_id) == $pemateri->user_id ? 'selected' : '' }}>
                                        {{ $pemateri->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pemateri_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Lokasi
                            </label>
                            <input type="text" 
                                   id="lokasi" 
                                   name="lokasi" 
                                   class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                   value="{{ old('lokasi', $workshop->lokasi ?? '') }}"
                                   required>
                            @error('lokasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kuota Peserta -->
                        <div>
                            <label for="kuota" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kuota Peserta
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="kuota" 
                                       name="kuota" 
                                       class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                       value="{{ old('kuota', $workshop->kuota ?? 80) }}"
                                       min="1"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('kuota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal
                            </label>
                            <div class="relative">
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                       value="{{ old('tanggal', $workshop->tanggal ? \Carbon\Carbon::parse($workshop->tanggal)->format('Y-m-d') : '') }}"
                                       required>
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label for="waktu" class="block text-sm font-semibold text-gray-700 mb-2">
                                Waktu
                            </label>
                            <div class="relative">
                                <input type="time" 
                                       id="waktu" 
                                       name="waktu" 
                                       class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                       value="{{ old('waktu', $workshop->waktu ? (strlen($workshop->waktu) > 5 ? substr($workshop->waktu, 0, 5) : $workshop->waktu) : '') }}"
                                       required>
                            </div>
                            @error('waktu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kata Kunci (Keywords) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kata Kunci Workshop
                            </label>

                            <div class="space-y-3" id="keywordContainer">
                                <!-- Default Keyword Input Field -->
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                        id="keywordInput"
                                        name="keywords[]" 
                                        class="flex-1 px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                        placeholder="Contoh: AI, Machine Learning"
                                        value="{{ old('keywords.0') }}">
                                    <button type="button" 
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-medium"
                                            onclick="addKeyword()">
                                        Tambah
                                    </button>
                                </div>
                                
                                <!-- Display added keywords below the input -->
                                <div id="addedKeywords" class="flex flex-wrap gap-2 mt-4">
                                    @php
                                        $existingKeywords = old('keywords', $workshop->keywords->pluck('keyword')->toArray() ?? []);
                                        // Skip first element if it's from old input
                                        if (!empty($existingKeywords) && isset($existingKeywords[0]) && $existingKeywords[0] === old('keywords.0')) {
                                            array_shift($existingKeywords);
                                        }
                                    @endphp
                                    @foreach($existingKeywords as $keyword)
                                    <div class="flex items-center space-x-3 keyword-item">
                                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg">{{ $keyword }}</span>
                                        <button type="button" class="text-red-500 hover:text-red-700" onclick="removeKeyword('{{ $keyword }}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @error('keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Sampul Workshop -->
                        <div>
                            <label for="sampul_poster_url" class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Sampul Workshop
                            </label>
                            <div class="border-2 border-dashed border-yellow-400 rounded-lg p-6">
                                <!-- Current Image Preview -->
                                @if($workshop->sampul_poster_url)
                                <div class="mb-4" id="currentImagePreview">
                                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $workshop->sampul_poster_url) }}" alt="Current workshop image" class="w-24 h-16 object-cover rounded border">
                                        <button type="button" 
                                                onclick="removeCurrentImage()" 
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                                @else
                                <!-- Default Preview -->
                                <div class="mb-4" id="defaultImagePreview">
                                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                     <div class="w-24 h-16 bg-linier-to-br from-purple-900 via-purple-800 to-blue-900 rounded flex items-center justify-center">
                                        <span class="text-white text-xs font-medium">AI</span>
                                    </div>
                                </div>
                                @endif
                                
                                <input type="file" 
                                       id="sampul_poster_url" 
                                       name="sampul_poster_url" 
                                       class="hidden" 
                                       accept="image/*"
                                       onchange="handleFileSelect(this); handleFileChange(event);">
                                <label for="sampul_poster_url" class="cursor-pointer block text-center">
                                    <div class="text-gray-600">
                                        <span class="bg-gray-200 px-3 py-1 rounded text-sm">Choose file</span>
                                        <span class="ml-2 text-sm" id="fileName">{{ $workshop->sampul_poster_url ? 'Current image selected' : 'No file chosen' }}</span>
                                    </div>
                                </label>
                                
                                <!-- Hidden input untuk mark image removal -->
                                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                            </div>
                            @error('sampul_poster_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.workshop.show', $workshop->workshop_id) }}"
                            class="px-8 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg font-medium">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts') 
    <script src="{{ asset('js/script.js') }}"></script>
@endpush

