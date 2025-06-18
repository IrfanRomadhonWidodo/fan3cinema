{{-- resources/views/livewire/user-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen User</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola data pengguna dengan mudah</p>
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
                    placeholder="Cari nama atau email..." 
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
                Tambah User
            </button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-gray-900 to-purple-600 text-white font-semibold text-sm">
                                    {{ $user->initials() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-300">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="edit({{ $user->id }})"
                                    type="button"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition duration-200"
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                @if(auth()->id() !== $user->id)
                                    <button 
                                        wire:click="confirmDelete({{ $user->id }})"
                                        type="button"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition duration-200"
                                    >
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data user</p>
                                    <p class="text-sm">Mulai dengan menambahkan user baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $users->links() }}
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
                        {{ $isEditing ? 'Edit User' : 'Tambah User Baru' }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model.defer="name"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('name') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                wire:model.defer="email"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('email') border-red-500 dark:border-red-500 @enderror"
                                placeholder="user@example.com"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password {{ $isEditing ? '(kosongkan jika tidak ingin mengubah)' : '' }}
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                wire:model.defer="password"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('password') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Minimal 8 karakter"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konfirmasi Password
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                wire:model.defer="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200 @error('password_confirmation') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Ulangi password"
                            >
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-200"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white text-sm font-medium rounded-md transition duration-200 flex items-center"
                                wire:loading.attr="disabled"
                            >
                                <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove>{{ $isEditing ? 'Update' : 'Simpan' }}</span>
                                <span wire:loading>Processing...</span>
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
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Konfirmasi Hapus
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex items-center justify-center space-x-3">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-sm font-medium rounded-md transition duration-200 flex items-center"
                            wire:loading.attr="disabled"
                        >
                            <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove>Ya, Hapus</span>
                            <span wire:loading>Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript untuk Alert System --}}
<script>
    // Alert System
    document.addEventListener('livewire:init', function () {
        Livewire.on('show-alert', function (data) {
            showAlert(data.type, data.message);
        });
    });

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertId = 'alert-' + Date.now();
        
        const alertColors = {
            success: 'bg-green-100 dark:bg-green-900 border-green-500 text-green-700 dark:text-green-300',
            error: 'bg-red-100 dark:bg-red-900 border-red-500 text-red-700 dark:text-red-300',
            warning: 'bg-yellow-100 dark:bg-yellow-900 border-yellow-500 text-yellow-700 dark:text-yellow-300',
            info: 'bg-purple-100 dark:bg-purple-900 border-purple-500 text-purple-700 dark:text-purple-300'
        };

        const alertIcons = {
            success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                      </svg>`,
            error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                   </svg>`,
            warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                       <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                     </svg>`,
            info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                  </svg>`
        };

        const alertHtml = `
            <div id="${alertId}" class="alert-item border-l-4 p-4 mb-4 rounded-md transition-all duration-300 ${alertColors[type]} transform translate-x-0">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${alertIcons[type]}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button onclick="removeAlert('${alertId}')" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 hover:opacity-75 transition-opacity duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        alertContainer.insertAdjacentHTML('beforeend', alertHtml);

        // Auto remove after 5 seconds
        setTimeout(() => {
            removeAlert(alertId);
        }, 5000);
    }

    function removeAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.transform = 'translateX(-100%)';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }

    // Handle modal focus management
    document.addEventListener('livewire:init', function () {
        Livewire.on('modal-opened', function (data) {
            // Focus management for accessibility
            setTimeout(() => {
                const firstInput = document.querySelector('input[type="text"], input[type="email"]');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 100);
        });

        Livewire.on('modal-closed', function () {
            // Return focus to trigger button or main content
            const createButton = document.querySelector('[wire\\:click="create"]');
            if (createButton) {
                createButton.focus();
            }
        });
    });

    // Handle escape key for modals
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.fixed.inset-0');
            if (modals.length > 0) {
                Livewire.dispatch('closeModal');
            }
        }
    });
</script>