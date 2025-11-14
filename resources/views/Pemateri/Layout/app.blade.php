<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Pemateri') - UPT Pustaka Unand</title>
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO UNAND.PNG') }}">
    @stack('head')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="sticky top-0 flex justify-between items-center bg-white p-4 px-4 sm:px-6 lg:px-40 shadow-md z-50">
        <a href="{{ route('pemateri.dashboard') }}" class="flex items-center hover:opacity-80 transition-opacity">
            <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Universitas Andalas Logo" class="w-12 h-auto mr-4">
            <div>
                <span class="text-lg font-bold" style="color: #057A55;">SIMAWA UNAND</span>
                <span class="text-sm font-bold text-black block">UPT Perpustakaan Universitas Andalas</span>
            </div>
        </a>
        
        <!-- Desktop Menu -->
        <ul class="hidden lg:flex space-x-6 items-center">
            <li>
                <a href="{{ route('pemateri.dashboard') }}" 
                   class="text-gray-600 hover:text-[#057A55] transition-colors {{ request()->routeIs('pemateri.dashboard') ? 'text-[#057A55] font-semibold' : '' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('pemateri.workshop.index') }}" 
                   class="text-gray-600 hover:text-[#057A55] transition-colors {{ request()->routeIs('pemateri.workshop.index') ? 'text-[#057A55] font-semibold' : '' }}">
                    My Workshop
                </a>
            </li>
            <li>
                <a href="{{ route('pemateri.requestWorkshop') }}" 
                   class="text-gray-600 hover:text-[#057A55] transition-colors {{ request()->routeIs('pemateri.requestWorkshop') ? 'text-[#057A55] font-semibold' : '' }}">
                    Request Workshop
                </a>
            </li>
            
            <!-- User Dropdown -->
            <li class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                <button type="button" class="hs-dropdown-toggle flex items-center space-x-2 text-gray-600 hover:text-[#057A55] focus:outline-none">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-[#ffffff] flex items-center justify-center border">
                        @if(Auth::user()->foto_profil_url && Auth::user()->foto_profil_url != 'default_profile.jpg')
                            <img src="{{ asset('storage/' . Auth::user()->foto_profil_url) }}" alt="{{ Auth::user()->nama }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-600 text-sm font-bold">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</span>
                        @endif
                    </div>
                    <span class="hidden xl:block">{{ Auth::user()->nama }}</span>
                    <svg class="hs-dropdown-open:rotate-180 w-4 h-4 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 divide-y divide-gray-100">
                    <div class="py-3 px-4 -m-2 bg-gray-50 rounded-t-lg">
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->nama }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->prodi_fakultas ?? 'Pemateri' }} | {{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <div class="mt-2 py-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-x-3.5 py-2 px-3 rounded-md text-sm text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Mobile Menu Button -->
        <button type="button" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-[#057A55] hover:bg-gray-100" id="mobile-menu-toggle" aria-expanded="false" aria-controls="mobile-menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-lg">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('pemateri.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pemateri.dashboard') ? 'text-[#057A55] bg-green-50' : 'text-gray-600 hover:text-[#057A55] hover:bg-gray-50' }}">Dashboard</a>
            <a href="{{ route('pemateri.workshop.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pemateri.workshop.index') ? 'text-[#057A55] bg-green-50' : 'text-gray-600 hover:text-[#057A55] hover:bg-gray-50' }}">Workshop Saya</a>
            <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-200">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow-mt text-black py-4 mt-12">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-sm">&copy; 2025 Kelompok 3 Matakuliah MPSI. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')

    <div id="toastContainer" class="fixed top-4 right-4 space-y-2" style="z-index: 99999 !important; pointer-events: none;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
                    mobileMenu.classList.toggle('hidden');
                    mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
                });
                const mobileLinks = mobileMenu.querySelectorAll('a, button[type="submit"]');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    });
                });
            }
        });
    </script>
</body>
</html>
