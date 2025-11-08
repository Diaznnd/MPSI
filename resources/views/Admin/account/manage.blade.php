@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
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
                    <li class="text-gray-900 font-medium">Manajemen Akun</li>
                </ol>
            </nav>

            <!-- Title Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen Akun</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola akun pengguna, pemateri, dan admin</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pengguna Card -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-blue-500 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Pengguna</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_pengguna']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium {{ $stats['total_pengguna']['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                        ↑ {{ $stats['total_pengguna']['change'] }}%
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Last 7 days</span>
                </div>
            </div>

            <!-- Pengguna Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Pengguna</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pengguna']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium {{ $stats['pengguna']['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                        ↑ {{ $stats['pengguna']['change'] }}%
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Last 7 days</span>
                </div>
            </div>

            <!-- Pemateri Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Pemateri</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pemateri']['value'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium {{ $stats['pemateri']['is_positive'] ? 'text-green-600' : 'text-red-600' }}">
                        ↑ {{ $stats['pemateri']['change'] }}%
                    </span>
                    <span class="ml-2 text-xs text-gray-500">Last 7 days</span>
                </div>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="mb-8">
            <!-- Flash Messages -->
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

            <!-- Search and Filters -->
            <form method="GET" action="{{ route('admin.account.manage') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama atau email pengguna..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Role Filter -->
                    <div class="w-full md:w-48">
                        <select name="role" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fakultas Filter -->
                    <div class="w-full md:w-48">
                        <select name="fakultas" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Fakultas</option>
                            @forelse($fakultasList as $fakultas)
                                <option value="{{ $fakultas }}" {{ request('fakultas') == $fakultas ? 'selected' : '' }}>
                                    {{ $fakultas }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada fakultas</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="px-6 py-2 bg-[#068B4B] hover:bg-[#08AA5C] text-white rounded-lg font-medium">
                        Filter
                    </button>

                    <!-- Reset Button -->
                    @if(request('search') || request('role') || request('fakultas'))
                    <a href="{{ route('admin.account.manage') }}" 
                       class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-medium text-center">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Induk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fakultas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $user->nim_nidn ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->prodi_fakultas)
                                    @php
                                        $parts = explode(' - ', $user->prodi_fakultas);
                                        $fakultas = count($parts) > 1 ? trim($parts[1]) : trim($parts[0]);

                                        // Daftar fakultas UNAND dengan warna tetap
                                        $facultyColors = [
                                            'Fakultas Kedokteran'                         => ['bg-red-200', 'text-red-800'],
                                            'Fakultas Hukum'                              => ['bg-yellow-200', 'text-yellow-800'],
                                            'Fakultas Pertanian'                          => ['bg-green-200', 'text-green-800'],
                                            'Fakultas Teknik'                             => ['bg-blue-200', 'text-blue-800'],
                                            'Fakultas Ekonomi dan Bisnis'                 => ['bg-amber-200', 'text-amber-800'],
                                            'Fakultas Ilmu Budaya'                        => ['bg-pink-200', 'text-pink-800'],
                                            'Fakultas Matematika dan Ilmu Pengetahuan Alam'=> ['bg-indigo-200', 'text-indigo-800'],
                                            'Fakultas Ilmu Sosial dan Ilmu Politik'       => ['bg-purple-200', 'text-purple-800'],
                                            'Fakultas Peternakan'                         => ['bg-lime-200', 'text-lime-800'],
                                            'Fakultas Teknologi Pertanian'                => ['bg-teal-200', 'text-teal-800'],
                                            'Fakultas Farmasi'                            => ['bg-cyan-200', 'text-cyan-800'],
                                            'Fakultas Kesehatan Masyarakat'               => ['bg-emerald-200', 'text-emerald-800'],
                                            'Fakultas Keperawatan'                        => ['bg-sky-200', 'text-sky-800'],
                                            'Fakultas Teknologi Informasi'                => ['bg-fuchsia-200', 'text-fuchsia-800'],
                                            'Fakultas Kedokteran Gigi'                    => ['bg-rose-200', 'text-rose-800'],
                                        ];

                                        // Tentukan warna berdasarkan fakultas, atau default jika tidak ditemukan
                                        [$bg, $text] = $facultyColors[$fakultas] ?? ['bg-gray-200', 'text-gray-800'];
                                    @endphp

                                    <div class="w-fit px-3 py-1 rounded-full font-medium text-sm {{ $bg }} {{ $text }}" title="{{ $fakultas }}">
                                        {{ $fakultas }}
                                    </div>
                                @else
                                    <div class="w-fit px-3 py-1 rounded-full bg-gray-200 text-gray-800 font-medium text-sm" title="Tidak ada fakultas">
                                        Tidak ada fakultas
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'pemateri')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Pemateri
                                    </span>
                                @elseif($user->role === 'pengguna')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Pengguna
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($user->role === 'pengguna')
                                        <form action="{{ route('admin.account.promote', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">
                                                Promote
                                            </button>
                                        </form>
                                    @elseif($user->role === 'pemateri')
                                        @php
                                            $pemateriUntil = $user->pemateri_until ? \Carbon\Carbon::parse($user->pemateri_until) : null;
                                            $now = \Carbon\Carbon::now();
                                            $remainingTime = $pemateriUntil ? $now->diffInSeconds($pemateriUntil, false) : 0;
                                        @endphp
                                        <form action="{{ route('admin.account.demote', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            @if($pemateriUntil && $remainingTime > 0)
                                                <button type="submit" 
                                                        id="timer-{{ $user->user_id }}"
                                                        class="px-4.5 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs "
                                                        data-until="{{ $pemateriUntil->format('Y-m-d H:i:s') }}"
                                                        data-user-id="{{ $user->user_id }}"
                                                        title="Klik untuk demote sekarang">
                                                    <span class="timer-text">Loading...</span>
                                                </button>
                                            @else
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs">
                                                    Demote
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                    
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.account.destroy', $user->user_id) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada user yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all timers
    const timerButtons = document.querySelectorAll('[id^="timer-"]');
    
    timerButtons.forEach(function(button) {
        const until = button.getAttribute('data-until');
        const userId = button.getAttribute('data-user-id');
        
        if (!until) return;
        
        const targetDate = new Date(until);
        
        function updateTimer() {
            const now = new Date();
            const diff = targetDate - now;
            
            if (diff <= 0) {
                // Time expired, reload page to trigger auto-demote
                button.textContent = 'Expired';
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-red-500');
                button.disabled = true;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            
            // Calculate days, hours, minutes, seconds
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            // Format display
            let displayText = '';
            if (days > 0) {
                displayText = `${days} days`;
            } else if (hours > 0) {
                displayText = `${hours}h ${minutes}m`;
            } else if (minutes > 0) {
                displayText = `${minutes}m ${seconds}s`;
            } else {
                displayText = `${seconds}s`;
            }
            
            const timerText = button.querySelector('.timer-text');
            if (timerText) {
                timerText.textContent = displayText;
            } else {
                button.innerHTML = displayText;
            }
        }
        
        // Update immediately
        updateTimer();
        
        // Update every second
        setInterval(updateTimer, 1000);
    });
});
</script>
@endsection

