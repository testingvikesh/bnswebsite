<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmSpotAdmission extends Model
{
    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'register_no',
        'qr_data',
        'facility_name',
        'reason',
        'attended_at',
        'ip_address',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];
}
