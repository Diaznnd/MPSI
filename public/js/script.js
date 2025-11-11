// JavaScript to manage added keywords dynamically
let keywords = [];

// Initialize keywords from existing data (for edit form)
document.addEventListener('DOMContentLoaded', function() {
    const addedKeywordsContainer = document.getElementById('addedKeywords');
    if (addedKeywordsContainer) {
        const existingKeywords = addedKeywordsContainer.querySelectorAll('.keyword-item span');
        existingKeywords.forEach(element => {
            const keyword = element.textContent.trim();
            if (keyword && !keywords.includes(keyword)) {
                keywords.push(keyword);
            }
        });
    }
});

// Function to add a new keyword
function addKeyword() {
    const keywordInput = document.getElementById('keywordInput');
    if (!keywordInput) return;
    
    const keyword = keywordInput.value.trim();  // Get the keyword

    if (keyword !== '' && !keywords.includes(keyword)) {
        keywords.push(keyword);  // Add to the temporary array

        // Create a new div element for the new keyword
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-3', 'keyword-item');
        div.innerHTML = `
            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg">${keyword}</span>
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeKeyword('${keyword}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        // Append the keyword element to the "addedKeywords" container
        const addedKeywords = document.getElementById('addedKeywords');
        if (addedKeywords) {
            addedKeywords.appendChild(div);
        }

        // Clear the input field after adding the keyword
        keywordInput.value = '';
    }
}

// Function to remove a keyword
function removeKeyword(keyword) {
    // Remove the keyword from the temporary array
    keywords = keywords.filter(k => k !== keyword);

    // Remove the keyword element from the display
    const keywordElements = document.querySelectorAll('#addedKeywords span');
    keywordElements.forEach((element) => {
        if (element.textContent.trim() === keyword) {
            element.closest('div').remove();
        }
    });
}

// Function to handle form submission and add keywords to the form
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Remove existing hidden keyword inputs first (if any)
            const existingHiddenInputs = form.querySelectorAll('input[name="keywords[]"][type="hidden"]');
            existingHiddenInputs.forEach(input => input.remove());
            
            // Get the first input field value if it's not empty
            const keywordInput = document.getElementById('keywordInput');
            if (keywordInput && keywordInput.value.trim() !== '') {
                const inputValue = keywordInput.value.trim();
                if (!keywords.includes(inputValue)) {
                    keywords.push(inputValue);
                }
            }
            
            // Remove the first input field from form submission (we'll use hidden inputs instead)
            if (keywordInput) {
                keywordInput.removeAttribute('name');
            }
            
            // Add all keywords to the form as hidden inputs
            keywords.forEach(keyword => {
                const keywordField = document.createElement('input');
                keywordField.setAttribute('type', 'hidden');
                keywordField.setAttribute('name', 'keywords[]');
                keywordField.setAttribute('value', keyword);
                form.appendChild(keywordField);
            });
        });
    });
});

// Drag and Drop functions
function handleDragOver(e) {
    e.preventDefault();
}

function handleDrop(e) {
    e.preventDefault();
    const dt = e.dataTransfer;
    const files = dt.files;
    const input = document.getElementById('sampul_poster_url');
    if (files && files.length) {
        input.files = files; // Assign the dropped files to the input
        handleFileChange({ target: input }); // Trigger the onchange event
    }
}

function handleFileChange(e) {
    const file = e.target.files && e.target.files[0];
    const previewImage = document.getElementById('previewImage');
    
    if (!file) return;

    // Validasi format gambar
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        alert('Format gambar tidak didukung.');
        e.target.value = ''; // Reset input
        if (previewImage) previewImage.style.display = 'none'; // Hide preview image
        return;
    }

    // Membaca file untuk preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (previewImage) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block'; // Menampilkan gambar
        }
    };
    reader.readAsDataURL(file);
}

// Function to handle file select (for edit form)
function handleFileSelect(input) {
    const fileName = document.getElementById('fileName');
    if (!input || !fileName) return;
    
    if (input.files.length > 0) {
        fileName.textContent = input.files[0].name;
        
        // Hide current/default image preview when new file selected
        const currentPreview = document.getElementById('currentImagePreview');
        const defaultPreview = document.getElementById('defaultImagePreview');
        if (currentPreview) currentPreview.style.display = 'none';
        if (defaultPreview) defaultPreview.style.display = 'none';
    } else {
        fileName.textContent = 'No file chosen';
        
        // Show previews again if no file selected
        const currentPreview = document.getElementById('currentImagePreview');
        const defaultPreview = document.getElementById('defaultImagePreview');
        if (currentPreview) currentPreview.style.display = 'block';
        if (defaultPreview) defaultPreview.style.display = 'block';
    }
}

// Function to remove current image (for edit form)
function removeCurrentImage() {
    const removeImageFlag = document.getElementById('removeImageFlag');
    const currentPreview = document.getElementById('currentImagePreview');
    const fileName = document.getElementById('fileName');
    
    if (removeImageFlag) {
        removeImageFlag.value = '1';
    }
    if (currentPreview) {
        currentPreview.style.display = 'none';
    }
    if (fileName) {
        fileName.textContent = 'Current image will be removed';
    }
}

// Form validation for edit form
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editWorkshopForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const judul = document.getElementById('judul');
            const deskripsi = document.getElementById('deskripsi');
            const lokasi = document.getElementById('lokasi');
            const tanggal = document.getElementById('tanggal');
            const kuota = document.getElementById('kuota');
            
            if (!judul || !deskripsi || !lokasi || !tanggal || !kuota) {
                return true; // Let server-side validation handle it
            }
            
            const judulValue = judul.value.trim();
            const deskripsiValue = deskripsi.value.trim();
            const lokasiValue = lokasi.value.trim();
            const tanggalValue = tanggal.value;
            const kuotaValue = kuota.value;
            
            if (!judulValue || !deskripsiValue || !lokasiValue || !tanggalValue || !kuotaValue) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return false;
            }
            
            if (parseInt(kuotaValue) < 1) {
                e.preventDefault();
                alert('Kuota peserta minimal 1 orang.');
                return false;
            }
            
            // Check if date is not in the past
            const selectedDate = new Date(tanggalValue);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                e.preventDefault();
                alert('Tanggal workshop tidak boleh di masa lalu.');
                return false;
            }
            
            return true;
        });
    }
});


document.addEventListener("DOMContentLoaded", function() {
  function updateClock() {
    const now = new Date();
    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    const jam = now.toLocaleTimeString('id-ID', { hour12: false });

    document.getElementById('hariTanggal').textContent = `${hari}, ${tanggal}`;
    document.getElementById('jamSekarang').textContent = jam;
  }

  updateClock(); // tampilkan segera
  setInterval(updateClock, 1000); // perbarui tiap detik
});


