<?php

namespace App\Services;

class ContactThankYouService
{
    /**
     * @param  array<string, mixed>  $sessionData
     * @return array<string, mixed>
     */
    public function buildPageData(array $sessionData): array
    {
        $formSource = (string) ($sessionData['form_source'] ?? 'inquiry-modal');
        $copy = config("contact_thank_you.{$formSource}", config('contact_thank_you.inquiry-modal', []));
        $name = (string) ($sessionData['full_name'] ?? '');

        return [
            'title' => $copy['title'] ?? 'Thank You',
            'eyebrow' => $copy['eyebrow'] ?? 'Thank You',
            'thank_you' => str_replace(':name', $name, $copy['thank_you'] ?? 'Thank you for contacting us.'),
            'contact_soon' => $copy['contact_soon'] ?? 'Our Admission Team will contact you shortly.',
            'reference_label' => $copy['reference_label'] ?? 'Your Reference Number',
            'details_section' => config('contact_thank_you.details_section', []),
            'whatsapp' => $this->buildWhatsappCopy($sessionData),
            'whatsapp_group' => $this->buildWhatsappGroupCopy(),
            'primary_location' => $this->primaryLocation(),
            'locations' => $this->locations(),
            'cta' => config('contact_thank_you.cta', []),
        ];
    }

    /**
     * @param  array<string, mixed>  $sessionData
     * @return array<string, string>
     */
    public function buildWhatsappCopy(array $sessionData): array
    {
        $mobile = (string) ($sessionData['mobile'] ?? '');
        $whatsapp = config('contact_thank_you.whatsapp', []);

        return [
            'button_label' => $whatsapp['button_label'] ?? 'Get on WhatsApp',
            'button_sub' => $whatsapp['button_sub'] ?? '',
            'hint' => str_replace(':mobile', $mobile, $whatsapp['hint'] ?? ''),
            'url' => $this->whatsappUrlForUser($sessionData),
        ];
    }

    /** @return array<string, string> */
    public function buildWhatsappGroupCopy(): array
    {
        $group = config('contact_thank_you.whatsapp_group', []);

        return [
            'button_label' => $group['button_label'] ?? 'Join BNS WhatsApp Group',
            'button_sub' => $group['button_sub'] ?? '',
            'hint' => $group['hint'] ?? '',
            'url' => $group['url'] ?? '',
        ];
    }

    /**
     * @param  array<string, mixed>  $sessionData
     */
    public function whatsappUrlForUser(array $sessionData): string
    {
        return bns_whatsapp_user_link(
            (string) ($sessionData['mobile'] ?? ''),
            $this->buildWhatsappMessage($sessionData)
        );
    }

    /**
     * @param  array<string, mixed>  $sessionData
     */
    public function buildWhatsappMessage(array $sessionData): string
    {
        $name = (string) ($sessionData['full_name'] ?? '');
        $regNo = (string) ($sessionData['registration_number'] ?? '');
        $program = (string) ($sessionData['interested_program'] ?? '');
        $formSource = (string) ($sessionData['form_source'] ?? 'inquiry-modal');
        $requestType = match ($formSource) {
            'intro-session-modal' => 'Introduction Session Request',
            'register-quick-modal' => 'Admission Request',
            default => 'Inquiry',
        };

        $venue = $this->primaryLocation();
        $phones = config('contact.phones', []);
        $helpline = $phones['helpline'] ?? '+91 72086 28671';
        $whatsapp = $phones['whatsapp'] ?? '+91 70218 39703';

        $lines = [
            '🙏 *Thank you for connecting with Business Navachar School (BNS)!*',
            '',
            "Dear {$name},",
            '',
            "Your {$requestType} has been received successfully.",
            '',
            "📋 *Reference Number:* {$regNo}",
        ];

        if ($program !== '') {
            $lines[] = "📚 *Program:* {$program}";
            $lines[] = '';
        }

        $lines[] = '📍 *BNS Program Venue*';
        $lines[] = ($venue['brand'] ?? 'Shri Vardhaman Sthanakwasi Jain Shravak Sangh');
        $lines[] = ($venue['address'] ?? 'Santacruz West, Mumbai, Maharashtra, India');
        if (! empty($venue['maps_url'])) {
            $lines[] = '🗺 *Google Map Location:*';
            $lines[] = $venue['maps_url'];
        }

        $lines[] = '';
        $lines[] = "📞 *Helpline:* {$helpline}";
        $lines[] = "💬 *WhatsApp:* {$whatsapp}";

        $lines = array_merge($lines, $this->buildWhatsappGroupMessageLines());

        $lines[] = '';
        $lines[] = 'Our Admission Team will contact you shortly on your registered mobile number.';
        $lines[] = '';
        $lines[] = '*Learn Prosperity • Create Prosperity • Build a Developed India*';
        $lines[] = '— Business Navachar School (BNS)';

        return implode("\n", $lines);
    }

    /** @return list<string> */
    private function buildWhatsappGroupMessageLines(): array
    {
        $group = config('contact_thank_you.whatsapp_group', []);
        $groupUrl = trim((string) ($group['url'] ?? ''));

        if ($groupUrl === '') {
            return [];
        }

        $lines = [
            '',
            '👥 *Join BNS WhatsApp Group*',
            '*Group Name:* '.trim((string) ($group['name'] ?? 'BNS:::Santacruz')),
        ];

        $details = trim((string) ($group['details'] ?? ''));
        if ($details !== '') {
            $lines[] = $details;
        }

        $lines[] = '🔗 *Join Link:*';
        $lines[] = $groupUrl;

        return $lines;
    }

    /** @return array<string, string|null> */
    private function primaryLocation(): array
    {
        $primary = config('contact_thank_you.primary_location', []);
        if (! empty($primary['maps_url'])) {
            return $primary;
        }

        $locations = config('contact_thank_you.locations', []);

        return $locations['venue'] ?? [];
    }

    /** @return array<string, array<string, string|null>> */
    private function locations(): array
    {
        return config('contact_thank_you.locations', []);
    }
}
