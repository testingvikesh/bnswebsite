<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use App\Services\TestRegistrationPurgeService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bns');
        Paginator::defaultSimpleView('vendor.pagination.bns-simple');

        $this->configureHostingerMailer();

        // Keep generated URLs on the same host/path the browser is using.
        // Forcing APP_URL (e.g. production) while browsing localhost causes CSRF 419.
        if (! $this->app->runningInConsole()) {
            try {
                $request = request();
                $root = rtrim((string) $request->root(), '/');
                if ($root !== '') {
                    URL::forceRootUrl($root);
                }
                if ($request->isSecure()) {
                    URL::forceScheme('https');
                }
            } catch (\Throwable) {
                if ($rootUrl = config('app.url')) {
                    URL::forceRootUrl($rootUrl);
                }
            }

            // Opportunistic purge for test mobiles when cron is not configured.
            try {
                app(TestRegistrationPurgeService::class)->purgeDueThrottled();
            } catch (\Throwable) {
                // never block page load
            }
        } elseif ($rootUrl = config('app.url')) {
            URL::forceRootUrl($rootUrl);
        }

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return route('controlpanel.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        View::composer('*', function ($view) {
            // Email layouts/partials are self-contained; skip site settings (avoids DB on mail render).
            $name = (string) $view->name();
            if ($name !== '' && (str_starts_with($name, 'emails.') || str_starts_with($name, 'mail.'))) {
                return;
            }

            try {
                $settings = app(SiteSettingsService::class);
                $view->with('siteLogoUrl', $settings->logoUrl());
                $view->with('siteFaviconUrl', $settings->faviconUrl());
                $view->with('siteLogoAlt', $settings->logoAlt());
                $view->with('siteHeader', $settings->headerBar());
                $view->with('siteBrochure', $settings->brochureMeta());
                $view->with('siteLegalDates', $settings->legalDates());
            } catch (\Throwable) {
                $view->with('siteLogoUrl', asset('assets/bnslogo.png'));
                $view->with('siteFaviconUrl', asset('assets/bnslogo.png'));
                $view->with('siteLogoAlt', 'BNS');
                $view->with('siteHeader', []);
                $view->with('siteBrochure', []);
                $view->with('siteLegalDates', []);
            }
        });
    }

    /**
     * Hostinger/cPanel intercepts outbound SMTP (ports 587/465) and presents
     * CN=autoconfig.*.hstgr.cloud instead of smtp.gmail.com, so STARTTLS fails.
     * Use the local sendmail/Exim MTA on that host unless MAIL_FORCE_GMAIL=true.
     */
    private function configureHostingerMailer(): void
    {
        if (filter_var(env('MAIL_FORCE_GMAIL', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $hostname = strtolower((string) gethostname());
        $onHostinger = str_contains($hostname, 'hstgr.cloud')
            || str_contains($hostname, 'srv1864255');
        $usingGmailSmtp = strtolower((string) config('mail.default')) === 'smtp'
            && str_contains(strtolower((string) config('mail.mailers.smtp.host')), 'gmail.com');

        if (! $onHostinger || ! $usingGmailSmtp) {
            return;
        }

        $fromHost = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'businessnavacharschool.com';
        $fromHost = preg_replace('/^www\./i', '', (string) $fromHost) ?: 'businessnavacharschool.com';

        config([
            'mail.default' => 'sendmail',
            'mail.mailers.sendmail.path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -t -i'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS_SERVER', 'noreply@'.$fromHost),
        ]);
    }
}
