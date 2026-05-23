<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Админка') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ session('error') }}</div>
                @endif

                <h3 class="text-lg font-semibold mb-4">Все мастера</h3>

                <!-- Десктоп-таблица -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 text-left">Имя</th>
                                <th class="py-2 px-4 text-left">Email</th>
                                <th class="py-2 px-4 text-center">Клиентов</th>
                                <th class="py-2 px-4 text-center">Сеансов</th>
                                <th class="py-2 px-4 text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:underline">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="py-2 px-4">{{ $user->email }}</td>
                                <td class="py-2 px-4 text-center">{{ $user->clients_count }}</td>
                                <td class="py-2 px-4 text-center">{{ $user->sessions_count }}</td>
                                <td class="py-2 px-4 text-right">
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="inline"
                                          onsubmit="return confirm('Сбросить пароль? Будет создан новый временный пароль.')">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:underline text-xs mr-2">🔑 Сбросить пароль</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Удалить мастера и все его данные?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">🗑 Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Мобильные карточки -->
                <div class="sm:hidden space-y-3">
                    @foreach($users as $user)
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:underline">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-sm mt-1">Клиентов: {{ $user->clients_count }}, сеансов: {{ $user->sessions_count }}</div>
                                </div>
                                <div class="flex space-x-1">
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}"
                                          onsubmit="return confirm('Сбросить пароль? Будет создан новый временный пароль.')">
                                        @csrf
                                        <button type="submit" class="text-yellow-500 hover:bg-yellow-50 p-1.5 rounded">🔑</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Удалить мастера и все его данные?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded">🗑</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>