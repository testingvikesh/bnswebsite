<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class CrmEmployee extends Model
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'mobile',
        'facility',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(CrmAssignment::class);
    }

    public function setPasswordAttribute(string $value): void
    {
        if ($value === '') {
            return;
        }

        if (str_starts_with($value, '$2y$') || str_starts_with($value, '$2a$') || str_starts_with($value, '$argon')) {
            $this->attributes['password'] = $value;

            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    public function passwordMatches(string $plain): bool
    {
        $stored = (string) ($this->attributes['password'] ?? '');
        if ($stored === '') {
            return false;
        }

        if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$') || str_starts_with($stored, '$argon')) {
            return Hash::check($plain, $stored);
        }

        return hash_equals($stored, $plain);
    }
}
