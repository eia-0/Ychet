<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Клиенты') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Верхняя панель с поиском и кнопками -->
            <div class="flex flex-col min-[831px]:flex-row min-[831px]:justify-between items-start min-[831px]:items-center gap-2">
                <form method="GET" class="flex flex-wrap items-center gap-2 w-full min-[831px]:w-auto">
                    <select name="template_id" class="w-full min-[831px]:w-auto rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Все шаблоны</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" @selected(request('template_id') == $tpl->id)>{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" placeholder="Поиск по фамилии или имени"
                           value="{{ request('search') }}"
                           class="w-full min-[831px]:w-64 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <button type="submit"
                            class="shrink-0 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow ml-auto">
                        Искать
                    </button>
                </form>
                <div class="flex gap-2 w-full min-[831px]:w-auto justify-end">
                    <a href="{{ route('templates.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                        ⚙ Шаблоны
                    </a>
                    <a href="{{ route('clients.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition shadow">
                        ➕ Клиент
                    </a>
                </div>
            </div>

            <!-- Сообщение об успехе -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
            @endif

            <!-- Таблица (десктоп) – показывается на экранах >= 992px -->
            <div class="hidden min-[992px]:block bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Фамилия</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Имя</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Отчество</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Телефон</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Шаблон</th>
                            <th class="py-3 px-4 text-center text-gray-600 font-medium">Сеансов</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Последний сеанс</th>
                            <th class="py-3 px-4 text-right text-gray-600 font-medium">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($clients as $client)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">{{ $client->last_name }}</td>
                            <td class="py-3 px-4">{{ $client->first_name }}</td>
                            <td class="py-3 px-4">{{ $client->middle_name }}</td>
                            <td class="py-3 px-4">{{ $client->phone }}</td>
                            <td class="py-3 px-4 text-sm">{{ $client->template->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-center font-medium">{{ $client->sessions_count }}</td>
                            <td class="py-3 px-4 text-gray-700">
                                @if($client->latestSession)
                                    {{ $client->latestSession->session_date->copy()->setTimezone(auth()->user()->timezone ?? 'UTC')->format('d.m.Y H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('clients.show', $client) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Просмотр">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('clients.sessions.create', $client) }}" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition" title="Добавить сеанс">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Удалить клиента и все его сеансы?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Удалить">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Мобильная версия: карточки – показывается на экранах < 992px -->
            <div class="min-[992px]:hidden space-y-3">
                @foreach($clients as $client)
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $client->last_name }} {{ $client->first_name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->middle_name }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $client->phone }}</div>
                                <div class="text-xs text-indigo-600 mt-1">{{ $client->template->name ?? '' }}</div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $client->sessions_count }} сеанс.
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    @if($client->latestSession)
                                        {{ $client->latestSession->session_date->copy()->setTimezone(auth()->user()->timezone ?? 'UTC')->format('d.m.Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end gap-2">
                            <a href="{{ route('clients.show', $client) }}" class="text-blue-600 p-1 hover:bg-blue-50 rounded" title="Просмотр">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('clients.sessions.create', $client) }}" class="text-green-600 p-1 hover:bg-green-50 rounded" title="Новый сеанс">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </a>
                            <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Удалить клиента и все его сеансы?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 p-1 hover:bg-red-50 rounded" title="Удалить">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-6">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>