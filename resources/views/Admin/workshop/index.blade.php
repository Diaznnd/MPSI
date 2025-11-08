@extends('Admin.Layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Manajemen Workshop</li>
                </ol>
            </nav>

            <!-- Title Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen Workshop</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola Workshop pada UPT Perpustakaan Universitas Andalas</p>
                </div>
            </div>
        </div>

    {{-- Add Workshop --}}
    <div class="mb-6">
      <a href="{{ route('admin.workshop.create') }}"
         class="bg-[#068B4B] hover:bg-[#08AA5C] text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Workshop
      </a>
    </div>

    {{-- Search Bar --}}
    <div class="rounded-lg p-3 mb-2">
      <form method="GET" action="{{ route('admin.workshop.index') }}" class="relative max-w-md ml-auto">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <input type="text" name="q" value="{{ request('q') }}"
               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
               placeholder="Cari Workshop Disini"
               id="searchWorkshop">
      </form>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="workshopGrid">
      @forelse($workshops as $workshop)
      <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl">
        {{-- Thumbnail --}}
        <div class="h-48 bg-gray-200">
          @php
            $img = $workshop->sampul_poster_url ?? null;
          @endphp
          @if($workshop['image'])
            <img src="{{ $workshop['image'] }}" alt="{{ $workshop['title'] }}" class="w-full h-48 object-cover rounded-t-xl">
          @else
            <img src="https://via.placeholder.com/600x360/e2e8f0/64748b?text=Workshop" class="w-full h-48 object-cover rounded-t-xl">
          @endif
        </div>
        {{-- Info --}}
        <div class="p-4 md:p-5">
          <h3 class="text-sm font-bold mb-2 line-clamp-1 text-gray-800">{{ $workshop['title'] }}</h3>

          <div class="flex items-center text-xs text-gray-600 mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>{{ $workshop['time'] }}</span>
          </div>

          <div class="flex items-center text-xs text-gray-600 mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $workshop['date'] }}</span>
          </div>

          <div class="flex items-center text-xs text-gray-600 mb-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ $workshop['location'] }}</span>
          </div>

          <div class="text-left">
            <a href="{{ route('admin.workshop.show', $workshop['id']) }}"
               class="mt-2 py-2 px-3 inline-flex justify-center items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-[#068B4B] text-white hover:bg-[#08AA5C]">
              Lihat selengkapnya →
            </a>
          </div>
        </div>
      </div>
      @empty
        <div class="col-span-4 text-center text-gray-500 py-10">Belum ada data workshop.</div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
      {{ $workshops->links() }}
    </div>

  </div>
</div>

<style>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

{{-- Client-side quick filter (opsional, tetap jalan tanpa reload) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('searchWorkshop');
  const grid  = document.getElementById('workshopGrid');

  if (!input || !grid) return;

  input.addEventListener('input', (e) => {
    const term = e.target.value.trim().toLowerCase();
    Array.from(grid.children).forEach(card => {
      const titleEl = card.querySelector('h3');
      const locEl   = card.querySelector('.flex.items-center.text-xs.text-gray-600.mb-3 span');
      const title   = (titleEl?.textContent || '').toLowerCase();
      const loc     = (locEl?.textContent || '').toLowerCase();
      card.style.display = (title.includes(term) || loc.includes(term)) ? '' : 'none';
    });
  });
});
</script>
@endsection
