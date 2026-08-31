<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class SponsorMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'profile',
        'photo_path',
        'default_photo',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function photoUrl(): string
    {
        if ($this->photo_path && File::exists(public_path($this->photo_path))) {
            return bns_vasset($this->photo_path);
        }

        if ($this->default_photo && File::exists(public_path($this->default_photo))) {
            return bns_vasset($this->default_photo);
        }

        return '';
    }

    public function hasCustomPhoto(): bool
    {
        return filled($this->photo_path) && File::exists(public_path($this->photo_path));
    }

    public function deleteUploadedPhoto(): void
    {
        if (! $this->photo_path) {
            return;
        }

        $fullPath = public_path($this->photo_path);

        if (File::exists($fullPath) && str_starts_with($this->photo_path, 'uploads/sponsors/')) {
            File::delete($fullPath);
        }
    }

    /** @return array<string, mixed> */
    public function toFrontArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'designation' => $this->designation,
            'profile' => $this->profile,
            'photo' => $this->photo_path ?: $this->default_photo,
        ];
    }
}
