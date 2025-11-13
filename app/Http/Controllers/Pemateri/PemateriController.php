<?php

namespace App\Http\Controllers\Pemateri;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemateriController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // Hitung statistik workshop pemateri
        $totalWorkshop = Workshop::where('pemateri_id', $user_id)->count();
        $workshopAktif = Workshop::where('pemateri_id', $user_id)
            ->where('status_workshop', 'aktif')
            ->count();
        $workshopSelesai = Workshop::where('pemateri_id', $user_id)
            ->where('status_workshop', 'selesai')
            ->count();
        
        // Hitung total pendaftar di semua workshop pemateri
        $totalPendaftar = Pendaftaran::whereHas('workshop', function($query) use ($user_id) {
            $query->where('pemateri_id', $user_id);
        })->count();
        
        // Ambil workshop terbaru
        $recentWorkshops = Workshop::where('pemateri_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('Pemateri.dashboard', compact(
            'totalWorkshop',
            'workshopAktif',
            'workshopSelesai',
            'totalPendaftar',
            'recentWorkshops'
        ));
    }
}
