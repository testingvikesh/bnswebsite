<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmAssignment extends Model
{
    protected $fillable = [
        'crm_employee_id',
        'contact_inquiry_id',
        'session_number',
        'attendance_status',
        'assigned_at',
    ];

    protected $casts = [
        'session_number' => 'integer',
        'assigned_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(CrmEmployee::class, 'crm_employee_id');
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class, 'contact_inquiry_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(CrmFollowup::class)->orderBy('followup_no');
    }

    public function followup(int $number): ?CrmFollowup
    {
        return $this->followups->firstWhere('followup_no', $number);
    }

    public function completedFollowups(): int
    {
        return $this->followups
            ->filter(fn (CrmFollowup $row) => $row->status !== CrmFollowup::STATUS_PENDING)
            ->count();
    }
}
