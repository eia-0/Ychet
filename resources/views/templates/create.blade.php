<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Новый шаблон') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('templates.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="name" value="Название шаблона" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" required />
                    </div>
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wider hover:bg-indigo-700 transition shadow-md">
                        Создать
                    </button>
                </form>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('templates.index') }}" class="text-gray-600 hover:underline text-sm">← Назад</a>
            </div>
        </div>
    </div>
</x-app-layout>