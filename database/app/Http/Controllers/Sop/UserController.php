<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $users = User::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('sop.users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('sop.users.create', ['user' => new User(['role' => User::ROLE_USER])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
        ]);

        User::create($validated);

        return redirect()->route('controlpanel.users.index')
            ->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('sop.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->withInput()->withErrors(['role' => 'You cannot remove your own administrator role.']);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('controlpanel.users.index')
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('controlpanel.users.index')
            ->with('status', 'User deleted successfully.');
    }
}
