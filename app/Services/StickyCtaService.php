<?php

namespace App\Services;

use Illuminate\Http\Request;

class StickyCtaService
{
    private const REGISTER_FORM_HASHES = [
        'youth-school',
        'student-school',
        'women-school',
        'working-women-school',
        'job-professional-school',
        'business-growth-school',
    ];

    /** @return array{enabled: bool, buttons: array<int, array<string, mixed>>, intro: array<string, string>, inquiry: array<string, string>, register: array<string, string>} */
    public function resolve(?Request $request = null): array
    {
        $request ??= request();
        $config = config('home.sticky_cta', []);

        if (empty($config['enabled'])) {
            return ['enabled' => false, 'buttons' => [], 'intro' => [], 'inquiry' => [], 'register' => []];
        }

        $hideOnRoutes = $config['hide_on_routes'] ?? ['register'];
        if ($request->routeIs(...$hideOnRoutes)) {
            return ['enabled' => false, 'buttons' => [], 'intro' => [], 'inquiry' => [], 'register' => []];
        }

        $introDefaults = $config['intro_session'] ?? [];
        $inquiryDefaults = $config['inquiry'] ?? [];

        if ($request->routeIs('programs.show')) {
            $slug = (string) $request->route('slug');
            $card = collect(config('home.audience_section.cards', []))
                ->first(fn (array $item) => ($item['program_slug'] ?? '') === $slug);

            if ($card) {
                $hash = $this->admissionFormHash($card);
                $label = (string) ($card['label'] ?? '');
                $context = $this->contextFromCard($card, $introDefaults, $inquiryDefaults);

                return [
                    'enabled' => true,
                    'buttons' => $this->buildButtons(
                        'Admission Now '.$label,
                        $hash,
                        $context['intro'],
                    ),
                    'intro' => $context['intro'],
                    'inquiry' => $context['inquiry'],
                    'register' => array_merge($context['intro'], ['register_program_id' => $hash]),
                ];
            }
        }

        $defaultButtons = $config['buttons'] ?? [];
        $context = $this->contextFromDefaults($introDefaults, $inquiryDefaults);

        return [
            'enabled' => true,
            'buttons' => $this->buildButtons(
                $defaultButtons[0]['label'] ?? 'Admission Now (BNS)',
                '',
                $context['intro'],
            ),
            'intro' => $context['intro'],
            'inquiry' => $context['inquiry'],
            'register' => array_merge($context['intro'], ['register_program_id' => '']),
        ];
    }

    /**
     * @param array<string, string> $registerContext
     * @return array<int, array<string, mixed>>
     */
    private function buildButtons(string $admissionLabel, string $registerProgramId, array $registerContext): array
    {
        return [
            [
                'label' => $admissionLabel,
                'short_label' => 'Admission',
                'style' => 'outline',
                'action' => 'modal',
                'modal' => 'bnsIntroSessionModal',
                'data' => [
                    'register-program-id' => $registerProgramId,
                    'contact-program' => $registerContext['contact_program'] ?? '',
                    'contact-category' => $registerContext['contact_category'] ?? '',
                    'program-title' => $registerContext['program_label'] ?? '',
                ],
            ],
            [
                'label' => 'Introduction Session Admission Now',
                'short_label' => 'Intro Session',
                'style' => 'primary',
                'action' => 'modal',
                'modal' => 'bnsIntroSessionModal',
            ],
            [
                'label' => 'Pay Now',
                'short_label' => 'Pay Now',
                'style' => 'pay',
                'action' => 'link',
                'url' => route('pay-now'),
            ],
            ...$this->attendanceButton(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function attendanceButton(): array
    {
        if (! bns_attendance_enabled()) {
            return [];
        }

        return [[
            'label' => (string) config('attendance.button_label', 'Attendance'),
            'short_label' => 'Attendance',
            'style' => 'attendance',
            'action' => 'link',
            'url' => route('attendance'),
        ]];
    }

    /** @param array<string, mixed> $card */
    /** @return array{intro: array<string, string>, inquiry: array<string, string>} */
    private function contextFromCard(array $card, array $introDefaults, array $inquiryDefaults): array
    {
        $label = (string) ($card['label'] ?? '');
        $contactProgram = (string) ($card['contact_program'] ?? ($introDefaults['contact_program'] ?? ''));
        $contactCategory = (string) ($card['contact_category'] ?? ($introDefaults['contact_category'] ?? 'Other'));

        return [
            'intro' => [
                'program_label' => $label,
                'contact_program' => $contactProgram,
                'contact_category' => $contactCategory,
            ],
            'inquiry' => [
                'program_label' => $label,
                'contact_program' => $contactProgram,
                'contact_category' => $contactCategory,
            ],
        ];
    }

    /** @return array{intro: array<string, string>, inquiry: array<string, string>} */
    private function contextFromDefaults(array $introDefaults, array $inquiryDefaults): array
    {
        return [
            'intro' => [
                'program_label' => (string) ($introDefaults['program_label'] ?? 'Business Navachar School'),
                'contact_program' => (string) ($introDefaults['contact_program'] ?? 'School Students Program'),
                'contact_category' => (string) ($introDefaults['contact_category'] ?? 'Other'),
            ],
            'inquiry' => [
                'program_label' => (string) ($inquiryDefaults['program_label'] ?? ($introDefaults['program_label'] ?? 'Business Navachar School')),
                'contact_program' => (string) ($inquiryDefaults['contact_program'] ?? ($introDefaults['contact_program'] ?? 'School Students Program')),
                'contact_category' => (string) ($inquiryDefaults['contact_category'] ?? ($introDefaults['contact_category'] ?? 'Other')),
            ],
        ];
    }

    /** @param array<string, mixed> $card */
    private function admissionFormHash(array $card): string
    {
        $hash = (string) ($card['admission_register_hash'] ?? '');
        if ($hash !== '') {
            return $hash;
        }

        $registerHash = (string) ($card['register_hash'] ?? '');

        return in_array($registerHash, self::REGISTER_FORM_HASHES, true) ? $registerHash : '';
    }
}
