<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    public function create(): View
    {
        return view('sop.auth.change-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('controlpanel.dashboard')
            ->with('status', 'Your password has been updated successfully.');
    }
}
