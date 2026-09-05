<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdmissionPayment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_INITIATED = 'initiated';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const REFUND_STATUS_PENDING = 'pending';

    public const REFUND_STATUS_SUCCESS = 'success';

    public const REFUND_STATUS_FAILED = 'failed';

    protected $fillable = [
        'merchant_txn_no',
        'payable_type',
        'payable_id',
        'form_type',
        'registration_number',
        'amount',
        'currency_code',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'addl_param1',
        'addl_param2',
        'status',
        'response_code',
        'response_description',
        'payment_mode',
        'payment_sub_inst_type',
        'payment_id',
        'txn_id',
        'tran_ctx',
        'redirect_uri',
        'payment_datetime',
        'initiate_request',
        'initiate_response',
        'callback_response',
        'status_response',
        'refund_merchant_txn_no',
        'refund_amount',
        'refund_status',
        'refund_response_code',
        'refund_response_description',
        'refund_request',
        'refund_response',
        'refund_status_response',
        'refunded_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'initiate_request' => 'array',
        'initiate_response' => 'array',
        'callback_response' => 'array',
        'status_response' => 'array',
        'refund_request' => 'array',
        'refund_response' => 'array',
        'refund_status_response' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isRefunded(): bool
    {
        return $this->refund_status === self::REFUND_STATUS_SUCCESS;
    }

    public function canOfferRefund(): bool
    {
        return $this->isPaid() && ! $this->isRefunded();
    }

    public function formattedAmount(): string
    {
        return number_format((float) $this->amount, 2, '.', '');
    }

    public function formattedRefundAmount(): string
    {
        return number_format((float) ($this->refund_amount ?? 0), 2, '.', '');
    }

    public static function generateRefundMerchantTxnNo(): string
    {
        return 'BNSR'.now()->format('ymdHis').random_int(1000, 9999);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SUCCESS => 'Paid',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_INITIATED => 'Initiated',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Pending',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_SUCCESS => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_INITIATED => 'info',
            self::STATUS_CANCELLED => 'secondary',
            default => 'warning',
        };
    }

    public static function generateMerchantTxnNo(): string
    {
        return 'BNS'.now()->format('ymdHis').random_int(1000, 9999);
    }
}
