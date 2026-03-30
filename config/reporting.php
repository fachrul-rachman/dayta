<?php

return [
    'attachments_disk' => env('REPORT_ATTACHMENTS_DISK', env('FILESYSTEM_DISK', 'local')),
    'attachments_path' => env('REPORT_ATTACHMENTS_PATH', 'daily_attachments'),
];

