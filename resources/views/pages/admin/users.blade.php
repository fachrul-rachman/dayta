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
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    wire:click="openImport"
                    wire:loading.attr="disabled"
                    wire:target="openImport"
                    class="inline-flex items-center rounded-full border border-zinc-300 bg-white px-3 py-1.5 text-xs font-medium text-zinc-800 hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-50 dark:hover:bg-zinc-700"
                >
                    <span wire:loading.remove wire:target="openImport">
                        {{ __('Upload Users') }}
                    </span>
                    <span wire:loading wire:target="openImport">
                        {{ __('Opening...') }}
                    </span>
                </button>
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
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                            {{ __('Users') }}
                        </h3>
                        <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                            {{ __('Filter by role, division, status, or search by name/email.') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-[11px]">
                        <div>
                            <label class="block text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
                                {{ __('Role') }}
                            </label>
                            <select
                                wire:model.live="filterRole"
                                class="mt-0.5 rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                            >
                                <option value="">{{ __('All') }}</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->value }}">{{ ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
                                {{ __('Division') }}
                            </label>
                            <select
                                wire:model.live="filterDivisionId"
                                class="mt-0.5 rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                            >
                                <option value="">{{ __('All') }}</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
                                {{ __('Status') }}
                            </label>
                            <select
                                wire:model.live="filterStatus"
                                class="mt-0.5 rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                            >
                                <option value="">{{ __('All') }}</option>
                                <option value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                        <div class="w-36">
                            <label class="block text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
                                {{ __('Search') }}
                            </label>
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="{{ __('Name or email') }}"
                                class="mt-0.5 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                            >
                        </div>
                    </div>
                </div>

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

                <div class="mt-3 flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400">
                    <div>
                        {{ __('Showing') }}
                        <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                            {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }}
                        </span>
                        {{ __('of') }}
                        <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                            {{ $users->total() }}
                        </span>
                        {{ __('users') }}
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

        <flux:modal
            name="users-bulk-import"
            class="max-w-4xl"
            wire:model="showImportModal"
        >
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                        {{ __('Upload Users') }}
                    </h3>
                    <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
                        {{ __('Upload a CSV or Excel file to create or update users in bulk. A preview will be shown before any changes are applied.') }}
                    </p>
                </div>

                <form wire:submit.prevent="previewImport" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('File') }}
                        </label>
                        <input
                            type="file"
                            wire:model="importFile"
                            class="mt-1 block w-full text-xs text-zinc-900 file:mr-2 file:rounded-full file:border-0 file:bg-zinc-900 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-zinc-800 dark:text-zinc-50 dark:file:bg-zinc-100 dark:file:text-zinc-900 dark:hover:file:bg-zinc-200"
                        >
                        @error('importFile')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                            {{ __('Expected columns (header row): name, email, role, division_name (for Manager/HoD), is_active, password') }}
                        </p>
                        <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                            <a href="{{ route('admin.users.import-template') }}" class="underline hover:text-zinc-700 dark:hover:text-zinc-200">
                                {{ __('Download example template') }}
                            </a>
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeImport"
                            class="inline-flex items-center rounded-full border border-zinc-300 px-4 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-100 dark:hover:bg-zinc-800"
                        >
                            {{ __('Cancel') }}
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="previewImport,importFile"
                            class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            <span wire:loading.remove wire:target="previewImport,importFile">
                                {{ __('Preview Import') }}
                            </span>
                            <span wire:loading wire:target="previewImport,importFile">
                                {{ __('Processing...') }}
                            </span>
                        </button>
                    </div>
                </form>

                @if ($importTotal > 0)
                    <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-3 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex flex-wrap items-center gap-3">
                            <div>
                                <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ __('Summary') }}:</span>
                                <span class="ml-1 text-zinc-700 dark:text-zinc-200">
                                    {{ __('Total') }} {{ $importTotal }} &bull;
                                    {{ __('Creates') }} {{ $importCreates }} &bull;
                                    {{ __('Updates') }} {{ $importUpdates }} &bull;
                                    {{ __('Errors') }} {{ $importErrors }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 max-h-80 overflow-auto rounded-xl border border-zinc-200 bg-white text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-900">
                                <tr class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                    <th class="px-3 py-2 text-left">{{ __('Row') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Name') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Email') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Role') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Division') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Is Active') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Action') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('Error') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach ($importRows as $row)
                                    <tr class="align-top">
                                        <td class="px-3 py-2 text-[11px] text-zinc-500 dark:text-zinc-400">
                                            {{ $row['row'] ?? '' }}
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-zinc-800 dark:text-zinc-100">
                                            {{ $row['name'] ?? '' }}
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-zinc-700 dark:text-zinc-200">
                                            {{ $row['email'] ?? '' }}
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-zinc-700 dark:text-zinc-200">
                                            {{ ucfirst($row['role'] ?? '') }}
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-zinc-700 dark:text-zinc-200">
                                            {{ $row['division_name'] ?? '' }}
                                        </td>
                                        <td class="px-3 py-2 text-[11px]">
                                            @if (isset($row['is_active']))
                                                @if ($row['is_active'])
                                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-100">
                                                        {{ __('Active') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                                        {{ __('Inactive') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-zinc-700 dark:text-zinc-200">
                                            @if (($row['status'] ?? null) === 'ok')
                                                @if (($row['action'] ?? null) === 'create')
                                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100">
                                                        {{ __('Create') }}
                                                    </span>
                                                @elseif(($row['action'] ?? null) === 'update')
                                                    <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:bg-sky-900/40 dark:text-sky-100">
                                                        {{ __('Update') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-[11px]">
                                            @if (($row['status'] ?? null) === 'ok')
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100">
                                                    {{ __('OK') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-medium text-red-700 dark:bg-red-900/40 dark:text-red-100">
                                                    {{ __('Error') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-[11px] text-red-600 dark:text-red-400">
                                            {{ $row['error'] ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeImport"
                            class="inline-flex items-center rounded-full border border-zinc-300 px-4 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-100 dark:hover:bg-zinc-800"
                        >
                            {{ __('Close') }}
                        </button>
                        <button
                            type="button"
                            wire:click="commitImport"
                            wire:loading.attr="disabled"
                            wire:target="commitImport"
                            class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            <span wire:loading.remove wire:target="commitImport">
                                {{ __('Apply Valid Rows') }}
                            </span>
                            <span wire:loading wire:target="commitImport">
                                {{ __('Applying...') }}
                            </span>
                        </button>
                    </div>
                @endif

                @if ($importCommitted)
                    <p class="mt-2 text-[11px] text-emerald-700 dark:text-emerald-300">
                        {{ __('Import completed. Users have been updated based on valid rows.') }}
                    </p>
                @endif
            </div>
        </flux:modal>
    </div>
</div>
