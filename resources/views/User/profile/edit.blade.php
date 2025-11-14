@extends('User.Layout.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('pengguna.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li><a href="{{ route('pengguna.profile.index') }}" class="hover:text-gray-900">Profil</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Edit Profil</li>
                </ol>
            </nav>
        </div>

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

        <!-- User Header Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#057A55] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Edit Profil</h2>
                    <p class="text-sm text-gray-600">Ubah foto profil dan nama anda</p>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-[#057A55] rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
            </div>

            <form action="{{ route('pengguna.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Photo Profil -->
                    <div class="border-b border-gray-200 pb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-4">Foto Profil</label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <div class="relative">
                                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center border-4 border-[#057A55] shadow-md">
                                    @if($user->foto_profil_url)
                                        <img id="profile-preview" src="{{ asset('storage/' . $user->foto_profil_url) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <img id="profile-preview" src="" alt="Profile" class="w-full h-full object-cover hidden">
                                        <span id="profile-placeholder" class="text-[#057A55] text-4xl font-bold">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <label for="foto_profil" class="absolute bottom-0 right-0 bg-[#057A55] hover:bg-[#016545] text-white rounded-full p-2 cursor-pointer shadow-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </label>
                                <input type="file" 
                                       id="foto_profil" 
                                       name="foto_profil" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif"
                                       class="hidden"
                                       onchange="previewImage(this)">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 mb-2">Upload Foto Profil</p>
                                <p class="text-xs text-gray-500 mb-3">Format yang didukung: JPG, PNG, GIF</p>
                                <p class="text-xs text-gray-500 mb-3">Ukuran maksimal: 2MB</p>
                                <p class="text-xs text-gray-400">Klik ikon kamera di foto untuk mengupload gambar baru</p>
                                @error('foto_profil')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="nama"
                               name="nama" 
                               value="{{ old('nama', $user->nama) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#057A55] focus:border-[#057A55] @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIM/NIDN (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIM/NIDN</label>
                        <input type="text" 
                               value="{{ $user->nim_nidn ?? '-' }}" 
                               disabled
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               value="{{ $user->email }}" 
                               disabled
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    </div>

                    <!-- Prodi/Fakultas (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prodi/Fakultas</label>
                        <input type="text" 
                               value="{{ $user->prodi_fakultas ?? '-' }}" 
                               disabled
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    </div>

                    <!-- Nomor Telepon (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" 
                               value="{{ $user->nomor_telepon ?? '-' }}" 
                               disabled
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    </div>

                    <!-- Alamat (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea 
                               disabled
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600"
                               rows="3">{{ $user->alamat ?? '-' }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4 border-t border-gray-200">
                        <button type="submit" 
                                class="flex-1 bg-white border-2 border-[#057A55] text-[#057A55] hover:bg-[#057A55] hover:text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('pengguna.profile.index') }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors text-center flex items-center justify-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024;
        if (input.files[0].size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            return;
        }

        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            const placeholder = document.getElementById('profile-placeholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.onerror = function() {
            alert('Error membaca file. Silakan coba lagi.');
            input.value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Validasi form sebelum submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('foto_profil');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024;
                
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    return false;
                }

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    return false;
                }
            }
        });
    }
});
</script>
@endsection
