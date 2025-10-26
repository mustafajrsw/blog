<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'My Blog'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-gray-50 text-gray-800">

    @include('partials.header')

    <div class="container mx-auto px-4 mt-6">
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    <button id="backToTop"
        class="fixed bottom-5 right-5 hidden p-3 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition"
        aria-label="Back to top">
        ↑
    </button>

    <script>
        document.addEventListener('scroll', () => {
            const btn = document.getElementById('backToTop');
            btn.classList.toggle('hidden', window.scrollY < 300);
        });
        document.getElementById('backToTop').addEventListener('click', () =>
            window.scrollTo({ top: 0, behavior: 'smooth' })
        );
    </script>

    @stack('scripts')
</body>

</html>
