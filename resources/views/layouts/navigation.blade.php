<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-1">
                        <img src="{{ asset('images/logo.svg') }}" alt="Учти" class="w-8 h-8">
                        <span class="text-xl font-bold text-indigo-700 tracking-tight">Учти</span>
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-3">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Выйти из аккаунта
                        </a>
                    </form>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <div class="px-4 py-2 text-sm text-gray-500">Привет, {{ Auth::user()->name }}</div>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Профиль') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Выйти из аккаунта') }}
                    </x-responsive-nav-link>
                </form>
            @endauth
        </div>
    </div>
</nav>