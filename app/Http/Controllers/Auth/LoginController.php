<?php

// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.Login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Pastikan jika user ditemukan dan password valid
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login_error' => 'Invalid credentials']);
        }

        // Jika login sukses
        Auth::login($user);

        // Redirect berdasarkan role user
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pemateri':
                return redirect()->route('pemateri.dashboard');
            case 'pengguna':
            default:
                return redirect()->route('pengguna.dashboard');
        }
    }

    public function logout()
    {
        Auth::logout(); // Log out the user
        return redirect('/'); // Redirect to the home page or login page
    }
}
