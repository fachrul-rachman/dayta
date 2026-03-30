<div class="flex flex-1 flex-col gap-4 rounded-xl">
        @if (! $settings)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-100">
                {{ __('Reporting is currently unavailable. Please contact Admin to configure report settings.') }}
            </div>
        @else
            <div class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                            {{ __('Today') }}
                        </div>
                        <div class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ now($settings->timezone ?? config('app.timezone'))->toFormattedDateString() }}
                        </div>
                    </div>
                    <div class="flex gap-2 text-xs">
                        <span class="inline-flex items-center rounded-lg bg-zinc-100 px-3 py-1.5 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                            {{ __('Plan:') }}
                            <span class="ms-1 inline-flex items-center rounded-md bg-white px-2 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
                                {{ $this->entry?->plan_status?->name ?? 'LOCKED' }}
                            </span>
                        </span>
                        <span class="inline-flex items-center rounded-lg bg-zinc-100 px-3 py-1.5 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                            {{ __('Realization:') }}
                            <span class="ms-1 inline-flex items-center rounded-md bg-white px-2 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
                                {{ $this->entry?->realization_status?->name ?? 'LOCKED' }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="flex rounded-lg bg-zinc-100 p-1 text-sm font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <button
                        type="button"
                        wire:click="switchMode('plan')"
                        class="flex-1 rounded-lg px-3 py-1.5 text-center {{ $mode === 'plan' ? 'bg-white text-zinc-900 shadow-sm dark:bg-zinc-900 dark:text-zinc-50' : '' }}"
                    >
                        {{ __('Plan') }}
                    </button>
                    <button
                        type="button"
                        wire:click="switchMode('realization')"
                        class="flex-1 rounded-lg px-3 py-1.5 text-center {{ $mode === 'realization' ? 'bg-white text-zinc-900 shadow-sm dark:bg-zinc-900 dark:text-zinc-50' : '' }}"
                    >
                        {{ __('Realization') }}
                    </button>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    @php
                        $planEditable = $this->planEditable;
                        $realizationEditable = $this->realizationEditable;
                        $isEditable = $mode === 'plan' ? $planEditable : $realizationEditable;
                    @endphp
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ $mode === 'plan' ? __('Plan Details') : __('Realization Details') }}
                        </h2>
                        @if ($isEditable)
                            <button
                                type="button"
                                wire:click="addItem"
                                wire:loading.attr="disabled"
                                wire:target="addItem"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            >
                                <span wire:loading.remove wire:target="addItem">
                                    {{ __('Add Item') }}
                                </span>
                                <span wire:loading wire:target="addItem">
                                    {{ __('Adding...') }}
                                </span>
                            </button>
                        @endif
                    </div>

                    @error('planItems')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    @error('realizationItems')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div class="mt-2">
                        @if (! $isEditable)
                            <p class="rounded-lg bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                @if ($mode === 'plan')
                                    {{ __('Plan is currently locked based on today\'s reporting window. You can review entries but not edit them right now.') }}
                                @else
                                    {{ __('Realization is currently locked based on today\'s reporting window. You can review entries but not edit them right now.') }}
                                @endif
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-col gap-4">
                        @php
                            $items = $mode === 'plan' ? $planItems : $realizationItems;
                        @endphp

                        @forelse ($items as $index => $item)
                            <div
                                class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800"
                                @if($mode === 'plan')
                                    wire:key="plan-item-{{ $index }}"
                                @else
                                    wire:key="realization-item-{{ $index }}"
                                @endif
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ __('Description') }}
                                        </label>
                                        <textarea
                                            wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.description" : "realizationItems.$index.description" }}"
                                            rows="2"
                                            @readonly(! $isEditable)
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                        ></textarea>
                                        @error(($mode === 'plan' ? "planItems.$index.description" : "realizationItems.$index.description"))<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    @if ($isEditable)
                                        <button
                                            type="button"
                                            wire:click="removeItem({{ $index }})"
                                            class="ml-2 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-200 text-sm text-zinc-700 hover:bg-red-100 hover:text-red-700 dark:bg-zinc-700 dark:text-zinc-100 dark:hover:bg-red-900/50 dark:hover:text-red-300"
                                        >
                                            ×
                                        </button>
                                    @endif
                                </div>

                                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-3">
                                    <div>
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ __('Work Type') }}
                                        </label>
                                        <select
                                            wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.work_type" : "realizationItems.$index.work_type" }}"
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                        >
                                            <option value="big_rock">{{ __('Big Rock') }}</option>
                                            <option value="operational">{{ __('Operational') }}</option>
                                            <option value="ad_hoc">{{ __('Ad Hoc') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ __('Big Rock (optional)') }}
                                        </label>
                                        <select
                                            wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.big_rock_id" : "realizationItems.$index.big_rock_id" }}"
                                            @disabled(! $isEditable)
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                        >
                                            <option value="">{{ __('None') }}</option>
                                            @foreach ($availableBigRocks as $bigRock)
                                                <option value="{{ $bigRock->id }}">{{ $bigRock->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ $mode === 'plan' ? __('Planned Hours') : __('Realized Hours') }}
                                        </label>
                                        <input
                                            type="number"
                                            step="0.25"
                                            min="0"
                                            wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.planned_hours" : "realizationItems.$index.realized_hours" }}"
                                            @disabled(! $isEditable)
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                        />
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                        {{ __('Notes (optional)') }}
                                    </label>
                                    <textarea
                                        wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.notes" : "realizationItems.$index.notes" }}"
                                        rows="2"
                                        @readonly(! $isEditable)
                                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                    ></textarea>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <div>
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ __('Attachments (optional)') }}
                                        </label>
                                        <input
                                            type="file"
                                            multiple
                                            wire:model="{{ $mode === 'plan' ? "planUploads.$index" : "realizationUploads.$index" }}"
                                            @disabled(! $isEditable)
                                            class="mt-1 block w-full text-xs text-zinc-700 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-100 dark:hover:file:bg-zinc-700"
                                        />
                                        @error($mode === 'plan' ? "planUploads.$index.*" : "realizationUploads.$index.*")
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @php
                                        $itemId = $mode === 'plan' ? ($planItems[$index]['id'] ?? null) : ($realizationItems[$index]['id'] ?? null);
                                        $attachments = collect($this->entry?->items ?? [])->firstWhere('id', $itemId)?->attachments ?? collect();
                                    @endphp
                                    @if ($attachments->isNotEmpty())
                                        <div class="rounded-lg bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                            <p class="mb-1 font-medium">
                                                {{ __('Existing attachments') }}
                                            </p>
                                            <ul class="space-y-0.5">
                                                @foreach ($attachments as $attachment)
                                                    <li>
                                                        <a
                                                            href="{{ \Illuminate\Support\Facades\Storage::disk(config('reporting.attachments_disk'))->url($attachment->file_path) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline dark:text-indigo-400"
                                                        >
                                                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                                                            {{ $attachment->file_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No items yet. Use "Add Item" to capture today\'s work.') }}
                            </p>
                        @endforelse
                    </div>

                    @if ($this->entry && $isEditable)
                        <div class="mt-4 flex flex-wrap justify-end gap-2">
                            <button
                                type="button"
                                wire:click="saveDraft"
                                wire:loading.attr="disabled"
                                wire:target="saveDraft"
                                class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-800 hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                            >
                                <span wire:loading.remove wire:target="saveDraft">
                                    {{ __('Save Draft') }}
                                </span>
                                <span wire:loading wire:target="saveDraft">
                                    {{ __('Saving...') }}
                                </span>
                            </button>
                            <button
                                type="button"
                                wire:click="submit"
                                wire:loading.attr="disabled"
                                wire:target="submit"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            >
                                <span wire:loading.remove wire:target="submit">
                                    {{ __('Submit') }}
                                </span>
                                <span wire:loading wire:target="submit">
                                    {{ __('Submitting...') }}
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
