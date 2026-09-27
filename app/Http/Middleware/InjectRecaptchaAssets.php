<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectRecaptchaAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $key = (string) config('services.recaptcha.site_key', '');
        if ($key === '' || ! method_exists($response, 'getContent')) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '' || ! str_contains($content, '</head>')) {
            return $response;
        }

        if (! str_contains($content, 'BNS_RECAPTCHA_SITE_KEY')) {
            $boot = '<script>window.BNS_RECAPTCHA_SITE_KEY='.json_encode($key).';</script>'
                .'<script src="https://www.google.com/recaptcha/api.js?render='.rawurlencode($key).'"></script>';
            $content = str_replace('</head>', $boot.'</head>', $content);
        }

        if (! str_contains($content, 'bns-recaptcha-v3.js') && str_contains($content, '</body>')) {
            $script = '<script src="'.e(bns_vasset('assets/js/bns-recaptcha-v3.js')).'"></script>';
            $content = str_replace('</body>', $script.'</body>', $content);
        }

        $response->setContent($content);

        return $response;
    }
}
