@extends('layouts.guest')
@section('title', 'Вход')

@section('content')
<div>
    <h2 class="text-xl font-semibold text-gray-800 text-center mb-6">Войти в «Учти»</h2>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Пароль -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Пароль')" class="text-gray-700" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="flex items-center text-sm text-gray-600">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2">{{ __('Запомнить меня') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                    {{ __('') }}
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:bg-indigo-700 transition shadow-md">
            {{ __('Войти') }}
        </button>
    </form>

    {{-- Ссылка на разработчика --}}
    <div class="text-center mt-4">
        <a href="https://max.ru/u/f9LHodD0cOJloELvW-_o56QfDA7xwbHghQLYkXo0_pGiRhhId7dSsQO2iVA" target="_blank" class="text-sm text-gray-500 hover:text-gray-700 underline">
            Хотите восстановить пароль? Напишите разработчику
        </a>
    </div>
</div>
@endsection