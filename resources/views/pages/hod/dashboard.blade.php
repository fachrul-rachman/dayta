<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3 xl:grid-cols-6">
        <x-dashboard.card
            title="Today"
            :value="now()->toFormattedDateString()"
        />
        <x-dashboard.card
            title="My Plan Status"
            :value="$ownToday?->plan_status?->name ?? 'LOCKED'"
            :href="route('hod.daily-entry')"
            :variant="match($ownToday?->plan_status?->name) {
                'SUBMITTED' => 'success',
                'DRAFT' => 'warning',
                'OPEN' => 'warning',
                default => 'default',
            }"
        />
        <x-dashboard.card
            title="My Realization Status"
            :value="$ownToday?->realization_status?->name ?? 'LOCKED'"
            :href="route('hod.daily-entry')"
            :variant="match($ownToday?->realization_status?->name) {
                'SUBMITTED' => 'success',
                'DRAFT' => 'warning',
                'OPEN' => 'warning',
                default => 'default',
            }"
        />
        <x-dashboard.card
            title="Team Submission"
            :value="$divisionEntriesCount"
            :href="route('hod.division-entries')"
        />
        <x-dashboard.card
            title="Division Flags"
            :value="$divisionFlagsCount"
            :href="route('hod.division-summary')"
            :variant="$divisionFlagsCount > 0 ? 'danger' : 'default'"
        />
        <x-dashboard.card
            title="Big Rock Alignment"
            :href="route('hod.big-rocks')"
        />
    </div>
</div>
