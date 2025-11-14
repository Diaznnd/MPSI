@extends('User.Layout.app')

@section('title', 'Forum Diskusi - ' . $workshop->judul)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('pengguna.dashboard') }}" class="hover:text-gray-900">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li><a href="{{ route('pengguna.daftar-workshop') }}" class="hover:text-gray-900">Daftar Workshop</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Forum Diskusi</li>
                </ol>
            </nav>
        </div>

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2"></div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-md flex items-center space-x-3 animate-fade-in" role="alert">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-md flex items-center space-x-3 animate-fade-in" role="alert">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="block sm:inline font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Workshop Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $workshop->judul }}</h1>
                    <p class="text-sm text-gray-600">Pemateri: {{ $workshop->pemateri->nama ?? 'Tidak diketahui' }}</p>
                    <p class="text-sm text-gray-600">
                        @if($workshop->tanggal)
                            Tanggal: {{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('d F Y') }}
                        @endif
                        @if($workshop->waktu)
                            | Waktu: {{ \Carbon\Carbon::parse($workshop->waktu)->setTimezone('Asia/Jakarta')->format('H.i') }} WIB
                        @endif
                    </p>
                </div>
                <a href="{{ route('pengguna.daftar-workshop') }}" 
                   class="ml-4 text-[#057A55] hover:text-[#016545] font-medium text-sm flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Forum Diskusi Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-[#057A55] rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Forum Diskusi</h2>
            </div>

            <!-- Chat Area -->
            <div id="chat-container" class="mb-6 space-y-4 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded-lg">
                @forelse($diskusi as $item)
                    <div class="flex items-start space-x-3 {{ $item->user_id == Auth::id() ? 'flex-row-reverse space-x-reverse' : '' }}" data-discussion-id="{{ $item->discussion_id }}">
                        <!-- User Avatar -->
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-[#057A55] flex items-center justify-center flex-shrink-0 border-2 border-[#057A55]">
                            @if($item->user->foto_profil_url)
                                <img src="{{ asset('storage/' . $item->user->foto_profil_url) }}" alt="{{ $item->user->nama }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white text-sm font-bold">
                                    {{ strtoupper(substr($item->user->nama, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Message -->
                        <div class="flex-1 {{ $item->user_id == Auth::id() ? 'items-end' : 'items-start' }} flex flex-col">
                            <div class="flex items-center space-x-2 mb-1 {{ $item->user_id == Auth::id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <span class="text-sm font-semibold {{ $item->user_id == Auth::id() ? 'text-[#057A55]' : 'text-gray-900' }}">{{ $item->user->nama }}</span>
                                <span class="text-xs {{ $item->user_id == Auth::id() ? 'text-[#057A55]' : 'text-gray-500' }}">{{ $item->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($item->user_id == Auth::id())
                                <!-- Own message - Green border with black text -->
                                <div class="bg-white border-2 border-[#057A55] rounded-lg p-3 shadow-sm max-w-2xl">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap" id="message-{{ $item->discussion_id }}">{{ $item->message }}</p>
                                </div>
                            @else
                                <!-- Other's message - White background with dark text -->
                                <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm max-w-2xl">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap" id="message-{{ $item->discussion_id }}">{{ $item->message }}</p>
                                </div>
                            @endif
                            
                            <!-- Action Buttons (only for own messages) -->
                            @if(Auth::check() && $item->user_id == Auth::id())
                                <div class="flex items-center space-x-2 mt-2 justify-end">
                                    <button onclick="editMessage({{ $item->discussion_id }}, event)" 
                                            data-message="{{ htmlspecialchars($item->message, ENT_QUOTES, 'UTF-8') }}"
                                            class="text-xs text-[#057A55] hover:text-[#016545] font-medium flex items-center space-x-1 px-2 py-1 rounded hover:bg-green-50 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <button onclick="deleteMessage({{ $item->discussion_id }})" 
                                            class="text-xs text-red-600 hover:text-red-700 font-medium flex items-center space-x-1 px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada diskusi. Mulai diskusi pertama!</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Form -->
            <form action="{{ route('pengguna.forum.store', $workshop->workshop_id) }}" method="POST" id="chat-form">
                @csrf
                <div class="flex space-x-3">
                    <div class="flex-1">
                        @error('message')
                            <textarea 
                                id="message-input"
                                name="message" 
                                rows="3"
                                required
                                placeholder="Tulis pesan diskusi Anda..."
                                class="w-full px-4 py-3 rounded-lg border-2 border-red-500 text-red-900 placeholder-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none">{{ old('message') }}</textarea>
                            <p class="mt-1 text-sm text-red-600 font-medium flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <textarea 
                                id="message-input"
                                name="message" 
                                rows="3"
                                required
                                placeholder="Tulis pesan diskusi Anda..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#057A55] focus:border-[#057A55] resize-none">{{ old('message') }}</textarea>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="bg-white border-2 border-[#057A55] text-[#057A55] hover:bg-[#057A55] hover:text-white font-semibold px-6 py-3 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Kirim</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Message Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Pesan</h3>
            <form id="edit-form">
                @csrf
                @method('PUT')
                <textarea 
                    id="edit-message-input"
                    name="message" 
                    rows="4"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#057A55] focus:border-[#057A55] resize-none"></textarea>
                <div class="flex space-x-3 mt-4">
                    <button type="submit" 
                            class="flex-1 bg-white border-2 border-[#057A55] text-[#057A55] hover:bg-[#057A55] hover:text-white font-medium py-2 px-4 rounded-lg transition-all">
                        Simpan
                    </button>
                    <button type="button" 
                            onclick="closeEditModal()"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

.animate-slide-out {
    animation: slideOut 0.3s ease-in;
}
</style>

<script>
let currentEditId = null;

// Toast notification function
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const bgColor = type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500';
    const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
    const iconColor = type === 'success' ? 'text-green-500' : 'text-red-500';
    const icon = type === 'success' 
        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `${bgColor} border-l-4 ${textColor} px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md animate-slide-in`;
    toast.innerHTML = `
        <svg class="w-5 h-5 ${iconColor} flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            ${icon}
        </svg>
        <span class="flex-1 font-medium">${message}</span>
        <button onclick="closeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        closeToast(toastId);
    }, 5000);
}

function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.remove('animate-slide-in');
        toast.classList.add('animate-slide-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

function editMessage(discussionId, event) {
    currentEditId = discussionId;
    // Get message from button data attribute
    const button = (event || window.event).target.closest('button[data-message]');
    const message = button ? button.getAttribute('data-message') : '';
    document.getElementById('edit-message-input').value = message;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    currentEditId = null;
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('edit-message-input').value = '';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
        closeEditModal();
    }
});

function deleteMessage(discussionId) {
    if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
        return;
    }

    const workshopId = {{ $workshop->workshop_id }};
    fetch(`/pengguna/workshop/${workshopId}/forum/${discussionId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove message from DOM
            const messageElement = document.querySelector(`[data-discussion-id="${discussionId}"]`);
            if (messageElement) {
                messageElement.style.transition = 'opacity 0.3s';
                messageElement.style.opacity = '0';
                setTimeout(() => {
                    messageElement.remove();
                    // Reload page if no messages left
                    if (document.querySelectorAll('[data-discussion-id]').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            // Show success toast
            showToast('Pesan berhasil dihapus.', 'success');
        } else {
            showToast('Gagal menghapus pesan: ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat menghapus pesan.', 'error');
    });
}

// Handle edit form submit
document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentEditId) {
        return;
    }

    const message = document.getElementById('edit-message-input').value.trim();
    if (!message) {
        showToast('Pesan tidak boleh kosong.', 'error');
        return;
    }

    const workshopId = {{ $workshop->workshop_id }};
    fetch(`/pengguna/workshop/${workshopId}/forum/${currentEditId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message: message
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update message in DOM
            const messageElement = document.getElementById(`message-${currentEditId}`);
            if (messageElement) {
                messageElement.textContent = data.data.message;
            }
            
            // Close modal
            closeEditModal();
            
            // Show success toast
            showToast('Pesan berhasil diperbarui.', 'success');
        } else {
            if (data.errors) {
                let errorMsg = '';
                for (let field in data.errors) {
                    errorMsg += data.errors[field].join(', ') + ' ';
                }
                showToast(errorMsg || 'Terjadi kesalahan saat memperbarui pesan.', 'error');
            } else {
                showToast('Gagal memperbarui pesan: ' + (data.message || 'Terjadi kesalahan'), 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memperbarui pesan.', 'error');
    });
});

// Auto scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
</script>
@endsection

