<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
        'full_name',
        'photo_path',
        'designation',
        'organization',
        'location',
        'mobile',
        'email',
        'website',
        'message',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Testimonial $testimonial) {
            $testimonial->deletePhoto();
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

        $directory = public_path('uploads/testimonials');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = pathinfo($storageFile, PATHINFO_EXTENSION) ?: 'jpg';
        $filename = 'testimonial-'.$this->id.'-'.time().'.'.strtolower($extension);
        File::copy($storageFile, $directory.DIRECTORY_SEPARATOR.$filename);

        $this->forceFill(['photo_path' => 'uploads/testimonials/'.$filename])->save();

        return true;
    }

    public function getWebsiteUrlAttribute(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $url = trim($this->website);

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        return $url;
    }

    public function getWebsiteLabelAttribute(): ?string
    {
        if (! $this->website) {
            return null;
        }

        return preg_replace('#^https?://#i', '', rtrim($this->website, '/'));
    }

    public function getMobileTelAttribute(): ?string
    {
        if (! $this->mobile) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->mobile);

        return $digits !== '' ? '+'.$digits : null;
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
}
