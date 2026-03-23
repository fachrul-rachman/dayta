<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-zinc-50 p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div>
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('User Directory') }}
                </h2>
                <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
                    {{ __('Use the form below to create or update user accounts for the reporting platform.') }}
                </p>
            </div>
            <button
                type="button"
                wire:click="create"
                wire:loading.attr="disabled"
                wire:target="create"
                class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                <span wire:loading.remove wire:target="create">
                    {{ __('Create User') }}
                </span>
                <span wire:loading wire:target="create">
                    {{ __('Opening form...') }}
                </span>
            </button>
        </div>

        <div class="grid gap-4 md:grid-cols-[2fr,3fr]">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ $editingId ? __('Edit User') : __('New User') }}
                </h3>
                <div class="mt-3 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Name') }}
                        </label>
                        <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        @error('name')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Email') }}
                        </label>
                        <input type="email" wire:model.defer="email" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        @error('email')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Role') }}
                        </label>
                        <select wire:model.defer="role" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                            @foreach ($roles as $roleOption)
                                <option value="{{ $roleOption->value }}">{{ ucfirst($roleOption->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Division') }}
                        </label>
                        <select wire:model.defer="division_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                            <option value="">{{ __('None') }}</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                        @error('division_id')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ $editingId ? __('Password (leave blank to keep)') : __('Password') }}
                        </label>
                        <input type="password" wire:model.defer="password" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        @error('password')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model.defer="is_active" id="is_active" class="h-3 w-3 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500 dark:border-zinc-600 dark:text-zinc-100">
                        <label for="is_active" class="text-xs text-zinc-700 dark:text-zinc-200">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            <span wire:loading.remove wire:target="save">
                                {{ __('Save') }}
                            </span>
                            <span wire:loading wire:target="save">
                                {{ __('Saving...') }}
                            </span>
                        </button>
                    </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Users') }}
                </h3>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-xs dark:divide-zinc-700">
                        <thead class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            <tr>
                                <th class="px-2 py-1 text-left">{{ __('Name') }}</th>
                                <th class="px-2 py-1 text-left">{{ __('Email') }}</th>
                                <th class="px-2 py-1 text-left">{{ __('Role') }}</th>
                                <th class="px-2 py-1 text-left">{{ __('Division') }}</th>
                                <th class="px-2 py-1 text-left">{{ __('Status') }}</th>
                                <th class="px-2 py-1 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-2 py-1 text-zinc-900 dark:text-zinc-50">{{ $user->name }}</td>
                                    <td class="px-2 py-1 text-zinc-600 dark:text-zinc-200">{{ $user->email }}</td>
                                    <td class="px-2 py-1 text-zinc-600 dark:text-zinc-200">{{ ucfirst($user->role->name) }}</td>
                                    <td class="px-2 py-1 text-zinc-600 dark:text-zinc-200">{{ $user->division?->name ?? '—' }}</td>
                                    <td class="px-2 py-1">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-medium {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                            {{ $user->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-right">
                                        <button
                                            type="button"
                                            wire:click="edit({{ $user->id }})"
                                            class="inline-flex items-center rounded-full border border-zinc-300 px-3 py-0.5 text-[11px] font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-100 dark:hover:bg-zinc-800"
                                        >
                                            {{ __('Edit') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-2 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                        {{ __('No users found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
