<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Services\AdmissionPageService;
use App\Services\AdmissionHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function __construct(
        private AdmissionPageService $admissionPages,
        private AdmissionHubService $admissionHub,
    ) {}

    public function index(): View
    {
        $hub = $this->admissionHub->get();
        $config = config('admission');

        return view('admission.index', [
            'hub' => $hub,
            'heroImage' => $hub->heroImageUrl(),
            'config' => $config,
        ]);
    }

    public function apply(): RedirectResponse
    {
        return redirect()->route('register');
    }

    public function onlineApply(): View
    {
        $config = config('admission');

        return view('admission.apply', [
            'pageTitle' => 'Book Your Spot Now',
            'heroImage' => bns_vasset('assets/images/backgrounds/page-header-bg.jpg'),
            'config' => $config,
            'hub' => $this->admissionHub->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $config = config('admission');

        $validated = $request->validate([
            'category' => ['required', 'string', Rule::in($config['categories'])],
            'program' => ['required', 'string', Rule::in($config['programs'])],
            'year_level' => ['required', 'string', Rule::in($config['year_levels'])],
            'batch' => ['required', 'string', Rule::in($config['batches'])],
            'city' => ['required', 'string', Rule::in($config['cities'])],
            'centre' => ['required', 'string', Rule::in($config['centres'])],
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'address' => ['nullable', 'string', 'max:1000'],
            'state' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_mobile' => ['nullable', 'string', 'max:30'],
            'education_qualification' => ['nullable', 'string', 'max:255'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'aadhaar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'school_id' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'marksheet' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'bonafide' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'graduation' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'experience_letter' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'business_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'admission_fee' => ['nullable', 'numeric', 'min:0'],
            'course_fee' => ['nullable', 'numeric', 'min:0'],
            'gst' => ['nullable', 'numeric', 'min:0'],
            'scholarship' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $documents = [];
        $photoPath = null;
        foreach (['photo', 'aadhaar', 'school_id', 'marksheet', 'bonafide', 'graduation', 'experience_letter', 'business_proof'] as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('admission-applications/documents', 'public');
                if ($field === 'photo') {
                    $photoPath = $path;
                } else {
                    $documents[$field] = $path;
                }
            }
        }

        $reg = (float) ($validated['registration_fee'] ?? 0);
        $adm = (float) ($validated['admission_fee'] ?? 0);
        $course = (float) ($validated['course_fee'] ?? 0);
        $gst = (float) ($validated['gst'] ?? 0);
        $scholarship = (float) ($validated['scholarship'] ?? 0);
        $discount = (float) ($validated['discount'] ?? 0);
        $total = max(0, $reg + $adm + $course + $gst - $scholarship - $discount);

        $application = AdmissionApplication::create([
            'application_number' => AdmissionApplication::generateApplicationNumber(),
            'category' => $validated['category'],
            'program' => $validated['program'],
            'year_level' => $validated['year_level'],
            'batch' => $validated['batch'],
            'city' => $validated['city'],
            'centre' => $validated['centre'],
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
            'whatsapp' => $validated['whatsapp'] ?? null,
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'state' => $validated['state'] ?? null,
            'pin_code' => $validated['pin_code'] ?? null,
            'parent_details' => ($validated['parent_name'] ?? null) ? [
                'name' => $validated['parent_name'],
                'mobile' => $validated['parent_mobile'] ?? null,
            ] : null,
            'education_qualification' => $validated['education_qualification'] ?? null,
            'institution_name' => $validated['institution_name'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'experience' => $validated['experience'] ?? null,
            'linkedin' => $validated['linkedin'] ?? null,
            'photo_path' => $photoPath,
            'documents' => $documents ?: null,
            'fee_breakdown' => [
                'registration_fee' => $reg,
                'admission_fee' => $adm,
                'course_fee' => $course,
                'gst' => $gst,
                'scholarship' => $scholarship,
                'discount' => $discount,
                'total_payable' => $total,
            ],
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        return redirect()->route('admissions.confirmation', $application)
            ->with('admission_success', true);
    }

    public function confirmation(AdmissionApplication $application): View
    {
        return view('admission.confirmation', [
            'application' => $application,
            'config' => config('admission'),
        ]);
    }

    public function page(string $slug): View
    {
        $validSlugs = collect(config('admission.menu', []))->pluck('slug')
            ->merge(array_keys(config('admission.pages', [])))
            ->unique()
            ->filter(fn ($s) => $s !== 'apply-now')
            ->all();

        if (! in_array($slug, $validSlugs, true)) {
            abort(404);
        }

        $page = $this->admissionPages->getBySlug($slug);
        $config = config('admission');

        $eligibility = null;
        $process = null;
        $faqs = null;
        if ($slug === 'eligibility-criteria') {
            $defaults = config('admission.pages.eligibility-criteria', []);
            $eligibility = array_merge($defaults, [
                'title' => $page->page_title ?: ($defaults['title'] ?? ''),
            ]);
        }
        if ($slug === 'admission-process') {
            $defaults = config('admission.pages.admission-process', []);
            $process = array_merge($defaults, [
                'title' => $page->page_title ?: ($defaults['title'] ?? ''),
            ]);
        }
        if ($slug === 'faqs') {
            $defaults = config('admission.pages.faqs', []);
            $faqs = array_merge($defaults, [
                'title' => $page->page_title ?: ($defaults['title'] ?? ''),
            ]);
        }

        return view('admission.page', [
            'page' => $page,
            'slug' => $slug,
            'heroImage' => $page->heroImageUrl(),
            'config' => $config,
            'hub' => $this->admissionHub->get(),
            'eligibility' => $eligibility,
            'process' => $process,
            'faqs' => $faqs,
            'showOffice' => $slug === 'contact-admission-office',
            'showTrust' => in_array($slug, ['online-admission', 'programs'], true),
            'showAfterAdmission' => $slug === 'online-admission',
            'showDashboard' => $slug === 'online-admission',
        ]);
    }
}
