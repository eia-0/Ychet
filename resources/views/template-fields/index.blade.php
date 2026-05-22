<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Настройка полей шаблона</h2>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <!-- Форма добавления -->
                <form method="POST" action="{{ route('template-fields.store') }}" class="mb-6 flex gap-2 items-end">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="name" value="Название поля" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="type" value="Тип" />
                        <select name="type" id="type"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="text">Текст</option>
                            <option value="textarea">Многострочный</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                        Добавить
                    </button>
                </form>

                <!-- Список полей -->
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Название</th>
                            <th class="py-2 px-4 border-b text-left">Тип</th>
                            <th class="py-2 px-4 border-b">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $field->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $field->type }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <!-- Кнопка удаления (простая форма) -->
                                <form method="POST" action="{{ route('template-fields.destroy', $field) }}" onsubmit="return confirm('Удалить поле и все его значения?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline mt-4 inline-block">← На главную</a>
            </div>
        </div>
    </div>
</x-app-layout>