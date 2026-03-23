<?php

namespace App\Models;

use App\Enums\FlagSeverity;
use App\Enums\ScopeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    use HasFactory;

    protected $fillable = [
        'scope_type',
        'scope_id',
        'severity',
        'flagged_at',
        'title',
        'details',
    ];

    protected $casts = [
        'scope_type' => ScopeType::class,
        'severity' => FlagSeverity::class,
        'flagged_at' => 'datetime',
    ];

    public function scope()
    {
        return $this->morphTo();
    }
}

