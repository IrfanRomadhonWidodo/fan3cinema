{{-- resources/views/livewire/film-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Film</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola data film dengan mudah</p>
    </div>

    {{-- Alert Container for Dynamic Messages --}}
    <div id="alert-container" class="mb-4"></div>

    {{-- Action Bar --}}
    <div class="flex flex-col lg:flex-row justify-between items-center mb-6 space-y-4 lg:space-y-0">
        {{-- Search and Filter --}}
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari judul atau sutradara..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <select wire:model.live="genreFilter" class="border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
                <option value="">Semua Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->nama_genre }}</option>
                @endforeach
            </select>
            
            <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
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
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Film
            </button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Poster</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sutradara</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($films as $film)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($films->currentPage() - 1) * $films->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($film->poster)
                                    <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->judul }}" class="h-16 w-12 object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="h-16 w-12 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $film->judul }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $film->sutradara }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                    {{ $film->tahun }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                    {{ $film->genre->nama_genre ?? 'Tidak Ada' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="edit({{ $film->id }})"
                                    type="button"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition duration-200"
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $film->id }})"
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
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 010-2h4zM6 6v12h12V6H6zm3 3a1 1 0 112 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V9z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data film</p>
                                    <p class="text-sm">Mulai dengan menambahkan film baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($films->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $films->links() }}
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
            <div class="relative top-10 mx-auto p-5 border border-gray-200 dark:border-gray-800 w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 transition-all duration-200 m-4" wire:click.stop>
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center">
                        {{ $isEditing ? 'Edit Film' : 'Tambah Film Baru' }}
                    </h3>
                    
                    <form wire:submit.prevent="save" class="mt-4">
                        {{-- Judul Film --}}
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Film <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="judul"
                                wire:model="judul"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Masukkan judul film"
                            >
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Sutradara --}}
                        <div class="mb-4">
                            <label for="sutradara" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sutradara <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="sutradara"
                                wire:model="sutradara"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Masukkan nama sutradara"
                            >
                            @error('sutradara')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        {{-- Tahun dan Genre --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="tahun"
                                    wire:model="tahun"
                                    min="1900"
                                    max="2030"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="2023"
                                >
                                @error('tahun')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="genre_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Genre <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="genre_id"
                                    wire:model="genre_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                >
                                    <option value="">Pilih Genre</option>
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->nama_genre }}</option>
                                    @endforeach
                                </select>
                                @error('genre_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Poster Upload --}}
                        <div class="mb-6">
                            <label for="poster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Poster Film
                            </label>
                            
                            {{-- Current Poster Preview --}}
                            @if($isEditing && $currentPoster)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Poster saat ini:</p>
                                    <img src="{{ Storage::url($currentPoster) }}" alt="Current poster" class="h-32 w-24 object-cover rounded-lg shadow-sm">
                                </div>
                            @endif
                            
                            {{-- File Input --}}
                            <input 
                                type="file" 
                                id="poster"
                                wire:model="poster"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800"
                            >
                            
                            {{-- New Poster Preview --}}
                            @if($poster)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview poster baru:</p>
                                    <img src="{{ $poster->temporaryUrl() }}" alt="Preview" class="h-32 w-24 object-cover rounded-lg shadow-sm">
                                </div>
                            @endif
                            
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Format: JPG, PNG, JPEG. Maksimal 2MB.
                            </p>
                            
                            @error('poster')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $messages }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 flex items-center"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>
                                    {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                                </span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
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
        >
            <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-800 w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 transition-all duration-200 m-4" wire:click.stop>
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-4">
                        Konfirmasi Hapus
                    </h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Apakah Anda yakin ingin menghapus film ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 dark:bg-red-500 text-white rounded-md hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200 flex items-center"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Hapus</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menghapus...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript untuk menangani alerts dan SPA behavior --}}
<script>
    document.addEventListener('livewire:init', function () {
        // Listen untuk show-alert events
        Livewire.on('show-alert', (event) => {
            const { type, message } = event;
            showAlert(type, message);
        });

        // Listen untuk modal events
        Livewire.on('modal-opened', (event) => {
            document.body.style.overflow = 'hidden';
        });

        Livewire.on('modal-closed', (event) => {
            document.body.style.overflow = 'auto';
        });

        // Handle ESC key untuk close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @this.closeModal();
            }
        });
    });

    // Function untuk menampilkan alert
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        
        // Warna berdasarkan type
        const alertColors = {
            success: 'bg-green-100 border-green-500 text-green-700 dark:bg-green-900 dark:border-green-400 dark:text-green-300',
            error: 'bg-red-100 border-red-500 text-red-700 dark:bg-red-900 dark:border-red-400 dark:text-red-300',
            warning: 'bg-yellow-100 border-yellow-500 text-yellow-700 dark:bg-yellow-900 dark:border-yellow-400 dark:text-yellow-300',
            info: 'bg-blue-100 border-blue-500 text-blue-700 dark:bg-blue-900 dark:border-blue-400 dark:text-blue-300'
        };

        // Icon berdasarkan type
        const alertIcons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        };

        const alertHtml = `
            <div class="border-l-4 p-4 mb-4 rounded-r-lg ${alertColors[type]} transition-all duration-300 transform translate-x-0 opacity-100" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${alertIcons[type]}
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300 transition ease-in-out duration-150" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;

        alertContainer.innerHTML = alertHtml;

        // Auto hide setelah 5 detik
        setTimeout(() => {
            const alertElement = alertContainer.querySelector('[role="alert"]');
            if (alertElement) {
                alertElement.style.transform = 'translateX(-100%)';
                alertElement.style.opacity = '0';
                setTimeout(() => {
                    alertElement.remove();
                }, 300);
            }
        }, 5000);
    }

    // Handle browser back/forward button
    window.addEventListener('popstate', function(event) {
        @this.dispatch('popstate');
    });

    // Prevent form submission on Enter key in search input
    document.addEventListener('keydown', function(event) {
        if (event.target.matches('input[wire\\:model*="search"]') && event.key === 'Enter') {
            event.preventDefault();
        }
    });
</script>