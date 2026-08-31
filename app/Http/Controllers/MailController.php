<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use App\Services\IntroSessionConfirmationMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MailController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
        private IntroSessionConfirmationMailer $mailer,
    ) {}

    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($this->isAuthenticated($request)) {
            return redirect()->route('mail.hub');
        }

        return view('mail.login', [
            'heroImage' => $this->heroImage(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:100'],
        ], [
            'username.required' => 'Please enter username.',
            'password.required' => 'Please enter password.',
        ]);

        $expectedUser = (string) config('mail_portal.username', 'bnsmail');
        $expectedPass = (string) config('mail_portal.password', '');

        $userOk = hash_equals(mb_strtolower($expectedUser), mb_strtolower(trim($validated['username'])));
        $passOk = $this->passwordMatches($expectedPass, (string) $validated['password']);

        if (! $userOk || ! $passOk) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid username or password.']);
        }

        $request->session()->put($this->sessionKey(), true);
        $request->session()->regenerate();

        return redirect()->route('mail.hub');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget($this->sessionKey());
        $request->session()->regenerate();

        return redirect()->route('mail.login')->with('status', 'You have been logged out.');
    }

    public function hub(Request $request): View
    {
        return view('mail.hub', [
            'hub' => config('mail_portal.hub', []),
            'pages' => config('mail_portal.pages', []),
            'heroImage' => $this->heroImage(),
        ]);
    }

    public function student(): View
    {
        return $this->sequencePage('student');
    }

    public function businessCoach(): View
    {
        return $this->sequencePage('business_coach');
    }

    public function sendMail(Request $request): JsonResponse
    {
        $templateIds = collect(bns_message_email_templates())
            ->reject(fn (array $template) => ($template['type'] ?? '') === 'welcome')
            ->pluck('id')
            ->merge(collect(bns_mail_portal_email_templates())->pluck('id'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'template' => ['required', 'string', Rule::in($templateIds)],
        ], [
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address.',
            'template.required' => 'Message template is missing.',
            'template.in' => 'Selected message template is invalid.',
        ]);

        $email = trim((string) $validated['email']);
        $templateId = (string) $validated['template'];
        $template = bns_message_email_template($templateId)
            ?? bns_mail_portal_email_template($templateId);

        if ($template === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Selected message template was not found.',
            ], 404);
        }

        if (
            trim((string) ($template['rich_html'] ?? '')) === ''
            && trim((string) ($template['body_html'] ?? '')) === ''
            && trim((string) ($template['whatsapp'] ?? '')) === ''
        ) {
            return response()->json([
                'ok' => false,
                'message' => 'Selected message has no email content.',
            ], 422);
        }

        try {
            $result = $this->mailer->send([
                'full_name' => 'Participant',
                'email' => $email,
                'registration_number' => '',
            ], null, $templateId);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'Unable to send mail right now. Please try again.',
            ], 500);
        }

        if (! ($result['ok'] ?? false)) {
            return response()->json([
                'ok' => false,
                'message' => $result['error'] ?? 'Unable to send mail. Please try again.',
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Mail sent successfully to '.$email.'.',
            'title' => $result['template_title'] ?? ($template['title'] ?? ''),
        ]);
    }

    private function sequencePage(string $audienceKey): View
    {
        $isCoach = $audienceKey === 'business_coach';
        $portalPage = config('mail_portal.pages.'.$audienceKey, []);

        if ($isCoach) {
            $coachPage = config('mail_messages.page', []);
            $page = array_merge($coachPage, $portalPage);
            $page['subtitle'] = $coachPage['subtitle'] ?? 'Business Coach Communication Sequence';
            $page['label'] = $coachPage['label'] ?? 'Business Coach Mail';
            $page['intro'] = $coachPage['intro'] ?? '';
            $sections = config('mail_messages.sections', []);
            $session = null;
        } else {
            $messagePage = config('messages.page', []);
            $page = array_merge($messagePage, $portalPage);
            $page['subtitle'] = $messagePage['subtitle'] ?? ($page['subtitle'] ?? '');
            $page['label'] = $messagePage['label'] ?? ($page['label'] ?? 'Messages');
            $page['intro'] = $messagePage['intro'] ?? ($page['intro'] ?? '');
            $sections = config('messages.sections', []);
            $session = bns_first_introduction_session();
        }

        foreach ($sections as $key => $section) {
            $items = $section['items'] ?? [];
            foreach ($items as $index => $item) {
                $sections[$key]['items'][$index] = bns_enrich_message_item(
                    is_array($item) ? $item : [],
                    $session
                );
            }
        }

        return view('mail.sequence', [
            'page' => $page,
            'sections' => $sections,
            'audienceKey' => $audienceKey,
            'flatBoxes' => $isCoach,
            'heroImage' => $this->heroImage(),
            'sendMailUrl' => route('mail.send-mail'),
        ]);
    }

    private function heroImage(): string
    {
        return $this->aboutPage->get()->heroImageUrl(
            fn () => $this->homeImages->url('about_bg')
        );
    }

    private function sessionKey(): string
    {
        return (string) config('mail_portal.session_key', 'bns_mail_portal_auth');
    }

    private function isAuthenticated(Request $request): bool
    {
        return (bool) $request->session()->get($this->sessionKey());
    }

    private function passwordMatches(string $expected, string $provided): bool
    {
        if ($expected === '') {
            return false;
        }

        // Support hashed passwords in .env / config, or plain for simple portals.
        if (str_starts_with($expected, '$2y$') || str_starts_with($expected, '$2a$') || str_starts_with($expected, '$argon')) {
            return Hash::check($provided, $expected);
        }

        return hash_equals($expected, $provided);
    }
}
