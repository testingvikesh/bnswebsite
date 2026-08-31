<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntroSessionEmailLog extends Model
{
    public const STATUS_SENT = 'sent';

    public const STATUS_SKIPPED = 'skipped';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'contact_inquiry_id',
        'session_number',
        'template_key',
        'template_title',
        'registration_number',
        'full_name',
        'email',
        'mobile',
        'status',
        'error_message',
        'sent_by',
        'batch_key',
        'sent_at',
    ];

    protected $casts = [
        'session_number' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class, 'contact_inquiry_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => 'Sent',
            self::STATUS_SKIPPED => 'Skipped',
            self::STATUS_FAILED => 'Failed',
            default => ucfirst((string) $this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => 'bg-success',
            self::STATUS_SKIPPED => 'bg-warning text-dark',
            self::STATUS_FAILED => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function sessionLabel(): string
    {
        return 'Session '.(int) $this->session_number;
    }

    public function templateLabel(): string
    {
        $title = trim((string) ($this->template_title ?? ''));
        if ($title !== '') {
            return $title;
        }

        $key = trim((string) ($this->template_key ?? ''));

        return $key !== '' ? $key : 'Welcome Confirmation';
    }
}
