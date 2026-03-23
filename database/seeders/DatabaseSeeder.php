<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\BigRock;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use App\Models\Division;
use App\Models\Flag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sales = Division::create(['name' => 'Sales', 'is_active' => true]);
        $ops = Division::create(['name' => 'Operations', 'is_active' => true]);

        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->director()->create([
            'name' => 'Director',
            'email' => 'director@example.com',
        ]);

        $salesHod = User::factory()->hod($sales)->create([
            'name' => 'Sales HoD',
            'email' => 'hod.sales@example.com',
        ]);

        User::factory()->hod($ops)->create([
            'name' => 'Operations HoD',
            'email' => 'hod.ops@example.com',
        ]);

        $salesManagers = User::factory()->manager($sales)->count(3)->create();
        User::factory()->manager($ops)->count(3)->create();

        // Seed Big Rocks for Sales division
        $salesBigRocks = [
            ['title' => 'Increase qualified pipeline for Q1', 'description' => 'Expand outreach to high-intent accounts and strengthen follow-up discipline.'],
            ['title' => 'Improve lead-to-opportunity conversion rate', 'description' => 'Tighten discovery calls and ensure next steps are always agreed.'],
            ['title' => 'Protect top 20 strategic accounts', 'description' => 'Maintain regular contact and proactively address renewal risks.'],
        ];

        $bigRocks = collect($salesBigRocks)->map(function (array $data) use ($sales) {
            return BigRock::create([
                'division_id' => $sales->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => \App\Enums\BigRockStatus::Active,
                'period_start' => now()->startOfQuarter(),
                'period_end' => now()->endOfQuarter(),
            ]);
        });

        // Seed recent daily entries for each Sales manager
        $days = 10;
        foreach ($salesManagers as $manager) {
            for ($i = 0; $i < $days; $i++) {
                $date = now()->subDays($i)->toDateString();

                $entry = DailyEntry::create([
                    'user_id' => $manager->id,
                    'division_id' => $sales->id,
                    'entry_date' => $date,
                    'plan_status' => \App\Enums\DailyPlanStatus::Submitted,
                    'realization_status' => \App\Enums\DailyRealizationStatus::Submitted,
                    'plan_submitted_at' => now()->subDays($i)->setTime(9, 0),
                    'realization_submitted_at' => now()->subDays($i)->setTime(18, 0),
                ]);

                // Planned work: mix of Big Rock and operational items
                DailyEntryItem::create([
                    'daily_entry_id' => $entry->id,
                    'description' => 'Prospect new high-intent accounts in the mid-market segment.',
                    'work_type' => \App\Enums\WorkType::Operational,
                    'big_rock_id' => null,
                    'planned_hours' => 2,
                    'realized_hours' => 2,
                    'notes' => 'Focused on accounts with active product interest.',
                ]);

                DailyEntryItem::create([
                    'daily_entry_id' => $entry->id,
                    'description' => 'Follow up and progress opportunities linked to key Big Rocks.',
                    'work_type' => \App\Enums\WorkType::BigRock,
                    'big_rock_id' => $bigRocks->random()->id,
                    'planned_hours' => 3,
                    'realized_hours' => 3,
                    'notes' => 'Prepared tailored follow-up based on customer context.',
                ]);

                DailyEntryItem::create([
                    'daily_entry_id' => $entry->id,
                    'description' => 'Handle urgent customer requests and inbound questions.',
                    'work_type' => \App\Enums\WorkType::AdHoc,
                    'big_rock_id' => null,
                    'planned_hours' => 1,
                    'realized_hours' => 1.5,
                    'notes' => 'Several requests required coordination with solutions team.',
                ]);

                // Occasionally create a division-level flag
                if ($i % 3 === 0) {
                    Flag::create([
                        'scope_type' => 'division',
                        'scope_id' => $sales->id,
                        'severity' => \App\Enums\FlagSeverity::Medium,
                        'flagged_at' => now()->subDays($i),
                        'title' => 'Pipeline quality risk for the month',
                        'details' => 'Volume targets are on track but mix is skewed towards late-stage deals with higher risk.',
                    ]);
                }
            }
        }
    }
}
