<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Assignment Details') }}
            </h2>
            <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
                {{ __('Assign or update the Head of Division for each active division.') }}
            </p>
            <div class="mt-3 grid grid-cols-1 gap-3 text-xs md:grid-cols-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('Division') }}
                    </label>
                    <select wire:model.defer="division_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        <option value="">{{ __('Select division') }}</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                    @error('division_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('HoD') }}
                    </label>
                    <select wire:model.defer="hod_user_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        <option value="">{{ __('Select HoD') }}</option>
                        @foreach ($hods as $hod)
                            <option value="{{ $hod->id }}">{{ $hod->name }}</option>
                        @endforeach
                    </select>
                    @error('hod_user_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                    <span wire:loading.remove wire:target="save">
                        {{ __('Save Assignment') }}
                    </span>
                    <span wire:loading wire:target="save">
                        {{ __('Saving...') }}
                    </span>
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                {{ __('Current Assignments') }}
            </h3>
            <div class="mt-3 grid gap-2 md:grid-cols-2">
                @forelse ($activeAssignments as $assignment)
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ $assignment->division->name }}
                        </div>
                        <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('HoD:') }} {{ $assignment->hod->name }}
                        </div>
                    </div>
                @empty
                    <p class="text-zinc-500 dark:text-zinc-400">
                        {{ __('No active HoD assignments found.') }}
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
