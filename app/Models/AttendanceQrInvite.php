<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttendanceQrInvite extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REVOKED = 'revoked';

    protected $fillable = [
        'contact_inquiry_id',
        'session_number',
        'token',
        'email',
        'full_name',
        'mobile',
        'registration_number',
        'status',
        'invite_sent_at',
        'expires_at',
        'approved_at',
        'approved_via',
        'approved_by',
        'session_attendance_id',
        'sent_by',
    ];

    protected $casts = [
        'session_number' => 'integer',
        'invite_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class, 'contact_inquiry_id');
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(SessionAttendance::class, 'session_attendance_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function makeToken(): string
    {
        return Str::random(48);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isApprovable(): bool
    {
        return $this->status === self::STATUS_PENDING && ! $this->isExpired();
    }

    public function scanUrl(): string
    {
        return route('attendance.qr.show', ['token' => $this->token]);
    }

    public function qrImageUrl(int $size = 240): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size='.$size.'x'.$size.'&margin=8&data='.rawurlencode($this->scanUrl());
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Approved / Present',
            self::STATUS_REVOKED => 'Revoked',
            default => 'Pending Scan',
        };
    }
}
