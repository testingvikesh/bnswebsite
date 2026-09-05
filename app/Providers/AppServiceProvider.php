<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use App\Services\TestRegistrationPurgeService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
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
     * Live Hostinger cannot use smtp.gmail.com (TLS is intercepted).
     * A cPanel mailbox such as info@businessnavacharschool.com must go
     * through smtp.hostinger.com with the same username/password.
     */
    private function configureHostingerMailer(): void
    {
        if (filter_var(env('MAIL_FORCE_GMAIL', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $hostname = strtolower((string) gethostname());
        $onHostinger = str_contains($hostname, 'hstgr.cloud')
            || str_contains($hostname, 'srv1864255')
            || str_contains($hostname, 'hostinger');

        if (! $onHostinger) {
            return;
        }

        $fromHost = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'businessnavacharschool.com';
        $fromHost = preg_replace('/^www\./i', '', (string) $fromHost) ?: 'businessnavacharschool.com';

        $username = strtolower(trim((string) config('mail.mailers.smtp.username')));
        $fromAddress = strtolower(trim((string) config('mail.from.address')));
        $domainMailbox = $this->isDomainMailbox($username, $fromHost)
            ? $username
            : ($this->isDomainMailbox($fromAddress, $fromHost) ? $fromAddress : '');

        $fromName = (string) env('MAIL_FROM_NAME', 'Business Navachar School');
        $smtpHost = strtolower((string) config('mail.mailers.smtp.host'));
        $usingGmailSmtp = str_contains($smtpHost, 'gmail.com');
        $usingHostingerSmtp = str_contains($smtpHost, 'hostinger')
            || str_contains($smtpHost, 'titan.email')
            || $smtpHost === 'localhost'
            || $smtpHost === '127.0.0.1';

        if ($domainMailbox !== '') {
            if (! $usingHostingerSmtp) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => env('MAIL_HOSTINGER_HOST', 'smtp.hostinger.com'),
                    'mail.mailers.smtp.port' => (int) env('MAIL_HOSTINGER_PORT', 465),
                    'mail.mailers.smtp.encryption' => env('MAIL_HOSTINGER_ENCRYPTION', 'ssl'),
                    'mail.mailers.smtp.username' => $domainMailbox,
                ]);
            }

            config([
                'mail.from.address' => $domainMailbox,
                'mail.from.name' => $fromName,
                'mail.reply_to.address' => $domainMailbox,
                'mail.reply_to.name' => $fromName,
            ]);

            Mail::alwaysFrom($domainMailbox, $fromName);
            Mail::alwaysReplyTo($domainMailbox, $fromName);

            return;
        }

        if (! $usingGmailSmtp) {
            return;
        }

        $domainFrom = 'info@'.$fromHost;
        $sendmailPath = trim((string) env('MAIL_SENDMAIL_PATH', ''));
        if ($sendmailPath === '') {
            $sendmailPath = '/usr/sbin/sendmail -t -i -f '.$domainFrom;
        }

        config([
            'mail.default' => 'sendmail',
            'mail.mailers.sendmail.path' => $sendmailPath,
            'mail.from.address' => $domainFrom,
            'mail.from.name' => $fromName,
        ]);
        Mail::alwaysFrom($domainFrom, $fromName);
        Mail::alwaysReplyTo($domainFrom, $fromName);
    }

    private function isDomainMailbox(string $email, string $fromHost): bool
    {
        $email = strtolower(trim($email));

        return $email !== ''
            && filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            && str_ends_with($email, '@'.$fromHost);
    }
}
