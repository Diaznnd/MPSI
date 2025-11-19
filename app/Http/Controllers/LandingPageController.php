<?php

// app/Http/Controllers/LandingPageController.php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        // Mengambil data jumlah workshop yang aktif
        $total_workshop_aktif = Workshop::where('status_workshop', 'aktif')->count();

        // Mengambil workshop populer (paling banyak pendaftar, status aktif)
        // Limit 6 workshop
        $popular_workshops = Workshop::where('status_workshop', 'aktif')
            ->withCount('pendaftaran')
            ->with('pemateri')
            ->orderByDesc('pendaftaran_count')
            ->limit(3)
            ->get();

        // Mengirim data ke view
        return view('landing', [
            'statistics' => [
                'total_workshop_aktif' => $total_workshop_aktif,
            ],
            'popular_workshops' => $popular_workshops
        ]);
    }

    /**
     * Redirect to role-specific workshop show route. If guest, redirect to login
     * and allow Laravel's intended redirect to come back here after login.
     */
    public function redirectToRoleWorkshop($workshop)
    {
        if (!Auth::check()) {
            // redirect guest to login; Laravel will store intended URL so after login
            // user will be returned here and then redirected based on role
            return Redirect::guest(route('login'));
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.workshop.show', $workshop);
        }

        if ($user->hasRole('pemateri')) {
            return redirect()->route('pemateri.workshop.show', $workshop);
        }

        // Default for authenticated pengguna (or other roles)
        return redirect()->route('pengguna.workshop.detail', $workshop);
    }
}
