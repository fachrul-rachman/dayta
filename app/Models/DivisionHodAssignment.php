<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionHodAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'hod_user_id',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_user_id');
    }
}
