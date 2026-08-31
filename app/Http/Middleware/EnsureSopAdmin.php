<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSopAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isSopAdmin()) {
            abort(403, 'Administrator access required.');
        }

        return $next($request);
    }
}
