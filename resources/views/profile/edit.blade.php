<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Профиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Основная форма: имя, email, часовой пояс -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
                @endif

                <h3 class="text-lg font-medium text-gray-800 mb-4">Общие настройки</h3>

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

            <!-- Форма смены пароля -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Сменить пароль</h3>

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Текущий пароль -->
                    <div class="mb-4" x-data="{ show: false }">
                        <x-input-label for="current_password" :value="__('Текущий пароль')" />
                        <div class="relative">
                            <x-text-input id="current_password" x-bind:type="show ? 'text' : 'password'" name="current_password"
                                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg x-show="!show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.583-5.362M17.458 6.825A10.05 10.05 0 0112 5C7.522 5 3.732 7.943 2.458 12a10.05 10.05 0 005.542 6.825M12 5v14m0-14C12 5 3.732 7.943 2.458 12M12 19c0 0 8.268-2.943 9.542-7"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Новый пароль -->
                    <div class="mb-4" x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Новый пароль')" />
                        <div class="relative">
                            <x-text-input id="password" x-bind:type="show ? 'text' : 'password'" name="password"
                                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg x-show="!show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.583-5.362M17.458 6.825A10.05 10.05 0 0112 5C7.522 5 3.732 7.943 2.458 12a10.05 10.05 0 005.542 6.825M12 5v14m0-14C12 5 3.732 7.943 2.458 12M12 19c0 0 8.268-2.943 9.542-7"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Подтверждение нового пароля -->
                    <div class="mb-6" x-data="{ show: false }">
                        <x-input-label for="password_confirmation" :value="__('Подтверждение нового пароля')" />
                        <div class="relative">
                            <x-text-input id="password_confirmation" x-bind:type="show ? 'text' : 'password'" name="password_confirmation"
                                          class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg x-show="!show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.583-5.362M17.458 6.825A10.05 10.05 0 0112 5C7.522 5 3.732 7.943 2.458 12a10.05 10.05 0 005.542 6.825M12 5v14m0-14C12 5 3.732 7.943 2.458 12M12 19c0 0 8.268-2.943 9.542-7"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:bg-green-700 transition shadow-md">
                        Сменить пароль
                    </button>
                </form>
            </div>

            <!-- Кнопка назад и ссылка на разработчика -->
            <div class="text-center px-6">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-wider hover:bg-gray-300 transition w-full sm:w-auto">
                    ← На главную
                </a>
                <div class="mt-4">
                    <a href="https://max.ru/u/f9LHodD0cOJloELvW-_o56QfDA7xwbHghQLYkXo0_pGiRhhId7dSsQO2iVA" target="_blank" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Хотите восстановить пароль? Есть вопросы или предложения? Напишите разработчику
                    </a>
                    <p class="text-sm text-gray-500">почта: eia_0@mail.ru</p>
                </div>
            </div>

            <!-- Удаление профиля -->
            <div class="text-center">
                <form method="POST" action="{{ route('profile.destroy') }}" 
                      onsubmit="return confirm('Вы уверены? Все ваши данные, клиенты и сеансы будут безвозвратно удалены.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2  border border-transparent rounded-xl font-semibold text-xs text-grau uppercase tracking-wider  transition shadow-md">
                        Удалить профиль
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>