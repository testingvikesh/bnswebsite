<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMailPortalAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = (string) config('mail_portal.session_key', 'bns_mail_portal_auth');

        if (! $request->session()->get($key)) {
            return redirect()
                ->route('mail.login')
                ->with('error', 'Please login to access BNS Mail.');
        }

        return $next($request);
    }
}
