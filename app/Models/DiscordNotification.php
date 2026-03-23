<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporting_date',
        'status',
        'channel',
        'message',
        'divisions_count',
        'people_count',
        'findings_count',
        'attempt_count',
        'sent_at',
        'failed_at',
        'error_message',
    ];

    protected $casts = [
        'reporting_date' => 'date',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
    ];
}

