<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Mail\Mailable;

class OutboundEmailLog extends Model
{
    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const PROCESS_PAYMENT = 'payment';

    public const PROCESS_INTRO_SESSION = 'intro-session';

    public const PROCESS_SESSION_MAIL = 'session-mail';

    public const PROCESS_ATTENDANCE = 'attendance';

    public const PROCESS_ATTENDANCE_INVITE = 'attendance-invite';

    public const PROCESS_REFUND = 'refund';

    public const PROCESS_PASSWORD_RESET = 'password-reset';

    public const PROCESS_OTHER = 'other';

    protected $fillable = [
        'process',
        'mailable',
        'to_email',
        'cc_email',
        'from_email',
        'subject',
        'body_html',
        'body_text',
        'status',
        'error_message',
        'mailer',
        'sent_by',
        'sent_by_name',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /** @return array<string, string> */
    public static function processLabels(): array
    {
        return [
            self::PROCESS_PAYMENT => 'Payment',
            self::PROCESS_INTRO_SESSION => 'Intro Session',
            self::PROCESS_SESSION_MAIL => 'Session Mail',
            self::PROCESS_ATTENDANCE => 'Attendance',
            self::PROCESS_ATTENDANCE_INVITE => 'Attendance Invite',
            self::PROCESS_REFUND => 'Refund',
            self::PROCESS_PASSWORD_RESET => 'Password Reset',
            self::PROCESS_OTHER => 'Other',
        ];
    }

    public static function processKeyForClass(?string $class): string
    {
        return match ($class) {
            \App\Mail\PaymentSuccessMail::class => self::PROCESS_PAYMENT,
            \App\Mail\IntroSessionConfirmationMail::class => self::PROCESS_INTRO_SESSION,
            \App\Mail\IntroSessionSequenceMail::class => self::PROCESS_SESSION_MAIL,
            \App\Mail\AttendanceConfirmedMail::class => self::PROCESS_ATTENDANCE,
            \App\Mail\AttendanceInviteMail::class => self::PROCESS_ATTENDANCE_INVITE,
            \App\Mail\RefundOtpMail::class => self::PROCESS_REFUND,
            default => self::PROCESS_OTHER,
        };
    }

    public static function processKeyForMailable(?Mailable $mailable): string
    {
        return $mailable ? self::processKeyForClass($mailable::class) : self::PROCESS_OTHER;
    }

    public function processLabel(): string
    {
        return self::processLabels()[$this->process] ?? ucfirst(str_replace('-', ' ', (string) $this->process));
    }

    public function statusLabel(): string
    {
        return $this->status === self::STATUS_FAILED ? 'Failed' : 'Sent';
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function senderLabel(): string
    {
        $name = trim((string) ($this->sent_by_name ?: $this->sender?->name ?: ''));

        return $name !== '' ? $name : 'System';
    }
}
