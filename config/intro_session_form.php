<?php

return [

    'business_profession_categories' => [
        'Business Owner',
        'Job Professional',
        'Student',
        'Home Maker (House wife)',
        'Freelancer',
        'Consultant',
        'Startup Founder',
        'Other',
    ],

    'business_industry_categories' => [
        'Manufacturing',
        'Trading',
        'Retail',
        'Wholesale',
        'Service',
        'Healthcare',
        'Education',
        'IT / Software',
        'Finance',
        'Construction',
        'Real Estate',
        'Food & Restaurant',
        'Textile',
        'Agriculture',
        'Digital Business',
        'Other',
    ],

    /*
    | How did you hear about BNS? (intro session admission form)
    | Stored in contact_inquiries.hear_about. "Other" opens a free-text field.
    */
    'hear_about_options' => [
        'Facebook',
        'Instagram',
        'YouTube',
        'Google Search',
        'WhatsApp',
        'WhatsApp Channel',
        'LinkedIn',
        'X (Twitter)',
        'Friend / Family Reference',
        'Business Coach Reference',
        'School / College',
        'Newspaper',
        'Seminar / Event',
        'Email',
        'Website',
        'Other',
    ],

    /*
    | Intro session: hide "About You & Your Business" when these
    | register_program_choice ids are selected (see register.quick_modal_programs).
    */
    'hide_business_for_program_choices' => [
        'student-school',
        'youth-school',
        'job-business-batch',
    ],

    /*
    | Unique mobiles ordered by id ASC (when no stored intro_session_number).
    | New registrations → Session 5 (06 Sep · 2:30–4:30 PM).
    | Past sessions are hidden on the admission form and events page;
    | assign only via stored intro_session_number / reporting.
    |
    | Set forced_session_number to override capacity for NEW form emails (null = auto).
    */
    'unique_mobile_capacity' => 166,
    'default_session_number' => 5,
    'overflow_session_number' => 6,
    'allowed_session_numbers' => [1, 2, 3, 4, 5, 6],
    'forced_session_number' => 5,

];
