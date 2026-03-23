<?php

namespace App\Models;

use App\Enums\BigRockStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigRock extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'title',
        'description',
        'status',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'status' => BigRockStatus::class,
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function dailyEntryItems()
    {
        return $this->hasMany(DailyEntryItem::class);
    }
}
