<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-white mb-2">Daftar Film</h2>
        <p class="text-purple-200 text-lg">Film terbaik yang tersedia</p>
        <div class="w-20 h-1 bg-purple-500 mx-auto mt-4 rounded-full"></div>
    </div>

    @forelse($films as $genre => $genreFilms)
        <div class="mb-14">
            <h3 class="text-3xl text-white font-semibold mb-6 border-b border-gray-700 pb-2">{{ $genre }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($genreFilms as $film)
                    <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-purple-600/40 transition duration-300 relative">
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                <a href="#" class="w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                                    Pesan Tiket
                                </a>
                            </div>
                        </div>
                        <div class="p-5">
                            <h4 class="text-xl font-bold text-white mb-1 truncate">{{ $film->judul }}</h4>
                            <div class="text-purple-300 text-sm">
                                <p>Sutradara: <span class="text-white">{{ $film->sutradara }}</span></p>
                                <p>Tahun: <span class="text-white">{{ $film->tahun }}</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center text-purple-200 text-lg mt-8">Belum ada data film yang tersedia saat ini.</div>
    @endforelse
</div>
