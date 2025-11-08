<?php

// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login dan memiliki peran 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Akses diteruskan ke route tujuan
        }

        // Jika tidak, alihkan ke halaman login dengan pesan error
        return redirect()->route('login')->withErrors(['role_error' => 'Anda tidak memiliki akses ke halaman ini']);
    }
}
