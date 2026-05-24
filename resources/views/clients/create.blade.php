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
                      x-data="clientForm()">
                    @csrf

                    <!-- Выбор шаблона -->
                    <div class="mb-4">
                        <x-input-label for="template_id" value="Шаблон" />
                        <select name="template_id" id="template_id" x-model="templateId" required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Выберите шаблон</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" @selected(old('template_id') == $template->id)>{{ $template->name }}</option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                            <x-text-input id="phone_display" type="text" x-model="phoneDisplay" @input="formatPhone"
                                          class="block mt-1 w-full" placeholder="+7 (___) ___-__-__" />
                            <input type="hidden" name="phone" x-model="phoneRaw" />
                        </div>
                    </div>

                    <!-- Динамические поля шаблона -->
                    <div id="dynamic-fields" x-html="fieldsHtml"></div>

                    <!-- Фото До -->
                    <div class="mt-6">
                        <x-input-label for="photo_before" value="Фото До" />
                        <input type="file" name="photo_before" id="photo_before" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <!-- Фото После -->
                    <div class="mt-4">
                        <x-input-label for="photo_after" value="Фото После" />
                        <input type="file" name="photo_after" id="photo_after" accept="image/*"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
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

    <!-- Скрипты: телефонная маска + загрузка полей шаблона -->
    <script>
        function clientForm() {
            return {
                templateId: '{{ old('template_id') }}',
                fieldsHtml: '',
                phoneRaw: '{{ old('phone') }}',
                phoneDisplay: '',

                init() {
                    if (this.phoneRaw) {
                        this.phoneDisplay = this.format(this.phoneRaw);
                    }
                    if (this.templateId) {
                        this.loadFields();
                    }
                    this.$watch('templateId', () => this.loadFields());
                },

                formatPhone() {
                    let value = this.phoneDisplay;
                    if (value.startsWith('+')) {
                        value = '+' + value.slice(1).replace(/\D/g, '');
                    } else {
                        value = value.replace(/\D/g, '');
                        if (value.length > 0) value = '+' + value;
                    }
                    const digits = value.replace(/[^\d+]/g, '');
                    const clean = digits.startsWith('+') ? digits.slice(1).replace(/\D/g, '') : digits.replace(/\D/g, '');
                    const truncated = clean.substring(0, 11);
                    this.phoneRaw = truncated;
                    this.phoneDisplay = this.format(truncated);
                },

                format(digits) {
                    if (!digits) return '';
                    let d = digits;
                    if (d.startsWith('7')) {
                        d = d.substring(1);
                    }
                    let formatted = '+7 ';
                    if (d.length > 0) formatted += '(' + d.substring(0, 3);
                    if (d.length > 3) formatted += ') ' + d.substring(3, 6);
                    if (d.length > 6) formatted += '-' + d.substring(6, 8);
                    if (d.length > 8) formatted += '-' + d.substring(8, 10);
                    return formatted;
                },

                async loadFields() {
                    if (this.templateId) {
                        const response = await fetch('/templates/' + this.templateId + '/fields-data');
                        this.fieldsHtml = await response.text();
                    } else {
                        this.fieldsHtml = '';
                    }
                }
            }
        }
    </script>
</x-app-layout>