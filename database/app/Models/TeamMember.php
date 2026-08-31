<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    public const CATEGORY_LEADERSHIP = 'leadership';

    public const CATEGORY_ACADEMIC = 'academic';

    protected $fillable = [
        'category',
        'full_name',
        'designation',
        'role',
        'expertise',
        'photo_path',
        'linkedin_url',
        'email',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'expertise' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (TeamMember $member) {
            $member->deletePhoto();
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path
            ? Storage::disk('public')->url($this->photo_path)
            : null;
    }

    public function deletePhoto(): void
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }

    public function isLeadership(): bool
    {
        return $this->category === self::CATEGORY_LEADERSHIP;
    }
}
