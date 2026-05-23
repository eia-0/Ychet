<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Поля шаблона: ') . $template->name }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
            @endif

            <!-- Форма добавления поля -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Добавить поле</h3>
                <form method="POST" action="{{ route('templates.fields.store', $template) }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="name" value="Название" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="type" value="Тип" />
                        <select name="type" id="type" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="text">Текст</option>
                            <option value="textarea">Многострочный</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="self-end inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow">
                        ➕ Добавить
                    </button>
                </form>
            </div>

            <!-- Список полей -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Название</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Тип</th>
                            <th class="py-3 px-4 text-right text-gray-600 font-medium">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($template->fields as $field)
                        <tr>
                            <td class="py-3 px-4">{{ $field->name }}</td>
                            <td class="py-3 px-4">{{ $field->type === 'textarea' ? 'Многострочный' : 'Текст' }}</td>
                            <td class="py-3 px-4 text-right">
                                <form method="POST" action="{{ route('templates.fields.destroy', [$template, $field]) }}" onsubmit="return confirm('Удалить поле?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Нет полей</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                <a href="{{ route('templates.index') }}" class="text-gray-600 hover:underline text-sm">← К списку шаблонов</a>
            </div>
        </div>
    </div>
</x-app-layout>