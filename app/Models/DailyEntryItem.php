<?php

namespace App\Models;

use App\Enums\WorkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_entry_id',
        'description',
        'work_type',
        'big_rock_id',
        'planned_hours',
        'realized_hours',
        'notes',
    ];

    protected $casts = [
        'work_type' => WorkType::class,
        'planned_hours' => 'float',
        'realized_hours' => 'float',
    ];

    public function dailyEntry()
    {
        return $this->belongsTo(DailyEntry::class);
    }

    public function bigRock()
    {
        return $this->belongsTo(BigRock::class);
    }

    public function attachments()
    {
        return $this->hasMany(DailyEntryItemAttachment::class, 'daily_entry_item_id');
    }
}
