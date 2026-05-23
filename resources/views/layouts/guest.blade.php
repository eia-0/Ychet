<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Дневник мастера') }} — @yield('title', 'Вход')</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-indigo-100 via-white to-purple-100 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Логотип и название -->
            <div class="text-center mb-6">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Учти" class="w-14 h-14 mx-auto">
                </a>
                <h1 class="text-2xl font-bold text-gray-800 mt-3"></h1>
                <p class="text-sm text-gray-500 mt-1">Дневник мастера — видеть прогресс, знать результат.</p>
            </div>

            <!-- Карточка формы -->
            <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-6 sm:p-8">
                @yield('content')   {{-- <-- было {{ $slot }}, теперь @yield --}}
            </div>

            <!-- Дополнительные ссылки -->
            @if (Route::has('login') && !request()->routeIs('login'))
                <p class="text-center text-sm text-gray-500 mt-4">
                    Уже есть аккаунт?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Войти</a>
                </p>
            @endif
            @if (Route::has('register') && !request()->routeIs('register'))
                <p class="text-center text-sm text-gray-500 mt-4">
                    Нет аккаунта?
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Зарегистрироваться</a>
                </p>
            @endif
        </div>
    </div>

    <footer class="text-center text-gray-400 text-xs py-4">
        © {{ date('Y') }} Дневник мастера. Все права защищены
    </footer>
</body>
</html>