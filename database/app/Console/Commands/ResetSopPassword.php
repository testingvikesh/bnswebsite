<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetSopPassword extends Command
{
    protected $signature = 'controlpanel:reset-password {email : Admin user email} {password? : New password (prompted if omitted)}';

    protected $description = 'Reset a Control Panel user password';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User not found.');

            return self::FAILURE;
        }

        $password = $this->argument('password') ?? $this->secret('New password');

        if (! $password) {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $user->password = $password;
        $user->save();

        $this->info("Password updated for {$user->email}");

        return self::SUCCESS;
    }
}
