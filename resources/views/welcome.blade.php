<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Дневник мастера') }}</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-indigo-100 via-white to-purple-100 min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col justify-center items-center px-4">
        <div class="max-w-2xl w-full bg-white/60 backdrop-blur-lg rounded-2xl shadow-xl p-8 sm:p-12 text-center">
            <div class="mb-6 flex justify-center">
                <img src="{{ asset('images/logo.svg') }}" alt="Дневник" class="w-16 h-16">
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-800 mb-2 tracking-tight"></h1>
            <p class="text-lg text-gray-500 mb-8">Дневник мастера — видеть прогресс, знать результат.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 transition shadow-md">
                            Перейти в кабинет
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 transition shadow-md">
                            Войти
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                Зарегистрироваться
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
        <p class="mt-6 text-gray-400 text-[10px]">© {{ date('Y') }} Дневник мастера. Все права защищены. DM_V6.0</p>
    </div>
</body>
</html>