<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class HomePageImage extends Model
{
    protected $fillable = [
        'key',
        'section',
        'label',
        'default_path',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function url(): string
    {
        if ($this->image_path && File::exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        return asset('assets/images/'.$this->default_path);
    }

    public function isCustom(): bool
    {
        return filled($this->image_path) && File::exists(public_path($this->image_path));
    }

    public function deleteUploadedFile(): void
    {
        if (! $this->image_path) {
            return;
        }

        $fullPath = public_path($this->image_path);

        if (File::exists($fullPath) && str_starts_with($this->image_path, 'uploads/home/')) {
            File::delete($fullPath);
        }
    }
}
