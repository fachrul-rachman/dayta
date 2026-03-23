<?php

namespace App\Console\Commands;

use App\Services\FlagEvaluationService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class EvaluateFlagsForDay extends Command
{
    protected $signature = 'flags:evaluate-day {date? : Reporting date in Y-m-d format (defaults to today)}';

    protected $description = 'Evaluate rule-based flags for a given reporting date.';

    public function handle(FlagEvaluationService $service): int
    {
        $dateInput = $this->argument('date');

        $day = $dateInput
            ? CarbonImmutable::createFromFormat('Y-m-d', $dateInput, config('app.timezone'))
            : CarbonImmutable::now(config('app.timezone'));

        $service->evaluateForDay($day);

        $this->info('Flags evaluated for '.$day->toDateString());

        return self::SUCCESS;
    }
}
