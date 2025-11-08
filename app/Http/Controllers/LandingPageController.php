<?php

// app/Http/Controllers/LandingPageController.php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Mengambil data jumlah workshop yang aktif
        $total_workshop_aktif = Workshop::where('status_workshop', 'aktif')->count();

        // Mengirim data ke view
        return view('landing', [
            'statistics' => [
                'total_workshop_aktif' => $total_workshop_aktif,
            ]
        ]);
    }
}
