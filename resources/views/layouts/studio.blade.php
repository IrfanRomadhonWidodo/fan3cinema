<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-white mb-2">Daftar Studio</h2>
        <p class="text-purple-200 text-lg">Studio yang tersedia untuk Anda</p>
        <div class="w-20 h-1 bg-purple-500 mx-auto mt-4 rounded-full"></div>
    </div>

    @if($studios->isEmpty())
        <!-- Empty State -->
        <div class="max-w-md mx-auto bg-purple-900/20 text-purple-100 p-6 rounded-xl text-center border border-purple-700">
            <svg class="w-10 h-10 mx-auto mb-2 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-1a3 3 0 016 0v1m-6 4h6m-6 0a2 2 0 01-2-2m8 0a2 2 0 01-2 2m0-14a4 4 0 00-4 4v2a4 4 0 004 4 4 4 0 004-4V9a4 4 0 00-4-4z" />
            </svg>
            <h3 class="text-xl font-semibold mb-1">Belum Ada Studio</h3>
            <p>Belum ada data studio tersedia saat ini. Silakan periksa kembali nanti.</p>
        </div>
    @else
        <!-- Studio Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($studios as $studio)
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-purple-500 transition duration-300 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white">{{ $studio->nama_studio }}</h3>
                        </div>
                    </div>
                    <p class="text-purple-200">Kapasitas: <span class="font-medium">{{ $studio->kapasitas }}</span> orang</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
