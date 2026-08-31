<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionAttendance extends Model
{
    public const STATUS_PRESENT = 'present';

    public const STATUS_ABSENT = 'absent';

    public const VIA_SELF = 'self';

    public const VIA_QR = 'qr';

    public const VIA_ADMIN = 'admin';

    protected $fillable = [
        'contact_inquiry_id',
        'registration_number',
        'session_number',
        'full_name',
        'email',
        'mobile',
        'program',
        'status',
        'marked_via',
        'qr_token',
        'attended_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'session_number' => 'integer',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class, 'contact_inquiry_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PRESENT => 'Present',
            default => ucfirst((string) $this->status),
        };
    }

    public function sessionLabel(): string
    {
        return 'Session '.(int) $this->session_number;
    }
}
