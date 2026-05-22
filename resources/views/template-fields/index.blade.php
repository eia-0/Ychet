<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Настройка полей шаблона') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Сообщение об успехе -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Форма добавления поля (адаптивная) -->
            <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Добавить новое поле</h3>
                <form method="POST" action="{{ route('template-fields.store') }}">
                    @csrf
                    <!-- На мобильных: всё в одну колонку, на sm+ : в две строки (название – отдельно, тип+кнопка – отдельно) -->
                    <div class="space-y-3">
                        <!-- Название поля (всегда на отдельной строке) -->
                        <div>
                            <x-input-label for="name" value="Название поля" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" required />
                        </div>
                        <!-- Тип и кнопка (на мобильных — друг под другом, на sm+ — в одной строке) -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="sm:w-1/2">
                                <x-input-label for="type" value="Тип" />
                                <select id="type" name="type"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="text">Текст</option>
                                    <option value="textarea">Многострочный</option>
                                </select>
                            </div>
                            <div class="sm:w-1/2 flex items-end">
                                <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow">
                                    Добавить
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Список полей (адаптивная таблица) -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="hidden sm:block">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Название</th>
                                <th class="py-3 px-4 text-left text-gray-600 font-medium">Тип</th>
                                <th class="py-3 px-4 text-right text-gray-600 font-medium">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($fields as $field)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4">{{ $field->name }}</td>
                                <td class="py-3 px-4">{{ $field->type == 'textarea' ? 'Многострочный' : 'Текст' }}</td>
                                <td class="py-3 px-4 text-right">
                                    <form method="POST" action="{{ route('template-fields.destroy', $field) }}" onsubmit="return confirm('Удалить поле и все его значения?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">🗑 Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">Поля ещё не добавлены</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Мобильные карточки -->
                <div class="sm:hidden divide-y divide-gray-100">
                    @forelse($fields as $field)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $field->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $field->type == 'textarea' ? 'Многострочный' : 'Текст' }}</div>
                            </div>
                            <form method="POST" action="{{ route('template-fields.destroy', $field) }}" onsubmit="return confirm('Удалить поле и все его значения?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">Поля ещё не добавлены</div>
                    @endforelse
                </div>
            </div>

            <!-- Кнопка назад -->
            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline text-sm">← На главную</a>
            </div>
        </div>
    </div>
</x-app-layout>