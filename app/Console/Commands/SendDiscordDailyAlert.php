<?php

namespace App\Console\Commands;

use App\Services\DiscordNotificationService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class SendDiscordDailyAlert extends Command
{
    protected $signature = 'discord:send-daily-alert {date? : Reporting date in Y-m-d format (defaults to today)}';

    protected $description = 'Send the daily reporting alert to Discord for the given reporting date.';

    public function handle(DiscordNotificationService $service): int
    {
        $dateInput = $this->argument('date');

        $day = $dateInput
            ? CarbonImmutable::createFromFormat('Y-m-d', $dateInput, config('app.timezone'))
            : CarbonImmutable::now(config('app.timezone'));

        $service->sendDailyAlert($day);

        $this->info('Discord alert processed for '.$day->toDateString());

        return self::SUCCESS;
    }
}
