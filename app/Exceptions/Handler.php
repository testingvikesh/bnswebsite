<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Laravel converts TokenMismatchException into HttpException 419 before
        // TokenMismatchException renderables run — catch the 419 instead.
        $this->renderable(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            return $this->expiredSessionResponse($request);
        });

        $this->renderable(function (TokenMismatchException $e, Request $request) {
            return $this->expiredSessionResponse($request);
        });
    }

    protected function expiredSessionResponse(Request $request): Response
    {
        if ($this->isLogoutRequest($request)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->to($this->logoutRedirectUrl($request))
                ->with('status', 'You have been logged out successfully.');
        }

        $message = 'Your session expired. Please try again.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $message,
                'token' => csrf_token(),
            ], 419);
        }

        $redirectTo = $this->expiredSessionRedirectUrl($request);

        return redirect()
            ->to($redirectTo)
            ->withInput($request->except(['_token', 'password', 'password_confirmation']))
            ->with('error', $message);
    }

    protected function expiredSessionRedirectUrl(Request $request): string
    {
        if ($request->routeIs('reporting.login') || $request->is('reporting/login')) {
            return route('reporting.index');
        }

        if ($request->routeIs('controlpanel.login') || $request->is('controlpanel/login')) {
            return route('controlpanel.login');
        }

        $formSource = (string) $request->input('form_source', '');
        if ($formSource === 'intro-session-modal') {
            return route('home', ['open' => 'introduction-session']);
        }
        if ($formSource === 'register-quick-modal') {
            return route('home', ['open' => 'book-your-spot']);
        }
        if ($formSource === 'inquiry-modal') {
            return url()->previous() ?: route('home');
        }

        $referer = (string) $request->headers->get('referer', '');
        $current = $request->fullUrl();

        if ($referer !== '' && $referer !== $current) {
            return $referer;
        }

        return url()->previous() !== $current
            ? (url()->previous() ?: route('home'))
            : route('home');
    }

    protected function isLogoutRequest(Request $request): bool
    {
        return $request->isMethod('post') && (
            $request->routeIs('*.logout')
            || $request->is('*/logout')
            || $request->is('controlpanel/logout')
            || $request->is('reporting/logout')
        );
    }

    protected function logoutRedirectUrl(Request $request): string
    {
        if ($request->routeIs('reporting.logout') || $request->is('reporting/logout')) {
            return route('reporting.index');
        }

        if ($request->routeIs('controlpanel.logout') || $request->is('controlpanel/logout')) {
            return route('controlpanel.login');
        }

        if ($request->routeIs('mail.logout') || $request->is('mail/logout')) {
            return route('mail.login');
        }

        return route('home');
    }
}
