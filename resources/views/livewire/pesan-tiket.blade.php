<div class="min-h-screen bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-2">Pemesanan Tiket</h2>
            <p class="text-purple-200 text-lg">Silakan pilih jumlah tiket yang ingin dipesan</p>
            <div class="w-20 h-1 bg-purple-500 mx-auto mt-4 rounded-full"></div>
        </div>


        @if (session()->has('message'))
            <div id="flash-message" class="bg-purple-900 border border-purple-500 text-purple-200 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($films as $index => $film)
                <div class="bg-gray-800 border border-purple-500 rounded-lg shadow-lg p-6 hover:shadow-purple-500/25 transition-all duration-300">
                    <h3 class="text-xl font-semibold text-white mb-2">{{ $film['film'] }}</h3>
                    <p class="text-sm text-purple-300">Studio: {{ $film['studio'] }}</p>
                    <p class="text-sm text-purple-300">Tanggal: {{ $film['tanggal'] }}</p>
                    <p class="text-sm text-purple-300">Jam: {{ $film['jam'] }}</p>
                    <p class="text-sm text-green-400 mt-2">Tiket Tersedia: {{ $film['tersedia'] }}</p>
                    <p class="text-sm text-yellow-400">Harga: Rp{{ number_format($film['harga'], 0, ',', '.') }}</p>
                    <button wire:click="pesanTiket({{ $index }})" class="mt-4 w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-md transition duration-300 shadow-lg hover:shadow-purple-600/50">
                        Pesan Tiket
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Modal Popup -->
        @if($showModal)
            <div class="fixed inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center" wire:click="closeModal">
            <div class="bg-gray-800 border border-purple-500 rounded-lg shadow-2xl p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto [&::-webkit-scrollbar]:hidden" wire:click.stop>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-white">Pesan Tiket</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-purple-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($selectedFilm)
                        <div class="mb-4">
                            <h4 class="font-semibold text-purple-300">{{ $selectedFilm['film'] }}</h4>
                            <p class="text-sm text-gray-300">Studio: {{ $selectedFilm['studio'] }}</p>
                            <p class="text-sm text-gray-300">{{ $selectedFilm['tanggal'] }} - {{ $selectedFilm['jam'] }}</p>
                            <p class="text-sm text-green-400">Tersedia: {{ $selectedFilm['tersedia'] }} tiket</p>
                            <p class="text-sm text-yellow-400">Harga: Rp{{ number_format($selectedFilm['harga'], 0, ',', '.') }}</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Tiket:</label>
                            <div class="flex items-center space-x-3">
                                <input wire:model.live="jumlahTiket" type="number" min="1" max="{{ $selectedFilm['tersedia'] }}" class="w-20 text-center bg-gray-700 border border-purple-500 text-white rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-gray-700 border border-purple-500 rounded">
                            <div class="flex justify-between text-gray-300">
                                <span>Harga per tiket:</span>
                                <span class="text-yellow-400">Rp{{ number_format($selectedFilm['harga'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>Jumlah tiket:</span>
                                <span class="text-purple-400">{{ $jumlahTiket }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg border-t border-purple-500 pt-2 mt-2">
                                <span class="text-white">Total:</span>
                                <span class="text-green-400">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-400 mt-1">
                                <span>Sisa kapasitas:</span>
                                <span class="text-orange-400">{{ $selectedFilm['tersedia'] - $jumlahTiket }} tiket</span>
                            </div>
                        </div>

                        @if(!$showPayment)
                            <button wire:click="generateKodePembayaran" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-md transition duration-300 shadow-lg hover:shadow-purple-600/50">
                                Lanjut ke Pembayaran
                            </button>
                        @else
                            <div class="mb-4 p-3 bg-purple-900 rounded border border-purple-400">
                                <h5 class="font-semibold text-purple-200 mb-2">Kode Pembayaran Bank:</h5>
                                <div class="bg-gray-800 border border-purple-500 p-2 rounded text-center">
                                    <span class="font-mono text-lg font-bold text-purple-300">{{ $kodePembayaran }}</span>
                                </div>
                                <p class="text-xs text-purple-300 mt-2">Gunakan kode ini untuk pembayaran via transfer bank</p>
                            </div>

                            <button wire:click="selesaiPemesanan" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md transition duration-300 shadow-lg hover:shadow-green-600/50">
                                Selesai
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    window.addEventListener('flash-message-show', () => {
        const flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.5s ease';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }, 4000); // 4 detik
        }
    });
</script>

