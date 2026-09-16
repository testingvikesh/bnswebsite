<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmFollowup extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONNECTED = 'connected';

    public const STATUS_NO_ANSWER = 'no_answer';

    public const STATUS_CALLBACK = 'callback';

    public const STATUS_INTERESTED = 'interested';

    public const STATUS_NOT_INTERESTED = 'not_interested';

    public const STATUS_ADMITTED = 'admitted';

    protected $fillable = [
        'crm_assignment_id',
        'followup_no',
        'status',
        'note',
        'called_at',
    ];

    protected $casts = [
        'followup_no' => 'integer',
        'called_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CrmAssignment::class, 'crm_assignment_id');
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONNECTED => 'Call connected',
            self::STATUS_NO_ANSWER => 'No answer',
            self::STATUS_CALLBACK => 'Call back later',
            self::STATUS_INTERESTED => 'Interested',
            self::STATUS_NOT_INTERESTED => 'Not interested',
            self::STATUS_ADMITTED => 'Admitted',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function isDone(): bool
    {
        return $this->status !== self::STATUS_PENDING;
    }
}
