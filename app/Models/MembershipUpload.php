<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MembershipUpload extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_TRUSTEE_VERIFIED = 'trustee_verified';

    public const STATUS_VERIFIED = 'verified';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_REFUNDED = 'refunded';

    public const STEP_PENDING = 'pending';

    public const STEP_APPROVED = 'approved';

    public const STEP_REJECTED = 'rejected';

    public const STEP_REFUNDED = 'refunded';

    public const PHOTO_DIR = 'uploads/membership-uploads';

    protected $fillable = [
        'membership_name',
        'membership_no',
        'photo_path',
        'email',
        'mobile',
        'registration_number',
        'status',
        'notes',
        'trustee_status',
        'trustee_remarks',
        'trustee_verified_by',
        'trustee_verified_at',
        'bns_status',
        'bns_remarks',
        'bns_verified_by',
        'bns_verified_at',
    ];

    protected $casts = [
        'trustee_verified_at' => 'datetime',
        'bns_verified_at' => 'datetime',
    ];

    public function trusteeVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trustee_verified_by');
    }

    public function bnsVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bns_verified_by');
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        $this->ensurePublicPhoto();

        $path = $this->normalizedPhotoPath();

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'uploads/') && is_file(public_path($path))) {
            return bns_vasset($path);
        }

        if (is_file(public_path('storage/'.$path))) {
            return bns_vasset('storage/'.$path);
        }

        if (Storage::disk('public')->exists($path)) {
            return bns_web_base_url().'/storage/'.$path;
        }

        return null;
    }

    public static function storePhoto(UploadedFile $file): string
    {
        $directory = public_path(self::PHOTO_DIR);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::random(40).'.'.strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move($directory, $filename);

        return self::PHOTO_DIR.'/'.$filename;
    }

    public function deletePhoto(): void
    {
        $path = $this->normalizedPhotoPath();

        if ($path === '') {
            return;
        }

        if (str_starts_with($path, 'uploads/') && File::exists(public_path($path))) {
            File::delete(public_path($path));

            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $publicStorage = public_path('storage/'.$path);
        if (File::exists($publicStorage)) {
            File::delete($publicStorage);
        }
    }

    /**
     * Copy legacy storage-disk photos into public/uploads so they are web-accessible.
     */
    public function ensurePublicPhoto(): bool
    {
        $path = $this->normalizedPhotoPath();

        if ($path === '' || str_starts_with($path, 'uploads/')) {
            return false;
        }

        if (is_file(public_path(self::PHOTO_DIR.'/'.basename($path)))) {
            $this->forceFill([
                'photo_path' => self::PHOTO_DIR.'/'.basename($path),
            ])->save();

            return true;
        }

        $storageFile = storage_path('app/public/'.$path);
        if (! is_file($storageFile)) {
            return false;
        }

        $directory = public_path(self::PHOTO_DIR);
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = basename($storageFile);
        File::copy($storageFile, $directory.DIRECTORY_SEPARATOR.$filename);

        $this->forceFill([
            'photo_path' => self::PHOTO_DIR.'/'.$filename,
        ])->save();

        return true;
    }

    public function canTrusteeVerify(): bool
    {
        return ($this->trustee_status ?: self::STEP_PENDING) === self::STEP_PENDING
            && in_array($this->status, [self::STATUS_PENDING, self::STATUS_TRUSTEE_VERIFIED], true);
    }

    public function canBnsVerify(): bool
    {
        return $this->trustee_status === self::STEP_APPROVED
            && ($this->bns_status ?: self::STEP_PENDING) === self::STEP_PENDING
            && $this->status === self::STATUS_TRUSTEE_VERIFIED;
    }

    public function canRefund(): bool
    {
        return $this->status === self::STATUS_VERIFIED
            && $this->bns_status === self::STEP_APPROVED;
    }

    public function successfulPayment(): ?AdmissionPayment
    {
        $registrationNumber = trim((string) $this->registration_number);
        if ($registrationNumber === '') {
            return null;
        }

        return AdmissionPayment::query()
            ->where('registration_number', $registrationNumber)
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->latest('paid_at')
            ->latest('id')
            ->first();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_TRUSTEE_VERIFIED => 'Trustee Verified',
            self::STATUS_VERIFIED => 'BNS Verified',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_REFUNDED => 'Refunded',
            default => 'Pending Trustee',
        };
    }

    public function trusteeStatusLabel(): string
    {
        return match ($this->trustee_status) {
            self::STEP_APPROVED => 'Approved',
            self::STEP_REJECTED => 'Rejected',
            default => 'Pending',
        };
    }

    public function bnsStatusLabel(): string
    {
        return match ($this->bns_status) {
            self::STEP_APPROVED => 'Approved',
            self::STEP_REJECTED => 'Rejected',
            self::STEP_REFUNDED => 'Refunded',
            default => 'Pending',
        };
    }

    /** @return list<string> */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_TRUSTEE_VERIFIED,
            self::STATUS_VERIFIED,
            self::STATUS_REJECTED,
            self::STATUS_REFUNDED,
        ];
    }

    /**
     * Active (non-rejected) upload for the same registration / membership number.
     */
    public static function findExistingActive(?string $registrationNumber = null, ?string $membershipNo = null): ?self
    {
        $registrationNumber = trim((string) $registrationNumber);
        $membershipNo = trim((string) $membershipNo);

        if ($registrationNumber === '' && $membershipNo === '') {
            return null;
        }

        return static::query()
            ->where('status', '!=', self::STATUS_REJECTED)
            ->where(function ($query) use ($registrationNumber, $membershipNo) {
                if ($registrationNumber !== '') {
                    $query->where('registration_number', $registrationNumber);
                }

                if ($membershipNo !== '') {
                    $method = $registrationNumber !== '' ? 'orWhere' : 'where';
                    $query->{$method}('membership_no', $membershipNo);
                }
            })
            ->latest()
            ->first();
    }

    private function normalizedPhotoPath(): string
    {
        $path = ltrim(str_replace('\\', '/', (string) $this->photo_path), '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        return $path;
    }
}
