@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Form Pembuatan Workshop</h1>
            <div class="mt-2 h-1 w-24 bg-yellow-500 mx-auto rounded"></div>
        </div>

        <div>
            <form action="{{ route('admin.workshop.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                   placeholder="Judul Workshop"
                                   value="{{ old('judul') }}" 
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
                                      placeholder="Deskripsi Workshop"
                                      required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemateri -->
                        <div>
                            <label for="pemateri_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pemateri
                            </label>
                            <select id="pemateri_id" name="pemateri_id" 
                                    class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                    required>
                                <option value="">Pilih Pemateri</option>
                                @foreach($pemateriUsers as $pemateri)
                                    <option value="{{ $pemateri->user_id }}" 
                                            {{ old('pemateri_id') == $pemateri->user_id ? 'selected' : '' }}>{{ $pemateri->nama }}
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
                                   placeholder="Contoh: Lantai 5 Aula Perpustakaan UNAND"
                                   value="{{ old('lokasi') }}" 
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
                            <input type="number" 
                                   id="kuota" 
                                   name="kuota" 
                                   class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                   placeholder="Jumlah Kuota Peserta"
                                   value="{{ old('kuota') }}" 
                                   required>
                            @error('kuota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-6">                
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Workshop
                            </label>
                            <input type="date" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                   value="{{ old('tanggal') }}" 
                                   required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label for="waktu" class="block text-sm font-semibold text-gray-700 mb-2">
                                Waktu Workshop
                            </label>
                            <input type="time" 
                                   id="waktu" 
                                   name="waktu" 
                                   class="w-full px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                   value="{{ old('waktu') }}" 
                                   required>
                            @error('waktu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kata Kunci Workshop
                            </label>

                            <div class="space-y-3" id="keywordContainer">
                                <!-- Default Material Input Field -->
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
                                    <!-- Added keywords will be displayed here dynamically -->
                                </div>
                            </div>

                            @error('keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sampul Poster -->
                        <div>
                            <label for="sampul_poster_url" class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Sampul Poster
                            </label>

                            <!-- Drag and Drop Container -->
                            <div 
                                class="border-2 border-dashed border-yellow-400 p-5 rounded-lg flex flex-col justify-center items-center cursor-pointer"
                                ondrop="handleDrop(event)" 
                                ondragover="handleDragOver(event)" 
                                onclick="document.getElementById('sampul_poster_url').click()">
                                <input type="file" 
                                    id="sampul_poster_url" 
                                    name="sampul_poster_url" 
                                    accept="image/*" 
                                    class="hidden" 
                                    onchange="handleFileChange(event)">
                                
                                <img src="{{ asset('images/document.png') }}" class="w-16 h-auto mb-4" id="previewImage" style="display: none;">
                                <p class="text-gray-500 text-center">Drag & Drop or Click to Upload</p>
                            </div>

                            @error('sampul_poster_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                <!-- Button Action -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" 
                            onclick="window.history.back()" 
                            class="px-8 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-8 py-3 text-white rounded-lg font-medium transition-colors"
                            style="background-color: #068B4B;"
                            onmouseover="this.style.backgroundColor='#08AA5C';"
                            onmouseout="this.style.backgroundColor='#068B4B';">
                        Buat Workshop
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts') 
    <!-- Push your external JavaScript file here -->
    <script src="{{ asset('js/script.js') }}"></script>
@endpush