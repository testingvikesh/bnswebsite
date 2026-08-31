<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Services\ContactPageService;
use App\Services\ContactThankYouService;
use App\Services\IntroSessionConfirmationMailer;
use App\Services\TestRegistrationPurgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private ContactPageService $contactPage,
        private ContactThankYouService $contactThankYou,
        private IntroSessionConfirmationMailer $introSessionMailer,
        private TestRegistrationPurgeService $testPurge,
    ) {}

    public function index(Request $request): View
    {
        $page = $this->contactPage->get();

        return view('contact.index', [
            'page' => $page,
            'heroImage' => $page->heroImageUrl(),
            'formConfig' => config('contact.form'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $form = config('contact.form');
        $categories = config('contact.form_categories');

        $allowedSources = ['intro-session-modal', 'pay-now-new-registration', 'inquiry-modal', 'register-quick-modal', 'contact-page'];
        $formSource = (string) $request->input('form_source', 'contact-page');
        if (! in_array($formSource, $allowedSources, true)) {
            $formSource = 'contact-page';
        }

        $isIntroSessionSource = in_array($formSource, ['intro-session-modal', 'pay-now-new-registration'], true);

        $mobileRules = [
            'required',
            'string',
            'regex:/^[6-9][0-9]{9}$/',
            function (string $attribute, mixed $value, \Closure $fail): void {
                if ($this->testPurge->isAutoPurgeMobile((string) $value)) {
                    return;
                }
                if (ContactInquiry::mobileExists((string) $value)) {
                    $fail('This mobile number is already registered.');
                }
            },
        ];
        $emailRules = [
            'required',
            'email',
            'max:255',
            function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                if ($this->testPurge->isAutoPurgeMobile((string) $request->input('mobile'))) {
                    return;
                }
                if (ContactInquiry::emailExists((string) $value)) {
                    $fail('This email is already registered.');
                }
            },
        ];

        $hideBusinessChoices = config('intro_session_form.hide_business_for_program_choices', []);
        $programChoice = (string) $request->input('register_program_choice', '');
        $introBusinessRequired = $isIntroSessionSource
            && $programChoice !== ''
            && ! in_array($programChoice, $hideBusinessChoices, true);
        $professionCategories = config('intro_session_form.business_profession_categories', []);
        $industryCategories = config('intro_session_form.business_industry_categories', []);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => $mobileRules,
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => $emailRules,
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in($form['genders'])],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'interested_program' => ['required', 'string', Rule::in($form['interested_programs'])],
            'category' => ['required', 'string', Rule::in($categories)],
            'educational_qualification' => ['nullable', 'string', Rule::in($form['qualifications'])],
            'occupation' => ['nullable', 'string', 'max:255'],
            'organization_name' => [
                Rule::requiredIf($introBusinessRequired),
                'nullable',
                'string',
                'max:255',
            ],
            'business_profession_category' => [
                Rule::requiredIf($introBusinessRequired),
                'nullable',
                'string',
                Rule::in($professionCategories),
            ],
            'business_profession_category_other' => [
                Rule::requiredIf(fn () => $introBusinessRequired && $request->input('business_profession_category') === 'Other'),
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
            'business_category' => [
                Rule::requiredIf($introBusinessRequired),
                'nullable',
                'string',
                Rule::in($industryCategories),
            ],
            'business_category_other' => [
                Rule::requiredIf(fn () => $introBusinessRequired && $request->input('business_category') === 'Other'),
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
            'products_services' => [
                Rule::requiredIf($introBusinessRequired),
                'nullable',
                'string',
                'max:2000',
            ],
            'preferred_centre' => ['nullable', 'string', 'max:255'],
            'preferred_batch' => ['nullable', 'string', Rule::in($form['batches'])],
            'intro_session_number' => [
                'nullable',
                'integer',
                Rule::in(bns_intro_session_allowed_numbers()),
            ],
            'preferred_language' => ['nullable', 'string', Rule::in($form['languages'])],
            'hear_about' => [
                Rule::requiredIf($isIntroSessionSource),
                'nullable',
                'string',
                Rule::in($form['hear_about'] ?? config('intro_session_form.hear_about_options', [])),
            ],
            'hear_about_other' => [
                Rule::requiredIf(fn () => $isIntroSessionSource && $request->input('hear_about') === 'Other'),
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
            'purpose_of_joining' => ['nullable', 'array'],
            'purpose_of_joining.*' => ['string', Rule::in($form['purpose'])],
            'expectations' => ['nullable', 'string', 'max:5000'],
            'message' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'aadhaar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'business_profile' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'agreed_info_correct' => ['accepted'],
            'agreed_to_contact' => ['accepted'],
            'agreed_privacy' => ['accepted'],
            'form_source' => ['nullable', 'string', 'max:50'],
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit mobile number (without +91).',
            'intro_session_number.in' => 'Please select a valid session date.',
            'hear_about.required' => 'Please tell us how you heard about BNS.',
            'hear_about_other.required' => 'Please specify how you heard about BNS.',
        ]);

        $hearAbout = $this->resolveIntroSessionCategoryChoice(
            $validated['hear_about'] ?? null,
            $request->input('hear_about_other')
        );

        $documents = [];
        foreach (['photo', 'aadhaar', 'certificate', 'resume', 'business_profile'] as $field) {
            if ($request->hasFile($field)) {
                $documents[$field] = $request->file($field)->store('contact-inquiries/documents', 'public');
            }
        }

        $registrationNumber = ContactInquiry::generateRegistrationNumber();

        $businessProfessionCategory = $this->resolveIntroSessionCategoryChoice(
            $validated['business_profession_category'] ?? null,
            $request->input('business_profession_category_other')
        );
        $businessCategory = $this->resolveIntroSessionCategoryChoice(
            $validated['business_category'] ?? null,
            $request->input('business_category_other')
        );

        // Count unique intro mobiles BEFORE create (legacy capacity fallback).
        $introCountBefore = $isIntroSessionSource
            ? bns_intro_session_unique_mobile_count()
            : 0;

        $selectedIntroSession = $isIntroSessionSource
            ? (int) ($validated['intro_session_number'] ?? 0)
            : 0;

        // Default to today's session date when none is posted/selected.
        $selectableSessions = bns_intro_session_selectable_numbers();
        if ($isIntroSessionSource && $selectedIntroSession <= 0) {
            $todaySession = bns_intro_session_number_for_date(
                now('Asia/Kolkata')->toDateString()
            );
            if ($todaySession && in_array($todaySession, $selectableSessions, true)) {
                $selectedIntroSession = $todaySession;
            }
        }

        // Fallback when posted value is invalid or today has no matching session.
        if ($isIntroSessionSource && ! in_array($selectedIntroSession, $selectableSessions, true)) {
            $selectedIntroSession = bns_intro_session_number_for_count($introCountBefore);
            if (! in_array($selectedIntroSession, $selectableSessions, true)) {
                $selectedIntroSession = $selectableSessions[0] ?? bns_intro_session_number_for_count($introCountBefore);
            }
        }

        $selectedIntroEvent = $isIntroSessionSource
            ? (bns_introduction_session($selectedIntroSession) ?? [])
            : [];
        $introMessage = (string) ($validated['message'] ?? '');
        if ($isIntroSessionSource && ! empty($selectedIntroEvent['date'])) {
            $introMessage = trim($introMessage.' Preferred session: '.$selectedIntroEvent['date'].'.');
        }

        // Store Pay Now registrations under the same source used by session lookup / payment.
        $storedFormSource = $formSource === 'pay-now-new-registration'
            ? 'intro-session-modal'
            : $formSource;

        $createdInquiry = ContactInquiry::create([
            'registration_number' => $registrationNumber,
            'form_source' => $storedFormSource,
            'full_name' => $validated['full_name'],
            'mobile' => ContactInquiry::normalizeMobile($validated['mobile']),
            'whatsapp' => $validated['whatsapp'] ?? null,
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pin_code' => $validated['pin_code'] ?? null,
            'country' => $validated['country'] ?? 'India',
            'interested_program' => $validated['interested_program'],
            'category' => $validated['category'],
            'educational_qualification' => $validated['educational_qualification'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'business_profession_category' => $businessProfessionCategory,
            'business_category' => $businessCategory,
            'products_services' => $validated['products_services'] ?? null,
            'preferred_centre' => $validated['preferred_centre'] ?? null,
            'preferred_batch' => $validated['preferred_batch'] ?? null,
            'intro_session_number' => $isIntroSessionSource ? $selectedIntroSession : null,
            'preferred_language' => $validated['preferred_language'] ?? null,
            'hear_about' => $hearAbout,
            'purpose_of_joining' => $validated['purpose_of_joining'] ?? [],
            'expectations' => $validated['expectations'] ?? null,
            'subject' => $validated['interested_program'],
            'message' => $introMessage !== '' ? $introMessage : ($validated['message'] ?? ''),
            'documents' => $documents ?: null,
            'agreed_to_contact' => true,
            'agreed_info_correct' => true,
            'agreed_privacy' => true,
            'status' => 'pending',
        ]);

        $this->testPurge->scheduleIfNeeded($createdInquiry);

        if ($isIntroSessionSource) {
            $this->introSessionMailer->send([
                'registration_number' => $registrationNumber,
                'full_name' => $validated['full_name'],
                'mobile' => ContactInquiry::normalizeMobile($validated['mobile']),
                'email' => $validated['email'],
                'interested_program' => $validated['interested_program'],
                'city' => $validated['city'],
                'state' => $validated['state'],
            ], $selectedIntroSession);
        }

        if (in_array($request->input('form_source'), ['intro-session-modal', 'pay-now-new-registration', 'inquiry-modal', 'register-quick-modal'], true)) {
            return redirect()
                ->route('contact.thank-you')
                ->with('contact_thank_you', [
                    'registration_number' => $registrationNumber,
                    'full_name' => $validated['full_name'],
                    'mobile' => ContactInquiry::normalizeMobile($validated['mobile']),
                    'email' => $validated['email'],
                    'form_source' => $storedFormSource,
                    'interested_program' => $validated['interested_program'],
                ]);
        }

        return redirect()->route('contact')
            ->withFragment('contact-form')
            ->with('contact_success', 'Thank you! Your enquiry has been submitted successfully. Our Admission Team will contact you shortly.');
    }

    public function checkMobile(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => ['required', 'string', 'max:30'],
            'form_source' => ['nullable', 'string', 'max:50'],
        ]);

        $mobile = (string) $request->input('mobile');

        if ($this->testPurge->isAutoPurgeMobile($mobile)) {
            return response()->json(true);
        }

        if (ContactInquiry::mobileExists($mobile)) {
            return response()->json('This mobile number is already registered.');
        }

        return response()->json(true);
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'form_source' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:30'],
        ]);

        $email = (string) $request->input('email');

        if ($this->testPurge->isAutoPurgeMobile((string) $request->input('mobile'))) {
            return response()->json(true);
        }

        if (ContactInquiry::emailExists($email)) {
            return response()->json('This email is already registered.');
        }

        return response()->json(true);
    }

    public function thankYou(Request $request): View|RedirectResponse
    {
        $thankYou = session('contact_thank_you');

        if (! is_array($thankYou) || empty($thankYou['registration_number'])) {
            return redirect()->route('contact');
        }

        return view('contact.thank-you', [
            'thankYou' => $thankYou,
            'messages' => $this->contactThankYou->buildPageData($thankYou),
        ]);
    }

    private function resolveIntroSessionCategoryChoice(?string $selected, mixed $other): ?string
    {
        if ($selected === null || $selected === '') {
            return null;
        }

        if ($selected !== 'Other') {
            return $selected;
        }

        $otherText = trim((string) $other);

        return $otherText !== '' ? $otherText : 'Other';
    }
}
