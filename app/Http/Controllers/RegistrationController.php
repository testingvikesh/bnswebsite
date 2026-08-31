<?php

namespace App\Http\Controllers;

use App\Models\StudentAdmission;
use App\Models\BusinessGrowthAdmission;
use App\Models\JobProfessionalAdmission;
use App\Models\WorkingWomenAdmission;
use App\Models\WomenAdmission;
use App\Models\YouthAdmission;
use App\Services\RegistrationPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(private RegistrationPaymentService $payments) {}

    public function index(): View
    {
        return view('register.index', [
            'heroImage' => bns_vasset('assets/images/backgrounds/page-header-bg.jpg'),
            'registerPrograms' => config('register.programs', []),
        ]);
    }

    public function storeYouth(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:youth-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'highest_qualification' => ['nullable', 'array'],
            'highest_qualification.*' => ['string', 'max:100'],
            'current_course' => ['nullable', 'array'],
            'current_course.*' => ['string', 'max:100'],
            'college_name' => ['nullable', 'string', 'max:255'],
            'current_year_status' => ['nullable', 'array'],
            'current_year_status.*' => ['string', 'max:100'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'parent_mobile' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'primary_goal' => ['nullable', 'array'],
            'primary_goal.*' => ['string', 'max:100'],
            'goal_other_specify' => ['nullable', 'string', 'max:500'],
            'current_status' => ['nullable', 'array'],
            'current_status.*' => ['string', 'max:100'],
            'work_experience' => ['nullable', 'string', 'max:100'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'consent_agreed' => ['accepted'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = YouthAdmission::query()->create([
            'registration_number' => YouthAdmission::generateRegistrationNumber(),
            'category' => 'youth_school',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'youth-school');
    }

    public function storeStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:student-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'current_standard' => ['nullable', 'array'],
            'current_standard.*' => ['string', 'max:50'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'board' => ['nullable', 'array'],
            'board.*' => ['string', 'max:50'],
            'medium' => ['nullable', 'array'],
            'medium.*' => ['string', 'max:50'],
            'last_academic_result' => ['nullable', 'string', 'max:20'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_mobile' => ['nullable', 'string', 'max:20'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_mobile' => ['nullable', 'string', 'max:20'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'parent_whatsapp' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'future_dream' => ['nullable', 'array'],
            'future_dream.*' => ['string', 'max:100'],
            'dream_other_specify' => ['nullable', 'string', 'max:500'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'consent_agreed' => ['accepted'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = StudentAdmission::query()->create([
            'registration_number' => StudentAdmission::generateRegistrationNumber(),
            'category' => 'student_school',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'student-school');
    }

    public function storeWomen(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:women-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:female'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'highest_qualification' => ['nullable', 'array'],
            'highest_qualification.*' => ['string', 'max:100'],
            'qualification_details' => ['nullable', 'string', 'max:500'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'husband_name' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'family_mobile' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'current_status' => ['nullable', 'array'],
            'current_status.*' => ['string', 'max:100'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'monthly_income' => ['nullable', 'string', 'max:100'],
            'primary_goal' => ['nullable', 'array'],
            'primary_goal.*' => ['string', 'max:100'],
            'goal_other_specify' => ['nullable', 'string', 'max:500'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'consent_agreed' => ['accepted'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = WomenAdmission::query()->create([
            'registration_number' => WomenAdmission::generateRegistrationNumber(),
            'category' => 'women_entrepreneurship_school',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'women-school');
    }

    public function storeWorkingWomen(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:working-women-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:female'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'highest_qualification' => ['nullable', 'array'],
            'highest_qualification.*' => ['string', 'max:100'],
            'qualification_details' => ['nullable', 'string', 'max:500'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'husband_name' => ['nullable', 'string', 'max:255'],
            'family_mobile' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'employment_status' => ['nullable', 'array'],
            'employment_status.*' => ['string', 'max:100'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'total_experience' => ['nullable', 'string', 'max:100'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'career_goals' => ['nullable', 'array'],
            'career_goals.*' => ['string', 'max:100'],
            'goal_other_specify' => ['nullable', 'string', 'max:500'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'consent_agreed' => ['accepted'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = WorkingWomenAdmission::query()->create([
            'registration_number' => WorkingWomenAdmission::generateRegistrationNumber(),
            'category' => 'working_women_leadership',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'working-women-school');
    }

    public function storeJobProfessional(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:job-professional-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'highest_qualification' => ['nullable', 'array'],
            'highest_qualification.*' => ['string', 'max:100'],
            'qualification_details' => ['nullable', 'string', 'max:500'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'family_mobile' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'employment_status' => ['nullable', 'array'],
            'employment_status.*' => ['string', 'max:100'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'total_experience' => ['nullable', 'string', 'max:100'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'professional_goals' => ['nullable', 'array'],
            'professional_goals.*' => ['string', 'max:100'],
            'goal_other_specify' => ['nullable', 'string', 'max:500'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'consent_agreed' => ['accepted'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = JobProfessionalAdmission::query()->create([
            'registration_number' => JobProfessionalAdmission::generateRegistrationNumber(),
            'category' => 'job_professional_growth',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'job-professional-school');
    }

    public function storeBusinessGrowth(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_type' => ['required', 'in:business-growth-school'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'highest_qualification' => ['nullable', 'array'],
            'highest_qualification.*' => ['string', 'max:100'],
            'qualification_details' => ['nullable', 'string', 'max:500'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'family_mobile' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'array'],
            'business_type.*' => ['string', 'max:100'],
            'business_category' => ['nullable', 'array'],
            'business_category.*' => ['string', 'max:100'],
            'business_since_year' => ['nullable', 'string', 'max:10'],
            'current_status' => ['nullable', 'array'],
            'current_status.*' => ['string', 'max:100'],
            'employee_count' => ['nullable', 'string', 'max:50'],
            'business_location' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'business_challenges' => ['nullable', 'array'],
            'business_challenges.*' => ['string', 'max:100'],
            'challenge_other_specify' => ['nullable', 'string', 'max:500'],
            'business_goals' => ['nullable', 'array'],
            'business_goals.*' => ['string', 'max:100'],
            'goal_other_specify' => ['nullable', 'string', 'max:500'],
            'digital_access' => ['nullable', 'array'],
            'digital_access.*' => ['string', 'max:100'],
            'medical_condition' => ['required', 'in:no,yes'],
            'medical_details' => ['nullable', 'string', 'max:1000'],
            'consent_agreed' => ['accepted'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'signature' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('admissions/photos', 'public');
        }

        $formData = collect($validated)
            ->except(['full_name', 'email', 'mobile', 'photo', 'consent_agreed', 'form_type'])
            ->toArray();

        $admission = BusinessGrowthAdmission::query()->create([
            'registration_number' => BusinessGrowthAdmission::generateRegistrationNumber(),
            'category' => 'business_growth_school',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo_path' => $photoPath,
            'form_data' => $formData,
            'status' => 'pending',
        ]);

        return $this->payments->redirectToCheckout($admission, 'business-growth-school');
    }
}
