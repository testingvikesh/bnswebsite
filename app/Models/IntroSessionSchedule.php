<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntroSessionSchedule extends Model
{
    protected $fillable = [
        'session_number',
        'title',
        'date_label',
        'time_label',
        'starts_at',
        'ends_at',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'session_number' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
