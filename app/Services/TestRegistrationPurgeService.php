<?php

namespace App\Services;

use App\Models\AdmissionPayment;
use App\Models\AttendanceQrInvite;
use App\Models\ContactInquiry;
use App\Models\IntroSessionEmailLog;
use App\Models\MembershipUpload;
use App\Models\SessionAttendance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class TestRegistrationPurgeService
{
    public const PURGE_AFTER_MINUTES = 5;

    public function __construct(private SiteSettingsService $settings) {}

    /**
     * @return list<string> Normalized 10-digit mobiles
     */
    public function configuredMobiles(): array
    {
        $raw = (string) $this->settings->get(SiteSettingsService::KEY_AUTO_PURGE_MOBILES, '');

        if (trim($raw) === '') {
            return [];
        }

        $mobiles = [];
        foreach (preg_split('/[,\n;]+/', $raw) ?: [] as $part) {
            $normalized = ContactInquiry::normalizeMobile($part);
            if (strlen($normalized) >= 10) {
                $mobiles[] = substr($normalized, -10);
            }
        }

        return array_values(array_unique($mobiles));
    }

    public function isAutoPurgeMobile(?string $mobile): bool
    {
        $normalized = ContactInquiry::normalizeMobile($mobile);

        if ($normalized === '') {
            return false;
        }

        $normalized = substr($normalized, -10);

        return in_array($normalized, $this->configuredMobiles(), true);
    }

    public function scheduleIfNeeded(ContactInquiry $inquiry): void
    {
        if (! $this->isAutoPurgeMobile($inquiry->mobile)) {
            return;
        }

        if (! Schema::hasColumn('contact_inquiries', 'auto_purge_at')) {
            return;
        }

        $inquiry->forceFill([
            'auto_purge_at' => now()->addMinutes(self::PURGE_AFTER_MINUTES),
        ])->save();
    }

    /**
     * Delete due test registrations and related rows.
     *
     * @return int Number of inquiries purged
     */
    public function purgeDue(): int
    {
        if (! Schema::hasTable('contact_inquiries') || ! Schema::hasColumn('contact_inquiries', 'auto_purge_at')) {
            return 0;
        }

        $due = ContactInquiry::query()
            ->whereNotNull('auto_purge_at')
            ->where('auto_purge_at', '<=', now())
            ->orderBy('id')
            ->limit(50)
            ->get();

        $count = 0;
        foreach ($due as $inquiry) {
            try {
                $this->purgeInquiry($inquiry);
                $count++;
            } catch (\Throwable $e) {
                Log::error('Auto-purge registration failed', [
                    'inquiry_id' => $inquiry->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Run purge at most once per minute on web traffic (when cron is unavailable).
     */
    public function purgeDueThrottled(): int
    {
        $ran = false;

        Cache::lock('contact-inquiry-auto-purge', 50)->get(function () use (&$ran) {
            if (Cache::get('contact-inquiry-auto-purge-tick')) {
                return;
            }

            Cache::put('contact-inquiry-auto-purge-tick', 1, now()->addMinutes(1));
            $this->purgeDue();
            $ran = true;
        });

        return $ran ? 1 : 0;
    }

    public function purgeInquiry(ContactInquiry $inquiry): void
    {
        DB::transaction(function () use ($inquiry) {
            $inquiryId = (int) $inquiry->id;
            $registrationNumber = (string) ($inquiry->registration_number ?? '');

            AttendanceQrInvite::query()
                ->where('contact_inquiry_id', $inquiryId)
                ->delete();

            SessionAttendance::query()
                ->where('contact_inquiry_id', $inquiryId)
                ->delete();

            if (Schema::hasTable('intro_session_email_logs')) {
                IntroSessionEmailLog::query()
                    ->where(function ($q) use ($inquiryId, $registrationNumber) {
                        $q->where('contact_inquiry_id', $inquiryId);
                        if ($registrationNumber !== '') {
                            $q->orWhere('registration_number', $registrationNumber);
                        }
                    })
                    ->delete();
            }

            if (Schema::hasTable('admission_payments')) {
                AdmissionPayment::query()
                    ->where(function ($q) use ($inquiry, $inquiryId, $registrationNumber) {
                        $q->where(function ($inner) use ($inquiry, $inquiryId) {
                            $inner->where('payable_type', $inquiry->getMorphClass())
                                ->where('payable_id', $inquiryId);
                        });
                        if ($registrationNumber !== '') {
                            $q->orWhere('registration_number', $registrationNumber);
                        }
                    })
                    ->delete();
            }

            if (Schema::hasTable('membership_uploads') && $registrationNumber !== '') {
                MembershipUpload::query()
                    ->where('registration_number', $registrationNumber)
                    ->delete();
            }

            $documents = is_array($inquiry->documents) ? $inquiry->documents : [];
            foreach ($documents as $path) {
                if (is_string($path) && $path !== '') {
                    try {
                        Storage::disk('public')->delete($path);
                    } catch (\Throwable) {
                        // ignore file cleanup errors
                    }
                }
            }

            $inquiry->delete();
        });
    }
}
