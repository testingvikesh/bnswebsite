<form method="POST" action="{{ route('venue-inspection.store') }}" class="bns-admission-form" id="venueInspectionForm">
    @csrf
    <input type="hidden" name="form_type" value="venue-inspection">

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Basic Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="vi_venue_name">Venue Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('venue_name') is-invalid @enderror" id="vi_venue_name" name="venue_name" value="{{ old('venue_name') }}" required>
                @error('venue_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="vi_institution_name">Institution Name</label>
                <input type="text" class="form-control" id="vi_institution_name" name="institution_name" value="{{ old('institution_name') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_city">City</label>
                <input type="text" class="form-control" id="vi_city" name="city" value="{{ old('city') }}">
            </div>
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="vi_address">Address</label>
                <textarea class="form-control" id="vi_address" name="address" rows="2" placeholder="Full venue address">{{ old('address') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_contact_person">Contact Person</label>
                <input type="text" class="form-control" id="vi_contact_person" name="contact_person" value="{{ old('contact_person') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="vi_mobile" name="mobile" value="{{ old('mobile') }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_inspection_date">Inspection Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('inspection_date') is-invalid @enderror" id="vi_inspection_date" name="inspection_date" value="{{ old('inspection_date', now()->toDateString()) }}" required>
                @error('inspection_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="vi_inspector_name">Inspector Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('inspector_name') is-invalid @enderror" id="vi_inspector_name" name="inspector_name" value="{{ old('inspector_name') }}" required>
                @error('inspector_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Venue Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_seating_capacity">Total Seating Capacity</label>
                <input type="text" class="form-control" id="vi_total_seating_capacity" name="total_seating_capacity" value="{{ old('total_seating_capacity') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_maximum_capacity">Maximum Capacity</label>
                <input type="text" class="form-control" id="vi_maximum_capacity" name="maximum_capacity" value="{{ old('maximum_capacity') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_hall_size">Hall Size (Approx.)</label>
                <input type="text" class="form-control" id="vi_hall_size" name="hall_size" value="{{ old('hall_size') }}" placeholder="e.g. 50 x 30 ft">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_number_of_floors">Number of Floors</label>
                <input type="text" class="form-control" id="vi_number_of_floors" name="number_of_floors" value="{{ old('number_of_floors') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'lift_available', 'label' => 'Lift Available?', 'cols' => 4])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Furniture Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_chairs">Total Chairs Available</label>
                <input type="text" class="form-control" id="vi_total_chairs" name="total_chairs" value="{{ old('total_chairs') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_tables">Total Tables Available</label>
                <input type="text" class="form-control" id="vi_total_tables" name="total_tables" value="{{ old('total_tables') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_student_tables">Student Tables Available</label>
                <input type="text" class="form-control" id="vi_student_tables" name="student_tables" value="{{ old('student_tables') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_registration_tables">Registration Tables Available</label>
                <input type="text" class="form-control" id="vi_registration_tables" name="registration_tables" value="{{ old('registration_tables') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_vip_chairs">VIP Chairs Available</label>
                <input type="text" class="form-control" id="vi_vip_chairs" name="vip_chairs" value="{{ old('vip_chairs') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'podium_available', 'label' => 'Podium Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'stage_available', 'label' => 'Stage Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_stage_size">Stage Size</label>
                <input type="text" class="form-control" id="vi_stage_size" name="stage_size" value="{{ old('stage_size') }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'AC / Fan Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_ac_units">Total AC Units</label>
                <input type="text" class="form-control" id="vi_total_ac_units" name="total_ac_units" value="{{ old('total_ac_units') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_ac_working_condition">AC Working Condition</label>
                <input type="text" class="form-control" id="vi_ac_working_condition" name="ac_working_condition" value="{{ old('ac_working_condition') }}" placeholder="Good / Fair / Poor">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_ceiling_fans">Total Ceiling Fans</label>
                <input type="text" class="form-control" id="vi_total_ceiling_fans" name="total_ceiling_fans" value="{{ old('total_ceiling_fans') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_wall_fans">Total Wall Fans</label>
                <input type="text" class="form-control" id="vi_total_wall_fans" name="total_wall_fans" value="{{ old('total_wall_fans') }}">
            </div>
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="vi_fan_working_condition">Fan Working Condition</label>
                <input type="text" class="form-control" id="vi_fan_working_condition" name="fan_working_condition" value="{{ old('fan_working_condition') }}" placeholder="Good / Fair / Poor">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Lighting Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_tube_lights">Total Tube Lights</label>
                <input type="text" class="form-control" id="vi_total_tube_lights" name="total_tube_lights" value="{{ old('total_tube_lights') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_led_lights">Total LED Lights</label>
                <input type="text" class="form-control" id="vi_total_led_lights" name="total_led_lights" value="{{ old('total_led_lights') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'emergency_lights_available', 'label' => 'Emergency Lights Available', 'cols' => 4])
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="vi_lighting_condition">Lighting Condition</label>
                <input type="text" class="form-control" id="vi_lighting_condition" name="lighting_condition" value="{{ old('lighting_condition') }}" placeholder="Good / Adequate / Poor">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Electricity Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_total_power_points">Total Power Points</label>
                <input type="text" class="form-control" id="vi_total_power_points" name="total_power_points" value="{{ old('total_power_points') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'generator_available', 'label' => 'Generator Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_generator_capacity">Generator Capacity</label>
                <input type="text" class="form-control" id="vi_generator_capacity" name="generator_capacity" value="{{ old('generator_capacity') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'inverter_available', 'label' => 'Inverter Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_electricity_backup_time">Electricity Backup Time</label>
                <input type="text" class="form-control" id="vi_electricity_backup_time" name="electricity_backup_time" value="{{ old('electricity_backup_time') }}" placeholder="e.g. 4 hours">
            </div>
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="vi_electrical_safety_condition">Electrical Safety Condition</label>
                <input type="text" class="form-control" id="vi_electrical_safety_condition" name="electrical_safety_condition" value="{{ old('electrical_safety_condition') }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Audio Visual Details'])
        <div class="row g-3">
            @include('venue-inspection.partials.yes-no', ['name' => 'projector_available', 'label' => 'Projector Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_projector_brand">Projector Brand</label>
                <input type="text" class="form-control" id="vi_projector_brand" name="projector_brand" value="{{ old('projector_brand') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'smartboard_available', 'label' => 'Smartboard Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'led_screen_available', 'label' => 'LED Screen Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'microphone_available', 'label' => 'Microphone Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_number_of_microphones">Number of Microphones</label>
                <input type="text" class="form-control" id="vi_number_of_microphones" name="number_of_microphones" value="{{ old('number_of_microphones') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'sound_system_available', 'label' => 'Sound System Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_speaker_quantity">Speaker Quantity</label>
                <input type="text" class="form-control" id="vi_speaker_quantity" name="speaker_quantity" value="{{ old('speaker_quantity') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'internet_wifi_available', 'label' => 'Internet/WiFi Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_internet_speed">Internet Speed</label>
                <input type="text" class="form-control" id="vi_internet_speed" name="internet_speed" value="{{ old('internet_speed') }}" placeholder="e.g. 100 Mbps">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Water Facility Details'])
        <div class="row g-3">
            @include('venue-inspection.partials.yes-no', ['name' => 'drinking_water_available', 'label' => 'Drinking Water Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'water_cooler_available', 'label' => 'Water Cooler Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'ro_water_available', 'label' => 'RO Water Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_number_of_water_points">Number of Water Points</label>
                <input type="text" class="form-control" id="vi_number_of_water_points" name="number_of_water_points" value="{{ old('number_of_water_points') }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Washroom Details'])
        <div class="row g-3">
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="vi_male_washrooms">Male Washrooms</label>
                <input type="text" class="form-control" id="vi_male_washrooms" name="male_washrooms" value="{{ old('male_washrooms') }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="vi_female_washrooms">Female Washrooms</label>
                <input type="text" class="form-control" id="vi_female_washrooms" name="female_washrooms" value="{{ old('female_washrooms') }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="vi_washroom_cleanliness_rating">Washroom Cleanliness Rating</label>
                <input type="text" class="form-control" id="vi_washroom_cleanliness_rating" name="washroom_cleanliness_rating" value="{{ old('washroom_cleanliness_rating') }}" placeholder="1–5 or Good/Fair/Poor">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'disabled_friendly_washroom', 'label' => 'Disabled Friendly Washroom', 'cols' => 3])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Parking Details'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_two_wheeler_parking_capacity">Two Wheeler Parking Capacity</label>
                <input type="text" class="form-control" id="vi_two_wheeler_parking_capacity" name="two_wheeler_parking_capacity" value="{{ old('two_wheeler_parking_capacity') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_four_wheeler_parking_capacity">Four Wheeler Parking Capacity</label>
                <input type="text" class="form-control" id="vi_four_wheeler_parking_capacity" name="four_wheeler_parking_capacity" value="{{ old('four_wheeler_parking_capacity') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'vip_parking_available', 'label' => 'VIP Parking Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'parking_security_available', 'label' => 'Parking Security Available?', 'cols' => 4])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Safety Details'])
        <div class="row g-3">
            @include('venue-inspection.partials.yes-no', ['name' => 'emergency_exit_available', 'label' => 'Emergency Exit Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'fire_extinguisher_available', 'label' => 'Fire Extinguisher Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_number_of_fire_extinguishers">Number of Fire Extinguishers</label>
                <input type="text" class="form-control" id="vi_number_of_fire_extinguishers" name="number_of_fire_extinguishers" value="{{ old('number_of_fire_extinguishers') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'first_aid_kit_available', 'label' => 'First Aid Kit Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'security_guard_available', 'label' => 'Security Guard Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'cctv_cameras_installed', 'label' => 'CCTV Cameras Installed?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_number_of_cctv_cameras">Number of CCTV Cameras</label>
                <input type="text" class="form-control" id="vi_number_of_cctv_cameras" name="number_of_cctv_cameras" value="{{ old('number_of_cctv_cameras') }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '12', 'title' => 'Branding Permission'])
        <div class="row g-3">
            @include('venue-inspection.partials.yes-no', ['name' => 'banner_permission_available', 'label' => 'Banner Permission Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'standee_permission_available', 'label' => 'Standee Permission Available?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'photography_permission', 'label' => 'Photography Permission?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'videography_permission', 'label' => 'Videography Permission?', 'cols' => 4])
            @include('venue-inspection.partials.yes-no', ['name' => 'social_media_coverage_permission', 'label' => 'Social Media Coverage Permission?', 'cols' => 4])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '13', 'title' => 'Management Support'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_venue_coordinator_name">Venue Coordinator Name</label>
                <input type="text" class="form-control" id="vi_venue_coordinator_name" name="venue_coordinator_name" value="{{ old('venue_coordinator_name') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_venue_coordinator_mobile">Venue Coordinator Mobile</label>
                <input type="text" class="form-control" id="vi_venue_coordinator_mobile" name="venue_coordinator_mobile" value="{{ old('venue_coordinator_mobile') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_technical_support_person">Technical Support Person</label>
                <input type="text" class="form-control" id="vi_technical_support_person" name="technical_support_person" value="{{ old('technical_support_person') }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_technical_support_mobile">Technical Support Mobile</label>
                <input type="text" class="form-control" id="vi_technical_support_mobile" name="technical_support_mobile" value="{{ old('technical_support_mobile') }}">
            </div>
            @include('venue-inspection.partials.yes-no', ['name' => 'housekeeping_support_available', 'label' => 'Housekeeping Support Available?', 'cols' => 4])
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_management_cooperation_level">Management Cooperation Level</label>
                <input type="text" class="form-control" id="vi_management_cooperation_level" name="management_cooperation_level" value="{{ old('management_cooperation_level') }}" placeholder="Excellent / Good / Fair / Poor">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '14', 'title' => 'Inspection Observation'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="vi_major_strengths">Major Strengths</label>
                <textarea class="form-control" id="vi_major_strengths" name="major_strengths" rows="4" placeholder="List key strengths of the venue">{{ old('major_strengths') }}</textarea>
            </div>
            <div class="col-12">
                <label class="bns-admission-form__label" for="vi_major_issues">Major Issues</label>
                <textarea class="form-control" id="vi_major_issues" name="major_issues" rows="4" placeholder="List major issues or concerns">{{ old('major_issues') }}</textarea>
            </div>
            <div class="col-12">
                <label class="bns-admission-form__label" for="vi_recommended_capacity">Recommended Capacity</label>
                <textarea class="form-control" id="vi_recommended_capacity" name="recommended_capacity" rows="2" placeholder="Recommended seating / participant capacity">{{ old('recommended_capacity') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '15', 'title' => 'Final Decision'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Final Decision <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['approved' => 'Approved', 'conditionally_approved' => 'Conditionally Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="final_decision" value="{{ $value }}" {{ old('final_decision') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('final_decision')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_inspector_signature">Inspector Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('inspector_signature') is-invalid @enderror" id="vi_inspector_signature" name="inspector_signature" value="{{ old('inspector_signature') }}" placeholder="Type full name as signature" required>
                @error('inspector_signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_venue_representative_signature">Venue Representative Signature</label>
                <input type="text" class="form-control" id="vi_venue_representative_signature" name="venue_representative_signature" value="{{ old('venue_representative_signature') }}" placeholder="Venue representative name">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="vi_submission_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('submission_date') is-invalid @enderror" id="vi_submission_date" name="submission_date" value="{{ old('submission_date', now()->toDateString()) }}" required>
                @error('submission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__footer">
        <p class="bns-admission-form__tagline">BUSINESS NAVACHAR SCHOOL (BNS)</p>
        <p class="bns-admission-form__motto">"Building Entrepreneurs, Strengthening Businesses, Creating Wealth &amp; Developing Industry Leaders"</p>
        <div class="bns-admission-form__actions">
            <button type="submit" id="venueInspectionSubmit" class="thm-btn bns-admission-form__btn bns-admission-form__btn--apply">
                Submit Inspection <i class="fas fa-check"></i>
            </button>
            <a href="{{ route('register') }}" class="thm-btn bns-admission-form__btn bns-admission-form__btn--brochure">
                Back to Registration <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</form>
