<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        return $this->photo_path
            ? Storage::disk('public')->url($this->photo_path)
            : null;
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
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }
}
