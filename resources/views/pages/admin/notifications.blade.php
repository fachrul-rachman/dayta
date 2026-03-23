<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-zinc-50 p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div>
            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Notification History') }}
            </h2>
            <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">
                {{ __('Review Discord daily alert delivery status, including what was sent and when.') }}
            </p>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="mb-3 flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Status') }}
                </label>
                <select
                    wire:model.live="status"
                    class="mt-1 rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                >
                    <option value="">{{ __('All') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="sent">{{ __('Sent') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                </select>
            </div>
            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                {{ __('Showing up to the 50 most recent notifications.') }}
            </p>
        </div>

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ $notification->reporting_date->format('d M Y') }}
                            </div>
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                {{ __('Divisions') }}: {{ $notification->divisions_count }}
                                • {{ __('People') }}: {{ $notification->people_count }}
                                • {{ __('Findings') }}: {{ $notification->findings_count }}
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1 text-[11px]">
                            @if ($notification->status === 'sent')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-100">
                                    {{ __('Sent') }}
                                </span>
                                @if ($notification->sent_at)
                                    <span class="text-zinc-500 dark:text-zinc-400">
                                        {{ __('Sent at') }} {{ $notification->sent_at->format('d M Y H:i') }}
                                    </span>
                                @endif
                            @elseif ($notification->status === 'failed')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-medium text-red-800 dark:bg-red-900 dark:text-red-100">
                                    {{ __('Failed') }}
                                </span>
                                @if ($notification->failed_at)
                                    <span class="text-zinc-500 dark:text-zinc-400">
                                        {{ __('Failed at') }} {{ $notification->failed_at->format('d M Y H:i') }}
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-100">
                                    {{ __('Pending') }}
                                </span>
                            @endif
                            <span class="text-zinc-500 dark:text-zinc-400">
                                {{ __('Attempts') }}: {{ $notification->attempt_count }}
                            </span>
                        </div>
                    </div>

                    @if ($notification->message)
                        <div class="mt-3">
                            <details class="group rounded-md bg-zinc-900/90 p-2 text-[11px] text-zinc-50 dark:bg-zinc-950">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-2">
                                    <div>
                                        <div class="mb-1 text-[10px] font-medium uppercase tracking-wide text-zinc-400">
                                            {{ __('Message content') }}
                                        </div>
                                        @php
                                            $firstLine = \Illuminate\Support\Str::before($notification->message, "\n");
                                            $rest = \Illuminate\Support\Str::after($notification->message, "\n");
                                        @endphp
                                        <p class="text-[11px] text-zinc-200">
                                            {{ $firstLine }}
                                        </p>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 group-open:rotate-90 transition-transform">
                                        ›
                                    </span>
                                </summary>
                                <pre class="mt-2 whitespace-pre-wrap break-words text-[11px] text-zinc-50">
@if (trim($rest) !== '')
{{ $rest }}
@else
{{ $firstLine }}
@endif
                                </pre>
                            </details>
                        </div>
                    @endif

                    @if ($notification->error_message)
                        <div class="mt-2">
                            <details class="group rounded-md bg-red-50 p-2 text-[11px] text-red-800 dark:bg-red-900/40 dark:text-red-100">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-2">
                                    <div>
                                        <div class="mb-1 text-[10px] font-medium uppercase tracking-wide">
                                            {{ __('Error') }}
                                        </div>
                                        <p class="text-[11px] text-red-800/90 line-clamp-2 dark:text-red-100/90">
                                            {{ \Illuminate\Support\Str::limit($notification->error_message, 200) }}
                                        </p>
                                    </div>
                                    <span class="text-[10px] text-red-500 group-open:rotate-90 transition-transform">
                                        ›
                                    </span>
                                </summary>
                                <p class="mt-2 whitespace-pre-wrap break-words">
                                    {{ $notification->error_message }}
                                </p>
                            </details>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                    {{ __('No notifications have been recorded yet.') }}
                </p>
            @endforelse
        </div>
    </div>
</div>
