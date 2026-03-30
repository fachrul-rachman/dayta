<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('home') }}" wire:navigate />

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('home') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                @php
                    $user = auth()->user();
                @endphp

                @if ($user)
                    @if ($user->role === \App\Enums\UserRole::Manager)
                        <flux:sidebar.group :heading="__('Platform')">
                            <flux:sidebar.item icon="squares-2x2" :href="route('manager.dashboard')" :current="request()->routeIs('manager.dashboard')" wire:navigate>
                                {{ __('Dashboard')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clipboard" :href="route('manager.daily-entry')" :current="request()->routeIs('manager.daily-entry')" wire:navigate>
                                {{ __('Daily Entry')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clock" :href="route('manager.history')" :current="request()->routeIs('manager.history')" wire:navigate>
                                {{ __('History')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                    @elseif ($user->role === \App\Enums\UserRole::Hod)
                        <flux:sidebar.group :heading="__('Personal')">
                            <flux:sidebar.item icon="squares-2x2" :href="route('hod.dashboard')" :current="request()->routeIs('hod.dashboard')" wire:navigate>
                                {{ __('Dashboard')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clipboard" :href="route('hod.daily-entry')" :current="request()->routeIs('hod.daily-entry')" wire:navigate>
                                {{ __('Daily Entry')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clock" :href="route('hod.history')" :current="request()->routeIs('hod.history')" wire:navigate>
                                {{ __('History')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                        <flux:sidebar.group :heading="__('Division')">
                            <flux:sidebar.item icon="cube" :href="route('hod.big-rocks')" :current="request()->routeIs('hod.big-rocks')" wire:navigate>
                                {{ __('Big Rocks')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="user-group" :href="route('hod.division-entries')" :current="request()->routeIs('hod.division-entries')" wire:navigate>
                                {{ __('Division Entries')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="presentation-chart-bar" :href="route('hod.division-summary')" :current="request()->routeIs('hod.division-summary')" wire:navigate>
                                {{ __('Division Summary')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                    @elseif ($user->role === \App\Enums\UserRole::Director)
                        <flux:sidebar.group :heading="__('Monitoring')">
                            <flux:sidebar.item icon="squares-2x2" :href="route('director.dashboard')" :current="request()->routeIs('director.dashboard')" wire:navigate>
                                {{ __('Dashboard')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="building-office" :href="route('director.company')" :current="request()->routeIs('director.company')" wire:navigate>
                                {{ __('Company')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="rectangle-group" :href="route('director.divisions')" :current="request()->routeIs('director.divisions')" wire:navigate>
                                {{ __('Divisions')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                    @elseif ($user->role === \App\Enums\UserRole::Admin)
                        <flux:sidebar.group :heading="__('System')">
                            <flux:sidebar.item icon="squares-2x2" :href="route('admin.home')" :current="request()->routeIs('admin.home')" wire:navigate>
                                {{ __('Admin Home')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                                {{ __('Users')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="building-office" :href="route('admin.divisions')" :current="request()->routeIs('admin.divisions')" wire:navigate>
                                {{ __('Divisions')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="user-group" :href="route('admin.hod-assignment')" :current="request()->routeIs('admin.hod-assignment')" wire:navigate>
                                {{ __('HoD Assignment')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>

                        <flux:sidebar.group :heading="__('Operations')">
                            <flux:sidebar.item icon="adjustments-horizontal" :href="route('admin.report-settings')" :current="request()->routeIs('admin.report-settings')" wire:navigate>
                                {{ __('Report Settings')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="shield-exclamation" :href="route('admin.override')" :current="request()->routeIs('admin.override')" wire:navigate>
                                {{ __('Override')  }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="bell" :href="route('admin.notifications')" :current="request()->routeIs('admin.notifications')" wire:navigate>
                                {{ __('Notification History')  }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
