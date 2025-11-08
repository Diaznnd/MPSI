<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Workshop UPT Pustaka Unand</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Bagian kiri -->
        <div class="w-1/2 relative flex flex-col items-center justify-center overflow-hidden text-white">
            <!-- Background image -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/login.png') }}');"></div>

            <!-- Overlay hijau semi-transparan -->
            <div class="absolute inset-0 bg-green-800/70"></div>

            <!-- Konten utama (logo dan teks) -->
            <div class="relative z-10 text-center px-8">
                <img src="{{ asset('images/LOGO UNAND.png') }}" alt="Logo Unand" class="h-40 mx-auto mb-8">
                <h1 class="text-2xl font-bold leading-snug mb-2">
                    SISTEM PENDAFTARAN WORKSHOP
                </h1>
                <h2 class="text-lg font-medium">
                    UPT PERPUSTAKAAN UNIVERSITAS ANDALAS
                </h2>
            </div>
        </div>

        <!-- Bagian kanan -->
        <div class="w-1/2 flex items-center justify-center px-10">
            <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-center text-2xl font-bold text-green-700 mb-2">LOGIN</h2>
                <p class="text-center text-sm text-gray-500 mb-6">Silahkan Login Menggunakan Akun SSO Unand</p>

                <!-- Tampilkan pesan error login jika ada -->
                @if ($errors->has('login_error'))
                    <div class="bg-red-100 text-red-600 p-3 mb-4 rounded-md text-center">
                        {{ $errors->first('login_error') }}
                    </div>
                @endif

                <!-- Form login -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mt-1 w-full border border-green-500 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-green-700">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="mt-1 w-full border border-green-500 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-green-700">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Lupa password link -->
                    <div class="flex justify-between items-center">
                        <a href="#" class="text-sm text-green-600 hover:underline">Lupa Password?</a>
                    </div>

                    <!-- Tombol login -->
                    <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-md shadow-sm transition">
                        LOGIN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle visibility password
        document.getElementById('togglePassword').addEventListener('click', function () {
            const pwd = document.getElementById('password');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
