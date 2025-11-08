@extends('Admin.Layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
  <div class="bg-[#ffffff] shadow-sm border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    {{-- Judul dan breadcrumb --}}
    <div>
      <h1 class="text-3xl font-bold text-[#000000] mb-1">
        Dashboard
      </h1>
      <p class="mt-2 text-sm text-gray-600">Hai <span class="font-semibold text-[#068b4b]">{{ Auth::user()->nama ?? 'Admin' }}</span>! 👋  
    Selamat datang di <span class="font-semibold">Sistem Workshop UNAND</span>.  
    Mari tingkatkan produktivitas dan semangat berbagi ilmu hari ini!</p>
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

    <div class="ml-8 mt-4 text-gray-700 text-sm font-semibold">
      {{ $greeting }} 👋  
      <span class="block text-gray-500 mt-1">
        <span id="hariTanggal">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span> — 
        <span id="jamSekarang">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
      </span>
    </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Filters Section (UI saja; AJAX optional) --}}
    <div class="bg-[#ffffff] shadow rounded-lg p-6 mb-8 flex justify-between items-center">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-[#068b4b]">Filters</h3>
      </div>
      <form id="filterForm" class="flex flex-wrap gap-8">
        @csrf
        {{-- Date Filter (dummy, bisa diganti datepicker) --}}
        <div class="flex items-center bg-white rounded-lg px-4 py-2 border">
          <svg class="w-5 h-5 text-[#22C995] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
          </svg>
          <input name="start_date" type="date" class="border-none outline-none bg-transparent">
          <span class="mx-2 text-gray-400">—</span>
          <input name="end_date" type="date" class="border-none outline-none bg-transparent">
        </div>

        {{-- Department/Prodi Filter (mengarah ke users.prodi_fakultas) --}}
        <div class="flex items-center bg-white rounded-lg px-4 py-2 border">
          <svg class="w-5 h-5 text-[#22C995] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
            </path>
          </svg>
          <select name="department" class="border-none outline-none bg-transparent">
            <option value="">All Department</option>
            <option value="Kedokteran">Kedokteran</option>
            <option value="Pertanian">Pertanian</option>
            <option value="Sistem Informasi">Sistem Informasi</option>
            <option value="Teknik Sipil">Teknik Sipil</option>
            <option value="Farmasi">Farmasi</option>
          </select>
        </div>

        <button type="submit"
          class="bg-[#068b4b] text-white px-4 py-2 rounded-md hover:bg-[#057841]">Apply</button>
      </form>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <a href="{{ route('admin.workshop.index') }}" class="bg-[#ffffff] shadow rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer">
        <div class="text-center">
          <div class="text-5xl font-bold text-gray-900 mb-2">{{ $statistics['total_workshop'] }}</div>
          <div class="text-sm font-bold text-[#068b4b]">Total Workshop</div>
        </div>
      </a>

      <div class="bg-[#ffffff] shadow rounded-lg p-6">
        <div class="text-center">
          <div class="text-5xl font-bold text-gray-900 mb-2">{{ $statistics['total_peserta_terdaftar'] }}</div>
          <div class="text-sm font-bold text-[#068b4b]">Total Peserta Terdaftar</div>
        </div>
      </div>

      <a href="{{ route('admin.request.index') }}" class="bg-[#ffffff] shadow rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer">
        <div class="text-center">
          <div class="text-5xl font-bold text-gray-900 mb-2">{{ $statistics['total_request'] }}</div>
          <div class="text-sm font-bold text-[#068b4b]">Total Request</div>
        </div>
      </a>

      <a href="{{ route('admin.account.manage') }}" class="bg-[#ffffff] shadow rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer">
        <div class="text-center">
          <div class="text-5xl font-bold text-gray-900 mb-2">{{ $statistics['total_user'] }}</div>
          <div class="text-sm font-bold text-[#068b4b]">Total User</div>
        </div>
      </a>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Trend Chart (kanvas sederhana) --}}
      <div class="bg-[#ffffff] shadow rounded-3xl py-6">
        <div class="bg-[#ffffff] shadow inline-block px-3 py-1 rounded-sm">
          <h3 class="font-medium text-[#068b4b] pr-9">
            Tren Pendaftaran Workshop (7 bulan)
          </h3>
        </div>
        <div class="h-64 flex items-center justify-center p-4">
          <canvas id="trendChart" width="520" height="240"></canvas>
        </div>
      </div>

      {{-- Department Bar "Terbanyak Mengikuti" --}}
      <div class="bg-[#ffffff] shadow rounded-3xl py-6">
        <div class="bg-[#ffffff] shadow inline-block px-3 py-1 rounded-sm">
          <h3 class="font-medium text-[#068b4b] pr-9">
            Department Terbanyak Mengikuti Workshop
          </h3>
        </div>

        <div id="deptBars" class="space-y-4 p-6">
          @forelse ($departmentData as $row)
            @php
              $pct = round(($row['count'] / $departmentMax) * 100);
            @endphp
            <div class="flex items-center justify-between">
              <span class="text-sm text-[#068b4b] w-40 truncate">{{ $row['name'] }}</span>
              <div class="flex-1 mx-4">
                <div class="bg-gray-300 rounded-full h-6 relative overflow-hidden">
                  <div class="bg-gray-700 h-6 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
              </div>
              <span class="text-sm font-medium text-gray-900 w-10 text-right">{{ $row['count'] }}</span>
            </div>
          @empty
            <div class="text-gray-500 text-sm px-6">Belum ada data pendaftaran.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>
</div>

{{-- Script: gambar garis sederhana tanpa library, data dari server --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const labels = @json($trendData['labels']);
  const values = @json($trendData['data']);

  const canvas = document.getElementById('trendChart');
  const ctx = canvas.getContext('2d');

  // padding & scaling sederhana
  const pad = 30;
  const W = canvas.width, H = canvas.height;
  const maxVal = Math.max(...values, 1);
  const stepX = (W - 2*pad) / Math.max(values.length - 1, 1);

  // background
  ctx.clearRect(0, 0, W, H);

  // axis
  ctx.strokeStyle = '#9CA3AF';
  ctx.lineWidth = 1;
  ctx.beginPath();
  ctx.moveTo(pad, pad);
  ctx.lineTo(pad, H - pad);
  ctx.lineTo(W - pad, H - pad);
  ctx.stroke();

  // polyline
  ctx.strokeStyle = '#374151';
  ctx.lineWidth = 2;
  ctx.beginPath();
  values.forEach((v, i) => {
    const x = pad + i * stepX;
    const y = H - pad - ((v / maxVal) * (H - 2*pad));
    i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
  });
  ctx.stroke();

  // simple labels (optional)
  ctx.fillStyle = '#374151';
  ctx.font = '10px sans-serif';
  labels.forEach((lb, i) => {
    const x = pad + i * stepX;
    ctx.fillText(lb, x - 12, H - pad + 12);
  });

  // === Optional AJAX filter ===
  const form = document.getElementById('filterForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(form);
      const res = await fetch("{{ route('admin.filterData') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
        body: fd
      });
      const json = await res.json();

      // update trend
      const vals = json.trend.data;
      const maxV = Math.max(...vals, 1);
      ctx.clearRect(0, 0, W, H);
      ctx.strokeStyle = '#9CA3AF';
      ctx.lineWidth = 1;
      ctx.beginPath(); ctx.moveTo(pad, pad); ctx.lineTo(pad, H - pad); ctx.lineTo(W - pad, H - pad); ctx.stroke();
      ctx.strokeStyle = '#374151'; ctx.lineWidth = 2; ctx.beginPath();
      vals.forEach((v, i) => {
        const x = pad + i * stepX;
        const y = H - pad - ((v / maxV) * (H - 2*pad));
        i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
      });
      ctx.stroke();

      // update dept bars
      const wrap = document.getElementById('deptBars');
      wrap.innerHTML = '';
      const maxDept = json.departments.max || 1;
      json.departments.data.forEach(row => {
        const pct = Math.round((row.count / maxDept) * 100);
        wrap.insertAdjacentHTML('beforeend', `
          <div class="flex items-center justify-between">
            <span class="text-sm text-[#068b4b] w-40 truncate">${row.name ?? '-'}</span>
            <div class="flex-1 mx-4">
              <div class="bg-gray-300 rounded-full h-6 relative overflow-hidden">
                <div class="bg-gray-700 h-6 rounded-full" style="width:${pct}%"></div>
              </div>
            </div>
            <span class="text-sm font-medium text-gray-900 w-10 text-right">${row.count}</span>
          </div>
        `);
      });
    });
  }
});
</script>

@push('scripts') 
    <!-- Push your external JavaScript file here -->
    <script src="{{ asset('js/script.js') }}"></script>
@endpush
@endsection
