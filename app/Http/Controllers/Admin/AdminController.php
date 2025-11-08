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
        // ===== Statistik ringkas (tanpa kolom yang tidak ada di skema) =====
        $statistics = [
            'total_workshop'            => Workshop::count(),           // tidak filter status_workshop
            'total_peserta_terdaftar'   => Pendaftaran::count(),
            'total_request'             => class_exists(\App\Models\RequestWorkshop::class) ? RequestWorkshop::count() : 0,
            'total_user'                => User::count(),
        ];

        // ===== Department (prodi_fakultas) leaderboard dari pendaftaran =====
        [$departmentData, $departmentMax] = $this->getDepartmentData();

        // ===== Tren pendaftaran 7 bulan terakhir (pakai pendaftaran.tanggal_daftar) =====
        $trendData = $this->getTrendData();

        return view('admin.dashboard', compact(
            'statistics',
            'departmentData',
            'departmentMax',
            'trendData'
        ));
    }

    private function getTrendData()
    {
        $labels = [];
        $series = [];

        // 7 titik (termasuk bulan ini): M-6 ... M
        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $count = Pendaftaran::whereMonth('tanggal_daftar', $month->month)
                ->whereYear('tanggal_daftar', $month->year)
                ->count();

            $series[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $series,
        ];
    }

    /**
     * Ambil 5 besar prodi_fakultas berdasarkan jumlah pendaftaran.
     * Menggunakan join pendaftaran → users (users.user_id).
     */
    private function getDepartmentData()
    {
        $rows = Pendaftaran::join('users', 'pendaftaran.user_id', '=', 'users.user_id')
            ->select('users.prodi_fakultas as name', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('users.prodi_fakultas')
            ->groupBy('users.prodi_fakultas')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $data = $rows->map(fn($r) => ['name' => $r->name, 'count' => (int) $r->cnt])->toArray();
        $max  = max(array_column($data ?: [['count'=>0]], 'count')) ?: 1; // hindari pembagi 0

        return [$data, $max];
    }

    /**
     * Endpoint filter (opsional): filter berbasis tanggal & prodi_fakultas.
     * Catatan: tabel workshops tidak punya faculty/department; jadi filter “fakultas”
     * diinterpretasikan sebagai users.prodi_fakultas via pendaftaran.
     */
    public function filterData(Request $request)
    {
        $start = $request->date('start_date', now()->subMonths(6)->startOfMonth());
        $end   = $request->date('end_date', now());
        $prodi = $request->input('department'); // alias fakultas/department → prodi_fakultas

        // Trend terfilter
        $labels = [];
        $series = [];
        for ($i = 6; $i >= 0; $i--) {
            $m = Carbon::parse($end)->subMonths($i);
            $labels[] = $m->format('M Y');

            $q = Pendaftaran::whereMonth('tanggal_daftar', $m->month)
                ->whereYear('tanggal_daftar', $m->year);

            if ($prodi) {
                $q->join('users', 'pendaftaran.user_id', '=', 'users.user_id')
                  ->where('users.prodi_fakultas', $prodi);
            }

            $series[] = $q->count();
        }

        // Department leaderboard terfilter rentang tanggal
        $deptRows = Pendaftaran::join('users', 'pendaftaran.user_id', '=', 'users.user_id')
            ->whereBetween('tanggal_daftar', [$start, $end])
            ->when($prodi, fn($qq) => $qq->where('users.prodi_fakultas', $prodi))
            ->select('users.prodi_fakultas as name', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('users.prodi_fakultas')
            ->groupBy('users.prodi_fakultas')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $deptData = $deptRows->map(fn($r) => ['name' => $r->name, 'count' => (int) $r->cnt])->toArray();
        $deptMax  = max(array_column($deptData ?: [['count'=>0]], 'count')) ?: 1;

        return response()->json([
            'status' => 'success',
            'trend'  => ['labels' => $labels, 'data' => $series],
            'departments' => ['data' => $deptData, 'max' => $deptMax],
        ]);
    }
}
