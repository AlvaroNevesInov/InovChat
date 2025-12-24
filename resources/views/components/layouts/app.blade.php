<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InovChat') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <h1 class="text-2xl font-bold text-gray-900">InovChat</h1>
                        @auth
                            <div class="flex gap-4">
                                <a href="{{ route('chat') }}" class="text-sm font-medium {{ request()->routeIs('chat') ? 'text-blue-600' : 'text-gray-700 hover:text-gray-900' }}">
                                    Chat
                                </a>
                                <a href="{{ route('contacts') }}" class="text-sm font-medium {{ request()->routeIs('contacts') ? 'text-blue-600' : 'text-gray-700 hover:text-gray-900' }}">
                                    Contactos
                                </a>
                                <a href="{{ route('profile.edit') }}" class="text-sm font-medium {{ request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-gray-700 hover:text-gray-900' }}">
                                    Perfil
                                </a>
                            </div>
                        @endauth
                    </div>
                    <div class="flex items-center gap-3">
                        @auth
                            <!-- Settings Dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div class="flex items-center gap-2">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-semibold">
                                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>{{ Auth::user()->name }}</div>
                                        </div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-6">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>
