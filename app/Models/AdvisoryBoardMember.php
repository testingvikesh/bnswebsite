<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdvisoryBoardMember extends Model
{
    protected $fillable = [
        'full_name',
        'designation',
        'organization',
        'expertise',
        'profile',
        'photo_path',
        'linkedin_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (AdvisoryBoardMember $member) {
            $member->deletePhoto();
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path
            ? bns_vurl(Storage::disk('public')->url($this->photo_path))
            : null;
    }

    /** @return list<string> */
    public function expertiseList(): array
    {
        return collect(preg_split('/[,|]/', $this->expertise))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    public function deletePhoto(): void
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }
}
