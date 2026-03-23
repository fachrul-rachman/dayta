<?php

return [
    'summary' => [
        'enabled' => (bool) env('AI_SUMMARY_ENABLED', false),
        'provider' => env('AI_SUMMARY_PROVIDER', 'openai'),
        'api_key' => env('AI_SUMMARY_API_KEY'),
        'base_url' => env('AI_SUMMARY_BASE_URL'),
        'model' => env('AI_SUMMARY_MODEL'),
    ],
];
