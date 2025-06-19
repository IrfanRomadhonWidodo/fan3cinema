<footer class="bg-gray-950 border-t border-purple-700/50 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="text-center mb-10">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <svg class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-2xl font-extrabold tracking-wide bg-gradient-to-r from-purple-400 via-white to-purple-500 text-transparent bg-clip-text">
                    Fan3Cinema
                </span>
            </div>
            <p class="text-sm text-gray-400 max-w-xl mx-auto">
                Nikmati pengalaman cinema terbaik bersama Fan3Cinema. Reservasi tiket cepat, nyaman, dan modern.
            </p>
        </div>

        {{-- Kontak dan Sosial Media --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-sm text-gray-300 text-center lg:text-left">
            <div>
                <h4 class="font-semibold text-white mb-2">Alamat</h4>
                <p class="text-purple-400">Jl. Raya Film No.123<br>Kota Cinemania, Indonesia</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-2">Email</h4>
                <p><a href="mailto:info@fan3cinema.com" class="hover:underline text-purple-400">info@fan3cinema.com</a></p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-2">Ikuti Kami</h4>
                <ul class="space-y-1">
                    <li><a href="https://www.instagram.com/irfan_romadhonn/" target="_blank" class="text-purple-400 hover:underline">Instagram</a></li>
                    <li><a href="https://www.youtube.com/@irfanromadhonwidodo2035" target="_blank" class="text-purple-400 hover:underline">YouTube</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-2">Developer</h4>
                <p><a href="https://github.com/IrfanRomadhonWidodo" target="_blank" class="text-purple-400 hover:underline">GitHub - Irfan</a></p>
            </div>
        </div>

        {{-- Garis bawah --}}
        <div class="mt-12 border-t border-gray-700 pt-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Fan3Cinema. All rights reserved.
        </div>
    </div>
</footer>
