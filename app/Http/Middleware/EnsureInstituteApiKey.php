<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstituteApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('institute.api_key');
        $provided = (string) ($request->header('X-Institute-Key') ?: $request->bearerToken());

        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized institute API key.',
            ], 401);
        }

        return $next($request);
    }
}
