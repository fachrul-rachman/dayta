<?php

return [
    'enabled' => env('DISCORD_NOTIFICATIONS_ENABLED', false),
    'webhook_url' => env('DISCORD_WEBHOOK_URL'),
    'timeout' => (int) env('DISCORD_NOTIFICATION_TIMEOUT', 5),
];
