<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContactInquiry extends Model
{
    protected $fillable = [
        'registration_number',
        'form_source',
        'full_name', 'mobile', 'whatsapp', 'email', 'gst_no',
        'date_of_birth', 'gender', 'address',
        'city', 'state', 'pin_code', 'country',
        'interested_program', 'category', 'educational_qualification',
        'occupation', 'organization_name',
        'business_profession_category', 'business_category', 'products_services',
        'preferred_centre', 'preferred_batch', 'intro_session_number', 'preferred_language',
        'hear_about', 'purpose_of_joining', 'expectations',
        'subject', 'message', 'documents',
        'agreed_to_contact', 'agreed_info_correct', 'agreed_privacy',
        'status',
        'auto_purge_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'purpose_of_joining' => 'array',
        'documents' => 'array',
        'agreed_to_contact' => 'boolean',
        'agreed_info_correct' => 'boolean',
        'agreed_privacy' => 'boolean',
        'intro_session_number' => 'integer',
        'auto_purge_at' => 'datetime',
    ];

    public static function generateRegistrationNumber(): string
    {
        $year = now()->format('Y');
        $last = static::query()
            ->where('registration_number', 'like', "BNS-ENQ-{$year}-%")
            ->orderByDesc('id')
            ->value('registration_number');

        $sequence = 1;
        if ($last && preg_match('/BNS-ENQ-\d{4}-(\d+)$/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('BNS-ENQ-%s-%04d', $year, $sequence);
    }

    /** @return array<string, string> */
    public function documentLabels(): array
    {
        return [
            'photo' => 'Passport Size Photo',
            'aadhaar' => 'Aadhaar Card',
            'certificate' => 'Educational Certificate',
            'resume' => 'Resume',
            'business_profile' => 'Business Profile',
        ];
    }

    public function documentUrl(string $key): ?string
    {
        $path = $this->documents[$key] ?? null;

        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public function resolvedFormSource(): string
    {
        if (filled($this->form_source)) {
            return (string) $this->form_source;
        }

        $message = (string) ($this->message ?? '');

        if (str_contains($message, 'Introduction session admission request')) {
            return 'intro-session-modal';
        }

        if (str_contains($message, 'Registration request for')) {
            return 'register-quick-modal';
        }

        if (filled($this->message) && ! str_contains($message, 'Introduction session admission request') && ! str_contains($message, 'Registration request for')) {
            return 'inquiry-modal';
        }

        return 'unknown';
    }

    public function formSourceLabel(): string
    {
        $source = $this->resolvedFormSource();

        return config("reporting.form_sources.{$source}", ucfirst(str_replace('-', ' ', $source)));
    }

    public static function normalizeMobile(?string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', (string) $mobile);

        if (strlen($digits) > 10) {
            $digits = substr($digits, -10);
        }

        return $digits;
    }

    public static function mobileExists(string $mobile, ?string $formSource = null, ?int $exceptId = null): bool
    {
        $normalized = self::normalizeMobile($mobile);

        if ($normalized === '') {
            return false;
        }

        $query = static::query()->whereNotNull('mobile')->where('mobile', '!=', '');

        if ($formSource === 'intro-session-modal') {
            $query = static::introSessionQuery()->whereNotNull('mobile')->where('mobile', '!=', '');
        } elseif ($formSource) {
            $query->where('form_source', $formSource);
        }

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        // Match last 10 digits so +91 / spaces / dashes still catch duplicates.
        return $query
            ->whereRaw(
                "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(mobile,''), ' ', ''), '-', ''), '+', ''), '(', ''), ')', ''), 10) = ?",
                [$normalized]
            )
            ->exists();
    }

    public static function normalizeEmail(?string $email): string
    {
        return strtolower(trim((string) $email));
    }

    public static function emailExists(string $email, ?string $formSource = null, ?int $exceptId = null): bool
    {
        $normalized = self::normalizeEmail($email);

        if ($normalized === '') {
            return false;
        }

        $query = static::query()->whereNotNull('email')->where('email', '!=', '');

        if ($formSource === 'intro-session-modal') {
            $query = static::introSessionQuery()->whereNotNull('email')->where('email', '!=', '');
        } elseif ($formSource) {
            $query->where('form_source', $formSource);
        }

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query
            ->whereRaw('LOWER(TRIM(email)) = ?', [$normalized])
            ->exists();
    }

    public static function countUniqueMobiles(?string $formSource = null): int
    {
        $query = static::query();

        if ($formSource) {
            $query->where('form_source', $formSource);
        }

        return static::countUniqueFromQuery($query);
    }

    /**
     * @param  list<string>  $formSources
     */
    public static function countUniqueMobilesForSources(array $formSources): int
    {
        if ($formSources === []) {
            return 0;
        }

        return static::countUniqueFromQuery(
            static::query()->whereIn('form_source', $formSources)
        );
    }

    public static function countUniqueFromQuery($query): int
    {
        return $query
            ->get()
            ->groupBy(function (self $item) {
                $mobile = self::normalizeMobile($item->mobile);

                return $mobile !== '' ? $mobile : 'record-'.$item->id;
            })
            ->count();
    }

    public static function introSessionQuery()
    {
        return static::query()->where(function ($query) {
            $query
                ->where('form_source', 'intro-session-modal')
                ->orWhere('message', 'like', 'Introduction session admission request%');
        });
    }

    /**
     * Intro Session + Inquiry + Confirm Admission (for reporting session split).
     */
    public static function primaryFormsQuery()
    {
        return static::query()->where(function ($query) {
            $query
                ->whereIn('form_source', [
                    'intro-session-modal',
                    'inquiry-modal',
                    'register-quick-modal',
                ])
                ->orWhere('message', 'like', 'Introduction session admission request%')
                ->orWhere('message', 'like', 'Registration request for%');
        });
    }

    public static function countUniqueIntroSessionMobiles(): int
    {
        return static::countUniqueFromQuery(static::introSessionQuery());
    }
}
