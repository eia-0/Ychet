<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мастер: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.index') }}" class="text-indigo-600 hover:underline text-sm">
                    ← Назад к списку мастеров
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                <div class="text-sm text-gray-600 mb-4">
                    Email: {{ $user->email }}<br>
                    Всего клиентов: {{ $clients->total() }}
                </div>

                @if($clients->isEmpty())
                    <p class="text-gray-500">У этого мастера пока нет клиентов.</p>
                @else
                    @foreach($clients as $client)
                        <div class="border rounded-lg p-4 mb-6 bg-gray-50">
                            <h3 class="font-semibold text-lg text-gray-800">
                                {{ $client->last_name }} {{ $client->first_name }} {{ $client->middle_name }}
                            </h3>
                            <p class="text-sm text-gray-500">Телефон: {{ $client->phone ?? '—' }}</p>

                            @if($client->sessions->isNotEmpty())
                                <div class="mt-3 space-y-4">
                                    @foreach($client->sessions as $session)
                                        <div class="border p-3 rounded bg-white">
                                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-2">
                                                <span class="font-medium text-sm">
                                                    {{ $session->session_date->copy()->setTimezone($user->timezone ?? 'UTC')->format('d.m.Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="overflow-x-auto text-xs sm:text-sm">
                                                <table class="min-w-full">
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

                                            @if($session->photo_path)
                                                <div class="mt-3 flex justify-center">
                                                    <div class="w-2/3 sm:w-1/2 lg:w-1/3 aspect-[3/4] overflow-hidden rounded-lg shadow-sm">
                                                        <img src="{{ asset('storage/' . $session->photo_path) }}" alt="Фото сеанса" class="w-full h-full object-cover">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 mt-2">Нет сеансов</p>
                            @endif
                        </div>
                    @endforeach

                    <div class="mt-4">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>