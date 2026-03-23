<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3 xl:grid-cols-5">
        <x-dashboard.card
            title="Active Users"
            :value="$activeUsers"
            :href="route('admin.users')"
        />
        <x-dashboard.card
            title="Active Divisions"
            :value="$activeDivisions"
            :href="route('admin.divisions')"
        />
        <x-dashboard.card
            title="HoD Assignment"
            :value="$activeHodAssignments"
            :href="route('admin.hod-assignment')"
        />
        <x-dashboard.card
            title="Report Settings"
            :value="$hasActiveSettings ? 'Configured' : 'Missing'"
            :href="route('admin.report-settings')"
        />
        <x-dashboard.card
            title="Override"
            helper="{{ __('Review and correct reporting data') }}"
            :href="route('admin.override')"
        />
    </div>
</div>
