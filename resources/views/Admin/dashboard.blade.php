@extends('Admin.Layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            {{-- Judul dan greeting --}}
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Hai <span class="font-semibold text-[#068b4b]">{{ Auth::user()->nama ?? 'Admin' }}</span>! 👋  
                    Selamat datang di <span class="font-semibold">Sistem Workshop UNAND</span>.  
                    Mari tingkatkan produktivitas dan semangat berbagi ilmu hari ini!
                </p>
            </div>
        </div>
    </div>

    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');
        $hour = Carbon::now()->format('H');
        if ($hour < 11) $greeting = 'Selamat Pagi';
        elseif ($hour < 15) $greeting = 'Selamat Siang';
        elseif ($hour < 18) $greeting = 'Selamat Sore';
        else $greeting = 'Selamat Malam';
    @endphp

    {{-- Greeting & Tanggal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col text-gray-700 text-sm font-semibold">
        {{ $greeting }} 👋  
        <span class="block text-gray-500 mt-1">
            <span id="hariTanggal">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span> — 
            <span id="jamSekarang">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
        </span>
    </div>

    {{-- Statistics Cards --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            {{-- Total Workshop --}}
            <a href="{{ route('admin.workshop.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Workshop</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['workshop_lagi_jalan'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Status</span>
                    <span class="text-sm ml-2 font-medium text-blue-600">Keseluruhan</span>
                </div>
            </a>

            {{-- Workshop Selesai --}}
            <a href="{{ route('admin.workshop.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Workshop Selesai</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['workshop_selesai'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Status</span>
                    <span class="text-sm ml-2 font-medium text-gray-600">Selesai</span>
                </div>
            </a>

            {{-- Workshop Nonaktif --}}
            <a href="{{ route('admin.workshop.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Workshop Nonaktif</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['workshop_batal'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Status</span>
                    <span class="text-sm ml-2 font-medium text-red-600">Nonaktif</span>
                </div>
            </a>

            {{-- Workshop Open Pendaftaran --}}
            <a href="{{ route('admin.workshop.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Open Pendaftaran</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['workshop_open_pendaftaran'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Status</span>
                    <span class="text-sm ml-2 font-medium text-green-600">Aktif</span>
                </div>
            </a>

        </div>

        {{-- Requests & Users --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">

            {{-- Total Request --}}
            <a href="{{ route('admin.request.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Request</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['total_request'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Status</span>
                    <span class="text-sm ml-2 font-medium text-yellow-600">Menunggu</span>
                </div>
            </a>

            {{-- Total Pengguna --}}
            <a href="{{ route('admin.account.manage') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Pengguna</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['total_user'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Pengguna Seluruh</span>
                    <span class="text-sm ml-2 font-medium text-[#057A55]">UNAND</span>
                </div>
            </a>

            {{-- Total Pemateri --}}
            <a href="{{ route('admin.account.manage') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Pemateri</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['total_pemateri'] }}</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500">Pemateri Dari</span>
                    <span class="text-sm ml-2 font-medium text-[#057A55]">UNAND</span>
                </div>
            </a>
          </div>
        
          <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mb-8">
        {{-- Kalender Workshop --}}
        @php
    use Illuminate\Support\Str;

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $workshopByDate = [];
    foreach ($calendarWorkshops as $w) {
        $date = Carbon::parse($w->tanggal)->format('Y-m-d');
        $workshopByDate[$date][] = $w;
    }

    $today = Carbon::today();
@endphp

<div class="bg-white shadow rounded-xl p-6">
    <h2 class="text-lg font-bold mb-4 text-[#000000]">Kalender Workshop Aktif Tahun {{ $year }}</h2>

    <div id="calendar-container">
        @foreach($months as $monthNumber => $monthName)
            @php
                $firstDay = Carbon::create($year, $monthNumber, 1);
                $daysInMonth = $firstDay->daysInMonth;
            @endphp

            <div class="month-calendar hidden" id="month-{{ $monthNumber }}">
                <h3 class="font-semibold text-center mb-4 text-[#068B4B] text-lg">{{ $monthName }}</h3>

                <div class="grid grid-cols-7 gap-1 text-center text-xs">
                    @foreach(['S', 'S', 'R', 'K', 'J', 'S', 'M'] as $d)
                        <div class="font-extrabold text-gray-600">{{ $d }}</div>
                    @endforeach

                    {{-- Spasi sebelum hari pertama --}}
                    @for ($i = 1; $i < $firstDay->dayOfWeekIso; $i++)
                        <div></div>
                    @endfor

                    {{-- Hari-hari --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = Carbon::create($year, $monthNumber, $day)->format('Y-m-d');
                            $eventsToday = $workshopByDate[$currentDate] ?? [];
                        @endphp

                        <div class="relative rounded min-h-[30px]
                            {{ $currentDate == $today->format('Y-m-d') ? 'bg-green-50' : 'bg-white' }}">
                            <div class="text-[11px] font-medium text-gray-700">{{ $day }}</div>

                            @foreach($eventsToday as $event)
                                <div class="text-[10px] mt-0.5 px-1 py-0.5 rounded bg-green-100 text-green-700 truncate" title="{{ $event->judul }}">
                                    {{ Str::limit($event->judul, 12) }}
                                </div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-between items-center mt-6 space-x-4">
        <button id="prevMonth" class="bg-gray-200 text-2xl px-1 rounded-2xl font-extrabold text-gray-700 hover:text-gray-900"> ← </button>
        <button id="nextMonth" class="bg-gray-200 text-2xl px-1 rounded-2xl font-extrabold text-gray-700 hover:text-gray-900"> → </button>
    </div>
</div>
      </div>
    </div>
</div>

<!-- Recent Workshops Table -->
<div class="bg-white rounded-lg shadow-md border m-8 border-gray-100 mt-8 overflow-hidden">
  <div class="flex justify-between items-center px-6 py-4">
    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
      Workshop Terbaru
    </h2>
    <a href="{{ route('admin.workshop.index') }}" class="text-sm text-[#068B4B] hover:underline">Lihat Semua</a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-700">
      <thead class="border-b text-gray-600 uppercase text-center text-xs">
        <tr>
          <th class="px-4 py-1">No.</th>
          <th class="px-6 py-3">Judul</th>
          <th class="px-6 py-3">Pemateri</th>
          <th class="px-6 py-3">Tempat</th>
          <th class="px-6 py-3">Tanggal</th>
          <th class="px-6 py-3">Waktu</th>
          <th class="px-6 py-3">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentWorkshops as $workshop)
          @php
              $statusColor = match($workshop->status_workshop) {
                  'aktif' => 'bg-green-100 text-green-700 border-green-300',
                  'penuh' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                  'selesai' => 'bg-blue-100 text-blue-700 border-blue-300',
                  'nonaktif' => 'bg-red-100 text-red-700 border-red-300',
                  default => 'bg-gray-100 text-gray-700 border-gray-300',
              };
          @endphp
          <tr class="hover:bg-gray-50 transition">
            <td class="text-center px-1 py-1">{{ $loop->iteration }}.</td>
            <td class="px-6 py-3 truncate">{{ $workshop->judul }}</td>
            <td class="text-center px-6 py-3 truncate">{{ $workshop->pemateri->nama ?? '-' }}</td>
            <td class="text-justify px-6 py-3 truncate">{{ $workshop->lokasi ?? '-' }}</td>
            <td class="text-center px-6 py-3 truncate">{{ \Carbon\Carbon::parse($workshop->tanggal)->format('d M Y') }}</td>
            <td class="text-center px-6 py-3">{{ $workshop->waktu ? \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i') . ' WIB' : '-' }}</td>
            <td class="text-center px-4 py-3">
              <span class="px-3 py-1 rounded-full text-xs border {{ $statusColor }}">
                {{ ucfirst($workshop->status_workshop ?? 'nonaktif') }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
              Belum ada workshop yang ditambahkan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
  <div class="bg-white shadow-md rounded-xl p-6 m-8">
      <h2 class="text-lg font-semibold mb-4 text-gray-800">
          Statistik Pelaksanaan Workshop per Bulan ({{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y') }})
      </h2>
      <img src="{{ $chartUrl }}" alt="Chart Statistik Workshop" class="w-full rounded-lg shadow">
  </div>

  <div class="bg-white shadow-md rounded-xl p-6 m-8">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">
        Perbandingan Workshop Aktif vs Nonaktif per Bulan
    </h2>
    <canvas id="statusChart" height="100"></canvas>
</div>

</div>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart');

    const labels = @json(array_values($labels));
    const selesaiData = @json(array_values($selesaiData));
    const aktifData = @json(array_values($aktifData));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Workshop selesai',
                    data: selesaiData,
                    borderColor: 'rgba(6, 139, 75, 1)',
                    backgroundColor: 'rgba(6, 139, 75, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Workshop aktif',
                    data: aktifData,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#ccc',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
        const months = @json(array_keys($months));
        let currentMonthIndex = new Date().getMonth(); // 0-based index
        const totalMonths = months.length;

        const showMonth = (index) => {
            document.querySelectorAll(".month-calendar").forEach(el => el.classList.add("hidden"));
            const monthElement = document.getElementById(`month-${months[index]}`);
            if (monthElement) {
                monthElement.classList.remove("hidden");
                document.getElementById("monthLabel").textContent = monthElement.querySelector("h3").textContent;
            }
        };

        document.getElementById("prevMonth").addEventListener("click", () => {
            currentMonthIndex = (currentMonthIndex - 1 + totalMonths) % totalMonths;
            showMonth(currentMonthIndex);
        });

        document.getElementById("nextMonth").addEventListener("click", () => {
            currentMonthIndex = (currentMonthIndex + 1) % totalMonths;
            showMonth(currentMonthIndex);
        });

        // Tampilkan bulan awal (bulan sekarang)
        showMonth(currentMonthIndex);
    });
</script>

<script src="{{ asset('js/script.js') }}"></script>
@endpush

@endsection
