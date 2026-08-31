<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('sop.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('email', $credentials['email'])->first();

        if ($user && ! $this->isBcryptHash($this->storedPassword($user))) {
            if (hash_equals($this->storedPassword($user), $credentials['password'])) {
                $user->password = $credentials['password'];
                $user->save();
                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('controlpanel.dashboard'));
            }

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        try {
            if (! Auth::attempt($credentials, $remember)) {
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'These credentials do not match our records.']);
            }
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), 'Bcrypt')) {
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Account password is not set correctly. Ask admin to run: php artisan controlpanel:reset-password your@email.com']);
            }

            throw $e;
        }

        $request->session()->regenerate();

        return redirect()->intended(route('controlpanel.dashboard'));
    }

    private function storedPassword(User $user): string
    {
        return (string) ($user->getAttributes()['password'] ?? '');
    }

    private function isBcryptHash(string $hash): bool
    {
        return str_starts_with($hash, '$2y$')
            || str_starts_with($hash, '$2a$')
            || str_starts_with($hash, '$2b$');
    }
}
