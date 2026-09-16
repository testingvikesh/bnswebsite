<?php

namespace App\Http\Middleware;

use App\Support\CrmPortal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCrmAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! CrmPortal::isAdmin($request)) {
            if (CrmPortal::isEmployee($request)) {
                return redirect()->route('crm.desk');
            }

            return redirect()
                ->route('crm.login')
                ->with('error', 'Admin login is required for this page.');
        }

        return $next($request);
    }
}
