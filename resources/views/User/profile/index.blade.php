@extends('User.Layout.app')

@section('title', 'Profile')

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

        <!-- User Header Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-[#057A55] flex items-center justify-center border-4 border-[#057A55]">
                        @if($user->foto_profil_url)
                            <img src="{{ asset('storage/' . $user->foto_profil_url) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <span class="text-white text-2xl font-bold">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->nama }}</h2>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $user->prodi_fakultas ?? 'User' }} | {{ ucfirst($user->role) }}</p>
                    </div>
                </div>
                <a href="{{ route('pengguna.profile.edit') }}" 
                   class="bg-white border-2 border-[#057A55] text-[#057A55] hover:bg-[#057A55] hover:text-white font-semibold py-3 px-6 rounded-lg transition-all flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profil</span>
                </a>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#057A55] rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                </div>
                <a href="{{ route('pengguna.profile.edit') }}" 
                   class="bg-white border-2 border-[#057A55] text-[#057A55] hover:bg-[#057A55] hover:text-white font-medium text-sm py-2 px-4 rounded-md transition-all flex items-center space-x-1 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
            </div>

            <div class="space-y-6">

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" 
                           value="{{ $user->nama }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- NIM/NIDN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM/NIDN</label>
                    <input type="text" 
                           value="{{ $user->nim_nidn ?? '-' }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           value="{{ $user->email }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- Prodi/Fakultas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prodi/Fakultas</label>
                    <input type="text" 
                           value="{{ $user->prodi_fakultas ?? '-' }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" 
                           value="{{ $user->nomor_telepon ?? '-' }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600"
                           rows="3">{{ $user->alamat ?? '-' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
