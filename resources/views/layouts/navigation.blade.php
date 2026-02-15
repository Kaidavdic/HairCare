<nav x-data="{ open: false }" class="navbar bg-base-100 shadow-sm border-b border-base-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto" />
                    <span class="font-semibold text-lg hidden sm:inline">
                        {{ config('app.name', 'HairCare') }}
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:flex">
                    <a href="{{ route('home') }}"
                        class="btn btn-ghost btn-sm {{ request()->routeIs('home') ? 'btn-active' : '' }}">
                        {{ __('Saloni') }}
                    </a>

                    @if (Auth::user()?->isSalonOwner())
                        <a href="{{ route('owner.dashboard') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('owner.dashboard') ? 'btn-active' : '' }}">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('owner.salon.edit') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('owner.salon.*') ? 'btn-active' : '' }}">
                            {{ __('Moj salon') }}
                        </a>
                        <a href="{{ route('owner.services.index') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('owner.services.*') ? 'btn-active' : '' }}">
                            {{ __('Usluge') }}
                        </a>
                        <a href="{{ route('owner.appointments.index') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('owner.appointments.*') ? 'btn-active' : '' }}">
                            {{ __('Termini salona') }}
                        </a>
                    @endif

                    @if (Auth::user()?->isClient())
                        <a href="{{ route('appointments.index') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('appointments.*') ? 'btn-active' : '' }}">
                            {{ __('Moji termini') }}
                        </a>
                    @endif

                    @if (Auth::user()?->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-active' : '' }}">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('admin.salons.pending') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('admin.salons.*') ? 'btn-active' : '' }}">
                            {{ __('Saloni') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('admin.users.*') ? 'btn-active' : '' }}">
                            {{ __('Korisnici') }}
                        </a>
                        <a href="{{ route('admin.notifications.index') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('admin.notifications.*') ? 'btn-active' : '' }}">
                            {{ __('Globalna obaveštenja') }}
                        </a>
                        <a href="{{ route('admin.settings.edit') }}"
                            class="btn btn-ghost btn-sm {{ request()->routeIs('admin.settings.*') ? 'btn-active' : '' }}">
                            {{ __('Postavke') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">
                            {{ __('Prijava') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                {{ __('Registracija') }}
                            </a>
                        @endif
                    @endguest
                @endif

                @auth
                    <div class="hidden sm:flex sm:items-center gap-2">
                        <!-- Messages Icon -->
                        <a href="{{ route('messages.index') }}" class="btn btn-ghost btn-circle btn-sm relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            @if(auth()->user()->unreadMessagesCount() > 0)
                                <span class="badge badge-error badge-xs absolute -top-1 -right-1">{{ auth()->user()->unreadMessagesCount() }}</span>
                            @endif
                        </a>

                        <!-- Notifications (Bell) Icon -->
                        <a href="{{ route('notifications.index') }}" class="btn btn-ghost btn-circle btn-sm relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotificationsCount() > 0)
                                <span class="badge badge-error badge-xs absolute -top-1 -right-1">{{ auth()->user()->unreadNotificationsCount() }}</span>
                            @endif
                        </a>

                        <!-- Profile Link -->
                        <a href="{{ route('profile.edit') }}" class="btn btn-ghost btn-sm {{ request()->routeIs('profile.*') ? 'btn-active' : '' }}">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                    </div>
                    
                    <!-- Visible logout button -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline-block ml-2">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('Odjavi se') }}
                        </button>
                    </form>
                @endauth

                <!-- Hamburger -->
                <div class="sm:hidden">
                    <button @click="open = ! open" class="btn btn-ghost btn-sm">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Saloni') }}
            </x-responsive-nav-link>

            @if (Auth::user()?->isSalonOwner())
                <x-responsive-nav-link :href="route('owner.salon.edit')" :active="request()->routeIs('owner.salon.*')">
                    {{ __('Moj salon') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.services.index')"
                    :active="request()->routeIs('owner.services.*')">
                    {{ __('Usluge') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.appointments.index')"
                    :active="request()->routeIs('owner.appointments.*')">
                    {{ __('Termini salona') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()?->isClient())
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                    {{ __('Moji termini') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()?->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Kontrolna tabla') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.salons.pending')" :active="request()->routeIs('admin.salons.pending')">
                    {{ __('Saloni') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Korisnici') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.notifications.index')" :active="request()->routeIs('admin.notifications.*')">
                    {{ __('Obaveštenja (Admin)') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-base-200">
                <div class="px-4">
                    <div class="font-medium text-base text-base-content">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-base-content/70">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('messages.index')">
                        <div class="flex items-center justify-between">
                            {{ __('Poruke') }}
                            @if(auth()->user()->unreadMessagesCount() > 0)
                                <span class="badge badge-error badge-sm">{{ auth()->user()->unreadMessagesCount() }}</span>
                            @endif
                        </div>
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('notifications.index')">
                        <div class="flex items-center justify-between">
                            {{ __('Obaveštenja') }}
                            @if(auth()->user()->unreadNotificationsCount() > 0)
                                <span class="badge badge-error badge-sm">{{ auth()->user()->unreadNotificationsCount() }}</span>
                            @endif
                        </div>
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                            {{ __('Odjavi se') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>