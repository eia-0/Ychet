<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold mb-1">
                    {{ $client->last_name }} {{ $client->first_name }} {{ $client->middle_name }}
                </h2>
                <p class="text-gray-600 text-sm sm:text-base mb-4">
                    Телефон: {{ $client->phone ?? 'не указан' }}
                </p>

                <div class="flex flex-col sm:flex-row gap-2 mb-6">
                    <a href="{{ route('clients.sessions.create', $client) }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                        ➕ Новый сеанс
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline py-2 text-sm">
                        ← На главную
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-lg font-semibold mb-3">История сеансов</h3>
                @forelse($client->sessions as $session)
                    <div class="border rounded p-3 sm:p-4 mb-4 bg-gray-50">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-3">
                            <span class="font-medium text-gray-700 text-sm sm:text-base">
                                {{ $session->session_date->copy()->setTimezone(auth()->user()->timezone ?? 'UTC')->format('d.m.Y H:i') }}
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr>
                                        <th class="py-1 px-2 border-b text-left">Поле</th>
                                        <th class="py-1 px-2 border-b text-left">Значение</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($session->fieldValues as $value)
                                    <tr>
                                        <td class="py-1 px-2 border-b">{{ $value->templateField->name }}</td>
                                        <td class="py-1 px-2 border-b">{{ $value->value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Фото: старые одиночные + новые До/После --}}
                        @if($session->photo_path || $session->photo_before || $session->photo_after)
                            <div class="mt-4">
                                @if($session->photo_path)
                                    <div class="mb-4">
                                        <p class="text-xs text-gray-500 mb-1">Фото</p>
                                        <div class="w-3/4 sm:w-1/2 lg:w-1/3 aspect-[3/4] overflow-hidden rounded-lg shadow-sm mx-auto">
                                            <img src="{{ asset('storage/' . $session->photo_path) }}" alt="Фото" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4">
                                    @if($session->photo_before)
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">До</p>
                                            <div class="aspect-[3/4] overflow-hidden rounded-lg shadow-sm">
                                                <img src="{{ asset('storage/' . $session->photo_before) }}" alt="До" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @endif
                                    @if($session->photo_after)
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">После</p>
                                            <div class="aspect-[3/4] overflow-hidden rounded-lg shadow-sm">
                                                <img src="{{ asset('storage/' . $session->photo_after) }}" alt="После" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- ✨ Красивые кнопки действий --}}
                        <div class="mt-3 flex justify-end gap-2">
                            <a href="{{ route('clients.sessions.edit', [$client, $session]) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium hover:bg-yellow-200 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Редактировать
                            </a>
                            <form method="POST" action="{{ route('clients.sessions.destroy', [$client, $session]) }}"
                                  onsubmit="return confirm('Удалить этот сеанс?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-medium hover:bg-red-200 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Нет сеансов</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>