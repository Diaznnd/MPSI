<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Workshop</title>
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO UNAND.PNG') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @endif
    
    <style>
        /* Nav Menu Styles */
        .nav-menu-item {
            color: #374151;
            transition: all 0.2s ease;
        }
        
        .nav-menu-item:hover {
            background-color: #068b4b;
            color: white;
        }
        
        .nav-menu-item.active {
            background-color: #e5e7eb;
            color: #068b4b;
        }
        
        .nav-menu-item.active:hover {
            background-color: #068b4b;
            color: white;
        }
        
        /* Fix untuk arbitrary color values yang tidak ter-compile oleh Tailwind CDN */
        /* Menggunakan attribute selector dengan contains */
        [class*="068b4b"] {
            color: #068b4b !important;
        }
        
        [class*="22C995"] {
            color: #22C995 !important;
        }
        
        [class*="057841"] {
            background-color: #057841 !important;
        }
        
        /* Background colors */
        [class*="bg-[#068b4b]"] {
            background-color: #068b4b !important;
        }
        
        [class*="bg-[#ffffff]"] {
            background-color: #ffffff !important;
        }
        
        /* Text colors - lebih spesifik */
        span[class*="068b4b"],
        h3[class*="068b4b"],
        div[class*="068b4b"],
        p[class*="068b4b"],
        a[class*="068b4b"],
        button[class*="068b4b"] {
            color: #068b4b !important;
        }
        
        svg[class*="22C995"] {
            color: #22C995 !important;
        }
        
        h1[class*="000000"],
        div[class*="000000"] {
            color: #000000 !important;
        }
    </style>
    <script>
        // Fix warna setelah DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Fix untuk elemen dengan class text-[#068b4b]
            document.querySelectorAll('[class*="text-[#068b4b]"]').forEach(el => {
                if (!el.style.color) {
                    el.style.color = '#068b4b';
                }
            });
            
            // Fix untuk elemen dengan class bg-[#068b4b]
            document.querySelectorAll('[class*="bg-[#068b4b]"]').forEach(el => {
                if (!el.style.backgroundColor || el.style.backgroundColor === '') {
                    el.style.backgroundColor = '#068b4b';
                }
            });
            
            // Fix untuk elemen dengan class text-[#22C995]
            document.querySelectorAll('[class*="text-[#22C995]"]').forEach(el => {
                if (!el.style.color) {
                    el.style.color = '#22C995';
                }
            });
            
            // Fix untuk elemen dengan class bg-[#057841]
            document.querySelectorAll('[class*="bg-[#057841]"]').forEach(el => {
                if (!el.style.backgroundColor) {
                    el.style.backgroundColor = '#057841';
                }
            });
            
            // Fix untuk elemen dengan class bg-[#ffffff]
            document.querySelectorAll('[class*="bg-[#ffffff]"]').forEach(el => {
                if (!el.style.backgroundColor) {
                    el.style.backgroundColor = '#ffffff';
                }
            });
            
            // Fix untuk elemen dengan class text-[#000000]
            document.querySelectorAll('[class*="text-[#000000]"]').forEach(el => {
                if (!el.style.color) {
                    el.style.color = '#000000';
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100">

    <div class="w-64 fixed top-0 left-0 h-full shadow-lg bg-white flex flex-col justify-between">
        <div class="p-4 overflow-y-auto">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-4 mb-8">
                <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Universitas Andalas" class="w-16 h-auto">
                <div class="text-lg font-bold leading-tight" style="color: #057A55;">
                    Sistem Informasi Workshop
                </div>
            </div>

            <div class="text-sm font-bold mb-10" style="color: #000000;">
                UPT Perpustakaan Universitas Andalas
            </div>

            <!-- Menu Items -->
            <nav>
                <ul>
                    <li class="mb-5">
                        <a href="/admin/dashboard"
                            class="nav-menu-item flex items-center justify-between py-2 px-2.5 text-sm rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <span>Dashboard</span>
                            @if(request()->routeIs('admin.dashboard'))
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: #068b4b;"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/workshops"
                            class="nav-menu-item flex items-center justify-between py-2 px-2.5 text-sm rounded-lg {{ request()->routeIs('admin.workshop.*') ? 'active' : '' }}">
                            <span>Workshop</span>
                            @if(request()->routeIs('admin.workshop.*'))
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: #068b4b;"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/account/manage"
                            class="nav-menu-item flex items-center justify-between py-2 px-2.5 text-sm rounded-lg {{ request()->routeIs('admin.account.*') ? 'active' : '' }}">
                            <span>Manajemen Akun</span>
                            @if(request()->routeIs('admin.account.*'))
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: #068b4b;"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/request"
                            class="nav-menu-item flex items-center justify-between py-2 px-2.5 text-sm rounded-lg {{ request()->routeIs('admin.request.*') ? 'active' : '' }}">
                            <span>Request</span>
                            @if(request()->routeIs('admin.request.*'))
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: #068b4b;"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/profile"
                            class="nav-menu-item flex items-center justify-between py-2 px-2.5 text-sm rounded-lg {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                            <span>Profil</span>
                            @if(request()->routeIs('admin.profile.*'))
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: #068b4b;"></span>
                            @endif
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Sticky Logout Button -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-md text-sm font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>


    <!-- Main Content -->
    <div class="flex-1 ml-64 p-8">
        <!-- Your main content goes here -->
        <div class="container mx-auto">
            @yield('content') <!-- Dynamically load content -->
        </div>

        <footer class="bg-white shadow-mt text-black py-4 mt-12">
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-sm">&copy; 2025 Kelompok 3 Matakuliah MPSI. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
