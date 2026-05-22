<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Профиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Имя -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Имя')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name', $user->name)" required autofocus
                                      class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required
                                      class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Часовой пояс -->
                    <div class="mb-6">
                        <x-input-label for="timezone" :value="__('Часовой пояс')" />
                        <select name="timezone" id="timezone"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($timezones as $tz => $label)
                                <option value="{{ $tz }}" @selected(old('timezone', $user->timezone) == $tz)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Время сеансов будет отображаться с учётом этого пояса</p>
                        @error('timezone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:bg-indigo-700 transition shadow-md">
                        Сохранить
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>