<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VisitingExpertFaculty extends Model
{
    protected $table = 'visiting_expert_faculty';

    protected $fillable = [
        'title_prefix',
        'full_name',
        'photo_path',
        'designation',
        'recognition',
        'expertise',
        'professional_experience',
        'industry',
        'qualification',
        'specialization',
        'faculty_since',
        'sessions_conducted',
        'learners_mentored',
        'languages',
        'about',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'faculty_since' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (VisitingExpertFaculty $faculty) {
            $faculty->deletePhoto();
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

        $directory = public_path('uploads/visiting-faculty');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = pathinfo($storageFile, PATHINFO_EXTENSION) ?: 'jpg';
        $filename = 'faculty-'.$this->id.'-'.time().'.'.strtolower($extension);
        File::copy($storageFile, $directory.DIRECTORY_SEPARATOR.$filename);

        $this->forceFill(['photo_path' => 'uploads/visiting-faculty/'.$filename])->save();

        return true;
    }

    public function getDisplayNameAttribute(): string
    {
        $prefix = trim((string) $this->title_prefix);

        return $prefix !== ''
            ? "{$prefix} {$this->full_name}"
            : $this->full_name;
    }

    /** @return list<string> */
    public function listFromField(?string $value, string $pattern = '/[,|•]/'): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split($pattern, $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    /** @return list<string> */
    public function expertiseList(): array
    {
        return $this->listFromField($this->expertise);
    }

    /** @return list<string> */
    public function specializationList(): array
    {
        return $this->listFromField($this->specialization);
    }

    /** @return list<string> */
    public function languagesList(): array
    {
        return $this->listFromField($this->languages);
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
