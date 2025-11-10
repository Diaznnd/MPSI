@extends('User.Layout.app')

@section('title', 'Upload Materi Workshop')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('pemateri.materi.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-[#057A55] transition-colors mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Materi
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Materi Workshop</h1>
        <p class="text-gray-600">{{ $workshop->judul }}</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('pemateri.materi.store', $workshop->workshop_id) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="materi_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih File Materi <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       id="materi_file" 
                       name="materi_file" 
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                       required
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-[#057A55] file:text-white
                              hover:file:bg-[#068b4b]
                              file:cursor-pointer
                              border border-gray-300 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-[#057A55] focus:border-transparent">
                <p class="mt-2 text-sm text-gray-500">
                    Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (Maksimal 10MB)
                </p>
                @error('materi_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pemateri.materi.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 text-white rounded-lg font-medium transition-colors" 
                        style="background-color: #057A55;"
                        onmouseover="this.style.backgroundColor='#068b4b';"
                        onmouseout="this.style.backgroundColor='#057A55';">
                    Upload Materi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
