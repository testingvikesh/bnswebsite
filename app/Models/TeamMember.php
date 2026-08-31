<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
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
        'profile',
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
        return bns_public_media_url($this->photo_path);
    }

    public function migratePhotoToPublicUploads(): bool
    {
        if (! $this->photo_path) {
            return false;
        }

        if ($this->photo_url !== null) {
            return false;
        }

        $path = ltrim(str_replace('\\', '/', $this->photo_path), '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if (str_starts_with($path, 'uploads/') || str_starts_with($path, 'assets/')) {
            return false;
        }

        $storageFile = storage_path('app/public/'.$path);
        if (! is_file($storageFile)) {
            return false;
        }

        $directory = public_path('uploads/team/members');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = pathinfo($storageFile, PATHINFO_EXTENSION) ?: 'jpg';
        $filename = 'member-'.$this->id.'-'.time().'.'.strtolower($extension);
        File::copy($storageFile, $directory.DIRECTORY_SEPARATOR.$filename);

        $this->forceFill(['photo_path' => 'uploads/team/members/'.$filename])->save();

        return true;
    }

    public function deletePhoto(): void
    {
        if (! $this->photo_path) {
            return;
        }

        $path = ltrim(str_replace('\\', '/', $this->photo_path), '/');

        if (str_starts_with($path, 'uploads/') && File::exists(public_path($path))) {
            File::delete(public_path($path));

            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function isLeadership(): bool
    {
        return $this->category === self::CATEGORY_LEADERSHIP;
    }
}
