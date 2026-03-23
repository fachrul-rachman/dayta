<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_user_id',
        'target_type',
        'target_id',
        'field',
        'old_value',
        'new_value',
        'reason',
        'overridden_at',
    ];

    protected $casts = [
        'overridden_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}

