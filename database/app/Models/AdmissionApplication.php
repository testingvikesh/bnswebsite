<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdmissionApplication extends Model
{
    protected $fillable = [
        'application_number', 'category', 'program', 'year_level', 'batch',
        'city', 'centre', 'full_name', 'mobile', 'whatsapp', 'email',
        'date_of_birth', 'gender', 'address', 'state', 'pin_code',
        'parent_details', 'education_qualification', 'institution_name',
        'occupation', 'experience', 'linkedin', 'photo_path', 'documents',
        'fee_breakdown', 'payment_status', 'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'parent_details' => 'array',
        'documents' => 'array',
        'fee_breakdown' => 'array',
    ];

    public static function generateApplicationNumber(): string
    {
        $year = now()->format('Y');
        $last = static::query()
            ->where('application_number', 'like', "BNS-ADM-{$year}-%")
            ->orderByDesc('id')
            ->value('application_number');

        $sequence = 1;
        if ($last && preg_match('/BNS-ADM-\d{4}-(\d+)$/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('BNS-ADM-%s-%04d', $year, $sequence);
    }

    public function documentUrl(string $key): ?string
    {
        $path = $this->documents[$key] ?? null;

        return $path ? Storage::disk('public')->url($path) : null;
    }
}
