<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueInspection extends Model
{
    protected $fillable = [
        'inspection_number',
        'venue_name',
        'institution_name',
        'city',
        'contact_person',
        'mobile',
        'inspection_date',
        'inspector_name',
        'final_decision',
        'form_data',
        'status',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'form_data' => 'array',
    ];

    public static function generateInspectionNumber(): string
    {
        $year = now()->format('Y');
        $last = static::query()
            ->where('inspection_number', 'like', "BNS-VI-{$year}-%")
            ->orderByDesc('id')
            ->value('inspection_number');

        $sequence = 1;
        if ($last && preg_match('/BNS-VI-\d{4}-(\d+)$/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('BNS-VI-%s-%04d', $year, $sequence);
    }
}
