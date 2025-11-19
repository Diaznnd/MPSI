<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workshop;
use App\Models\RequestWorkshop;
use App\Models\User;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $year = Carbon::now()->year;
        
        // ===== Statistik Workshop =====
        // Workshop yang lagi jalan (sedang berlangsung): tanggal >= hari ini (akan datang atau sedang berlangsung)
        $workshop_lagi_jalan = Workshop::count();
        
        // Workshop selesai: tanggal < hari ini (sudah lewat tanggalnya)
        $workshop_selesai = Workshop::where('status_workshop', 'selesai')->count();
        
        // Workshop batal: status nonaktif (dibatalkan, tidak peduli tanggal)
        $workshop_batal = Workshop::where('status_workshop', 'nonaktif')
            ->orWhereNull('status_workshop')
            ->count();
        
        // Workshop open pendaftaran: status aktif (buka pendaftaran)
        $workshop_open_pendaftaran = Workshop::where('status_workshop', 'aktif')->count();

        $totalRequest = RequestWorkshop::where('status_request', 'menunggu')->count();

        $totalUser = User::where('role', 'pengguna')->count();

        $totalpemateri = User::where('role', 'pemateri')->count();
        
        $statistics = [
            'workshop_lagi_jalan'        => $workshop_lagi_jalan,
            'workshop_selesai'           => $workshop_selesai,
            'workshop_batal'             => $workshop_batal,
            'workshop_open_pendaftaran'  => $workshop_open_pendaftaran,
            'total_request'              => $totalRequest,
            'total_user'                 => $totalUser,
            'total_pemateri'             => $totalpemateri
        ];

        $calendarWorkshops = Workshop::whereYear('tanggal', $year)
        ->where('status_workshop', 'aktif')
        ->orderBy('tanggal', 'asc')
        ->get();

        $recentWorkshops = Workshop::orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        $pelaksanaan = Workshop::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
        ->whereYear('tanggal', Carbon::now()->year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $data[$i] = $pelaksanaan[$i] ?? 0;
    }

    $labels = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Generate chart sebagai URL dari QuickChart
    $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
        'type' => 'bar', // ganti jadi 'line' jika ingin line chart
        'data' => [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Jumlah Workshop per Bulan',
                'data' => array_values($data),
                'backgroundColor' => 'rgba(6, 139, 75, 0.7)',
                'borderColor' => 'rgba(6, 139, 75, 1)',
                'fill' => false,
            ]]
        ],
        'options' => [
            'scales' => [
                'y' => ['beginAtZero' => true]
            ],
            'plugins' => [
                'title' => ['display' => true, 'text' => 'Statistik Workshop ' . date('Y')]
            ]
        ]
    ]));

    // === DATA UNTUK LINE CHART: Workshop Aktif vs Nonaktif ===
    $selesaiPerBulan = Workshop::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
        ->where('status_workshop', 'selesai')
        ->whereYear('tanggal', now()->year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    $aktifPerBulan = Workshop::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
        ->where('status_workshop', 'aktif')
        ->whereYear('tanggal', now()->year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // pastikan semua bulan (1–12) terisi angka 0 kalau belum ada data
    $labels = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $selesaiData = [];
    $aktifData = [];

    foreach (range(1, 12) as $i) {
        $selesaiData[] = $selesaiPerBulan[$i] ?? 0;
        $aktifData[] = $aktifPerBulan[$i] ?? 0;
    }


        return view('admin.dashboard', compact(
            'statistics',
            'calendarWorkshops',
            'year',
            'recentWorkshops',
            'chartUrl',
            'labels',
            'selesaiData',
            'aktifData'
        ));
    }
}
