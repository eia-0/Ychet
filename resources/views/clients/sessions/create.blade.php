<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">
                    Новый сеанс для {{ $client->last_name }} {{ $client->first_name }}
                </h2>

                <form method="POST" action="{{ route('clients.sessions.store', $client) }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Динамические поля, предзаполненные предыдущими значениями -->
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

                    <!-- Загрузка нового фото -->
                    <div class="mt-6">
                        <x-input-label for="photo" value="Новое фото (если нужно)" />
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('clients.show', $client) }}" class="text-gray-600 hover:underline py-2">← Назад к клиенту</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Сохранить сеанс
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>