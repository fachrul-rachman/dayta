<?php

namespace App\Models;

use App\Enums\DailyPlanStatus;
use App\Enums\DailyRealizationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'division_id',
        'entry_date',
        'plan_status',
        'realization_status',
        'plan_submitted_at',
        'realization_submitted_at',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'plan_status' => DailyPlanStatus::class,
        'realization_status' => DailyRealizationStatus::class,
        'plan_submitted_at' => 'datetime',
        'realization_submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function items()
    {
        return $this->hasMany(DailyEntryItem::class);
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'scope');
    }
}

