<?php

namespace App\Http\Controllers;

use App\Models\CrmEmployee;
use App\Services\HomeImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmStaffController extends Controller
{
    public function __construct(private HomeImageService $homeImages) {}

    public function index(): View
    {
        $employees = CrmEmployee::query()
            ->withCount('assignments')
            ->orderBy('name')
            ->get();

        return view('crm.employees', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'employees' => $employees,
            'facilities' => config('admission.centres', []),
            'isAdmin' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('crm_employees', 'username')],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'facility' => ['nullable', 'string', 'max:120'],
        ], [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'username.unique' => 'This username is already used.',
            'email.email' => 'Enter a valid email ID.',
        ]);

        $payload = [
            'name' => trim($validated['name']),
            'username' => trim($validated['username']),
            'password' => $validated['password'],
            'mobile' => trim((string) ($validated['mobile'] ?? '')),
            'is_active' => true,
        ];

        if (Schema::hasColumn('crm_employees', 'email')) {
            $payload['email'] = trim((string) ($validated['email'] ?? '')) ?: null;
        }
        if (Schema::hasColumn('crm_employees', 'facility')) {
            $payload['facility'] = trim((string) ($validated['facility'] ?? '')) ?: null;
        }

        CrmEmployee::query()->create($payload);

        return back()->with('status', 'Employee added. They can now login to CRM.');
    }

    public function update(Request $request, CrmEmployee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['nullable', 'email', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'facility' => ['nullable', 'string', 'max:120'],
        ], [
            'email.email' => 'Enter a valid email ID.',
        ]);

        $payload = [
            'mobile' => trim((string) ($validated['mobile'] ?? '')),
        ];
        if (Schema::hasColumn('crm_employees', 'email')) {
            $payload['email'] = trim((string) ($validated['email'] ?? '')) ?: null;
        }
        if (Schema::hasColumn('crm_employees', 'facility')) {
            $payload['facility'] = trim((string) ($validated['facility'] ?? '')) ?: null;
        }

        $employee->forceFill($payload)->save();

        return back()->with('status', $employee->name.' details updated.');
    }

    public function toggle(CrmEmployee $employee): RedirectResponse
    {
        $employee->is_active = ! $employee->is_active;
        $employee->save();

        $label = $employee->is_active ? 'activated' : 'deactivated';

        return back()->with('status', $employee->name.' '.$label.'.');
    }
}
