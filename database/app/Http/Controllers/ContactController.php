<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Services\ContactPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private ContactPageService $contactPage) {}

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

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
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
            'organization_name' => ['nullable', 'string', 'max:255'],
            'preferred_centre' => ['nullable', 'string', 'max:255'],
            'preferred_batch' => ['nullable', 'string', Rule::in($form['batches'])],
            'preferred_language' => ['nullable', 'string', Rule::in($form['languages'])],
            'hear_about' => ['nullable', 'string', Rule::in($form['hear_about'])],
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
        ]);

        $documents = [];
        foreach (['photo', 'aadhaar', 'certificate', 'resume', 'business_profile'] as $field) {
            if ($request->hasFile($field)) {
                $documents[$field] = $request->file($field)->store('contact-inquiries/documents', 'public');
            }
        }

        ContactInquiry::create([
            'registration_number' => ContactInquiry::generateRegistrationNumber(),
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
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
            'preferred_centre' => $validated['preferred_centre'] ?? null,
            'preferred_batch' => $validated['preferred_batch'] ?? null,
            'preferred_language' => $validated['preferred_language'] ?? null,
            'hear_about' => $validated['hear_about'] ?? null,
            'purpose_of_joining' => $validated['purpose_of_joining'] ?? [],
            'expectations' => $validated['expectations'] ?? null,
            'subject' => $validated['interested_program'],
            'message' => $validated['message'] ?? '',
            'documents' => $documents ?: null,
            'agreed_to_contact' => true,
            'agreed_info_correct' => true,
            'agreed_privacy' => true,
            'status' => 'pending',
        ]);

        return redirect()->route('contact')
            ->withFragment('contact-form')
            ->with('contact_success', 'Thank you! Your enquiry has been submitted successfully. Our Admission Team will contact you shortly.');
    }
}
