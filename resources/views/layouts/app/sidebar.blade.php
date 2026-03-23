<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                @php
                    $user = auth()->user();
                @endphp

                @if ($user)
                    @if ($user->role === \App\Enums\UserRole::Manager)
                        <flux:sidebar.group :heading="__('Platform')" class="grid">
                            <flux:sidebar.item icon="layout-grid" :href="route('manager.dashboard')" :current="request()->routeIs('manager.dashboard')" wire:navigate>
                                {{ __('Dashboard') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="calendar-days" :href="route('manager.daily-entry')" :current="request()->routeIs('manager.daily-entry')" wire:navigate>
                                {{ __('Daily Entry') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="archive-box" :href="route('manager.history')" :current="request()->routeIs('manager.history')" wire:navigate>
                                {{ __('History') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @elseif ($user->role === \App\Enums\UserRole::Hod)
                        <flux:sidebar.group :heading="__('Platform')" class="grid">
                            <flux:sidebar.item icon="layout-grid" :href="route('hod.dashboard')" :current="request()->routeIs('hod.dashboard')" wire:navigate>
                                {{ __('Dashboard') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="book-open-text" :href="route('hod.daily-entry')" :current="request()->routeIs('hod.daily-entry')" wire:navigate>
                                {{ __('Daily Entry') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="folder-git-2" :href="route('hod.history')" :current="request()->routeIs('hod.history')" wire:navigate>
                                {{ __('History') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="layout-grid" :href="route('hod.big-rocks')" :current="request()->routeIs('hod.big-rocks')" wire:navigate>
                                {{ __('Big Rocks') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="layout-grid" :href="route('hod.division-entries')" :current="request()->routeIs('hod.division-entries')" wire:navigate>
                                {{ __('Division Entries') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="layout-grid" :href="route('hod.division-summary')" :current="request()->routeIs('hod.division-summary')" wire:navigate>
                                {{ __('Division Summary') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @elseif ($user->role === \App\Enums\UserRole::Director)
                        <flux:sidebar.group :heading="__('Platform')" class="grid">
                            <flux:sidebar.item icon="layout-grid" :href="route('director.dashboard')" :current="request()->routeIs('director.dashboard')" wire:navigate>
                                {{ __('Dashboard') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="book-open-text" :href="route('director.company')" :current="request()->routeIs('director.company')" wire:navigate>
                                {{ __('Company') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="folder-git-2" :href="route('director.divisions')" :current="request()->routeIs('director.divisions')" wire:navigate>
                                {{ __('Divisions') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @elseif ($user->role === \App\Enums\UserRole::Admin)
                        <flux:sidebar.group :heading="__('Platform')" class="grid">
                            <flux:sidebar.item icon="layout-grid" :href="route('admin.home')" :current="request()->routeIs('admin.home')" wire:navigate>
                                {{ __('Admin Home') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                                {{ __('Users') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="building-office" :href="route('admin.divisions')" :current="request()->routeIs('admin.divisions')" wire:navigate>
                                {{ __('Divisions') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="briefcase" :href="route('admin.hod-assignment')" :current="request()->routeIs('admin.hod-assignment')" wire:navigate>
                                {{ __('HoD Assignment') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="adjustments-horizontal" :href="route('admin.report-settings')" :current="request()->routeIs('admin.report-settings')" wire:navigate>
                                {{ __('Report Settings') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="arrow-path" :href="route('admin.override')" :current="request()->routeIs('admin.override')" wire:navigate>
                                {{ __('Override') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="bell-alert" :href="route('admin.notifications')" :current="request()->routeIs('admin.notifications')" wire:navigate>
                                {{ __('Notification History') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
