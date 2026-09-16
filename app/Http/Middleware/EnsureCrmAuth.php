<?php

namespace App\Http\Middleware;

use App\Support\CrmPortal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCrmAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! CrmPortal::isLoggedIn($request)) {
            return redirect()
                ->route('crm.login')
                ->with('error', 'Please login to access BNS CRM.');
        }

        if (CrmPortal::isEmployee($request) && ! CrmPortal::employee($request)) {
            CrmPortal::logout($request);

            return redirect()
                ->route('crm.login')
                ->with('error', 'This employee account is inactive. Please contact admin.');
        }

        return $next($request);
    }
}
