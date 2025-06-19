{{-- resources/views/livewire/tiket-manager.blade.php --}}
<div class="container mx-auto px-4 py-8 bg-white dark:bg-zinc-800 min-h-screen transition-colors duration-200">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Tiket</h1>
        <p class="text-gray-800 dark:text-gray-400">Kelola data tiket dengan mudah</p>
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
                    placeholder="Cari film, studio, atau harga..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <select wire:model.live="statusFilter" class="border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
                <option value="">Semua Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="terjual">Terjual</option>
                <option value="blokir">Blokir</option>
            </select>
            
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
                Tambah Tiket
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Film</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($tiket as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($tiket->currentPage() - 1) * $tiket->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->jadwal->film->judul ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->jadwal->studio->nama_studio ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Kapasitas: {{ $item->jadwal->studio->kapasitas ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->jadwal->tanggal ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->jadwal->jam ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'tersedia')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Tersedia
                                    </span>
                                @elseif($item->status === 'terjual')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Terjual
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Blokir
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->created_at->format('d/m/Y H:i') }}
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
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 002-2 2-2h14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Tidak ada tiket ditemukan</p>
                                    <p class="text-sm">Silakan tambah tiket baru atau ubah filter pencarian</p>
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
        {{ $tiket->links() }}
    </div>

    {{-- Modal untuk Create/Edit --}}
    @if($showModal)
    <div class="my-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <form wire:submit.prevent="save">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                {{ $isEditing ? 'Edit Tiket' : 'Tambah Tiket Baru' }}
            </h3>

            {{-- Form Fields --}}
            <div class="space-y-4">
                {{-- Jadwal Tayang --}}
                <div>
                    <label for="jadwal_tayang_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jadwal Tayang <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="jadwal_tayang_id" id="jadwal_tayang_id"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        required>
                        <option value="">Pilih Jadwal Tayang</option>
                        @foreach($jadwalOptions as $option)
                            <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                    @error('jadwal_tayang_id')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                        <input type="number" wire:model="harga" id="harga" min="0" step="1000" placeholder="50000"
                            class="w-full pl-10 border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                            required>
                    </div>
                    @error('harga')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $messages }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status" id="status"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        required>
                        <option value="tersedia">Tersedia</option>
                        <option value="terjual">Terjual</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $messages }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end mt-6 space-x-2">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-md hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                    {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
@endif


    {{-- Modal Konfirmasi Delete --}}
    @if($confirmingDelete)
    <div class="mt-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow p-6 w-full max-w-2xl mx-auto">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Konfirmasi Hapus Tiket
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                    Apakah Anda yakin ingin menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex space-x-3">
                    <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Hapus
                    </button>
                    <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

</div>

{{-- JavaScript untuk Alert Handling --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle show-alert events
        window.addEventListener('show-alert', function(event) {
            const alertContainer = document.getElementById('alert-container');
            const alertType = event.detail.type;
            const alertMessage = event.detail.message;
            
            // Clear existing alerts
            alertContainer.innerHTML = '';
            
            // Create alert element
            const alertElement = document.createElement('div');
            alertElement.className = `alert-${alertType} p-4 mb-4 text-sm rounded-lg transition-all duration-500 transform opacity-0 translate-y-2`;
            
            if (alertType === 'success') {
                alertElement.className += ' bg-green-50 text-green-800 border border-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-800';
                alertElement.innerHTML = `
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Berhasil!</span> ${alertMessage}
                    </div>
                `;
            } else if (alertType === 'error') {
                alertElement.className += ' bg-red-50 text-red-800 border border-red-200 dark:bg-red-900 dark:text-red-300 dark:border-red-800';
                alertElement.innerHTML = `
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Error!</span> ${alertMessage}
                    </div>
                `;
            }
            
            // Add close button
            const closeButton = document.createElement('button');
            closeButton.className = 'ml-auto -mx-1.5 -my-1.5 bg-transparent text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-800';
            closeButton.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            `;
            closeButton.onclick = function() {
                alertElement.remove();
            };
            
            alertElement.appendChild(closeButton);
            alertContainer.appendChild(alertElement);
            
            // Animate in
            setTimeout(() => {
                alertElement.classList.add('opacity-100');
                alertElement.classList.remove('opacity-0', 'translate-y-2');
            }, 100);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                if (alertElement.parentNode) {
                    alertElement.classList.add('opacity-0', 'translate-y-2');
                    alertElement.classList.remove('opacity-100');
                    setTimeout(() => {
                        alertElement.remove();
                    }, 500);
                }
            }, 5000);
        });
    });
</script>