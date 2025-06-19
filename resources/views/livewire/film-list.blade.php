<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-white mb-2">Daftar Film</h2>
        <p class="text-purple-200 text-lg">Film terbaik yang tersedia</p>
        <div class="w-20 h-1 bg-purple-500 mx-auto mt-4 rounded-full"></div>
    </div>

    {{-- Filter Genre --}}
    @if($availableGenres->count())
    <div class="mb-6 flex flex-wrap gap-4 justify-center">
        <button wire:click="resetFilters"
            class="px-4 py-2 rounded-lg {{ !$selectedGenre ? 'bg-purple-600' : 'bg-gray-700' }} text-white hover:bg-purple-500 transition-colors">
            Semua Genre
        </button>
        @foreach($availableGenres as $genre)
        <button wire:click="filterByGenre('{{ $genre }}')"
            class="px-4 py-2 rounded-lg {{ $selectedGenre === $genre ? 'bg-purple-600' : 'bg-gray-700' }} text-white hover:bg-purple-500 transition-colors">
            {{ $genre }}
        </button>
        @endforeach
    </div>
    @endif

    {{-- Filter Tanggal --}}
    <div class="max-w-md mx-auto mb-10">
        <label for="tanggal" class="block text-white font-semibold mb-2 text-center">Pilih Tanggal:</label>
        <input type="date" id="tanggal" wire:model.lazy="selectedDate"
            class="w-full rounded-lg px-4 py-2 bg-gray-800 text-white border border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-600">
    </div>

    {{-- Info Tanggal --}}
    <div class="text-center text-purple-300 text-lg mb-10">
        Menampilkan film untuk tanggal <span class="text-white font-semibold">
            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
        </span>
        @if($selectedGenre)
            dan genre <span class="text-white font-semibold">{{ $selectedGenre }}</span>
        @endif
    </div>

    {{-- Film List --}}
    @forelse($films as $genre => $genreFilms)
        <div class="mb-14">
            <h3 class="text-3xl text-white font-semibold mb-2">{{ $genre }}</h3>
            <p class="text-purple-400 mb-6">
                Jadwal tayang pada {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($genreFilms as $film)
                <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-purple-600/40 transition-all duration-300 group">
                    <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-full h-[400px] object-cover">

                   <div class="absolute inset-0 flex items-end justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/30 z-20">
                    @auth
                        <a href="#pesan" class="mb-20 w-11/12 text-center bg-purple-600/90 backdrop-blur-sm hover:bg-purple-700/90 text-white font-semibold py-2 rounded-lg transition-all duration-300 border border-purple-500/30">
                            Pesan Tiket
                        </a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="mb-20 w-11/12 text-center bg-purple-600/90 backdrop-blur-sm hover:bg-purple-700/90 text-white font-semibold py-2 rounded-lg transition-all duration-300 border border-purple-500/30">
                            Login untuk Pesan Tiket
                        </a>
                    @endguest
                </div>


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
        <div class="text-center text-purple-200 text-lg mt-8">
            Tidak ada film tersedia untuk tanggal dan genre yang dipilih.
        </div>
    @endforelse
</div>
