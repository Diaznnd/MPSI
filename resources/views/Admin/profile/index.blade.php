@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Profil</li>
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

        <!-- Admin Header Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#068b4b] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Setting Profile</h2>
                    <p class="text-sm text-gray-600">Kelola informasi profil dan keamanan data anda</p>
                </div>
            </div>
        </div>

        <!-- Password Verification Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Password</h3>
            <p class="text-sm text-gray-600 mb-4">Masukkan password Anda untuk mengakses halaman edit profil.</p>
            
            <form action="{{ route('admin.profile.verify') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#068b4b] focus:border-[#068b4b] @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" 
                        class="w-full bg-[#068b4b] hover:bg-green-600 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    Verifikasi dan Lanjutkan
                </button>
            </form>
        </div>

        <!-- Profile Information Card (Read-only) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-[#068b4b] rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
            </div>

            <div class="space-y-6">
                <!-- Photo Profil -->
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                        @if($user->foto_profil_url)
                            <img src="{{ asset('storage/' . $user->foto_profil_url) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Foto Profil</p>
                        <p class="text-xs text-gray-400">Verifikasi password untuk mengubah foto</p>
                    </div>
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" 
                           value="{{ $user->nama }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    <p class="mt-1 text-xs text-gray-500">Nama dan email tidak dapat diubah</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           value="{{ $user->email }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" 
                           value="{{ $user->nomor_telepon ?? '' }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    <p class="mt-1 text-xs text-gray-500">Verifikasi password untuk mengubah</p>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <input type="text" 
                           value="{{ $user->alamat ?? '' }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    <p class="mt-1 text-xs text-gray-500">Verifikasi password untuk mengubah</p>
                </div>
            </div>
        </div>

        <!-- Update Password Card (Read-only) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-[#068b4b] rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v13a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Perbarui Kata Sandi</h3>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman
            </p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                    <input type="password" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
                    <input type="password" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>
            </div>

            <p class="mt-4 text-xs text-gray-500">Verifikasi password untuk mengubah kata sandi</p>
        </div>
    </div>
</div>
@endsection

