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
                    <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-purple-600/40 transition-all duration-300 group">
    <!-- Poster film -->
    <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-full h-[400px] object-cover">
    
    <!-- Overlay tombol saat hover -->
    <div class="absolute inset-0 flex items-end justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/30 z-20">
        <a href="#" class="mb-20 w-11/12 text-center bg-purple-600/90 backdrop-blur-sm hover:bg-purple-700/90 text-white font-semibold py-2 rounded-lg transition-all duration-300 border border-purple-500/30">
            Pesan Tiket
        </a>
    </div>
    
    <!-- Informasi film dengan efek plastik (frosted glass) - hanya muncul saat hover -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-white/10 backdrop-blur-lg text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
        <h4 class="text-lg font-bold truncate">{{ $film->judul }}</h4>
        <div class="text-sm">
            <div class="flex justify-between">
                <span class="text-purple-300">Sutradara:</span>
                <span class="text-gray-200 truncate">{{ $film->sutradara }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-purple-300">Tahun:</span>
                <span class="text-gray-200">{{ $film->tahun }}</span>
            </div>
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