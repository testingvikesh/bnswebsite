<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SiteSettingsService
{
    public const KEY_LOGO = 'site_logo';

    public const KEY_FAVICON = 'site_favicon';

    public const KEY_LOGO_ALT = 'site_logo_alt';

    public const KEY_HEADER_EMAIL = 'header_email';

    public const KEY_HEADER_PHONE = 'header_phone';

    public const KEY_HEADER_ADDRESS = 'header_address';

    public const KEY_HEADER_WELCOME = 'header_welcome_text';

    public const KEY_HEADER_SOCIAL_TITLE = 'header_social_title';

    public const KEY_HEADER_SOCIAL_TWITTER = 'header_social_twitter';

    public const KEY_HEADER_SOCIAL_FACEBOOK = 'header_social_facebook';

    public const KEY_HEADER_SOCIAL_PINTEREST = 'header_social_pinterest';

    public const KEY_HEADER_SOCIAL_INSTAGRAM = 'header_social_instagram';

    public const KEY_BROCHURE_PDF = 'site_brochure_pdf';

    public const KEY_BROCHURE_VERSION = 'site_brochure_version';

    public const KEY_BROCHURE_TITLE = 'site_brochure_title';

    public const KEY_BROCHURE_SUBTITLE = 'site_brochure_subtitle';

    public const KEY_BROCHURE_INTRO = 'site_brochure_intro';

    public const KEY_LEGAL_EFFECTIVE_DATE = 'legal_effective_date';

    public const KEY_LEGAL_LAST_UPDATED = 'legal_last_updated';

    public const KEY_HERO_VIDEO_URL = 'hero_intro_video_url';

    public const KEY_HERO_VIDEO_LABEL = 'hero_intro_video_label';

    public const KEY_AUTO_PURGE_MOBILES = 'auto_purge_mobiles';

    private const DEFAULT_LOGO = 'assets/bnslogo.png';

    private const LEGAL_DATE_FALLBACK = 'As published on the BNS official website';

    /** @var array<string, string|null>|null */
    private static ?array $cache = null;

    public function logoUrl(): string
    {
        return $this->assetUrl(self::KEY_LOGO, self::DEFAULT_LOGO);
    }

    public function faviconUrl(): string
    {
        return $this->assetUrl(self::KEY_FAVICON, self::DEFAULT_LOGO);
    }

    public function logoAlt(): string
    {
        $alt = trim((string) $this->get(self::KEY_LOGO_ALT));

        return $alt !== '' ? $alt : 'BNS School';
    }

    public function hasCustomLogo(): bool
    {
        $path = $this->get(self::KEY_LOGO);

        return filled($path) && File::exists(public_path($path));
    }

    public function hasCustomFavicon(): bool
    {
        $path = $this->get(self::KEY_FAVICON);

        return filled($path) && File::exists(public_path($path));
    }

    /** @return array<string, mixed> */
    public function headerBar(): array
    {
        $defaults = config('site.header', []);
        $phones = $this->normalizeHeaderPhones($defaults);

        $socialMap = [
            ['key' => self::KEY_HEADER_SOCIAL_TWITTER, 'default' => $defaults['social']['twitter'] ?? '', 'icon' => 'fab fa-twitter', 'label' => 'Twitter'],
            ['key' => self::KEY_HEADER_SOCIAL_FACEBOOK, 'default' => $defaults['social']['facebook'] ?? '', 'icon' => 'fab fa-facebook', 'label' => 'Facebook'],
            ['key' => self::KEY_HEADER_SOCIAL_PINTEREST, 'default' => $defaults['social']['pinterest'] ?? '', 'icon' => 'fab fa-pinterest-p', 'label' => 'Pinterest'],
            ['key' => self::KEY_HEADER_SOCIAL_INSTAGRAM, 'default' => $defaults['social']['instagram'] ?? '', 'icon' => 'fab fa-instagram', 'label' => 'Instagram'],
        ];

        $socialLinks = [];
        foreach ($socialMap as $item) {
            $url = trim((string) $this->get($item['key'], $item['default']));
            if ($url !== '' && $url !== '#') {
                $socialLinks[] = [
                    'url' => $url,
                    'icon' => $item['icon'],
                    'label' => $item['label'],
                ];
            }
        }

        return [
            'email' => trim((string) $this->get(self::KEY_HEADER_EMAIL, $defaults['email'] ?? '')),
            'phone' => $phones[0]['label'] ?? '',
            'phone_digits' => $phones[0]['digits'] ?? '',
            'phone_href' => $phones[0]['href'] ?? '#',
            'phones' => $phones,
            'email_href' => filled($this->get(self::KEY_HEADER_EMAIL, $defaults['email'] ?? ''))
                ? 'mailto:'.trim((string) $this->get(self::KEY_HEADER_EMAIL, $defaults['email'] ?? ''))
                : '#',
            'address' => trim((string) $this->get(self::KEY_HEADER_ADDRESS, $defaults['address'] ?? '')),
            'maps_url' => $this->mapsUrl($defaults),
            'welcome_text' => trim((string) $this->get(self::KEY_HEADER_WELCOME, $defaults['welcome_text'] ?? '')),
            'social_title' => trim((string) $this->get(self::KEY_HEADER_SOCIAL_TITLE, $defaults['social_title'] ?? 'Follow Us On:')),
            'social_links' => $socialLinks,
        ];
    }

    /** @param array<string, mixed> $defaults
     * @return array<int, array{label: string, digits: string, href: string}>
     */
    private function normalizeHeaderPhones(array $defaults): array
    {
        $phones = [];

        foreach ($defaults['phones'] ?? [] as $entry) {
            $label = trim((string) ($entry['label'] ?? ''));
            $digits = preg_replace('/\D+/', '', (string) ($entry['digits'] ?? $label));

            if ($label !== '' && $digits !== '') {
                $phones[] = [
                    'label' => $label,
                    'digits' => $digits,
                    'href' => 'tel:'.$digits,
                ];
            }
        }

        if ($phones !== []) {
            return $phones;
        }

        $phone = trim((string) $this->get(self::KEY_HEADER_PHONE, $defaults['phone'] ?? ''));
        $digits = preg_replace('/\D+/', '', $phone) ?: (string) ($defaults['phone_digits'] ?? '');

        if ($phone === '' || $digits === '') {
            return [];
        }

        return [[
            'label' => $phone,
            'digits' => $digits,
            'href' => 'tel:'.$digits,
        ]];
    }

    /** @param array<string, mixed> $defaults */
    private function mapsUrl(array $defaults): string
    {
        $configured = trim((string) ($defaults['maps_url'] ?? config('contact.maps_url', '')));
        if ($configured !== '') {
            return $configured;
        }

        $address = trim((string) $this->get(self::KEY_HEADER_ADDRESS, $defaults['address'] ?? ''));
        $query = trim($address.' Business Navachar School');

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($query !== '' ? $query : 'Business Navachar School');
    }

    /** @return array<string, string> */
    public function headerFormValues(): array
    {
        $defaults = config('site.header', []);

        return [
            'email' => (string) $this->get(self::KEY_HEADER_EMAIL, $defaults['email'] ?? ''),
            'phone' => (string) $this->get(self::KEY_HEADER_PHONE, $defaults['phone'] ?? ''),
            'address' => (string) $this->get(self::KEY_HEADER_ADDRESS, $defaults['address'] ?? ''),
            'welcome_text' => (string) $this->get(self::KEY_HEADER_WELCOME, $defaults['welcome_text'] ?? ''),
            'social_title' => (string) $this->get(self::KEY_HEADER_SOCIAL_TITLE, $defaults['social_title'] ?? ''),
            'social_twitter' => (string) $this->get(self::KEY_HEADER_SOCIAL_TWITTER, $defaults['social']['twitter'] ?? ''),
            'social_facebook' => (string) $this->get(self::KEY_HEADER_SOCIAL_FACEBOOK, $defaults['social']['facebook'] ?? ''),
            'social_pinterest' => (string) $this->get(self::KEY_HEADER_SOCIAL_PINTEREST, $defaults['social']['pinterest'] ?? ''),
            'social_instagram' => (string) $this->get(self::KEY_HEADER_SOCIAL_INSTAGRAM, $defaults['social']['instagram'] ?? ''),
        ];
    }

    /** @param array<string, string|null> $data */
    public function updateHeader(array $data): void
    {
        $map = [
            'email' => self::KEY_HEADER_EMAIL,
            'phone' => self::KEY_HEADER_PHONE,
            'address' => self::KEY_HEADER_ADDRESS,
            'welcome_text' => self::KEY_HEADER_WELCOME,
            'social_title' => self::KEY_HEADER_SOCIAL_TITLE,
            'social_twitter' => self::KEY_HEADER_SOCIAL_TWITTER,
            'social_facebook' => self::KEY_HEADER_SOCIAL_FACEBOOK,
            'social_pinterest' => self::KEY_HEADER_SOCIAL_PINTEREST,
            'social_instagram' => self::KEY_HEADER_SOCIAL_INSTAGRAM,
        ];

        foreach ($map as $field => $key) {
            if (array_key_exists($field, $data)) {
                $this->set($key, trim((string) $data[$field]) ?: null);
            }
        }
    }

    public function get(string $key, ?string $default = null): ?string
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }

            if (self::$cache === null) {
                self::$cache = SiteSetting::query()
                    ->pluck('value', 'key')
                    ->all();
            }
        } catch (\Throwable) {
            return $default;
        }

        $value = self::$cache[$key] ?? null;

        return filled($value) ? (string) $value : $default;
    }

    private function hasKey(string $key): bool
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return false;
            }

            if (self::$cache === null) {
                self::$cache = SiteSetting::query()
                    ->pluck('value', 'key')
                    ->all();
            }
        } catch (\Throwable) {
            return false;
        }

        return array_key_exists($key, self::$cache);
    }

    public function set(string $key, ?string $value): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        self::clearCache();
    }

    public function storeLogo(UploadedFile $file): string
    {
        return $this->storeUpload(self::KEY_LOGO, $file, 'logo');
    }

    public function storeFavicon(UploadedFile $file): string
    {
        return $this->storeUpload(self::KEY_FAVICON, $file, 'favicon');
    }

    public function resetLogo(): void
    {
        $this->deleteUploadedFile(self::KEY_LOGO);
        $this->set(self::KEY_LOGO, null);
    }

    public function resetFavicon(): void
    {
        $this->deleteUploadedFile(self::KEY_FAVICON);
        $this->set(self::KEY_FAVICON, null);
    }

    /** @return array<string, mixed> */
    public function brochureMeta(): array
    {
        $path = $this->get(self::KEY_BROCHURE_PDF);
        $hasPdf = filled($path) && File::exists(public_path($path));
        $version = $hasPdf ? $this->brochureVersion((string) $path) : null;

        return [
            'has_pdf' => $hasPdf,
            'path' => $hasPdf ? (string) $path : null,
            'url' => $hasPdf ? route('brochure.view', ['v' => $version]) : null,
            'file_url' => $hasPdf ? bns_vasset($path) : null,
            'download_url' => $hasPdf ? route('brochure.download', ['v' => $version]) : null,
            'version' => $version,
            'page_url' => route('brochure'),
            'title' => trim((string) $this->get(self::KEY_BROCHURE_TITLE, 'BNS Program Brochure')) ?: 'BNS Program Brochure',
            'subtitle' => trim((string) $this->get(self::KEY_BROCHURE_SUBTITLE, 'Business Navachar School (BNS)')) ?: 'Business Navachar School (BNS)',
            'intro' => trim((string) $this->get(self::KEY_BROCHURE_INTRO, 'Download and explore the official Business Navachar School brochure for complete program details, learning pathways, and admission information.')),
        ];
    }

    public function hasBrochure(): bool
    {
        return (bool) ($this->brochureMeta()['has_pdf'] ?? false);
    }

    /** @return array<string, string> */
    public function brochureFormValues(): array
    {
        $meta = $this->brochureMeta();

        return [
            'title' => $meta['title'],
            'subtitle' => $meta['subtitle'],
            'intro' => $meta['intro'],
        ];
    }

    /** @param array<string, string|null> $data */
    public function updateBrochureMeta(array $data): void
    {
        $map = [
            'title' => self::KEY_BROCHURE_TITLE,
            'subtitle' => self::KEY_BROCHURE_SUBTITLE,
            'intro' => self::KEY_BROCHURE_INTRO,
        ];

        foreach ($map as $field => $key) {
            if (array_key_exists($field, $data)) {
                $this->set($key, trim((string) $data[$field]) ?: null);
            }
        }
    }

    public function storeBrochure(UploadedFile $file): string
    {
        $directory = public_path('uploads/brochure');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $this->purgeBrochureFiles();

        $filename = 'bns-brochure-'.time().'.pdf';
        $file->move($directory, $filename);

        $relativePath = 'uploads/brochure/'.$filename;
        $fullPath = $directory.DIRECTORY_SEPARATOR.$filename;
        $version = is_file($fullPath) ? md5_file($fullPath) : (string) time();

        $this->set(self::KEY_BROCHURE_PDF, $relativePath);
        $this->set(self::KEY_BROCHURE_VERSION, $version);

        return $relativePath;
    }

    public function resetBrochure(): void
    {
        $this->purgeBrochureFiles();
        $this->set(self::KEY_BROCHURE_PDF, null);
        $this->set(self::KEY_BROCHURE_VERSION, null);
    }

    /** @return array<string, string> */
    public function legalDates(): array
    {
        return [
            'effective_date' => $this->formatLegalDateLabel($this->get(self::KEY_LEGAL_EFFECTIVE_DATE)),
            'last_updated' => $this->formatLegalDateLabel($this->get(self::KEY_LEGAL_LAST_UPDATED)),
        ];
    }

    /** @return array<string, string> */
    public function legalFormValues(): array
    {
        return [
            'effective_date' => (string) $this->get(self::KEY_LEGAL_EFFECTIVE_DATE, ''),
            'last_updated' => (string) $this->get(self::KEY_LEGAL_LAST_UPDATED, ''),
        ];
    }

    /** @return array<string, mixed> */
    public function heroVideo(): array
    {
        $defaults = config('home.hero', []);

        if ($this->hasKey(self::KEY_HERO_VIDEO_URL)) {
            $url = trim((string) ($this->get(self::KEY_HERO_VIDEO_URL) ?? ''));
        } else {
            $url = trim((string) ($defaults['video_url'] ?? ''));
        }

        if ($this->hasKey(self::KEY_HERO_VIDEO_LABEL)) {
            $label = trim((string) ($this->get(self::KEY_HERO_VIDEO_LABEL) ?? ''));
        } else {
            $label = trim((string) ($defaults['video_label'] ?? 'Introduction Video'));
        }

        return [
            'url' => $url,
            'label' => $label !== '' ? $label : 'Introduction Video',
            'has_video' => $url !== '',
        ];
    }

    /** @return array<string, string> */
    public function heroVideoFormValues(): array
    {
        $video = $this->heroVideo();

        return [
            'url' => $video['url'],
            'label' => $video['label'],
        ];
    }

    /** @param array<string, string|null> $data */
    public function updateHeroVideo(array $data): void
    {
        if (array_key_exists('url', $data)) {
            $this->set(self::KEY_HERO_VIDEO_URL, trim((string) $data['url']) ?: null);
        }

        if (array_key_exists('label', $data)) {
            $this->set(self::KEY_HERO_VIDEO_LABEL, trim((string) $data['label']) ?: null);
        }
    }

    public function autoPurgeMobiles(): string
    {
        return (string) $this->get(self::KEY_AUTO_PURGE_MOBILES, '');
    }

    public function updateAutoPurgeMobiles(?string $value): void
    {
        $parts = array_filter(array_map('trim', preg_split('/[,\n;]+/', (string) $value) ?: []));
        $normalized = [];

        foreach ($parts as $part) {
            $digits = preg_replace('/\D+/', '', $part) ?: '';
            if ($digits !== '') {
                $normalized[] = $digits;
            }
        }

        $this->set(
            self::KEY_AUTO_PURGE_MOBILES,
            $normalized !== [] ? implode(', ', $normalized) : null
        );
    }

    /** @param array<string, string|null> $data */
    public function updateLegalDates(array $data): void
    {
        if (array_key_exists('effective_date', $data)) {
            $this->set(self::KEY_LEGAL_EFFECTIVE_DATE, $this->normalizeLegalDateInput($data['effective_date']));
        }

        if (array_key_exists('last_updated', $data)) {
            $this->set(self::KEY_LEGAL_LAST_UPDATED, $this->normalizeLegalDateInput($data['last_updated']));
        }
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }

    private function assetUrl(string $key, string $default): string
    {
        $path = $this->get($key);

        if (filled($path) && File::exists(public_path($path))) {
            return bns_vasset($path);
        }

        return bns_vasset($default);
    }

    private function storeUpload(string $key, UploadedFile $file, string $prefix): string
    {
        $directory = public_path('uploads/branding');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $this->deleteUploadedFile($key);

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = $prefix.'-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $relativePath = 'uploads/branding/'.$filename;
        $this->set($key, $relativePath);

        return $relativePath;
    }

    private function deleteUploadedFile(string $key): void
    {
        $path = $this->get($key);

        if (! filled($path) || ! str_starts_with($path, 'uploads/branding/')) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function brochureVersion(string $path): string
    {
        $storedVersion = trim((string) $this->get(self::KEY_BROCHURE_VERSION, ''));

        if ($storedVersion !== '') {
            return $storedVersion;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            return (string) filemtime($fullPath);
        }

        return (string) time();
    }

    private function purgeBrochureFiles(): void
    {
        $this->deleteBrochureFile();

        $directory = public_path('uploads/brochure');

        if (! File::isDirectory($directory)) {
            return;
        }

        foreach (File::glob($directory.DIRECTORY_SEPARATOR.'*.pdf') ?: [] as $file) {
            if (is_file($file)) {
                File::delete($file);
            }
        }
    }

    private function deleteBrochureFile(): void
    {
        $path = $this->get(self::KEY_BROCHURE_PDF);

        if (! filled($path)) {
            return;
        }

        $normalized = ltrim(str_replace('\\', '/', (string) $path), '/');

        if (! str_starts_with($normalized, 'uploads/brochure/')) {
            return;
        }

        $fullPath = public_path($normalized);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function formatLegalDateLabel(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return self::LEGAL_DATE_FALLBACK;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $timestamp = strtotime($value);

            return $timestamp ? date('d F Y', $timestamp) : $value;
        }

        return $value;
    }

    private function normalizeLegalDateInput(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }
}
