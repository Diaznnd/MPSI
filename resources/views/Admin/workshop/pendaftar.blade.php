@extends('Admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb-like header -->
            <div class="flex items-center space-x-2 text-green-600 mb-4">
                <a href="{{ route('admin.workshop.index') }}" class="hover:text-green-700">Workshop</a>
                <span class="text-gray-400">></span>
                <a href="{{ route('admin.workshop.show', $workshop->workshop_id) }}" class="hover:text-green-700">{{ $workshop->judul }}</a>
                <span class="text-gray-400">></span>
                <span class="text-gray-600">Daftar Peserta</span>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <h1 class="text-2xl font-bold text-green-600">
                            Daftar Peserta - {{ $workshop->judul }}
                        </h1>
                    </div>
                    <div class="flex items-center space-x-2 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xl font-semibold">{{ count($participants) }}</span>
                    </div>
                </div>
                
                <!-- Search Box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           id="searchParticipants"
                           class="block w-80 pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500"
                           placeholder="Cari nama peserta workshop disini...">
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header with Green Background -->
            <div class="bg-green-100 px-6 py-4 border-b border-green-200">
                <div class="grid grid-cols-6 gap-4 text-sm font-semibold text-green-800">
                    <div>Nama Peserta</div>
                    <div>Email</div>
                    <div>Department</div>
                    <div>Fakultas</div>
                    <div>Judul Workshop</div>
                    <div>Tanggal Pendaftaran</div>
                </div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-200" id="participantsTableBody">
                @forelse($participants as $participant)
                <div class="px-6 py-4 participant-row">
                    <div class="grid grid-cols-6 gap-4 text-sm">
                        <!-- Nama Peserta -->
                        <div class="font-medium text-gray-900">
                            {{ $participant['name'] }}
                        </div>
                        
                        <!-- Email -->
                        <div class="text-gray-600 break-all">
                            {{ $participant['email'] }}
                        </div>
                        
                        <!-- Department -->
                        <div class="text-gray-600">
                            {{ $participant['department'] }}
                        </div>
                        
                        <!-- Fakultas -->
                        <div class="text-gray-600">
                            {{ $participant['faculty'] }}
                        </div>
                        
                        <!-- Judul Workshop -->
                        <div class="text-gray-600">
                            {{ $participant['workshop_title'] }}
                        </div>
                        
                        <!-- Tanggal Pendaftaran -->
                        <div class="text-gray-600">
                            {{ $participant['registration_date'] }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p>Belum ada peserta yang mendaftar untuk workshop ini.</p>
                </div>
                @endforelse
            </div>

            <!-- Empty State (for search) -->
            <div id="emptyState" class="px-6 py-12 text-center text-gray-500 hidden">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>Tidak ada peserta yang ditemukan</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('admin.workshop.show', $workshop->workshop_id) }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Detail Workshop
            </a>
            
            <div class="flex space-x-3">
                <!-- Export Button -->
                <button onclick="exportParticipants()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#068B4B] border border-[#068B4B] rounded-lg hover:bg-[#08AA5C]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
                
                <!-- Print Button -->
                <button onclick="printParticipants()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($participants) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Fakultas Terbanyak</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $topFaculty ?? 'Tidak ada data' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Department Terbanyak</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $topDepartment ?? 'Tidak ada data' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchParticipants').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const participantRows = document.querySelectorAll('.participant-row');
    const emptyState = document.getElementById('emptyState');
    let visibleCount = 0;
    
    participantRows.forEach(row => {
        const textElements = row.querySelectorAll('.text-gray-900, .text-gray-600');
        let found = false;
        
        textElements.forEach(el => {
            if (el.textContent.toLowerCase().includes(searchTerm)) {
                found = true;
            }
        });
        
        if (found || searchTerm === '') {
            row.style.display = 'block';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    if (visibleCount === 0 && searchTerm !== '') {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
});

// Export functionality
function exportParticipants() {
    // In real implementation, create Excel file
    const workshopId = {{ $workshop->workshop_id }};
    
    // For demo, show alert
    alert('Fitur Export Excel akan segera tersedia. Workshop ID: ' + workshopId);
    
    // Real implementation would be:
    // window.location.href = `/admin/workshops/${workshopId}/pendaftar/export`;
}

// Print functionality
function printParticipants() {
    const printContent = document.querySelector('.bg-white.rounded-lg.shadow-sm.border.border-gray-200').cloneNode(true);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Daftar Peserta - {{ $workshop->judul }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .bg-green-100 { background-color: #dcfce7; }
                .text-green-800 { color: #166534; }
                .grid { display: grid; }
                .grid-cols-6 { grid-template-columns: repeat(6, 1fr); }
                .gap-4 { gap: 1rem; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
                .font-semibold { font-weight: 600; }
                .font-medium { font-weight: 500; }
                .text-sm { font-size: 0.875rem; }
                .border-b { border-bottom: 1px solid #e5e7eb; }
                .divide-y > * + * { border-top: 1px solid #e5e7eb; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <h1>Daftar Peserta - {{ $workshop->judul }}</h1>
            <p>Total Peserta: {{ count($participants) }} orang</p>
            <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
            <hr>
            ${printContent.outerHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>
@endsection
