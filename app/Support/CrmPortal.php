<?php

namespace App\Support;

use App\Models\CrmEmployee;
use Illuminate\Http\Request;

class CrmPortal
{
    public const ROLE_ADMIN = 'admin';

    public const ROLE_EMPLOYEE = 'employee';

    public static function sessionAuthKey(): string
    {
        return (string) config('crm.session_key', 'bns_crm_portal_auth');
    }

    public static function isLoggedIn(Request $request): bool
    {
        return (bool) $request->session()->get(self::sessionAuthKey());
    }

    public static function role(Request $request): string
    {
        return (string) $request->session()->get('bns_crm_role', '');
    }

    public static function isAdmin(Request $request): bool
    {
        return self::isLoggedIn($request) && self::role($request) === self::ROLE_ADMIN;
    }

    public static function isEmployee(Request $request): bool
    {
        return self::isLoggedIn($request) && self::role($request) === self::ROLE_EMPLOYEE;
    }

    public static function employeeId(Request $request): ?int
    {
        $id = (int) $request->session()->get('bns_crm_employee_id', 0);

        return $id > 0 ? $id : null;
    }

    public static function employee(Request $request): ?CrmEmployee
    {
        $id = self::employeeId($request);
        if (! $id) {
            return null;
        }

        return CrmEmployee::query()->where('is_active', true)->find($id);
    }

    public static function loginAdmin(Request $request): void
    {
        $request->session()->put(self::sessionAuthKey(), true);
        $request->session()->put('bns_crm_role', self::ROLE_ADMIN);
        $request->session()->forget('bns_crm_employee_id');
        $request->session()->regenerate();
    }

    public static function loginEmployee(Request $request, CrmEmployee $employee): void
    {
        $request->session()->put(self::sessionAuthKey(), true);
        $request->session()->put('bns_crm_role', self::ROLE_EMPLOYEE);
        $request->session()->put('bns_crm_employee_id', $employee->id);
        $request->session()->regenerate();
    }

    public static function logout(Request $request): void
    {
        $request->session()->forget(self::sessionAuthKey());
        $request->session()->forget('bns_crm_role');
        $request->session()->forget('bns_crm_employee_id');
        $request->session()->regenerate();
    }
}
