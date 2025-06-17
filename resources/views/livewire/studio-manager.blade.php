{{-- resources/views/livewire/studio-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Studio</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola data studio dengan mudah</p>
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
                    placeholder="Cari nama studio..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
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
                class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Studio
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kapasitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($studios as $studio)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($studios->currentPage() - 1) * $studios->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $studio->nama_studio }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-300">
                                    {{ $studio->kapasitas }} orang
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $studio->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="edit({{ $studio->id }})"
                                    type="button"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition duration-200"
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $studio->id }})"
                                    type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition duration-200"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data studio</p>
                                    <p class="text-sm">Mulai dengan menambahkan studio baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($studios->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $studios->links() }}
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        {{ $isEditing ? 'Edit Studio' : 'Tambah Studio Baru' }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="nama_studio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Studio
                            </label>
                            <input 
                                type="text" 
                                id="nama_studio"
                                wire:model.defer="nama_studio"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('nama_studio') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Masukkan nama studio"
                            >
                            @error('nama_studio')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="kapasitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kapasitas
                            </label>
                            <input 
                                type="number" 
                                id="kapasitas"
                                wire:model.defer="kapasitas"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('kapasitas') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Masukkan kapasitas studio"
                                min="1"
                            >
                            @error('kapasitas')
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
                                class="px-4 py-2 bg-emerald-600 dark:bg-emerald-500 text-white rounded-md hover:bg-emerald-700 dark:hover:bg-emerald-600 transition duration-200"
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
            class="fixed inset-0 bg-black/30 backdrop-blur-sm dark:bg-black/40 overflow-y-auto h-full w-full z-50 transition-opacity duration-200"
            wire:click="closeModal"
            wire:keydown.escape="closeModal"
        >
            <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-800 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transition-all duration-200" wire:click.stop>
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Apakah Anda yakin ingin menghapus studio ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <button 
                            wire:click="closeModal"
                            type="button"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            wire:click="delete"
                            type="button"
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

    {{-- Loading Overlay --}}
    <div wire:loading.delay.longer class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500 dark:border-emerald-400"></div>
                <span class="text-gray-700 dark:text-gray-300">Sedang memproses...</span>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk handle events dan keyboard shortcuts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for Livewire events
            window.addEventListener('show-alert', event => {
                showAlert(event.detail.type, event.detail.message);
            });

            // Function to show dynamic alerts
            function showAlert(type, message) {
                const alertContainer = document.getElementById('alert-container');
                const alertClass = {
                    'success': 'bg-emerald-100 dark:bg-emerald-900 border-emerald-400 dark:border-emerald-600 text-emerald-700 dark:text-emerald-300',
                    'error': 'bg-red-100 dark:bg-red-900 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300',
                    'info': 'bg-purple-100 dark:bg-purple-900 border-purple-400 dark:border-purple-600 text-purple-700 dark:text-purple-300'
                };

                const alertHTML = `
                    <div class="${alertClass[type]} border px-4 py-3 rounded mb-4 transition-all duration-300 transform" role="alert">
                        <div class="flex justify-between items-center">
                            <span class="block sm:inline">${message}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 font-bold text-lg hover:opacity-75">&times;</button>
                        </div>
                    </div>
                `;

                alertContainer.innerHTML = alertHTML;

                // Auto remove after 5 seconds
                setTimeout(() => {
                    const alert = alertContainer.firstElementChild;
                    if (alert) {
                        alert.classList.add('opacity-0', 'transform', '-translate-y-2');
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 5000);
            }

            // Handle keyboard shortcuts globally
            document.addEventListener('keydown', function(e) {
                // ESC key to close modals
                if (e.key === 'Escape') {
                    @this.closeModal();
                }
                
                // Ctrl+N or Cmd+N to create new studio
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    @this.create();
                }
            });

            // Prevent form submission on Enter key in modal inputs
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                    e.preventDefault();
                }
            });
        });
    </script>
</div>