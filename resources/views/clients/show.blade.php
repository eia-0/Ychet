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

                        {{-- Фото сеанса: вертикальное, 3:4, по центру --}}
                        @if($session->photo_path)
                            <div class="mt-4 flex justify-center">
                                <div class="w-3/4 sm:w-1/2 lg:w-1/3 aspect-[3/4] overflow-hidden rounded-lg shadow-md">
                                    <img src="{{ asset('storage/' . $session->photo_path) }}"
                                         alt="Фото сеанса"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif

                        <div class="mt-3 text-right">
                            <form method="POST" action="{{ route('clients.sessions.destroy', [$client, $session]) }}"
                                  onsubmit="return confirm('Удалить этот сеанс?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs sm:text-sm">
                                    🗑 Удалить сеанс
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