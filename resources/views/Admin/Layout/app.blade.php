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
</head>
<body class="bg-gray-100">

    <div class="w-64 fixed top-0 left-0 h-full shadow-lg bg-white flex flex-col justify-between">
        <div class="p-4 overflow-y-auto">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-4 mb-8">
                <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Universitas Andalas" class="w-16 h-auto">
                <div class="text-lg text-[#057A55] font-bold leading-tight">
                    Sistem Informasi Workshop
                </div>
            </div>

            <div class="text-sm font-bold text-[#000000] mb-10">
                UPT Perpustakaan Universitas Andalas
            </div>

            <!-- Menu Items -->
            <nav>
                <ul>
                    <li class="mb-5">
                        <a href="/admin/dashboard"
                            class="flex items-center justify-between py-2 px-2.5 text-sm rounded-lg transition-colors 
                                hover:bg-[#068b4b] hover:text-white
                                @if(request()->routeIs('admin.dashboard')) bg-gray-200 text-[#068b4b] @endif">
                            <span>Dashboard</span>
                            @if(request()->routeIs('admin.dashboard'))
                                <span class="w-2.5 h-2.5 bg-[#068b4b] rounded-full"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/workshops"
                            class="flex items-center justify-between py-2 px-2.5 text-sm rounded-lg transition-colors 
                            hover:bg-[#068b4b] hover:text-white
                            @if(request()->routeIs('admin.workshop.index')) bg-gray-200 @endif">
                            <span>Workshop</span>
                            @if(request()->routeIs('admin.workshop.index'))
                                <span class="w-2.5 h-2.5 bg-[#068b4b] rounded-full"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/account/manage"
                            class="flex items-center justify-between py-2 px-2.5 text-sm rounded-lg transition-colors 
                            hover:bg-[#068b4b] hover:text-white
                            @if(request()->routeIs('admin.account.manage')) bg-gray-200 @endif">
                            <span>Manajemen Akun</span>
                            @if(request()->routeIs('admin.account.manage'))
                                <span class="w-2.5 h-2.5 bg-[#068b4b] rounded-full"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/request"
                            class="flex items-center justify-between py-2 px-2.5 text-sm rounded-lg transition-colors 
                            hover:bg-[#068b4b] hover:text-white
                            @if(request()->routeIs('admin.request.index')) bg-gray-200 @endif">
                            <span>Request</span>
                            @if(request()->routeIs('admin.request.index'))
                                <span class="w-2.5 h-2.5 bg-[#068b4b] rounded-full"></span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-5">
                        <a href="/admin/profile"
                            class="flex items-center justify-between py-2 px-2.5 text-sm rounded-lg transition-colors 
                            hover:bg-[#068b4b] hover:text-white
                            @if(request()->routeIs('admin.profile.index')) bg-gray-200 @endif">
                            <span>Profil</span>
                            @if(request()->routeIs('admin.profile.index'))
                                <span class="w-2.5 h-2.5 bg-[#068b4b] rounded-full"></span>
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

    <script src="{{ mix('js/script.js') }}"></script> <!-- Or any common script -->
    @stack('scripts')
</body>
</html>
