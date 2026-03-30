<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.card
            title="Today"
            :value="now()->toFormattedDateString()"
        />
        <x-dashboard.card
            title="Plan Status"
            :value="$todayEntry?->plan_status?->name ?? 'LOCKED'"
            helper="Today's plan window status"
            :href="route('manager.daily-entry')"
            :variant="match($todayEntry?->plan_status?->name) {
                'SUBMITTED' => 'success',
                'DRAFT' => 'warning',
                'OPEN' => 'warning',
                default => 'default',
            }"
        />
        <x-dashboard.card
            title="Realization Status"
            :value="$todayEntry?->realization_status?->name ?? 'LOCKED'"
            helper="Today's realization window status"
            :href="route('manager.daily-entry')"
            :variant="match($todayEntry?->realization_status?->name) {
                'SUBMITTED' => 'success',
                'DRAFT' => 'warning',
                'OPEN' => 'warning',
                default => 'default',
            }"
        />
        <x-dashboard.card
            title="Latest History"
            :value="$latestEntry?->entry_date?->toFormattedDateString() ?? '—'"
            helper="Most recent submitted entry"
            :href="route('manager.history')"
        />
        <x-dashboard.card
            title="Personal Flags"
            :value="$flagsCount"
            helper="Flags linked to your entries"
            :href="route('manager.history')"
            :variant="$flagsCount > 0 ? 'danger' : 'default'"
        />
    </div>
</div>
