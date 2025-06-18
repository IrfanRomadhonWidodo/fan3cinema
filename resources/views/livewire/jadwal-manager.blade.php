{{-- resources/views/livewire/jadwal-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Jadwal Tayang</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola jadwal tayang film dengan mudah</p>
    </div>

    {{-- Alert Container for Dynamic Messages --}}
    <div id="alert-container" class="mb-4"></div>

{{-- Action Toolbar --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Filter Card --}}
    <div class="col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Search Input --}}
            <div class="relative col-span-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari film atau studio..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Film Filter --}}
            <select wire:model.live="filmFilter" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Film</option>
                @foreach($films as $film)
                    <option value="{{ $film->id }}">{{ $film->judul }}</option>
                @endforeach
            </select>

            {{-- Studio Filter --}}
            <select wire:model.live="studioFilter" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Studio</option>
                @foreach($studios as $studio)
                    <option value="{{ $studio->id }}">{{ $studio->nama_studio }}</option>
                @endforeach
            </select>

            {{-- Tanggal Filter --}}
            <input 
                type="date" 
                wire:model.live="tanggalFilter"
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
            >

            {{-- Per Page --}}
            <select wire:model.live="perPage" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                <option value="5">5 per halaman</option>
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
    </div>

    {{-- Action Buttons Card --}}
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 flex flex-col space-y-3 xl:space-y-4">
        <button wire:click="clearFilters" type="button" class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Clear Filter
        </button>
        <button wire:click="refresh" type="button" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
        </button>
        <button wire:click="create" type="button" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Jadwal
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Film</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($jadwal as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($jadwal->currentPage() - 1) * $jadwal->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->film->judul ?? 'Film tidak ditemukan' }}</div>
                                {{-- @if($item->film)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->film->genre ?? 'Genre tidak tersedia' }}</div>
                                @endif --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300">
                                    {{ $item->studio->nama_studio ?? 'Studio tidak ditemukan' }}
                                </span>
                                @if($item->studio)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kapasitas: {{ $item->studio->kapasitas }} orang</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->tanggal->format('d/m/Y') }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->tanggal->format('l') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                    {{ date('H:i', strtotime($item->jam)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="edit({{ $item->id }})"
                                    type="button"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition duration-200"
                                >
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                    </button>
                                <button 
                                    wire:click="confirmDelete({{ $item->id }})"
                                    type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition duration-200 ml-2"
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
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">Tidak ada jadwal ditemukan</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">Belum ada jadwal tayang atau sesuaikan filter pencarian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $jadwal->links() }}
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-opacity duration-200" >
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 transition-colors duration-200">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $isEditing ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}
                    </h3>
                    <button 
                        wire:click="closeModal"
                        type="button"
                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition duration-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="save">
                    {{-- Film Selection --}}
                    <div class="mb-4">
                        <label for="film_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Film</label>
                        <select 
                            wire:model="film_id" 
                            id="film_id"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-colors duration-200"
                        >
                            <option value="">Pilih Film</option>
                            @foreach($films as $film)
                                <option value="{{ $film->id }}">{{ $film->judul }}</option>
                            @endforeach
                        </select>
                        @error('film_id') 
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Studio Selection --}}
                    <div class="mb-4">
                        <label for="studio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Studio</label>
                        <select 
                            wire:model="studio_id" 
                            id="studio_id"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-colors duration-200"
                        >
                            <option value="">Pilih Studio</option>
                            @foreach($studios as $studio)
                                <option value="{{ $studio->id }}">{{ $studio->nama_studio }} ({{ $studio->kapasitas }} kursi)</option>
                            @endforeach
                        </select>
                        @error('studio_id') 
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $messages }}</span> 
                        @enderror
                    </div>

                    {{-- Date Input --}}
                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                        <input 
                            type="date" 
                            wire:model="tanggal" 
                            id="tanggal"
                            min="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-colors duration-200"
                        >
                        @error('tanggal') 
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $messages }}</span> 
                        @enderror
                    </div>

                    {{-- Time Input --}}
                    <div class="mb-4">
                        <label for="jam" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam</label>
                        <input 
                            type="time" 
                            wire:model="jam" 
                            id="jam"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-colors duration-200"
                        >
                        @error('jam') 
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $messages }}</span> 
                        @enderror
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button 
                            wire:click="closeModal"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 rounded-lg transition duration-200 flex items-center"
                            wire:loading.attr="disabled"
                        >
                            <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove>{{ $isEditing ? 'Update' : 'Simpan' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </class=>
    @endif

    {{-- Modal Konfirmasi Delete --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-opacity duration-200" >
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 transition-colors duration-200">
                {{-- Modal Header --}}
                <div class="flex items-center justify-center mb-4">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-center space-x-3">
                    <button 
                        wire:click="closeModal"
                        type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition duration-200"
                    >
                        Batal
                    </button>
                    <button 
                        wire:click="delete"
                        type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 rounded-lg transition duration-200 flex items-center"
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
    @endif
</div>

{{-- Script untuk Alert Management --}}
<script>
    // Listen untuk event alert dari Livewire
    document.addEventListener('livewire:initialized', function() {
        Livewire.on('show-alert', function(data) {
            showAlert(data.type, data.message);
        });
    });

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        
        // Hapus alert yang ada
        alertContainer.innerHTML = '';
        
        // Tentukan warna berdasarkan type
        let bgColor, textColor, iconColor, icon;
        switch(type) {
            case 'success':
                bgColor = 'bg-green-50 dark:bg-green-900';
                textColor = 'text-green-800 dark:text-green-200';
                iconColor = 'text-green-400 dark:text-green-300';
                icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            case 'error':
                bgColor = 'bg-red-50 dark:bg-red-900';
                textColor = 'text-red-800 dark:text-red-200';
                iconColor = 'text-red-400 dark:text-red-300';
                icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            case 'warning':
                bgColor = 'bg-yellow-50 dark:bg-yellow-900';
                textColor = 'text-yellow-800 dark:text-yellow-200';
                iconColor = 'text-yellow-400 dark:text-yellow-300';
                icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            default:
                bgColor = 'bg-purple-50 dark:bg-purple-900';
                textColor = 'text-purple-800 dark:text-purple-200';
                iconColor = 'text-purple-400 dark:text-purple-300';
                icon = `<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>`;
        }
        
        // Buat element alert
        const alertElement = document.createElement('div');
        alertElement.className = `${bgColor} border border-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'purple'}-200 dark:border-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'purple'}-700 rounded-md p-4 transition-all duration-300 transform`;
        alertElement.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <div class="${iconColor}">
                        ${icon}
                    </div>
                </div>
                <div class="ml-3">
                    <p class="${textColor} text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" class="${textColor} hover:bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'purple'}-100 dark:hover:bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'purple'}-800 rounded-md p-1.5 inline-flex h-8 w-8 transition-colors duration-200" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Tambahkan ke container
        alertContainer.appendChild(alertElement);
        
        // Auto-hide setelah 5 detik
        setTimeout(function() {
            if (alertElement.parentNode) {
                alertElement.style.opacity = '0';
                alertElement.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    if (alertElement.parentNode) {
                        alertElement.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // Handle modal keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            // Trigger close modal jika ada modal yang terbuka
            if (document.querySelector('.fixed.inset-0')) {
                @this.closeModal();
            }
        }
    });
</script>