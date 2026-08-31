<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingWomenAdmission extends Model
{
    protected $fillable = [
        'registration_number',
        'category',
        'full_name',
        'email',
        'mobile',
        'photo_path',
        'form_data',
        'status',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public static function generateRegistrationNumber(): string
    {
        $year = now()->format('Y');
        $last = static::query()
            ->where('registration_number', 'like', "BNS-WWL-{$year}-%")
            ->orderByDesc('id')
            ->value('registration_number');

        $sequence = 1;
        if ($last && preg_match('/BNS-WWL-\d{4}-(\d+)$/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('BNS-WWL-%s-%04d', $year, $sequence);
    }
}
