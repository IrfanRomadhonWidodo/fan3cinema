{{-- resources/views/livewire/genre-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Genre</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola data genre film dengan mudah</p>
    </div>

    {{-- Alert Container for Dynamic Messages --}}
    <div id="alert-container" class="mb-4"></div>

    {{-- Action Bar --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        {{-- Search and Filter --}}
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari nama genre..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
                <option value="5">5 per halaman</option>
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="flex space-x-2">
            <button 
                wire:click="refresh"
                type="button"
                class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-800 dark:hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button 
                wire:click="create"
                type="button"
                class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Genre
            </button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Film</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($genres as $genre)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($genres->currentPage() - 1) * $genres->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $genre->nama_genre }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $genre->films_count > 0 
                                        ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300' 
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                                    {{ $genre->films_count }} film
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $genre->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="edit({{ $genre->id }})"
                                    type="button"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition duration-200"
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $genre->id }})"
                                    type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition duration-200 
                                        {{ $genre->films_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $genre->films_count > 0 ? 'disabled' : '' }}
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM9 3v1h6V3H9zm0 4v10h6V7H9z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data genre</p>
                                    <p class="text-sm">Mulai dengan menambahkan genre baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($genres->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $genres->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div 
            class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-opacity duration-200" 
            wire:click="closeModal"
            wire:keydown.escape="closeModal"
        >
            <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-800 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transition-all duration-200" wire:click.stop>
                <div class="mt-3">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM9 3v1h6V3H9zm0 4v10h6V7H9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 text-center">
                        {{ $isEditing ? 'Edit Genre' : 'Tambah Genre Baru' }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="mb-6">
                            <label for="nama_genre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Genre
                            </label>
                            <input 
                                type="text" 
                                id="nama_genre"
                                wire:model.defer="nama_genre"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('nama_genre') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Masukkan nama genre (misal: Action, Drama, Comedy)"
                                autocomplete="off"
                            >
                            @error('nama_genre')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-200"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-purple-600 dark:bg-purple-500 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition duration-200"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                            >
                                <span wire:loading.remove>{{ $isEditing ? 'Update' : 'Simpan' }}</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingDelete)
        <div 
            class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-opacity duration-200" 
            wire:click="closeModal"
            wire:keydown.escape="closeModal"
        >
            <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-800 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transition-all duration-200" wire:click.stop>
                <div class="mt-3">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 text-center">
                        Konfirmasi Hapus Genre
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6">
                        Apakah Anda yakin ingin menghapus genre ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    
                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 dark:bg-red-500 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 transition duration-200"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <span wire:loading.remove>Ya, Hapus</span>
                            <span wire:loading>Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript untuk Enhanced UX --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alert system
    window.addEventListener('show-alert', function(event) {
        showAlert(event.detail.type, event.detail.message);
    });

    // Modal management
    window.addEventListener('modal-opened', function(event) {
        document.body.style.overflow = 'hidden';
        const modal = document.querySelector('[wire\\:click="closeModal"]');
        if (modal) {
            modal.focus();
        }
    });

    window.addEventListener('modal-closed', function(event) {
        document.body.style.overflow = 'auto';
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        // ESC key untuk close modal
        if (e.key === 'Escape') {
            const isModalOpen = document.querySelector('.fixed.inset-0.bg-black\\/30');
            if (isModalOpen) {
                @this.call('closeModal');
            }
        }
        
        // Ctrl+N untuk tambah genre baru
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            @this.call('create');
        }
        
        // Ctrl+R untuk refresh
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            @this.call('refresh');
        }
    });

    // Search input focus dengan keyboard shortcut
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[wire\\:model\\.live\\.debounce\\.500ms="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
    });

    // Auto-focus untuk modal input
    document.addEventListener('livewire:navigated', function() {
        const modalInput = document.querySelector('#nama_genre');
        if (modalInput) {
            setTimeout(() => modalInput.focus(), 100);
        }
    });
});

// Function untuk menampilkan alert
function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;

    const alertId = 'alert-' + Date.now();
    const iconPaths = {
        success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        error: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
        info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    };

    const colors = {
        success: 'bg-green-100 dark:bg-green-900 border-green-500 text-green-700 dark:text-green-300',
        error: 'bg-red-100 dark:bg-red-900 border-red-500 text-red-700 dark:text-red-300',
        warning: 'bg-yellow-100 dark:bg-yellow-900 border-yellow-500 text-yellow-700 dark:text-yellow-300',
        info: 'bg-blue-100 dark:bg-blue-900 border-blue-500 text-blue-700 dark:text-blue-300'
    };

    const alertHTML = `
        <div id="${alertId}" class="flex items-center p-4 mb-4 border-l-4 rounded-r-md ${colors[type]} transition-all duration-300 transform translate-x-0 opacity-100">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPaths[type]}"></path>
            </svg>
            <span class="text-sm font-medium flex-1">${message}</span>
            <button onclick="dismissAlert('${alertId}')" class="ml-3 text-current hover:opacity-70 transition-opacity duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    alertContainer.insertAdjacentHTML('beforeend', alertHTML);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        dismissAlert(alertId);
    }, 5000);
}

// Function untuk dismiss alert
function dismissAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Loading state management
document.addEventListener('livewire:init', function() {
    Livewire.on('show-loading', function() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loading-overlay';
        loadingOverlay.className = 'fixed inset-0 bg-black/20 dark:bg-black/40 backdrop-blur-sm flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-600 dark:border-purple-400"></div>
                    <span class="text-gray-700 dark:text-gray-300">Memproses...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    });

    Livewire.on('hide-loading', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    });
});

// Enhanced pagination dengan keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.target.tagName.toLowerCase() === 'input') return;
    
    // Arrow keys untuk navigasi pagination
    if (e.key === 'ArrowLeft' && e.altKey) {
        e.preventDefault();
        const prevButton = document.querySelector('a[rel="prev"]');
        if (prevButton && !prevButton.classList.contains('cursor-not-allowed')) {
            prevButton.click();
        }
    }
    
    if (e.key === 'ArrowRight' && e.altKey) {
        e.preventDefault();
        const nextButton = document.querySelector('a[rel="next"]');
        if (nextButton && !nextButton.classList.contains('cursor-not-allowed')) {
            nextButton.click();
        }
    }
});

// Performance optimization - Lazy loading untuk gambar jika ada
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Accessibility improvements
document.addEventListener('DOMContentLoaded', function() {
    // ARIA labels untuk interactive elements
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        if (!button.getAttribute('aria-label') && button.title) {
            button.setAttribute('aria-label', button.title);
        }
    });

    // Focus management untuk modals
    let lastFocusedElement = null;
    
    document.addEventListener('focusin', function(e) {
        const isModalOpen = document.querySelector('.fixed.inset-0.bg-black\\/30');
        if (!isModalOpen) {
            lastFocusedElement = e.target;
        }
    });

    // Trap focus dalam modal
    document.addEventListener('keydown', function(e) {
        const modal = document.querySelector('.fixed.inset-0.bg-black\\/30 .relative');
        if (!modal) return;

        if (e.key === 'Tab') {
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });

    // Restore focus ketika modal ditutup
    window.addEventListener('modal-closed', function() {
        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
    });
});
</script>

