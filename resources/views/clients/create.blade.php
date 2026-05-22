<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Добавить клиента</h2>

                <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- ФИО и телефон -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="last_name" value="Фамилия" />
                            <x-text-input id="last_name" name="last_name" class="block mt-1 w-full" required />
                        </div>
                        <div>
                            <x-input-label for="first_name" value="Имя" />
                            <x-text-input id="first_name" name="first_name" class="block mt-1 w-full" required />
                        </div>
                        <div>
                            <x-input-label for="middle_name" value="Отчество" />
                            <x-text-input id="middle_name" name="middle_name" class="block mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Телефон" />
                            <x-text-input id="phone" name="phone" class="block mt-1 w-full" />
                        </div>
                    </div>

                    <!-- Динамические поля -->
                    @foreach($templateFields as $field)
                        <div class="mt-4">
                            <x-input-label :for="'field_'.$field->id" :value="$field->name" />
                            @if($field->type == 'textarea')
                                <textarea name="fields[{{ $field->id }}]"
                                          id="field_{{ $field->id }}"
                                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                          rows="3">{{ old('fields.'.$field->id) }}</textarea>
                            @else
                                <x-text-input id="field_{{ $field->id }}" name="fields[{{ $field->id }}]"
                                              class="block mt-1 w-full" value="{{ old('fields.'.$field->id) }}" />
                            @endif
                        </div>
                    @endforeach

                    <!-- Загрузка фото -->
                    <div class="mt-6">
                        <x-input-label for="photo" value="Фото сеанса" />
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline py-2">← Назад</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Сохранить клиента и сеанс
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>