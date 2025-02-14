<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WatchTowr Laravel Shop</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col">

            <nav class="bg-white shadow-md">
                <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Laravel Shop</h1>
                    
                    @if (Route::has('login'))
                        <div>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-black font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 py-2 bg-blue-600 text-black font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-blue-600 text-black font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </nav>

            <!-- Hero Section -->
            <header class="flex-1 flex items-center justify-center text-center px-6">
                <div class="max-w-2xl">
                    <h2 class="text-4xl font-bold text-gray-900 leading-tight">Welcome to Laravel Shop</h2>
                    <p class="mt-4 text-gray-600">The best place to find and buy amazing products.</p>
                    
                    @guest
                        <div class="mt-6">
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 text-black font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                                Get Started
                            </a>
                        </div>
                    @endguest
                </div>
            </header>

            <!-- Footer -->
            <footer class="py-6 text-center text-gray-500">
                Built by Hass for WatchTowr: Laravel v{{ Illuminate\Foundation\Application::VERSION }} and PHP v{{ PHP_VERSION }}
            </footer>

        </div>
    </body>
</html>
