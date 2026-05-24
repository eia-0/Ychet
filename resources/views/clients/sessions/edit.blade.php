<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">
                    Редактирование сеанса от {{ $session->session_date->format('d.m.Y H:i') }}
                </h2>

                <form method="POST" action="{{ route('clients.sessions.update', [$client, $session]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @foreach($templateFields as $field)
                        <div class="mt-4">
                            <x-input-label :for="'field_'.$field->id" :value="$field->name" />
                            @if($field->type == 'textarea')
                                <textarea name="fields[{{ $field->id }}]"
                                          id="field_{{ $field->id }}"
                                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                          rows="3">{{ old('fields.'.$field->id, $values[$field->id] ?? '') }}</textarea>
                            @else
                                <x-text-input id="field_{{ $field->id }}" name="fields[{{ $field->id }}]"
                                              class="block mt-1 w-full"
                                              value="{{ old('fields.'.$field->id, $values[$field->id] ?? '') }}" />
                            @endif
                        </div>
                    @endforeach

                    <!-- Фото До (текущее + замена) -->
                    <div class="mt-6">
                        <x-input-label for="photo_before" value="Фото До" />
                        @if($session->photo_before)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $session->photo_before) }}" class="w-32 h-32 object-cover rounded" />
                            </div>
                        @endif
                        <input type="file" name="photo_before" id="photo_before" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <!-- Фото После (текущее + замена) -->
                    <div class="mt-4">
                        <x-input-label for="photo_after" value="Фото После" />
                        @if($session->photo_after)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $session->photo_after) }}" class="w-32 h-32 object-cover rounded" />
                            </div>
                        @endif
                        <input type="file" name="photo_after" id="photo_after" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('clients.show', $client) }}" class="text-gray-600 hover:underline py-2">← Назад к клиенту</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Обновить сеанс
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>