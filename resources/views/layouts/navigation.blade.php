<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex items-center space-x-8">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center space-x-5">

                <!-- Notifications -->
                <div x-data="{ open: false }" class="relative">

                    <!-- Bell -->
                    <button @click="open = !open"
                            class="relative p-2 rounded-full hover:bg-gray-100 transition">

                        🔔

                        @php
                            $notifications = auth()->user()->unreadNotifications;
                        @endphp

                        @if($notifications->count())
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                {{ $notifications->count() }}
                            </span>
                        @endif

                    </button>

                    <!-- Dropdown -->
                    <div x-show="open"
                         @click.outside="open = false"
                         class="absolute right-0 mt-3 w-72 bg-white shadow-xl rounded-lg border z-50">

                        <div class="p-3 border-b font-semibold text-gray-700">
                            Notifications
                        </div>

                        <div class="max-h-60 overflow-y-auto">

                            @forelse($notifications as $note)
                                <div class="p-3 text-sm hover:bg-gray-50 border-b">
                                    {{ $note->data['message'] ?? 'New Notification' }}
                                </div>
                            @empty
                                <div class="p-3 text-sm text-gray-500 text-center">
                                    No notifications
                                </div>
                            @endforelse

                        </div>

                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="hidden sm:flex sm:items-center">

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-100 transition">

                                <span class="text-sm text-gray-700">
                                    {{ Auth::user()->name }}
                                </span>

                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>

                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>

                    </x-dropdown>

                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex sm:hidden">
                    <button @click="open = !open"
                            class="p-2 rounded-md text-gray-500 hover:bg-gray-100">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{'hidden': open}" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !open}" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>

                    </button>
                </div>

            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t">

        <div class="px-4 py-2 space-y-2">

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

        </div>

    </div>

</nav>
