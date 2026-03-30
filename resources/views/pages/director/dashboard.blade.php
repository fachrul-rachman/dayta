<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3 xl:grid-cols-5">
        <x-dashboard.card
            title="Company Health"
            :value="$companySubmissionRate !== null ? $companySubmissionRate.'%' : '—'"
            helper="Submission completion today"
            :href="route('director.company')"
            :variant="match(true) {
                $companySubmissionRate === null => 'default',
                $companySubmissionRate >= 80 => 'success',
                $companySubmissionRate >= 50 => 'warning',
                default => 'danger',
            }"
        ></x-dashboard.card>
        <x-dashboard.card
            title="Company Flags"
            :value="$companyFlags"
            helper="All severity levels"
            :href="route('director.company')"
            :variant="$companyFlags > 0 ? 'danger' : 'default'"
        ></x-dashboard.card>
        <x-dashboard.card
            title="Division Requiring Attention"
            :value="$divisionNeedingAttention['division']->name ?? '—'"
            :helper="$divisionNeedingAttention
                ? ($divisionNeedingAttention['flags'].' flags · '.(($divisionNeedingAttention['submission_rate'] ?? null) !== null ? $divisionNeedingAttention['submission_rate'].'% submitted' : 'no reporters'))
                : 'No division highlighted today'"
            :href="route('director.divisions')"
            :variant="$divisionNeedingAttention ? 'warning' : 'default'"
        ></x-dashboard.card>
        <x-dashboard.card
            title="Company Overview"
            helper="Open full company monitoring"
            :href="route('director.company')"
        ></x-dashboard.card>
        <x-dashboard.card
            title="Division Overview"
            helper="Compare divisions side by side"
            :href="route('director.divisions')"
        ></x-dashboard.card>
    </div>
</div>
