<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    private static ?array $usersColumnMap = null;

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            self::syncLegacyUserColumns($user);
        });
    }

    public function getNameAttribute(?string $value): string
    {
        if (filled($value)) {
            return $value;
        }

        return (string) ($this->attributes['full_name'] ?? '');
    }

    public function getAuthPassword(): string
    {
        $password = (string) ($this->attributes['password'] ?? '');

        if ($password !== '') {
            return $password;
        }

        return (string) ($this->attributes['password_hash'] ?? '');
    }

    private static function syncLegacyUserColumns(User $user): void
    {
        if (self::usersHasColumn('full_name') && self::usersHasColumn('name')) {
            $name = trim((string) ($user->attributes['name'] ?? ''));
            $fullName = trim((string) ($user->attributes['full_name'] ?? ''));

            if ($name !== '') {
                $user->attributes['full_name'] = $name;
            } elseif ($fullName !== '') {
                $user->attributes['name'] = $fullName;
            } else {
                $user->attributes['full_name'] = 'User';
            }
        }

        if (self::usersHasColumn('password_hash')) {
            $password = (string) ($user->attributes['password'] ?? '');

            if ($password !== '') {
                $user->attributes['password_hash'] = $password;
            } elseif (! empty($user->attributes['password_hash'])) {
                $user->attributes['password'] = $user->attributes['password_hash'];
            }
        }
    }

    private static function usersHasColumn(string $column): bool
    {
        return isset(self::usersColumnMap()[$column]);
    }

    /** @return array<string, int> */
    private static function usersColumnMap(): array
    {
        return self::$usersColumnMap ??= array_flip(Schema::getColumnListing('users'));
    }

    public function isSopAdmin(): bool
    {
        return ($this->role ?? self::ROLE_USER) === self::ROLE_ADMIN;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            default => 'User',
        };
    }
}
