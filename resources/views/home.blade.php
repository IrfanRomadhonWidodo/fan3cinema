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
</head>
<body class="bg-gray-900 text-white">
    {{-- Include Navbar --}}
    @include('layouts.navbar')
    <div class="pt-20">
        @include('layouts.trendfilm')
        @include('layouts.layanan')
        @include('layouts.studio')
        @include('layouts.film')
        @include('layouts.footer')
    </div>

    {{-- Optional: Include Alpine.js for interactivity --}}
</body>
</html>