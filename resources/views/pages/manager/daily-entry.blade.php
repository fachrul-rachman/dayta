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
                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                            {{ __('Plan:') }} {{ $this->entry?->plan_status?->name ?? 'LOCKED' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                            {{ __('Realization:') }} {{ $this->entry?->realization_status?->name ?? 'LOCKED' }}
                        </span>
                    </div>
                </div>

                <div class="flex rounded-full bg-zinc-100 p-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <button
                        type="button"
                        wire:click="switchMode('plan')"
                        class="flex-1 rounded-full px-3 py-1 text-center {{ $mode === 'plan' ? 'bg-white text-zinc-900 shadow-sm dark:bg-zinc-900 dark:text-zinc-50' : '' }}"
                    >
                        {{ __('Plan') }}
                    </button>
                    <button
                        type="button"
                        wire:click="switchMode('realization')"
                        class="flex-1 rounded-full px-3 py-1 text-center {{ $mode === 'realization' ? 'bg-white text-zinc-900 shadow-sm dark:bg-zinc-900 dark:text-zinc-50' : '' }}"
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
                        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ $mode === 'plan' ? __('Plan Details') : __('Realization Details') }}
                        </h2>
                        @if ($isEditable)
                            <button
                                type="button"
                                wire:click="addItem"
                                class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                {{ __('Add Item') }}
                            </button>
                        @endif
                    </div>

                    @error('planItems')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    @error('realizationItems')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div class="mt-2">
                        @if (! $isEditable)
                            <p class="rounded-lg bg-zinc-50 px-3 py-2 text-[11px] text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                @if ($mode === 'plan')
                                    {{ __('Plan is currently locked based on today’s reporting window. You can review entries but not edit them right now.') }}
                                @else
                                    {{ __('Realization is currently locked based on today’s reporting window. You can review entries but not edit them right now.') }}
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
                                class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800"
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
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                        ></textarea>
                                        @error(($mode === 'plan' ? "planItems.$index.description" : "realizationItems.$index.description"))<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="removeItem({{ $index }})"
                                        class="ml-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-200 text-xs text-zinc-700 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-600"
                                    >
                                        ×
                                    </button>
                                </div>

                                <div class="mt-3 grid grid-cols-1 gap-3 text-xs md:grid-cols-3">
                                    <div>
                                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                            {{ __('Work Type') }}
                                        </label>
                                        <select
                                            wire:model.defer="{{ $mode === 'plan' ? "planItems.$index.work_type" : "realizationItems.$index.work_type" }}"
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
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
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
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
                                            class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
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
                                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                                    ></textarea>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('No items yet. Use “Add Item” to capture today’s work.') }}
                            </p>
                        @endforelse
                    </div>

                    @if ($this->entry && $isEditable)
                        <div class="mt-4 flex flex-wrap justify-end gap-2">
                            <button
                                type="button"
                                wire:click="saveDraft"
                                class="inline-flex items-center rounded-full border border-zinc-300 bg-white px-3 py-1 text-xs font-medium text-zinc-800 hover:bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                            >
                                {{ __('Save Draft') }}
                            </button>
                            <button
                                type="button"
                                wire:click="submit"
                                class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                            >
                                {{ __('Submit') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
