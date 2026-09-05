<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use App\Services\IntroSessionConfirmationMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
        private IntroSessionConfirmationMailer $mailer,
    ) {}

    public function index(): View
    {
        $page = config('messages.page', []);
        $sections = config('messages.sections', []);
        $session = bns_first_introduction_session();

        foreach ($sections as $key => $section) {
            $items = $section['items'] ?? [];
            foreach ($items as $index => $item) {
                $sections[$key]['items'][$index] = bns_enrich_message_item(
                    is_array($item) ? $item : [],
                    $session
                );
            }
        }

        return view('message.index', [
            'page' => $page,
            'sections' => $sections,
            'heroImage' => $this->aboutPage->get()->heroImageUrl(
                fn () => $this->homeImages->url('about_bg')
            ),
        ]);
    }

    public function sendMail(Request $request): JsonResponse
    {
        $templateIds = collect(bns_message_email_templates())
            ->reject(fn (array $template) => ($template['type'] ?? '') === 'welcome')
            ->pluck('id')
            ->filter()
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
        $template = bns_message_email_template($templateId);

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

            $detail = trim((string) $e->getMessage());
            $friendly = 'Unable to send mail right now. Please try again.';
            if (str_contains(strtolower($detail), '535') || str_contains(strtolower($detail), 'authentication failed')) {
                $friendly = 'Could not send mail through G Suite SMTP from this server. Pull the latest code so mail goes out from info@businessnavacharschool.com via the school server, then try again.';
            }

            return response()->json([
                'ok' => false,
                'message' => $friendly,
            ], 500);
        }

        if (! $result['ok']) {
            $error = strtolower((string) ($result['error'] ?? ''));
            $message = $result['error'] ?: 'Unable to send mail. Please try again.';
            if (str_contains($error, '535') || str_contains($error, 'authentication failed')) {
                $message = 'Could not send mail through G Suite SMTP from this server. Pull the latest code so mail goes out from info@businessnavacharschool.com via the school server, then try again.';
            }

            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Mail sent successfully to '.$email.'.',
            'title' => $result['template_title'] ?? ($template['title'] ?? ''),
        ]);
    }
}
