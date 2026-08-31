<?php

namespace App\Http\Controllers;

use App\Models\VenueInspection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenueInspectionController extends Controller
{
    public function index(): View
    {
        return view('venue-inspection.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $yesNoFields = [
            'lift_available',
            'podium_available',
            'stage_available',
            'emergency_lights_available',
            'generator_available',
            'inverter_available',
            'projector_available',
            'smartboard_available',
            'led_screen_available',
            'microphone_available',
            'sound_system_available',
            'internet_wifi_available',
            'drinking_water_available',
            'water_cooler_available',
            'ro_water_available',
            'disabled_friendly_washroom',
            'vip_parking_available',
            'parking_security_available',
            'emergency_exit_available',
            'fire_extinguisher_available',
            'first_aid_kit_available',
            'security_guard_available',
            'cctv_cameras_installed',
            'banner_permission_available',
            'standee_permission_available',
            'photography_permission',
            'videography_permission',
            'social_media_coverage_permission',
            'housekeeping_support_available',
        ];

        $rules = [
            'form_type' => ['required', 'in:venue-inspection'],
            'venue_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'inspection_date' => ['required', 'date'],
            'inspector_name' => ['required', 'string', 'max:255'],
            'total_seating_capacity' => ['nullable', 'string', 'max:50'],
            'maximum_capacity' => ['nullable', 'string', 'max:50'],
            'hall_size' => ['nullable', 'string', 'max:100'],
            'number_of_floors' => ['nullable', 'string', 'max:20'],
            'total_chairs' => ['nullable', 'string', 'max:50'],
            'total_tables' => ['nullable', 'string', 'max:50'],
            'student_tables' => ['nullable', 'string', 'max:50'],
            'registration_tables' => ['nullable', 'string', 'max:50'],
            'vip_chairs' => ['nullable', 'string', 'max:50'],
            'stage_size' => ['nullable', 'string', 'max:100'],
            'total_ac_units' => ['nullable', 'string', 'max:50'],
            'ac_working_condition' => ['nullable', 'string', 'max:255'],
            'total_ceiling_fans' => ['nullable', 'string', 'max:50'],
            'total_wall_fans' => ['nullable', 'string', 'max:50'],
            'fan_working_condition' => ['nullable', 'string', 'max:255'],
            'total_tube_lights' => ['nullable', 'string', 'max:50'],
            'total_led_lights' => ['nullable', 'string', 'max:50'],
            'lighting_condition' => ['nullable', 'string', 'max:255'],
            'total_power_points' => ['nullable', 'string', 'max:50'],
            'generator_capacity' => ['nullable', 'string', 'max:100'],
            'electricity_backup_time' => ['nullable', 'string', 'max:100'],
            'electrical_safety_condition' => ['nullable', 'string', 'max:255'],
            'projector_brand' => ['nullable', 'string', 'max:255'],
            'number_of_microphones' => ['nullable', 'string', 'max:50'],
            'speaker_quantity' => ['nullable', 'string', 'max:50'],
            'internet_speed' => ['nullable', 'string', 'max:100'],
            'number_of_water_points' => ['nullable', 'string', 'max:50'],
            'male_washrooms' => ['nullable', 'string', 'max:50'],
            'female_washrooms' => ['nullable', 'string', 'max:50'],
            'washroom_cleanliness_rating' => ['nullable', 'string', 'max:100'],
            'two_wheeler_parking_capacity' => ['nullable', 'string', 'max:50'],
            'four_wheeler_parking_capacity' => ['nullable', 'string', 'max:50'],
            'number_of_fire_extinguishers' => ['nullable', 'string', 'max:50'],
            'number_of_cctv_cameras' => ['nullable', 'string', 'max:50'],
            'venue_coordinator_name' => ['nullable', 'string', 'max:255'],
            'venue_coordinator_mobile' => ['nullable', 'string', 'max:20'],
            'technical_support_person' => ['nullable', 'string', 'max:255'],
            'technical_support_mobile' => ['nullable', 'string', 'max:20'],
            'management_cooperation_level' => ['nullable', 'string', 'max:255'],
            'major_strengths' => ['nullable', 'string', 'max:3000'],
            'major_issues' => ['nullable', 'string', 'max:3000'],
            'recommended_capacity' => ['nullable', 'string', 'max:1000'],
            'final_decision' => ['required', 'in:approved,conditionally_approved,rejected'],
            'inspector_signature' => ['required', 'string', 'max:255'],
            'venue_representative_signature' => ['nullable', 'string', 'max:255'],
            'submission_date' => ['required', 'date'],
        ];

        foreach ($yesNoFields as $field) {
            $rules[$field] = ['nullable', 'in:yes,no'];
        }

        $validated = $request->validate($rules);

        $formData = collect($validated)
            ->except([
                'form_type',
                'venue_name',
                'institution_name',
                'city',
                'contact_person',
                'mobile',
                'inspection_date',
                'inspector_name',
                'final_decision',
            ])
            ->toArray();

        $inspection = VenueInspection::query()->create([
            'inspection_number' => VenueInspection::generateInspectionNumber(),
            'venue_name' => $validated['venue_name'],
            'institution_name' => $validated['institution_name'] ?? null,
            'city' => $validated['city'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'mobile' => $validated['mobile'],
            'inspection_date' => $validated['inspection_date'],
            'inspector_name' => $validated['inspector_name'],
            'final_decision' => $validated['final_decision'],
            'form_data' => $formData,
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('venue-inspection')
            ->with('success', "Venue inspection submitted successfully. Inspection Number: {$inspection->inspection_number}");
    }
}
