<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои шаблоны') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
            @endif

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-800">Все шаблоны ({{ $templates->count() }}/10)</h3>
                @if(auth()->user()->templates()->count() < 10)
                    <a href="{{ route('templates.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow">
                        ➕ Создать
                    </a>
                @else
                    <span class="text-sm text-gray-500">Лимит исчерпан</span>
                @endif
            </div>

            <!-- Таблица с горизонтальной прокруткой на мобильных -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Название</th>
                            <th class="py-3 px-4 text-center text-gray-600 font-medium">Полей</th>
                            <th class="py-3 px-4 text-right text-gray-600 font-medium">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($templates as $template)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">{{ $template->name }}</td>
                            <td class="py-3 px-4 text-center">{{ $template->fields_count }}</td>
                            <td class="py-3 px-4">
                                <!-- Кнопки действий: вертикально на < 700px, горизонтально на >= 700px -->
                                <div class="flex flex-col min-[700px]:flex-row min-[700px]:items-center gap-2 justify-end">
                                    <a href="{{ route('templates.show', $template) }}"
                                       class="text-indigo-600 hover:underline text-xs sm:text-sm whitespace-nowrap">
                                        Настроить поля шаблона
                                    </a>
                                    <a href="{{ route('templates.edit', $template) }}"
                                       class="text-yellow-600 hover:underline text-xs sm:text-sm whitespace-nowrap">
                                        Изменить название
                                    </a>
                                    <form method="POST" action="{{ route('templates.destroy', $template) }}"
                                          onsubmit="return confirm('Удалить шаблон и все его поля?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:underline text-xs sm:text-sm whitespace-nowrap">
                                            Удалить название
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline text-sm">← На главную</a>
            </div>
        </div>
    </div>
</x-app-layout>