<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Добавить клиента') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data"
                      x-data="phoneMask()">
                    @csrf

                    <!-- ФИО и телефон -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
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
                            <x-input-label for="phone_display" value="Телефон" />
                            <!-- Поле с маской – визуальное, отправляем значение через скрытое поле -->
                            <x-text-input id="phone_display" type="text" x-model="phoneDisplay" @input="formatPhone"
                                          class="block mt-1 w-full" placeholder="+7 (___) ___-__-__" />
                            <input type="hidden" name="phone" x-model="phoneRaw" />
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
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition shadow-md">
                            Сохранить клиента и сеанс
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Скрипт для телефонной маски -->
    <script>
        function phoneMask() {
            return {
                phoneRaw: '{{ old('phone') }}',   // чистое значение (только цифры)
                phoneDisplay: '',                 // отформатированное отображение

                init() {
                    // Если есть старое значение (например после ошибки валидации), отображаем его
                    if (this.phoneRaw) {
                        this.phoneDisplay = this.format(this.phoneRaw);
                    }
                },

                formatPhone() {
                    // Удаляем всё, кроме цифр и ведущего "+"
                    let value = this.phoneDisplay;
                    // Разрешаем только цифры и ведущий "+"
                    if (value.startsWith('+')) {
                        value = '+' + value.slice(1).replace(/\D/g, '');
                    } else {
                        value = value.replace(/\D/g, '');
                        if (value.length > 0) value = '+' + value;
                    }

                    // Оставляем только цифры, но сохраняем "+" если есть
                    const digits = value.replace(/[^\d+]/g, '');
                    // Убираем всё кроме цифр, но помним, что может быть "+"
                    const clean = digits.startsWith('+') ? digits.slice(1).replace(/\D/g, '') : digits.replace(/\D/g, '');
                    
                    // Ограничим 11 цифрами (российский номер)
                    const truncated = clean.substring(0, 11);
                    this.phoneRaw = truncated;

                    // Форматируем
                    this.phoneDisplay = this.format(truncated);
                },

                format(digits) {
                    if (!digits) return '';
                    let d = digits;
                    if (d.startsWith('7')) {
                        // убираем первую 7, т.к. маска начинается с +7
                        d = d.substring(1);
                    }
                    let formatted = '+7 ';
                    if (d.length > 0) {
                        formatted += '(' + d.substring(0, 3);
                    }
                    if (d.length > 3) {
                        formatted += ') ' + d.substring(3, 6);
                    }
                    if (d.length > 6) {
                        formatted += '-' + d.substring(6, 8);
                    }
                    if (d.length > 8) {
                        formatted += '-' + d.substring(8, 10);
                    }
                    return formatted;
                }
            }
        }
    </script>
</x-app-layout>