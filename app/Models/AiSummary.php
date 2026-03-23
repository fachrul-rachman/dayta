<?php

namespace App\Models;

use App\Enums\ScopeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'scope_type',
        'scope_id',
        'date_from',
        'date_to',
        'summary',
        'filters',
        'generated_by_user_id',
    ];

    protected $casts = [
        'scope_type' => ScopeType::class,
        'date_from' => 'date',
        'date_to' => 'date',
        'filters' => 'array',
    ];

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }
}
