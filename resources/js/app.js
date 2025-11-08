import './bootstrap';
import 'preline';


    function addMaterial() {
        const container = document.getElementById('materiContainer');
        const newMaterialField = document.createElement('div');
        newMaterialField.classList.add('flex', 'items-center', 'space-x-2', 'material-item');
        newMaterialField.innerHTML = `
            <input type="text" 
                   name="materials[]" 
                   class="flex-1 px-4 py-3 border border-yellow-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                   placeholder="Materi workshop">
            <button type="button" 
                    class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg"
                    onclick="removeMaterial(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(newMaterialField);
    }

    // Function to remove a material input field
    function removeMaterial(button) {
        const field = button.closest('.material-item');
        field.remove();
    }