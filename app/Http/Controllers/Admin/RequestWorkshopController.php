<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RequestWorkshopController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan tanggal jika ada
        $filterDate = $request->get('date', 'all');
        // Filter berdasarkan status jika ada
        $filterStatus = $request->get('status', 'all');
        
        $query = RequestWorkshop::with('user');

        // Filter berdasarkan tanggal (hanya jika tanggal_tanggapan tidak null)
        if ($filterDate === 'today') {
            $query->whereNotNull('tanggal_tanggapan')
                  ->whereDate('tanggal_tanggapan', Carbon::today());
        } elseif ($filterDate === 'week') {
            $query->whereNotNull('tanggal_tanggapan')
                  ->whereBetween('tanggal_tanggapan', [
                      Carbon::now()->startOfWeek(),
                      Carbon::now()->endOfWeek()
                  ]);
        } elseif ($filterDate === 'month') {
            $query->whereNotNull('tanggal_tanggapan')
                  ->whereMonth('tanggal_tanggapan', Carbon::now()->month)
                  ->whereYear('tanggal_tanggapan', Carbon::now()->year);
        }
        // Jika 'all', tidak ada filter tambahan - tampilkan semua request

        // Filter berdasarkan status
        if ($filterStatus === 'menunggu') {
            $query->where(function($q) {
                $q->where('status_request', 'menunggu')
                  ->orWhereNull('status_request')
                  ->orWhere('status_request', '');
            });
        } elseif ($filterStatus === 'disetujui') {
            $query->where('status_request', 'disetujui');
        } elseif ($filterStatus === 'ditolak') {
            $query->where('status_request', 'ditolak');
        }
        // Jika 'all', tidak ada filter tambahan

        // Urut berdasarkan tanggal terbaru (null di akhir), lalu request_id
        // MySQL/MariaDB tidak mendukung NULLS LAST, jadi kita gunakan ISNULL untuk memindahkan NULL ke akhir
        $requests = $query->orderByRaw('ISNULL(tanggal_tanggapan), tanggal_tanggapan DESC')
                          ->orderByDesc('request_id')
                          ->get();

        // Hitung statistik
        $stats = $this->calculateStatistics();

        return view('admin.request.index', compact(
            'requests',
            'stats',
            'filterDate',
            'filterStatus'
        ));
    }

    /**
     * Hitung statistik request workshop
     */
    private function calculateStatistics()
    {
        // Total request
        $totalRequest = RequestWorkshop::count();
        
        // Request menunggu
        $requestMenunggu = RequestWorkshop::where(function($q) {
            $q->where('status_request', 'menunggu')
              ->orWhereNull('status_request')
              ->orWhere('status_request', '');
        })->count();
        
        // Request disetujui
        $requestDisetujui = RequestWorkshop::where('status_request', 'disetujui')->count();
        
        // Request ditolak
        $requestDitolak = RequestWorkshop::where('status_request', 'ditolak')->count();
        
        // Hitung request 7 hari terakhir (termasuk yang belum ditanggapi jika tanggal_tanggapan null)
        $last7Days = RequestWorkshop::where(function($q) {
            $q->where('tanggal_tanggapan', '>=', Carbon::now()->subDays(7))
              ->orWhereNull('tanggal_tanggapan');
        })->count();
        
        $previous7Days = RequestWorkshop::whereBetween('tanggal_tanggapan', [
            Carbon::now()->subDays(14),
            Carbon::now()->subDays(7)
        ])->count();

        // Hitung persentase perubahan
        $percentageChange = 0;
        if ($previous7Days > 0) {
            $percentageChange = (($last7Days - $previous7Days) / $previous7Days) * 100;
        } elseif ($last7Days > 0) {
            $percentageChange = 100;
        }

        return [
            'total_request' => [
                'value' => $totalRequest,
                'change' => number_format($percentageChange, 1),
                'is_positive' => $percentageChange >= 0
            ],
            'request_menunggu' => [
                'value' => $requestMenunggu,
                'change' => '0.0',
                'is_positive' => true
            ],
            'request_disetujui' => [
                'value' => $requestDisetujui,
                'change' => '0.0',
                'is_positive' => true
            ],
            'request_ditolak' => [
                'value' => $requestDitolak,
                'change' => '0.0',
                'is_positive' => false
            ]
        ];
    }

    public function show($request_id)
    {
        $request = RequestWorkshop::with('user')->findOrFail($request_id);
        
        return view('admin.request.show', compact('request'));
    }

    public function updateStatus(Request $request, $request_id)
    {
        $requestWorkshop = RequestWorkshop::findOrFail($request_id);
        
        $validated = $request->validate([
            'status_request' => 'required|in:menunggu,disetujui,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        // Update status dan catatan admin
        $requestWorkshop->status_request = $validated['status_request'];
        $requestWorkshop->catatan_admin = $validated['catatan_admin'] ?? null;
        
        // Jika status diubah menjadi disetujui atau ditolak, set tanggal_tanggapan
        if (in_array($validated['status_request'], ['disetujui', 'ditolak'])) {
            $requestWorkshop->tanggal_tanggapan = Carbon::now();
        }
        
        $requestWorkshop->save();

        $statusMessages = [
            'menunggu' => 'Status request diubah menjadi Menunggu',
            'disetujui' => 'Request telah disetujui',
            'ditolak' => 'Request telah ditolak',
        ];

        return redirect()->route('admin.request.show', $request_id)
            ->with('success', $statusMessages[$validated['status_request']] ?? 'Status berhasil diperbarui.');
    }
}

