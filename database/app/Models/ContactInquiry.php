<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContactInquiry extends Model
{
    protected $fillable = [
        'registration_number',
        'full_name', 'mobile', 'whatsapp', 'email',
        'date_of_birth', 'gender', 'address',
        'city', 'state', 'pin_code', 'country',
        'interested_program', 'category', 'educational_qualification',
        'occupation', 'organization_name',
        'preferred_centre', 'preferred_batch', 'preferred_language',
        'hear_about', 'purpose_of_joining', 'expectations',
        'subject', 'message', 'documents',
        'agreed_to_contact', 'agreed_info_correct', 'agreed_privacy',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'purpose_of_joining' => 'array',
        'documents' => 'array',
        'agreed_to_contact' => 'boolean',
        'agreed_info_correct' => 'boolean',
        'agreed_privacy' => 'boolean',
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
}
