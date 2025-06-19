{{-- home.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fan3Cinema - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Atau jika menggunakan CDN Tailwind --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @livewireStyles

</head>
<body class="bg-gray-900 text-white">
    {{-- Include Navbar --}}
    @include('layouts.navbar')
    <div class="pt-20">
    {{-- Trend Film --}}
    <div id="trendfilm">
        @include('layouts.trendfilm')
    </div>

    {{-- Layanan --}}
    <div id="layanan">
        @include('layouts.layanan')
    </div>

    {{-- Studio --}}
    <div id="studio" class="scroll-mt-20">
        @include('layouts.studio')
    </div>

    {{-- Film List (Livewire) --}}
    <div id="film" class="scroll-mt-20">
        @livewire('film-list')
    </div>

@auth
    {{-- Pemesanan Tiket --}}
    <div id="pesan" class="scroll-mt-20">
        @livewire('pesan-tiket')
    </div>
@endauth


    {{-- Footer --}}
    @include('layouts.footer')

    </div>
    @livewireScripts

    {{-- Optional: Include Alpine.js for interactivity --}}
</body>
</html>