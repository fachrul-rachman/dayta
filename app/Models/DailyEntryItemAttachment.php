<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntryItemAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_entry_item_id',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
    ];

    public function item()
    {
        return $this->belongsTo(DailyEntryItem::class, 'daily_entry_item_id');
    }
}

